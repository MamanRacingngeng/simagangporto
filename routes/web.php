<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PendaftarController;
use App\Http\Controllers\MagangController;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\SocialiteController;

// Landing page (public)
Route::get('/', [WelcomeController::class, 'index'])->name('home');
Route::get('/tentang-kami', [WelcomeController::class, 'tentangKami'])->name('tentang-kami');

// Galeri Magang (public - tidak perlu login)
Route::get('/galeri-magang', [DashboardController::class, 'galeriMagang'])->name('galeri-magang');

// ========== AUTH ROUTES (PUBLIC) ==========
// Registrasi Akun (Pendaftar)
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'registerWeb'])->name('register.post');

// Login (Pendaftar)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'loginWeb'])->name('login.post');

// Email Verification
Route::get('/verify', [AuthController::class, 'verify'])->name('verification.verify');
Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail'])->name('verification.verify.legacy');
Route::post('/verification/resend', [AuthController::class, 'resendVerificationEmail'])->name('verification.resend');

// Google OAuth (user & admin) - menggunakan SocialiteController
Route::get('/oauth/google/{context?}', [SocialiteController::class, 'redirect'])
    ->name('oauth.google.redirect');

// Callback route - PASTIKAN INI DIPANGGIL
Route::get('/oauth/google/callback', [SocialiteController::class, 'callback'])
    ->name('oauth.google.callback');

// Route alternatif untuk debugging - tangkap semua kemungkinan callback URL
Route::any('/auth/google/callback', function(Request $request) {
    \Log::info('=== ALTERNATIVE CALLBACK ROUTE HIT ===', [
        'url' => $request->fullUrl(),
        'method' => $request->method(),
        'all_params' => $request->all(),
    ]);
    // Redirect ke callback yang benar
    return redirect()->route('oauth.google.callback', $request->all());
})->name('oauth.google.callback.alt');

// Test route untuk memastikan callback bisa diakses
Route::get('/test-callback', function() {
    return response()->json([
        'message' => 'Callback route is accessible',
        'timestamp' => now(),
        'params' => request()->all(),
        'redirect_uri' => config('services.google.redirect'),
        'app_url' => config('app.url'),
        'server_running' => true,
    ]);
})->name('test.callback');

// Test route untuk memverifikasi callback OAuth bisa diakses
Route::get('/test-oauth-callback', function() {
    \Log::info('Test OAuth Callback Route Accessed', [
        'url' => request()->fullUrl(),
        'params' => request()->all(),
    ]);
    
    return response()->json([
        'message' => 'OAuth callback route is accessible',
        'timestamp' => now(),
        'params' => request()->all(),
        'redirect_uri' => config('services.google.redirect'),
        'app_url' => config('app.url'),
        'callback_route' => route('oauth.google.callback'),
        'note' => 'Jika route ini bisa diakses, berarti callback route juga bisa diakses. Pastikan redirect URI di Google Cloud Console sama dengan: ' . config('services.google.redirect'),
    ]);
})->name('test.oauth.callback');

// Debug route untuk melihat konfigurasi Google OAuth
Route::get('/debug-oauth', function() {
    return response()->json([
        'app_url' => config('app.url'),
        'google_redirect' => config('services.google.redirect'),
        'google_client_id' => config('services.google.client_id') ? 'SET' : 'NOT SET',
        'google_client_secret' => config('services.google.client_secret') ? 'SET' : 'NOT SET',
        'expected_callback_url' => config('app.url') . '/oauth/google/callback',
        'callback_route_exists' => Route::has('oauth.google.callback'),
    ]);
})->name('debug.oauth');

