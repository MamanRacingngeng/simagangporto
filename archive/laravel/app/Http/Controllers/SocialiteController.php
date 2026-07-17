<?php

namespace App\Http\Controllers;

use App\Services\GoogleOAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class SocialiteController extends Controller
{
    protected $oauthService;

    public function __construct(GoogleOAuthService $oauthService)
    {
        $this->oauthService = $oauthService;
    }

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

            Log::info('SocialiteController: Redirecting to Google OAuth', [
                'context' => $context,
                'session_id' => $request->session()->getId(),
            ]);

            return $this->oauthService->redirectToGoogle($context);
        } catch (\Exception $e) {
            Log::error('Google OAuth Redirect Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            if ($context === 'admin') {
                return redirect('/admin/login')
                    ->withErrors(['error' => 'Terjadi kesalahan saat mengakses Google: ' . $e->getMessage()]);
            }
            return redirect('/login')
                ->withErrors(['error' => 'Terjadi kesalahan saat mengakses Google: ' . $e->getMessage()]);
        }
    }

    /**
     * Handle callback from Google OAuth.
     * INI ADALAH POINT KRITIS - Pastikan session tidak hilang
     */
    public function callback(Request $request)
    {
        // Log SEMUA request yang masuk ke callback - PASTIKAN INI DIPANGGIL
        Log::info('=== SocialiteController: Google OAuth Callback CALLED ===', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'has_code' => $request->has('code'),
            'has_error' => $request->has('error'),
            'code' => $request->input('code') ? 'EXISTS' : 'MISSING',
            'error' => $request->input('error'),
            'state' => $request->input('state') ? 'EXISTS' : 'MISSING',
            'all_params' => $request->all(),
            'session_id' => $request->session()->getId(),
            'session_data' => $request->session()->all(),
        ]);

        try {
            // Ambil state token dari request (dari Google)
            $stateToken = $request->input('state');
            
            // Backup: coba ambil dari session jika state token tidak ada
            $context = $request->session()->get('google_login_context', 'user');
            
            Log::info('Processing Google OAuth callback', [
                'state_token' => $stateToken ? 'EXISTS' : 'MISSING',
                'context_from_session' => $context,
                'session_id' => $request->session()->getId(),
                'has_code' => $request->has('code'),
                'has_error' => $request->has('error'),
            ]);

            // Handle callback menggunakan service dengan state token
            // Service akan mengambil context dari cache menggunakan state token
            $result = $this->oauthService->handleCallback($stateToken);

            // Hapus context dari session setelah berhasil
            $request->session()->forget('google_login_context');
            $request->session()->save();
            
            // Pastikan session disimpan sebelum redirect
            $request->session()->save();

            // Verifikasi login berhasil
            if (!auth()->check()) {
                Log::error('Login failed - user not authenticated after OAuth service', [
                    'user_id' => $result['user']->id ?? null,
                    'email' => $result['user']->email ?? null
                ]);
                throw new \Exception('Login gagal. User tidak terautentikasi setelah OAuth.');
            }

            Log::info('=== REDIRECTING TO DASHBOARD ===', [
                'email' => $result['user']->email,
                'role' => $result['user']->role,
                'redirect_route' => $result['redirect_route'],
                'auth_check' => auth()->check(),
                'auth_user_id' => auth()->id(),
                'session_id' => $request->session()->getId(),
            ]);

            // Pastikan session disimpan sebelum redirect
            $request->session()->save();
            
            // Redirect ke dashboard dengan menggunakan intended() untuk menghindari redirect loop
            $redirectRoute = $result['redirect_route'];
            
            // Redirect ke dashboard berdasarkan route
            if ($redirectRoute === 'admin.dashboard') {
                return redirect('/admin/dashboard')
                    ->with('success', $result['message']);
            }
            return redirect('/dashboard')
                ->with('success', $result['message']);

        } catch (\Exception $e) {
            Log::error('Google OAuth Callback Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'session_id' => $request->session()->getId(),
            ]);

            $context = $request->session()->get('google_login_context', 'user');
            if ($context === 'admin') {
                return redirect('/admin/login')
                    ->withErrors(['error' => 'Login Google gagal: ' . $e->getMessage()]);
            }
            return redirect('/login')
                ->withErrors(['error' => 'Login Google gagal: ' . $e->getMessage()]);
        }
    }
}
