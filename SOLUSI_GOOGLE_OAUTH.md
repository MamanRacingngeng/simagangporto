# Solusi Masalah Google OAuth - Redirect ke Pilih Akun

## Masalah
Setelah memilih akun di Google dan klik "Lanjutkan", masih diarahkan ke halaman pilih akun lagi, bukan ke dashboard.

## Solusi Cepat (Lakukan Langkah Ini)

### Langkah 1: Verifikasi Konfigurasi
Jalankan perintah ini di terminal untuk memverifikasi konfigurasi:

```bash
php artisan oauth:verify-google
```

Perintah ini akan menampilkan:
- Status konfigurasi APP_URL
- Status GOOGLE_CLIENT_ID dan GOOGLE_CLIENT_SECRET
- Redirect URI yang digunakan vs yang diharapkan
- Status route callback

### Langkah 2: Periksa Redirect URI di Google Cloud Console

1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Pilih project Anda
3. Buka **APIs & Services** → **Credentials**
4. Klik pada OAuth 2.0 Client ID Anda
5. Di bagian **Authorized redirect URIs**, pastikan ada:
   ```
   http://127.0.0.1:8000/oauth/google/callback
   ```
   **PENTING:** 
   - Harus sama persis (case sensitive)
   - Tidak ada trailing slash
   - Jika menggunakan localhost, ubah menjadi 127.0.0.1

### Langkah 3: Periksa File .env

Pastikan file `.env` memiliki:

```env
APP_URL=http://127.0.0.1:8000
GOOGLE_CLIENT_ID=your-client-id-here
GOOGLE_CLIENT_SECRET=your-client-secret-here
```

**PENTING:**
- `APP_URL` tidak boleh ada trailing slash (`/`)
- Jika menggunakan `http://localhost:8000`, ubah menjadi `http://127.0.0.1:8000`

### Langkah 4: Clear Cache

Jalankan perintah berikut:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

Kemudian restart server:

```bash
php artisan serve
```

### Langkah 5: Test Debug Endpoint

Buka browser dan akses:
```
http://127.0.0.1:8000/debug-oauth
```

Ini akan menampilkan JSON dengan informasi konfigurasi. Pastikan:
- `google_redirect` sama dengan `expected_callback_url`
- `google_client_id` dan `google_client_secret` adalah "SET"

### Langkah 6: Test Callback Route

Buka browser dan akses:
```
http://127.0.0.1:8000/oauth/google/callback?test=1
```

Jika muncul error atau halaman kosong, berarti route bisa diakses. Jika muncul 404, berarti ada masalah dengan routing.

### Langkah 7: Periksa Log

Setelah mencoba login dengan Google, periksa log:

```bash
tail -f storage/logs/laravel.log
```

Atau buka file `storage/logs/laravel.log` dan cari:
- `"Redirecting to Google OAuth"` - berarti redirect ke Google berhasil
- `"=== Google OAuth Callback CALLED ==="` - berarti callback dipanggil

**Jika TIDAK ada log "=== Google OAuth Callback CALLED ==="**, berarti:
- Callback tidak pernah dipanggil
- Redirect URI di Google Cloud Console tidak cocok
- Google tidak redirect ke callback URL

### Langkah 8: Reset Google OAuth Consent

Jika masih bermasalah, reset consent di Google:

1. Buka [Google Account Settings](https://myaccount.google.com/)
2. Klik **Security** → **Third-party apps with account access**
3. Cari aplikasi Anda dan klik **Remove access**
4. Coba login lagi

## Checklist

Sebelum mencoba lagi, pastikan semua ini sudah dilakukan:

- [ ] Sudah menjalankan `php artisan oauth:verify-google` dan semua status ✅
- [ ] Redirect URI di Google Cloud Console sama persis dengan yang ditampilkan di command
- [ ] `APP_URL` di `.env` benar (tidak ada trailing slash)
- [ ] `GOOGLE_CLIENT_ID` dan `GOOGLE_CLIENT_SECRET` sudah diisi
- [ ] Sudah clear cache (`php artisan config:clear`)
- [ ] Server sudah di-restart
- [ ] Test endpoint `/debug-oauth` menampilkan konfigurasi yang benar
- [ ] Callback route bisa diakses (test dengan `/oauth/google/callback?test=1`)

## Masalah Umum dan Solusinya

### Masalah 1: Callback tidak pernah dipanggil
**Gejala:** Tidak ada log "=== Google OAuth Callback CALLED ==="

**Solusi:**
1. Pastikan redirect URI di Google Cloud Console sama persis dengan `{APP_URL}/oauth/google/callback`
2. Pastikan tidak ada trailing slash di APP_URL
3. Clear cache dan restart server

### Masalah 2: Redirect URI tidak cocok
**Gejala:** Error "redirect_uri_mismatch" di log

**Solusi:**
1. Periksa redirect URI di Google Cloud Console
2. Pastikan sama persis dengan yang di `.env` (APP_URL + /oauth/google/callback)
3. Jika menggunakan localhost, ubah menjadi 127.0.0.1

### Masalah 3: Masih redirect ke halaman pilih akun
**Gejala:** Setelah memilih akun, masih kembali ke halaman pilih akun

**Solusi:**
1. Reset consent di Google Account Settings
2. Pastikan callback dipanggil (cek log)
3. Pastikan tidak ada error di callback handler (cek log)

## Jika Masih Bermasalah

Jika setelah semua langkah di atas masih bermasalah:

1. **Kirimkan output dari command:**
   ```bash
   php artisan oauth:verify-google
   ```

2. **Kirimkan output dari debug endpoint:**
   ```
   http://127.0.0.1:8000/debug-oauth
   ```

3. **Kirimkan log Laravel:**
   - File `storage/logs/laravel.log` setelah mencoba login
   - Cari bagian yang berisi "Google OAuth"

4. **Screenshot:**
   - Google Cloud Console (bagian Authorized redirect URIs)
   - Browser Network tab (setelah klik "Lanjutkan" di Google)

## File yang Telah Dibuat

1. **TROUBLESHOOTING_GOOGLE_OAUTH.md** - Dokumentasi troubleshooting lengkap
2. **SOLUSI_GOOGLE_OAUTH.md** - File ini, solusi cepat
3. **Command `php artisan oauth:verify-google`** - Untuk verifikasi konfigurasi
4. **Endpoint `/debug-oauth`** - Untuk melihat konfigurasi di browser

## Perbaikan yang Telah Dilakukan

1. ✅ Menambahkan validasi redirect URI
2. ✅ Menambahkan logging yang lebih detail
3. ✅ Memastikan redirect langsung ke dashboard tanpa loop
4. ✅ Menambahkan endpoint debug
5. ✅ Menambahkan command verifikasi konfigurasi

