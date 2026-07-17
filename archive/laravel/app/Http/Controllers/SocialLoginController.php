<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    /**
     * Redirect user to Google OAuth consent screen.
     */
    public function redirectToGoogle(Request $request, string $context = 'user')
    {
        try {
            // Simpan konteks login (user/admin) di session
            $allowedContexts = ['user', 'admin'];
            $context = in_array($context, $allowedContexts) ? $context : 'user';

            // Simpan context ke session dan pastikan session disimpan
            $request->session()->put('google_login_context', $context);
            $request->session()->save();

            // Pastikan redirect URI benar
            $redirectUri = config('services.google.redirect');
            
            // Log redirect dengan informasi lengkap
            \Log::info('Redirecting to Google OAuth', [
                'context' => $context,
                'redirect_uri' => $redirectUri,
                'app_url' => config('app.url'),
                'expected_callback' => config('app.url') . '/oauth/google/callback',
                'session_id' => $request->session()->getId(),
            ]);

            // Validasi redirect URI
            if (empty($redirectUri)) {
                \Log::error('Google OAuth redirect URI is empty! Check .env file.');
                return redirect()->route($context === 'admin' ? 'admin.login' : 'login')
                    ->withErrors(['error' => 'Konfigurasi Google OAuth tidak lengkap. Pastikan APP_URL di .env sudah diisi.']);
            }

            // Gunakan redirect dengan redirect URI yang benar
            // Simpan context di state parameter juga sebagai backup
            // Gunakan prompt=consent untuk memastikan consent diberikan dan langsung redirect setelah itu
            return Socialite::driver('google')
                ->with([
                    'state' => base64_encode(json_encode(['context' => $context])),
                    'prompt' => 'consent', // Memaksa consent screen, setelah consent langsung redirect
                    'access_type' => 'offline' // Untuk mendapatkan refresh token jika diperlukan
                ])
                ->redirect();
        } catch (\Exception $e) {
            \Log::error('Google OAuth Redirect Error: ' . $e->getMessage());
            return redirect()->route($context === 'admin' ? 'admin.login' : 'login')
                ->withErrors(['error' => 'Terjadi kesalahan saat mengakses Google. Pastikan konfigurasi Google OAuth sudah benar.']);
        }
    }

    /**
     * Handle callback from Google OAuth.
     */
    public function handleGoogleCallback(Request $request)
    {
        // Log untuk debugging - PASTIKAN INI DIPANGGIL - LOG PERTAMA SEBELUM APAPUN
        // Tulis log langsung tanpa try-catch untuk memastikan log ditulis
        \Log::info('=== Google OAuth Callback CALLED ===', [
            'url' => $request->fullUrl(),
            'has_code' => $request->has('code'),
            'has_error' => $request->has('error'),
            'code_param' => $request->input('code') ? 'EXISTS' : 'MISSING',
            'error_param' => $request->input('error'),
            'state_param' => $request->input('state') ? 'EXISTS' : 'MISSING',
        ]);

        // PENTING: Jika tidak ada code atau error, berarti callback tidak valid
        // Ini bisa terjadi jika redirect URI tidak cocok atau ada masalah dengan Google OAuth
        if (!$request->has('code') && !$request->has('error')) {
            \Log::error('Callback called but no code or error parameter - INVALID CALLBACK', [
                'all_params' => $request->all(),
                'query_string' => $request->getQueryString(),
                'full_url' => $request->fullUrl(),
            ]);
            // JANGAN redirect ke login karena akan menyebabkan loop
            // Redirect langsung ke dashboard jika sudah login, atau ke home
            if (auth()->check()) {
                return redirect()->route('dashboard');
            }
            return redirect()->route('login')
                ->withErrors(['error' => 'Callback tidak valid. Pastikan redirect URI di Google Cloud Console sama dengan: ' . config('services.google.redirect')]);
        }

        try {
            // Ambil context dari session atau dari state parameter
            $context = $request->session()->get('google_login_context', 'user');
            
            // Backup: coba ambil dari state parameter jika session hilang
            if ($context === 'user' && $request->has('state')) {
                try {
                    $state = json_decode(base64_decode($request->input('state')), true);
                    if (isset($state['context'])) {
                        $context = $state['context'];
                        // Simpan kembali ke session
                        $request->session()->put('google_login_context', $context);
                        \Log::info('Context retrieved from state parameter', ['context' => $context]);
                    }
                } catch (\Exception $e) {
                    \Log::warning('Failed to decode state parameter: ' . $e->getMessage());
                }
            }
            
            \Log::info('Context retrieved', ['context' => $context, 'source' => $request->session()->has('google_login_context') ? 'session' : 'state']);
            
            // Handle jika user menolak akses
            if ($request->has('error')) {
                $error = $request->input('error');
                \Log::warning('Google OAuth access denied', ['error' => $error]);
                return redirect()->route($context === 'admin' ? 'admin.login' : 'login')
                    ->withErrors(['error' => 'Akses Google ditolak. Silakan coba lagi.']);
            }

            // Gunakan stateless() untuk menghindari masalah session dengan Google OAuth
            // Stateless lebih reliable untuk OAuth callback
            \Log::info('Attempting to get user from Google');
            try {
                $googleUser = Socialite::driver('google')->stateless()->user();
            } catch (\Exception $e) {
                // Jika stateless gagal, coba dengan session
                \Log::warning('Failed to get user with stateless, trying with session: ' . $e->getMessage());
                $googleUser = Socialite::driver('google')->user();
            }
            \Log::info('Google user retrieved', ['email' => $googleUser->getEmail(), 'id' => $googleUser->getId()]);

            // Cari user berdasarkan email atau google_id
            $user = User::where('email', $googleUser->getEmail())
                ->orWhere('google_id', $googleUser->getId())
                ->first();

            if (!$user) {
                if ($context === 'admin') {
                    return redirect()->route('admin.login')
                        ->withErrors(['email' => 'Akun admin tidak ditemukan untuk email ini.'])
                        ->withInput(['email' => $googleUser->getEmail()]);
                }

                // Buat user baru untuk login user biasa
                // User dari Google login langsung aktif (is_active = true) karena sudah verified oleh Google
                $user = User::create([
                    'nama' => $googleUser->getName(),
                    'email' => strtolower(trim($googleUser->getEmail())),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => Hash::make(Str::random(32)), // Random password untuk Google login
                    'role' => 'user',
                    'is_active' => true, // User Google langsung aktif
                    'email_verified_at' => now(), // Email sudah verified oleh Google
                ]);
                \Log::info('New user created from Google login', ['email' => $user->email, 'id' => $user->id]);
            } else {
                // Update google_id dan avatar jika belum ada
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

            // Pastikan user aktif sebelum login
            if (!$user->is_active) {
                // Aktifkan user jika belum aktif (untuk user yang sudah ada)
                $user->update([
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]);
                \Log::info('User activated from Google login', ['email' => $user->email]);
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
                'redirect_to' => $user->isAdmin() ? 'admin.dashboard' : 'dashboard',
                'auth_check' => Auth::check(),
                'auth_user_id' => Auth::id()
            ]);

            // Redirect berdasarkan role - PASTIKAN TIDAK ADA REDIRECT LOOP
            // Gunakan redirect langsung dengan route() untuk memastikan middleware berjalan dengan benar
            if ($user->isAdmin()) {
                \Log::info('Redirecting admin to admin.dashboard');
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Login Google berhasil sebagai Admin.');
            }

            \Log::info('Redirecting user to dashboard');
            return redirect()->route('dashboard')
                ->with('success', 'Login Google berhasil.');
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Google OAuth Callback Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'context' => $request->session()->get('google_login_context', 'user'),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            $context = $request->session()->get('google_login_context', 'user');
            return redirect()->route($context === 'admin' ? 'admin.login' : 'login')
                ->withErrors(['error' => 'Login Google gagal: ' . $e->getMessage() . '. Silakan coba lagi atau gunakan email dan password.']);
        }
    }
}

