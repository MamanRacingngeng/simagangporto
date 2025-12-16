<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect user to Google OAuth.
     */
    public function redirect(Request $request, string $context = 'user')
    {
        try {
            // Simpan konteks login (user/admin) di session
            $allowedContexts = ['user', 'admin'];
            $context = in_array($context, $allowedContexts) ? $context : 'user';

            // Simpan context ke session
            $request->session()->put('google_login_context', $context);
            $request->session()->save();

            \Log::info('Redirecting to Google OAuth', [
                'context' => $context,
                'redirect_uri' => config('services.google.redirect'),
            ]);

            return Socialite::driver('google')
                ->with(['state' => base64_encode(json_encode(['context' => $context]))])
                ->redirect();
        } catch (\Exception $e) {
            \Log::error('Google OAuth Redirect Error: ' . $e->getMessage());
            return redirect()->route($context === 'admin' ? 'admin.login' : 'login')
                ->withErrors(['error' => 'Terjadi kesalahan saat mengakses Google.']);
        }
    }

    /**
     * Handle callback from Google OAuth.
     */
    public function callback(Request $request)
    {
        \Log::info('=== Google OAuth Callback CALLED ===', [
            'url' => $request->fullUrl(),
            'has_code' => $request->has('code'),
            'has_error' => $request->has('error'),
        ]);

        try {
            // Ambil context dari session atau state parameter
            $context = $request->session()->get('google_login_context', 'user');
            
            // Backup: ambil dari state parameter jika session hilang
            if ($context === 'user' && $request->has('state')) {
                try {
                    $state = json_decode(base64_decode($request->input('state')), true);
                    if (isset($state['context'])) {
                        $context = $state['context'];
                        $request->session()->put('google_login_context', $context);
                    }
                } catch (\Exception $e) {
                    \Log::warning('Failed to decode state parameter: ' . $e->getMessage());
                }
            }

            // Handle jika user menolak akses
            if ($request->has('error')) {
                \Log::warning('Google OAuth access denied', ['error' => $request->input('error')]);
                return redirect()->route($context === 'admin' ? 'admin.login' : 'login')
                    ->withErrors(['error' => 'Akses Google ditolak. Silakan coba lagi.']);
            }

            // Get user from Google
            \Log::info('Attempting to get user from Google');
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            \Log::info('Google user retrieved', [
                'email' => $googleUser->getEmail(),
                'id' => $googleUser->getId()
            ]);

            // Cari atau buat user
            $user = User::where('email', $googleUser->getEmail())
                ->orWhere('google_id', $googleUser->getId())
                ->first();

            if (!$user) {
                if ($context === 'admin') {
                    return redirect()->route('admin.login')
                        ->withErrors(['email' => 'Akun admin tidak ditemukan untuk email ini.'])
                        ->withInput(['email' => $googleUser->getEmail()]);
                }

                // Buat user baru
                $user = User::create([
                    'nama' => $googleUser->getName(),
                    'email' => strtolower(trim($googleUser->getEmail())),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => Hash::make(Str::random(32)),
                    'role' => 'user',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]);
                \Log::info('New user created from Google login', ['email' => $user->email]);
            } else {
                // Update google_id jika belum ada
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                    ]);
                }
            }

            // Validasi untuk admin
            if ($context === 'admin' && !$user->isAdmin()) {
                return redirect()->route('admin.login')
                    ->withErrors(['email' => 'Akun ini tidak memiliki akses admin.'])
                    ->withInput(['email' => $googleUser->getEmail()]);
            }

            // Pastikan user aktif
            if (!$user->is_active) {
                $user->update([
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]);
            }

            // Login user dengan remember me
            Auth::login($user, true);
            
            // Simpan session SEBELUM regenerate untuk memastikan login tersimpan
            $request->session()->save();
            
            // Regenerate session untuk keamanan
            $request->session()->regenerate();
            
            // Hapus context dari session
            $request->session()->forget('google_login_context');
            
            // Simpan session lagi setelah regenerate
            $request->session()->save();

            // Verifikasi login berhasil
            if (!Auth::check()) {
                \Log::error('Login failed - user not authenticated after Auth::login()', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
                throw new \Exception('Login gagal. User tidak terautentikasi setelah login.');
            }

            \Log::info('User logged in successfully via Google - REDIRECTING TO DASHBOARD', [
                'email' => $user->email,
                'role' => $user->role,
                'is_admin' => $user->isAdmin(),
                'session_id' => $request->session()->getId(),
                'auth_check' => Auth::check(),
                'auth_user_id' => Auth::id()
            ]);

            // Redirect berdasarkan role - PASTIKAN TIDAK ADA REDIRECT LOOP
            if ($user->isAdmin()) {
                \Log::info('Redirecting admin to admin.dashboard');
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Login Google berhasil sebagai Admin.');
            }

            \Log::info('Redirecting user to dashboard');
            return redirect()->route('dashboard')
                ->with('success', 'Login Google berhasil.');
        } catch (\Exception $e) {
            \Log::error('Google OAuth Callback Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            $context = $request->session()->get('google_login_context', 'user');
            return redirect()->route($context === 'admin' ? 'admin.login' : 'login')
                ->withErrors(['error' => 'Login Google gagal: ' . $e->getMessage()]);
        }
    }
}