// ========== PENDAFTAR ROUTES ==========
// Middleware untuk memastikan hanya user biasa (bukan admin) yang bisa akses route user
Route::middleware(['auth', 'user'])->group(function () {
    // Logout Pendaftar
    Route::post('/logout', [AuthController::class, 'logoutWeb'])->name('logout');

    // Mengisi Data Diri
    Route::get('/isi-data-diri', [PendaftarController::class, 'isiDataDiri'])->name('pendaftar.isi_data_diri');
    Route::post('/isi-data-diri', [PendaftarController::class, 'updateDataDiri'])->name('pendaftar.update_data_diri');

    // Unggah Dokumen
    Route::get('/unggah-dokumen', [PendaftarController::class, 'unggahDokumen'])->name('pendaftar.unggah_dokumen');
    Route::post('/unggah-dokumen', [PendaftarController::class, 'storeDokumen'])->name('pendaftar.store_dokumen');
    Route::delete('/unggah-dokumen/{id}', [PendaftarController::class, 'deleteDokumen'])->name('pendaftar.delete_dokumen');
    Route::delete('/unggah-dokumen/{id}/{field}', [PendaftarController::class, 'deleteDokumenField'])->name('pendaftar.delete_dokumen_field');

    // Ajukan Permohonan Magang (Sequence 2: Pengajuan Permohonan Magang)
    Route::get('/ajukan-permohonan', [MagangController::class, 'showFormPengajuan'])->name('pendaftar.ajukan_permohonan');
    Route::post('/ajukan-permohonan', [MagangController::class, 'storePengajuan'])->name('pendaftar.store_permohonan');

    // Lihat Status Permohonan
    Route::get('/status-permohonan', [PendaftarController::class, 'lihatStatusPermohonan'])->name('pendaftar.status_permohonan');

    // Legacy routes (for backward compatibility)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/lowongan', [DashboardController::class, 'lowongan'])->name('lowongan');
    Route::get('/lamaran', [DashboardController::class, 'lamaran'])->name('lamaran');
    Route::get('/riwayat-lamaran', [DashboardController::class, 'riwayatLamaran'])->name('riwayat.lamaran');
    Route::get('/panduan-onboarding', [DashboardController::class, 'panduanOnboarding'])->name('panduan.onboarding');
    Route::get('/profil', [DashboardController::class, 'profil'])->name('profil');
    Route::post('/profil', [DashboardController::class, 'updateProfil'])->name('profil.update');
    
    // Notifikasi
    Route::post('/notifikasi/{id}/baca', [DashboardController::class, 'tandaiNotifikasiDibaca'])->name('notifikasi.baca');
    Route::get('/laporan', [DashboardController::class, 'laporan'])->name('laporan');
    Route::post('/laporan', [DashboardController::class, 'storeLaporan'])->name('laporan.store');
    Route::get('/penugasan', [DashboardController::class, 'penugasan'])->name('penugasan');
    Route::get('/sertifikat', [DashboardController::class, 'sertifikat'])->name('sertifikat');
    Route::get('/status-lamaran', [DashboardController::class, 'statusLamaran'])->name('status.lamaran.json');
});

