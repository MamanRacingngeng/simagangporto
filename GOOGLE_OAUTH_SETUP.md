# Setup Google OAuth Login

## Langkah-langkah Setup Google OAuth

### 1. Buat Google OAuth Credentials

1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Buat project baru atau pilih project yang sudah ada
3. Aktifkan **Google+ API** atau **Google Identity API**
4. Buka **Credentials** → **Create Credentials** → **OAuth client ID**
5. Pilih **Web application**
6. Isi:
   - **Name**: SIMAGANG (atau nama aplikasi Anda)
   - **Authorized JavaScript origins**: 
     - `http://127.0.0.1:8000` (untuk development)
     - `https://yourdomain.com` (untuk production)
   - **Authorized redirect URIs**:
     - `http://127.0.0.1:8000/oauth/google/callback` (untuk development)
     - `https://yourdomain.com/oauth/google/callback` (untuk production)

### 2. Konfigurasi .env

Tambahkan konfigurasi berikut di file `.env`:

```env
# Google OAuth
GOOGLE_CLIENT_ID=your-google-client-id-here
GOOGLE_CLIENT_SECRET=your-google-client-secret-here

# App URL (penting untuk redirect URI)
APP_URL=http://127.0.0.1:8000
```

### 3. Konfigurasi Gmail SMTP (Opsional)

Untuk mengirim email, tambahkan di `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=emailpengirimmu@gmail.com
MAIL_PASSWORD=kodeaplikasigmail
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=emailpengirimmu@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Catatan untuk Gmail:**
- Gunakan **App Password** (bukan password biasa)
- Aktifkan 2-Step Verification di Google Account
- Buat App Password di: https://myaccount.google.com/apppasswords

### 4. Clear Cache

Setelah mengubah `.env`, jalankan:

```bash
php artisan config:clear
php artisan config:cache
```

### 5. Test Login

1. Buka `/login` untuk login user
2. Buka `/admin/login` untuk login admin
3. Klik tombol "Masuk dengan Google"
4. Pilih akun Google
5. Setujui permintaan akses

## Troubleshooting

### Error: "Missing required parameter: redirect_uri"
- Pastikan `APP_URL` di `.env` sudah benar
- Pastikan redirect URI di Google Cloud Console sama dengan `{APP_URL}/oauth/google/callback`
- Clear cache: `php artisan config:clear`

### Error: "Invalid client"
- Pastikan `GOOGLE_CLIENT_ID` dan `GOOGLE_CLIENT_SECRET` di `.env` sudah benar
- Pastikan OAuth client sudah diaktifkan di Google Cloud Console

### Error: "Access blocked"
- Pastikan email yang digunakan sudah terdaftar di database
- Untuk admin: pastikan role user adalah 'admin'

