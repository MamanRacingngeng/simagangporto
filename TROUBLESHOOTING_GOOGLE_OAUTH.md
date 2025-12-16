# Troubleshooting Google OAuth - Masalah Redirect ke Pilih Akun

## Masalah
Setelah memilih akun di Google dan klik "Lanjutkan", masih diarahkan ke halaman pilih akun lagi, bukan ke dashboard.

## Penyebab Umum
1. **Redirect URI tidak cocok** - URI di Google Cloud Console tidak sama dengan yang dikonfigurasi
2. **Callback tidak pernah dipanggil** - Google tidak redirect ke callback URL
3. **Masalah dengan consent** - Google masih meminta consent berulang kali

## Solusi Langkah Demi Langkah

### Langkah 1: Verifikasi Konfigurasi .env

Pastikan file `.env` Anda memiliki konfigurasi berikut:

```env
APP_URL=http://127.0.0.1:8000
GOOGLE_CLIENT_ID=your-client-id-here
GOOGLE_CLIENT_SECRET=your-client-secret-here
```

**PENTING:** 
- Pastikan `APP_URL` tidak ada trailing slash (`/`)
- Pastikan `GOOGLE_CLIENT_ID` dan `GOOGLE_CLIENT_SECRET` benar

### Langkah 2: Verifikasi Redirect URI di Google Cloud Console

1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Pilih project Anda
3. Buka **APIs & Services** → **Credentials**
4. Klik pada OAuth 2.0 Client ID Anda
5. Di bagian **Authorized redirect URIs**, pastikan ada:
   - `http://127.0.0.1:8000/oauth/google/callback` (untuk development)
   - Atau `https://yourdomain.com/oauth/google/callback` (untuk production)

**PENTING:**
- URI harus **SAMA PERSIS** (case sensitive, tidak ada trailing slash)
- Jika menggunakan `http://localhost:8000`, ubah menjadi `http://127.0.0.1:8000`
- Jangan ada spasi di awal atau akhir URI

### Langkah 3: Clear Cache Laravel

Jalankan perintah berikut di terminal:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

Kemudian restart server:

```bash
php artisan serve
```

### Langkah 4: Test Callback Route

Buka browser dan akses:
```
http://127.0.0.1:8000/oauth/google/callback?test=1
```

Jika muncul error atau halaman kosong, berarti route bisa diakses. Jika muncul 404, berarti ada masalah dengan routing.

### Langkah 5: Periksa Log Laravel

Setelah mencoba login dengan Google, periksa file log:
```
storage/logs/laravel.log
```

Cari log yang berisi:
- `"Redirecting to Google OAuth"` - berarti redirect ke Google berhasil
- `"=== Google OAuth Callback CALLED ==="` - berarti callback dipanggil

**Jika TIDAK ada log "=== Google OAuth Callback CALLED ==="**, berarti:
- Callback tidak pernah dipanggil
- Redirect URI di Google Cloud Console tidak cocok
- Google tidak redirect ke callback URL

### Langkah 6: Verifikasi Redirect URI yang Digunakan

Tambahkan log di `app/Http/Controllers/SocialLoginController.php` untuk melihat redirect URI yang digunakan:

File sudah memiliki log di line 31:
```php
'redirect_uri' => config('services.google.redirect'),
```

Periksa log dan pastikan redirect URI di log sama dengan yang ada di Google Cloud Console.

### Langkah 7: Test dengan URL Langsung

Coba akses langsung URL OAuth:
```
http://127.0.0.1:8000/oauth/google/user
```

Ini akan redirect ke Google. Setelah memilih akun dan klik "Lanjutkan", periksa:
1. Apakah redirect ke callback URL?
2. Apakah muncul log "=== Google OAuth Callback CALLED ==="?

### Langkah 8: Periksa Browser Console

Buka Developer Tools (F12) di browser, pergi ke tab **Network**:
1. Klik "Masuk dengan Google"
2. Perhatikan request yang terjadi
3. Setelah memilih akun dan klik "Lanjutkan", periksa:
   - Apakah ada request ke `/oauth/google/callback`?
   - Apa status code-nya? (harus 200 atau 302)
   - Apa response-nya?

### Langkah 9: Pastikan Tidak Ada Redirect Loop

Jika callback dipanggil tapi masih redirect ke Google, berarti ada redirect loop. Periksa:
1. Apakah ada middleware yang redirect ke login?
2. Apakah session hilang setelah callback?
3. Apakah ada error di log yang menyebabkan redirect kembali?

### Langkah 10: Reset Google OAuth Consent

Jika masih bermasalah, reset consent di Google:
1. Buka [Google Account Settings](https://myaccount.google.com/)
2. Klik **Security** → **Third-party apps with account access**
3. Cari aplikasi Anda dan klik **Remove access**
4. Coba login lagi

## Checklist Final

Sebelum mencoba lagi, pastikan:

- [ ] `APP_URL` di `.env` benar (tidak ada trailing slash)
- [ ] `GOOGLE_CLIENT_ID` dan `GOOGLE_CLIENT_SECRET` benar
- [ ] Redirect URI di Google Cloud Console sama persis dengan `{APP_URL}/oauth/google/callback`
- [ ] Sudah clear cache Laravel
- [ ] Server sudah di-restart
- [ ] Tidak ada error di log Laravel
- [ ] Callback route bisa diakses (test dengan URL langsung)

## Jika Masih Bermasalah

Jika setelah semua langkah di atas masih bermasalah:

1. **Kirimkan log Laravel** - File `storage/logs/laravel.log` setelah mencoba login
2. **Kirimkan screenshot** - Screenshot dari:
   - Google Cloud Console (bagian Authorized redirect URIs)
   - Browser Network tab (setelah klik "Lanjutkan")
   - Error message jika ada

3. **Informasi tambahan:**
   - URL aplikasi yang digunakan (localhost atau production)
   - Versi Laravel
   - Versi Socialite package

## Solusi Alternatif

Jika masalah masih terjadi, coba gunakan `stateless()` di redirect juga:

Ubah di `app/Http/Controllers/SocialLoginController.php` line 39-43:

```php
return Socialite::driver('google')
    ->stateless()  // Tambahkan ini
    ->with([
        'state' => base64_encode(json_encode(['context' => $context]))
    ])
    ->redirect();
```

Namun, ini akan membuat session tidak tersimpan, jadi context harus diambil dari state parameter saja.