// ========== ADMIN ROUTES ==========
// Login Admin
Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Logout Admin
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

    // Dashboard Admin
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Activity Diagram - Admin Verifikasi Permohonan Magang
    // Admin memilih menu "Data Pendaftar"
    Route::get('/data-pendaftar', [AdminController::class, 'lihatDataPendaftar'])->name('data_pendaftar');
    
    // Admin membuka detail permohonan
    Route::get('/data-pendaftar/{id}', [AdminController::class, 'detailPendaftar'])->name('detail_pendaftar');
    
    // Verifikasi Permohonan (Activity Diagram: Admin mengecek dokumen, validasi, cek kuota)
    Route::post('/verifikasi-permohonan/{id}', [AdminController::class, 'verifikasiPermohonan'])->name('verifikasi_permohonan');

    // Verifikasi Dokumen (legacy - bisa digunakan untuk verifikasi manual)
    Route::get('/verifikasi-dokumen', [AdminController::class, 'verifikasiDokumen'])->name('verifikasi_dokumen');
    Route::post('/verifikasi-dokumen/{id}', [AdminController::class, 'updateVerifikasiDokumen'])->name('update_verifikasi_dokumen');

    // Ubah Status Permohonan (manual update jika diperlukan)
    Route::get('/ubah-status-permohonan', [AdminController::class, 'ubahStatusPermohonan'])->name('ubah_status_permohonan');
    Route::post('/ubah-status-permohonan/{id}', [AdminController::class, 'updateStatusPermohonan'])->name('update_status_permohonan');
    
    // Hapus Permohonan (untuk semua status)
    Route::delete('/delete-permohonan/{id}', [AdminController::class, 'deletePermohonan'])->name('delete_permohonan');

    // Atur Kuota Magang
    Route::get('/atur-kuota-magang', [AdminController::class, 'aturKuotaMagang'])->name('atur_kuota_magang');
    Route::post('/atur-kuota-magang', [AdminController::class, 'storeKuotaMagang'])->name('store_kuota_magang');
    Route::put('/atur-kuota-magang/{id}', [AdminController::class, 'updateKuotaMagang'])->name('update_kuota_magang');
    Route::delete('/atur-kuota-magang/{id}', [AdminController::class, 'deleteKuotaMagang'])->name('delete_kuota_magang');

    // Notifikasi Kekurangan Syarat
    Route::get('/notifikasi-kekurangan-syarat', [AdminController::class, 'notifikasiKekuranganSyarat'])->name('notifikasi_kekurangan_syarat');
    Route::get('/kirim-notifikasi/{id?}', [AdminController::class, 'kirimNotifikasi'])->name('kirim_notifikasi');
    Route::post('/kirim-notifikasi', [AdminController::class, 'storeNotifikasi'])->name('store_notifikasi');
    
    // Pengawasan Sumber Daya
    Route::get('/pengawasan-sumber-daya', [AdminController::class, 'pengawasanSumberDaya'])->name('pengawasan_sumber_daya');
    
    // Manajemen Galeri Magang
    Route::get('/kelola-galeri', [AdminController::class, 'kelolaGaleri'])->name('kelola_galeri');
    Route::post('/galeri', [AdminController::class, 'storeGaleri'])->name('store_galeri');
    Route::post('/galeri/{id}', [AdminController::class, 'updateGaleri'])->name('update_galeri');
    Route::delete('/galeri/{id}', [AdminController::class, 'deleteGaleri'])->name('delete_galeri');

    // Atur Jadwal Magang
    Route::get('/atur-jadwal-magang', [AdminController::class, 'aturJadwalMagang'])->name('atur_jadwal_magang');
    Route::post('/atur-jadwal-magang', [AdminController::class, 'storeJadwalMagang'])->name('store_jadwal_magang');
    Route::put('/atur-jadwal-magang/{id}', [AdminController::class, 'updateJadwalMagang'])->name('update_jadwal_magang');
    Route::delete('/atur-jadwal-magang/{id}', [AdminController::class, 'deleteJadwalMagang'])->name('delete_jadwal_magang');

    // Kelola Data Pendaftar
    Route::get('/kelola-data-pendaftar', [AdminController::class, 'kelolaDataPendaftar'])->name('kelola_data_pendaftar');
    Route::put('/kelola-data-pendaftar/{id}', [AdminController::class, 'updateDataPendaftar'])->name('update_data_pendaftar');
    Route::delete('/kelola-data-pendaftar/{id}', [AdminController::class, 'deleteDataPendaftar'])->name('delete_data_pendaftar');
});

// Optional: UI Varian A (Top Navbar) - akses cepat tanpa mengganggu UI lama
Route::middleware('auth')->prefix('app2')->group(function () {
    Route::view('/dashboard', 'app_top.dashboard')->name('app2.dashboard');
    Route::view('/lowongan', 'app_top.lowongan')->name('app2.lowongan');
    Route::view('/lamaran', 'app_top.lamaran')->name('app2.lamaran');
    Route::view('/profil', 'app_top.profil')->name('app2.profil');
});
