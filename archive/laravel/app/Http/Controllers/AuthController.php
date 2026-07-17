<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // -------- WEB AUTH (Session) --------
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        // Baca dari env dengan trim untuk menghilangkan spasi
        $recaptchaSiteKey = trim(env('RECAPTCHA_SITE_KEY', ''));
        $recaptchaSecretKey = trim(env('RECAPTCHA_SECRET_KEY', ''));
        
        // Cek apakah kedua kunci tidak kosong
        $recaptchaEnabled = !empty($recaptchaSiteKey) && !empty($recaptchaSecretKey);
        
        // Debug: Log untuk memastikan kunci terdeteksi (hapus di production)
        if (config('app.debug')) {
            \Log::info('reCAPTCHA Config Check', [
                'site_key_exists' => !empty($recaptchaSiteKey),
                'secret_key_exists' => !empty($recaptchaSecretKey),
                'enabled' => $recaptchaEnabled,
            ]);
        }
        
        return view('auth.register', [
            'recaptchaEnabled' => $recaptchaEnabled,
            'recaptchaSiteKey' => $recaptchaSiteKey,
        ]);
    }

    /**
     * Login untuk User (Pendaftar)
     * Hanya user dengan role 'user' yang bisa login melalui route ini
     * Admin harus login melalui /admin/login
     */
    public function loginWeb(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        // Normalize email: trim dan lowercase
        $email = strtolower(trim($credentials['email']));
        $password = $credentials['password'];

        // Cek apakah user ada
        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email atau kata sandi salah.',
            ])->onlyInput('email');
        }

        // Cek password
        if (!Hash::check($password, $user->password)) {
            return back()->withErrors([
                'email' => 'Email atau kata sandi salah.',
            ])->onlyInput('email');
        }

        // Cek apakah akun sudah diaktifkan (is_active = true)
        if (!$user->is_active) {
            // Jika user belum punya token verifikasi, generate token baru
            if (!$user->email_verification_token) {
                $verificationToken = Str::random(64);
                $user->update([
                    'email_verification_token' => $verificationToken,
                ]);
                
                // Kirim email verifikasi ke email user yang login (bukan hardcoded)
                try {
                    // Validasi konfigurasi email
                    $mailUsername = env('MAIL_USERNAME');
                    $mailPassword = env('MAIL_PASSWORD');
                    
                    if (!empty($mailUsername) && 
                        $mailUsername !== 'emailpengirimygmaudipake' && 
                        filter_var($mailUsername, FILTER_VALIDATE_EMAIL) &&
                        !empty($mailPassword) &&
                        $mailPassword !== 'kodegoggleemail') {
                        Mail::to($user->email)->send(new VerifyEmail($user, $verificationToken));
                        \Log::info('Verification email sent to user on login attempt: ' . $user->email);
                    } else {
                        \Log::warning('Email configuration not set, skipping email send for: ' . $user->email);
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to send verification email to ' . $user->email . ': ' . $e->getMessage());
                }
            }
            
            return back()->withErrors([
                'email' => 'Akun Anda belum diaktifkan. Silakan cek email Anda dan klik tautan verifikasi untuk mengaktifkan akun. Jika belum menerima email, klik link di bawah untuk kirim ulang.',
            ])->with('resend_verification', true)->onlyInput('email');
        }

        // Login user
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        
        // Validasi: Hanya user biasa yang bisa login melalui route ini
        if ($user->isAdmin()) {
            // Jika admin mencoba login melalui route user, logout dan redirect ke admin login
            Auth::logout();
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'Admin harus login melalui halaman login admin.'])
                ->withInput($request->only('email'));
        }
        
        // User biasa berhasil login
        return redirect()->intended(route('dashboard'))
            ->with('success', 'Login berhasil! Selamat datang.');
    }

    /**
     * Activity Diagram - Pendaftaran Magang
     * User membuka halaman pendaftaran
     * User klik "Registrasi"
     * Decision: Data yang diisi lengkap?
     *   - Ya: Sistem menyimpan data akun → User melakukan login
     *   - Tidak: Sistem menampilkan pesan kesalahan
     */
    public function registerWeb(Request $request)
    {
        // Validasi data lengkap sesuai activity diagram
        $recaptchaSiteKey = env('RECAPTCHA_SITE_KEY');
        $recaptchaSecretKey = env('RECAPTCHA_SECRET_KEY');
        $recaptchaEnabled = !empty($recaptchaSiteKey) && !empty($recaptchaSecretKey);
        
        $validationRules = [
            'nama' => ['required', 'string', 'max:255'], // Sesuai ERD: nama bukan name
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
        
        $validationMessages = [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ];
        
        // Hanya wajibkan reCAPTCHA jika sudah dikonfigurasi
        if ($recaptchaEnabled) {
            $validationRules['g-recaptcha-response'] = ['required'];
            $validationMessages['g-recaptcha-response.required'] = 'Silakan verifikasi bahwa Anda bukan robot.';
        }
        
        $data = $request->validate($validationRules, $validationMessages);

        // Cek apakah email sudah terdaftar
        $existingUser = User::where('email', strtolower(trim($data['email'])))->first();
        
        if ($existingUser) {
            // Generate token verifikasi baru (selalu generate baru untuk memastikan token fresh)
            $verificationToken = Str::random(64);
            
            // Update data user yang sudah ada (update nama, password, dan reset status verifikasi)
            $existingUser->update([
                'nama' => $data['nama'],
                'password' => bcrypt($data['password']),
                'email_verification_token' => $verificationToken,
                'email_verified_at' => null,
                'is_active' => false, // Reset status aktif agar perlu verifikasi ulang
            ]);
            
            // Kirim email verifikasi ke email user yang sudah terdaftar
            try {
                // Validasi konfigurasi email
                $mailUsername = env('MAIL_USERNAME');
                $mailPassword = env('MAIL_PASSWORD');
                
                if (empty($mailUsername) || 
                    $mailUsername === 'emailpengirimygmaudipake' || 
                    !filter_var($mailUsername, FILTER_VALIDATE_EMAIL) ||
                    empty($mailPassword) ||
                    $mailPassword === 'kodegoggleemail') {
                    return redirect()->route('login')
                        ->with('error', 'Registrasi berhasil, namun konfigurasi email belum lengkap. Silakan hubungi administrator untuk mengaktifkan email verifikasi.');
                }
                
                // Kirim email ke email user yang sudah terdaftar (bukan hardcoded)
                Mail::to($existingUser->email)->send(new VerifyEmail($existingUser, $verificationToken));
                \Log::info('Verification email sent to existing user (re-registration): ' . $existingUser->email);
                
                return redirect()->route('login')
                    ->with('success', 'Registrasi berhasil! Data akun telah diperbarui. Email verifikasi telah dikirim ke ' . $existingUser->email . '. Silakan cek inbox email Anda (termasuk folder Spam) untuk mengaktifkan akun.');
            } catch (\Exception $e) {
                \Log::error('Failed to send verification email to existing user ' . $existingUser->email . ': ' . $e->getMessage());
                return redirect()->route('login')
                    ->with('error', 'Registrasi berhasil, namun email verifikasi gagal dikirim ke ' . $existingUser->email . '. Silakan gunakan fitur "Kirim Ulang Email Verifikasi" di halaman login atau hubungi administrator.');
            }
        }

        // Verifikasi reCAPTCHA (hanya jika sudah dikonfigurasi)
        if ($recaptchaEnabled) {
            $recaptchaResponse = $request->input('g-recaptcha-response');
            
            if (!$recaptchaResponse) {
                return back()->withErrors([
                    'g-recaptcha-response' => 'Silakan verifikasi bahwa Anda bukan robot.',
                ])->withInput($request->except('password', 'password_confirmation'));
            }
            
            $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecretKey}&response={$recaptchaResponse}");
            $responseData = json_decode($verifyResponse);
            
            if (!$responseData || !$responseData->success) {
                return back()->withErrors([
                    'g-recaptcha-response' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.',
                ])->withInput($request->except('password', 'password_confirmation'));
            }
        }

        // Generate unique verification token (minimal 32 karakter)
        $verificationToken = Str::random(64);

        // Decision: Data yang diisi lengkap? → Ya
        // Sistem menyimpan data akun dengan status is_active = FALSE
        $user = User::create([
            'nama' => $data['nama'],
            'email' => strtolower(trim($data['email'])),
            'password' => bcrypt($data['password']),
            'role' => 'user',
            'is_active' => false,
            'email_verification_token' => $verificationToken,
            'email_verified_at' => null,
        ]);

        // Kirim email verifikasi ke email user yang mendaftar
        try {
            // Validasi konfigurasi email sebelum kirim
            $mailUsername = env('MAIL_USERNAME');
            $mailPassword = env('MAIL_PASSWORD');
            $mailFrom = env('MAIL_FROM_ADDRESS');
            
            // Cek apakah email sudah dikonfigurasi dengan benar
            if (empty($mailUsername) || 
                $mailUsername === 'emailpengirimygmaudipake' || 
                !filter_var($mailUsername, FILTER_VALIDATE_EMAIL) ||
                empty($mailPassword) ||
                $mailPassword === 'kodegoggleemail') {
                \Log::warning('Email configuration is not set properly. User: ' . $user->email);
                return redirect()->route('login')
                    ->with('error', 'Email verifikasi tidak dapat dikirim karena konfigurasi email belum lengkap. Silakan hubungi administrator.');
            }
            
            // Kirim email ke email user yang mendaftar (bukan hardcoded)
            Mail::to($user->email)->send(new VerifyEmail($user, $verificationToken));
            \Log::info('Verification email sent successfully to: ' . $user->email);
            
            // Redirect dengan pesan sukses yang jelas
            return redirect()->route('login')
                ->with('success', 'Registrasi berhasil! Email verifikasi telah dikirim ke ' . $user->email . '. Silakan cek inbox email Anda (termasuk folder Spam) untuk mengaktifkan akun.');
                
        } catch (\Exception $e) {
            \Log::error('Failed to send verification email to ' . $user->email . ': ' . $e->getMessage());
            \Log::error('Email error details: ' . $e->getTraceAsString());
            
            // Tetap lanjutkan proses, tapi beri tahu user dengan pesan yang jelas
            return redirect()->route('login')
                ->with('error', 'Registrasi berhasil, namun email verifikasi gagal dikirim ke ' . $user->email . '. Silakan gunakan fitur "Kirim Ulang Email Verifikasi" di halaman login atau hubungi administrator.');
        }

        // Redirect sudah dilakukan di blok try-catch di atas
        // Kode ini tidak akan pernah dieksekusi, tapi tetap ada untuk safety
    }

    public function logoutWeb(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    /**
     * Verifikasi email user (endpoint lama: /verify-email/{token})
     */
    public function verifyEmail($token)
    {
        $user = User::where('email_verification_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Token verifikasi tidak valid atau sudah kedaluwarsa.']);
        }

        // Update user: hapus token, set email_verified_at, dan aktifkan akun
        $user->update([
            'email_verified_at' => now(),
            'email_verification_token' => null,
            'is_active' => true,
        ]);

        return redirect()->route('login')
            ->with('success', 'Email berhasil diverifikasi! Akun Anda telah diaktifkan. Silakan login untuk melanjutkan.');
    }

    /**
     * Verifikasi email user (endpoint baru: /verify?token=...)
     */
    public function verify(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Token verifikasi tidak ditemukan.']);
        }

        $user = User::where('email_verification_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Token verifikasi tidak valid atau sudah kedaluwarsa.']);
        }

        // Update user: hapus token, set email_verified_at, dan aktifkan akun
        $user->update([
            'email_verified_at' => now(),
            'email_verification_token' => null,
            'is_active' => true,
        ]);

        return redirect()->route('login')
            ->with('success', 'Email berhasil diverifikasi! Akun Anda telah diaktifkan. Silakan login untuk melanjutkan.');
    }

    /**
     * Kirim ulang email verifikasi
     * Bisa digunakan berkali-kali tanpa batasan
     */
    public function resendVerificationEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar.',
        ]);

        $user = User::where('email', $request->email)->first();

        // Jika sudah verified dan aktif, tetap izinkan kirim ulang (untuk keperluan testing atau reset)
        // Tapi beri informasi bahwa akun sudah aktif
        if ($user->email_verified_at && $user->is_active) {
            // Tetap generate token baru dan kirim email (untuk memungkinkan verifikasi ulang jika diperlukan)
            $verificationToken = Str::random(64);
            $user->update([
                'email_verification_token' => $verificationToken,
            ]);
            
            // Kirim email verifikasi meskipun sudah aktif (untuk keperluan testing atau reset)
            try {
                $mailUsername = env('MAIL_USERNAME');
                $mailPassword = env('MAIL_PASSWORD');
                
                if (empty($mailUsername) || 
                    $mailUsername === 'emailpengirimygmaudipake' || 
                    !filter_var($mailUsername, FILTER_VALIDATE_EMAIL) ||
                    empty($mailPassword) ||
                    $mailPassword === 'kodegoggleemail') {
                    return back()->withErrors(['email' => 'Konfigurasi email belum lengkap. Silakan hubungi administrator untuk mengaktifkan fitur email verifikasi.']);
                }
                
                Mail::to($user->email)->send(new VerifyEmail($user, $verificationToken));
                \Log::info('Verification email resent to active user: ' . $user->email);
                
                return back()->with('info', 'Email verifikasi telah dikirim ulang ke ' . $user->email . '. Catatan: Akun Anda sudah aktif, namun email verifikasi tetap dikirim untuk keperluan testing atau reset.');
            } catch (\Exception $e) {
                \Log::error('Failed to resend verification email to active user ' . $user->email . ': ' . $e->getMessage());
                return back()->withErrors(['email' => 'Gagal mengirim email verifikasi: ' . $e->getMessage() . '. Silakan coba lagi nanti atau hubungi administrator.']);
            }
        }

        // Generate token baru (selalu generate baru untuk memastikan token fresh)
        $verificationToken = Str::random(64);
        $user->update([
            'email_verification_token' => $verificationToken,
        ]);

        // Validasi konfigurasi email sebelum kirim
        $mailUsername = env('MAIL_USERNAME');
        $mailPassword = env('MAIL_PASSWORD');
        
        if (empty($mailUsername) || 
            $mailUsername === 'emailpengirimygmaudipake' || 
            !filter_var($mailUsername, FILTER_VALIDATE_EMAIL) ||
            empty($mailPassword) ||
            $mailPassword === 'kodegoggleemail') {
            \Log::warning('Email configuration is not set properly for resend verification.');
            return back()->withErrors(['email' => 'Konfigurasi email belum lengkap. Silakan hubungi administrator untuk mengaktifkan fitur email verifikasi.']);
        }

        // Kirim email verifikasi
        try {
            Mail::to($user->email)->send(new VerifyEmail($user, $verificationToken));
            \Log::info('Verification email resent successfully to: ' . $user->email);
            return back()->with('success', 'Email verifikasi telah dikirim ulang. Silakan cek inbox email Anda.');
        } catch (\Exception $e) {
            \Log::error('Failed to resend verification email to ' . $user->email . ': ' . $e->getMessage());
            return back()->withErrors(['email' => 'Gagal mengirim email verifikasi: ' . $e->getMessage() . '. Silakan coba lagi nanti atau hubungi administrator.']);
        }
    }

    // -------- API AUTH (Sanctum) --------
    public function login(Request $request)
    {
        $v = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($v->fails()) {
            return response()->json(['success' => false, 'errors' => $v->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Credensial tidak valid'], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(['success' => true, 'token' => $token, 'user' => $user]);
    }

    public function register(Request $request)
    {
        $v = Validator::make($request->all(), [
            'nama' => 'required|string', // Sesuai ERD: nama bukan name
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        if ($v->fails()) {
            return response()->json(['success' => false, 'errors' => $v->errors()], 422);
        }

        $user = User::create([
            'nama' => $request->nama, // Sesuai ERD: nama bukan name
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'user', // Sesuai ERD: role adalah 'user' atau 'admin'
        ]);

        return response()->json(['success' => true, 'user' => $user]);
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            // revoke current token
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json(['success' => true]);
    }
}
