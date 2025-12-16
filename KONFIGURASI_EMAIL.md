# Konfigurasi Email untuk Verifikasi

## Masalah: Email Verifikasi Tidak Terkirim

Email verifikasi tidak terkirim karena konfigurasi email di `.env` masih menggunakan placeholder.

## Solusi: Update Konfigurasi Email di .env

### Langkah 1: Buka file `.env`

Buka file `.env` di root project Anda.

### Langkah 2: Update Konfigurasi Email

Ganti konfigurasi berikut dengan email dan App Password Gmail Anda:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=email_anda@gmail.com
MAIL_PASSWORD=app_password_gmail_anda
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=email_anda@gmail.com
MAIL_FROM_NAME="Magang-BKKKB Yogyakarta"
```

**Ganti:**
- `email_anda@gmail.com` → Email Gmail Anda yang akan digunakan untuk mengirim email
- `app_password_gmail_anda` → App Password Gmail (bukan password biasa!)

### Langkah 3: Buat App Password Gmail

1. Buka: https://myaccount.google.com/
2. Klik "Security" (Keamanan)
3. Aktifkan "2-Step Verification" (Verifikasi 2 Langkah) jika belum aktif
4. Scroll ke bawah, klik "App passwords" (Kata sandi aplikasi)
5. Pilih "Mail" dan "Other (Custom name)"
6. Masukkan nama: "SIMAGANG"
7. Klik "Generate"
8. Salin password yang muncul (16 karakter, tanpa spasi)
9. Paste ke `MAIL_PASSWORD` di `.env`

### Langkah 4: Clear Cache

Setelah update `.env`, jalankan:

```bash
php artisan config:clear
```

### Langkah 5: Test Email

1. Daftar akun baru di: `http://127.0.0.1:8000/register`
2. Cek email inbox untuk email verifikasi
3. Jika masih tidak ada, cek:
   - Folder Spam/Junk
   - Log di `storage/logs/laravel.log`

## Troubleshooting

### Error: "Connection could not be established"
- Pastikan `MAIL_HOST=smtp.gmail.com`
- Pastikan `MAIL_PORT=587`
- Pastikan `MAIL_ENCRYPTION=tls`

### Error: "Authentication failed"
- Pastikan menggunakan **App Password**, bukan password biasa
- Pastikan 2-Step Verification sudah aktif
- Pastikan App Password sudah dibuat dengan benar

### Email masuk ke Spam
- Cek folder Spam/Junk di email
- Pastikan `MAIL_FROM_ADDRESS` sama dengan `MAIL_USERNAME`

## Alternatif: Test dengan Log Driver (Development)

Untuk testing tanpa email, ubah di `.env`:

```env
MAIL_MAILER=log
```

Email akan disimpan di `storage/logs/laravel.log` sebagai teks.

