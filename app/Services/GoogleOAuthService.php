<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;

class GoogleOAuthService
{
    /**
     * Redirect user to Google OAuth consent screen
     * Menggunakan state management via cache dan session sebagai backup
     */
    public function redirectToGoogle(string $context = 'user'): \Illuminate\Http\RedirectResponse
    {
        try {
            $allowedContexts = ['user', 'admin'];
            $context = in_array($context, $allowedContexts) ? $context : 'user';

            $redirectUri = config('services.google.redirect');
            
            \Log::info('Redirecting to Google OAuth', [
                'context' => $context,
                'redirect_uri' => $redirectUri,
                'app_url' => config('app.url'),
            ]);

            // Pastikan redirect URI sudah benar
            if (empty($redirectUri)) {
                throw new \Exception('Redirect URI tidak dikonfigurasi. Pastikan APP_URL di .env sudah diisi.');
            }

            // Generate unique state token dan simpan di cache DAN session sebagai backup
            $stateToken = Str::random(40);
            Cache::put('oauth_state_' . $stateToken, $context, now()->addMinutes(10));
            
            // Simpan juga di session sebagai backup
            session()->put('oauth_state_token', $stateToken);
            session()->put('oauth_context', $context);
            session()->save();

            // Gunakan redirect dengan state token
            // JANGAN gunakan stateless() karena bisa menyebabkan masalah dengan state
            return Socialite::driver('google')
                ->redirectUrl($redirectUri)
                ->with([
                    'state' => $stateToken, 
                    'prompt' => 'consent', // Memaksa Google menampilkan consent screen
                    'access_type' => 'offline',
                ])
                ->redirect();
        } catch (\Exception $e) {
            \Log::error('Google OAuth Redirect Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Handle callback from Google OAuth
     * Menggunakan state dari cache dan session sebagai backup
     */
    public function handleCallback(?string $stateToken = null): array
    {
        try {
            // Ambil state token dari parameter jika tidak diberikan
            if (!$stateToken && request()->has('state')) {
                $stateToken = request()->input('state');
            }
            
            // Ambil context dari state token (cache) atau session sebagai backup
            $context = 'user';
            if ($stateToken) {
                // Coba ambil dari cache dulu
                $context = Cache::get('oauth_state_' . $stateToken, null);
                
                // Jika tidak ada di cache, coba ambil dari session
                if (!$context) {
                    $context = session()->get('oauth_context', 'user');
                    \Log::warning('State token not found in cache, using session context', [
                        'state_token' => $stateToken,
                        'context_from_session' => $context,
                    ]);
                } else {
                    // Hapus state token setelah digunakan (one-time use)
                    Cache::forget('oauth_state_' . $stateToken);
                }
            } else {
                // Fallback ke session jika state token tidak ada
                $context = session()->get('oauth_context', 'user');
                \Log::warning('No state token provided, using session context', [
                    'context_from_session' => $context,
                ]);
            }

            \Log::info('=== Google OAuth Service: Handling Callback ===', [
                'context' => $context,
                'state_token' => $stateToken ? 'EXISTS' : 'MISSING',
                'has_code' => request()->has('code'),
            ]);

            // Handle jika user menolak akses
            if (request()->has('error')) {
                \Log::warning('Google OAuth access denied', ['error' => request()->input('error')]);
                throw new \Exception('Akses Google ditolak. Silakan coba lagi.');
            }

            // Pastikan ada authorization code
            if (!request()->has('code')) {
                throw new \Exception('Authorization code tidak ditemukan. Silakan coba lagi.');
            }

            // Pastikan redirect URI sama dengan yang digunakan saat redirect
            $redirectUri = config('services.google.redirect');
            
            \Log::info('Attempting to get user from Google', [
                'redirect_uri' => $redirectUri,
                'has_code' => request()->has('code'),
                'has_state' => request()->has('state'),
            ]);
            
            // JANGAN gunakan stateless() - biarkan Socialite menggunakan session normal
            // Ini akan menghindari masalah dengan state dan session
            $googleUser = Socialite::driver('google')
                ->redirectUrl($redirectUri)
                ->user();
            
            \Log::info('Google user retrieved', [
                'email' => $googleUser->getEmail(),
                'id' => $googleUser->getId(),
                'name' => $googleUser->getName(),
            ]);

            // Cari atau buat user
            $user = $this->findOrCreateUser($googleUser, $context);

            // Validasi untuk admin
            if ($context === 'admin' && !$user->isAdmin()) {
                throw new \Exception('Akun ini tidak memiliki akses admin.');
            }

            // Pastikan user aktif
            if (!$user->is_active) {
                $user->update([
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]);
            }

            // Hapus state token dari session setelah digunakan
            session()->forget('oauth_state_token');
            session()->forget('oauth_context');
            
            // Login user
            Auth::login($user, true);
            
            // Regenerate session untuk keamanan
            session()->regenerate();
            
            // Pastikan session disimpan
            session()->save();

            \Log::info('User logged in successfully via Google', [
                'email' => $user->email,
                'role' => $user->role,
                'is_admin' => $user->isAdmin(),
                'auth_check' => Auth::check(),
                'auth_user_id' => Auth::id(),
                'session_id' => session()->getId(),
            ]);

            return [
                'success' => true,
                'user' => $user,
                'redirect_route' => $user->isAdmin() ? 'admin.dashboard' : 'dashboard',
                'message' => $user->isAdmin() ? 'Login Google berhasil sebagai Admin.' : 'Login Google berhasil.'
            ];
        } catch (\Exception $e) {
            \Log::error('Google OAuth Service Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }

    /**
     * Find or create user from Google OAuth data
     */
    protected function findOrCreateUser(SocialiteUser $googleUser, string $context): User
    {
        // Cari user berdasarkan email atau google_id
        $user = User::where('email', strtolower(trim($googleUser->getEmail())))
            ->orWhere('google_id', $googleUser->getId())
            ->first();

        if (!$user) {
            // Jika context admin dan user tidak ditemukan, throw error
            if ($context === 'admin') {
                throw new \Exception('Akun admin tidak ditemukan untuk email ini.');
            }

            // Buat user baru untuk context user
            $user = User::create([
                'nama' => $googleUser->getName() ?? 'User',
                'email' => strtolower(trim($googleUser->getEmail())),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => Hash::make(Str::random(32)), // Random password untuk OAuth user
                'role' => 'user',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
            
            \Log::info('New user created from Google login', [
                'email' => $user->email,
                'google_id' => $user->google_id
            ]);
        } else {
            // Update google_id dan avatar jika belum ada atau berbeda
            $updateData = [];
            
            if (!$user->google_id || $user->google_id !== $googleUser->getId()) {
                $updateData['google_id'] = $googleUser->getId();
            }
            
            if ($googleUser->getAvatar() && (!$user->avatar || $user->avatar !== $googleUser->getAvatar())) {
                $updateData['avatar'] = $googleUser->getAvatar();
            }
            
            // Update nama jika dari Google lebih lengkap
            if ($googleUser->getName() && $googleUser->getName() !== $user->nama) {
                $updateData['nama'] = $googleUser->getName();
            }
            
            if (!empty($updateData)) {
                $user->update($updateData);
                \Log::info('User updated from Google login', [
                    'email' => $user->email,
                    'updated_fields' => array_keys($updateData)
                ]);
            }
        }

        return $user;
    }
}

