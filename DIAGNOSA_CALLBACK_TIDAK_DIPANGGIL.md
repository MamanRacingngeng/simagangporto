# 🔍 Diagnosa: Callback Tidak Pernah Dipanggil

## Masalah
Setelah memilih akun Google dan klik "Lanjutkan", callback URL tidak pernah dipanggil. Proses terlihat "stuck" atau kembali ke halaman pilih akun.

## Tanda-tanda Masalah
- ✅ Log menunjukkan "Redirecting to Google OAuth" (redirect berhasil)
- ❌ Log **TIDAK** menunjukkan "=== Google OAuth Callback CALLED ===" (callback tidak dipanggil)
- ❌ Setelah pilih akun, tidak ada redirect ke aplikasi

## Penyebab Umum

### 1. Server Laravel Tidak Aktif ⚠️
**Masalah:** Jika server Laravel tidak berjalan, Google tidak bisa memanggil callback URL.

**Solusi:**
```bash
# Pastikan server Laravel berjalan
php artisan serve
```

**Verifikasi:**
- Buka browser dan akses: `http://127.0.0.1:8000`
- Jika halaman muncul, server aktif
- Jika tidak muncul, jalankan `php artisan serve`

### 2. Redirect URI di Google Cloud Console Tidak Cocok ⚠️⚠️⚠️
**Masalah:** Redirect URI yang dikonfigurasi di Google Cloud Console tidak sama dengan yang digunakan Laravel.

**Solusi:**
1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Pilih project Anda
3. Buka **APIs & Services** → **Credentials**
4. Klik pada OAuth 2.0 Client ID Anda
5. Di bagian **Authorized redirect URIs**, pastikan ada:
   ```
   http://127.0.0.1:8000/oauth/google/callback
   ```

**PENTING:**
- ✅ Harus **SAMA PERSIS** (case sensitive)
- ✅ Tidak ada trailing slash (`/`)
- ✅ Menggunakan `127.0.0.1` bukan `localhost`
- ✅ Tidak ada spasi di awal atau akhir

**Verifikasi:**
Jalankan perintah ini untuk melihat redirect URI yang digunakan Laravel:
```bash
php artisan oauth:verify-google
```

Pastikan redirect URI yang ditampilkan **SAMA PERSIS** dengan yang ada di Google Cloud Console.

### 3. Masalah dengan Localhost (Development) ⚠️
**Masalah:** Google tidak bisa mengakses `localhost` atau `127.0.0.1` dari internet. Ini normal untuk development.

**Solusi untuk Development:**
- Gunakan `http://127.0.0.1:8000` (bukan `localhost`)
- Pastikan server Laravel berjalan
- Pastikan redirect URI di Google Cloud Console menggunakan `127.0.0.1`

**Solusi untuk Production:**
- Gunakan domain yang bisa diakses dari internet
- Pastikan redirect URI menggunakan `https://yourdomain.com/oauth/google/callback`

### 4. Cache Konfigurasi ⚠️
**Masalah:** Konfigurasi di cache tidak sesuai dengan `.env`.

**Solusi:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

## Langkah Diagnosa

### Langkah 1: Verifikasi Konfigurasi Laravel
```bash
php artisan oauth:verify-google
```

Pastikan semua menunjukkan ✅ (hijau).

### Langkah 2: Test Callback Route
Buka browser dan akses:
```
http://127.0.0.1:8000/oauth/google/callback?test=1
```

**Hasil yang diharapkan:**
- Jika muncul error atau halaman kosong → Route bisa diakses ✅
- Jika muncul 404 → Route tidak terdaftar ❌

### Langkah 3: Test Debug Endpoint
Buka browser dan akses:
```
http://127.0.0.1:8000/debug-oauth
```

**Periksa:**
- `google_redirect` harus sama dengan `expected_callback_url`
- `google_client_id` dan `google_client_secret` harus "SET"

### Langkah 4: Verifikasi di Google Cloud Console
1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. **APIs & Services** → **Credentials**
3. Klik OAuth 2.0 Client ID Anda
4. Periksa **Authorized redirect URIs**

**Harus ada:**
```
http://127.0.0.1:8000/oauth/google/callback
```

**Tidak boleh ada:**
- `http://localhost:8000/oauth/google/callback`
- `http://127.0.0.1:8000/auth/google/callback`
- `http://127.0.0.1:8000/oauth/google/callback/` (dengan trailing slash)

### Langkah 5: Periksa Log
```bash
# Windows PowerShell
Get-Content storage\logs\laravel.log -Tail 50 -Wait

# Linux/Mac
tail -f storage/logs/laravel.log
```

**Cari log:**
- ✅ `"Redirecting to Google OAuth"` → Redirect berhasil
- ❌ `"=== Google OAuth Callback CALLED ==="` → **Ini harus muncul setelah pilih akun!**

Jika log callback **TIDAK muncul**, berarti:
- Redirect URI di Google Cloud Console tidak cocok
- Server Laravel tidak aktif
- Ada masalah dengan aksesibilitas URL

## Checklist Perbaikan

- [ ] Server Laravel berjalan (`php artisan serve`)
- [ ] Konfigurasi Laravel benar (`php artisan oauth:verify-google` menunjukkan semua ✅)
- [ ] Redirect URI di Google Cloud Console sama persis dengan `http://127.0.0.1:8000/oauth/google/callback`
- [ ] Cache sudah di-clear (`php artisan config:clear`)
- [ ] Route callback bisa diakses (test dengan browser)
- [ ] Log menunjukkan callback dipanggil setelah pilih akun

## Solusi Cepat

Jika semua sudah dicek tapi masih tidak berfungsi:

1. **Hapus semua redirect URI di Google Cloud Console**
2. **Tambah ulang:** `http://127.0.0.1:8000/oauth/google/callback`
3. **Simpan perubahan**
4. **Tunggu 1-2 menit** (Google perlu waktu untuk update)
5. **Clear cache Laravel:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```
6. **Restart server Laravel**
7. **Coba login lagi**

## Catatan Penting

1. **Redirect URI harus SAMA PERSIS** antara:
   - Google Cloud Console
   - Konfigurasi Laravel (`config/services.php`)
   - Route Laravel (`routes/web.php`)

2. **Untuk Development:**
   - Gunakan `127.0.0.1` bukan `localhost`
   - Server harus berjalan saat test

3. **Untuk Production:**
   - Gunakan domain yang bisa diakses dari internet
   - Gunakan `https://` bukan `http://`

4. **Jika masih tidak berfungsi:**
   - Periksa firewall atau antivirus yang mungkin memblokir
   - Periksa apakah ada proxy atau VPN yang mengganggu
   - Coba dengan browser lain atau mode incognito

