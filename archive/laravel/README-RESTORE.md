# Arsip Laravel — SIMAGANG BBKB

Folder ini berisi **versi Laravel asli** proyek SIMAGANG yang sudah tidak dipakai untuk deploy production.

Aplikasi production sekarang: **`../../web/`** (Next.js + Vercel).

## Isi Arsip

- Laravel 12 (PHP, Blade, MySQL/SQLite)
- Controller, Model, Migration, View asli
- Asset `public/` (CSS, gambar, JS)
- Composer & npm dependencies (jika ada)

## Cara Memulihkan Laravel

### 1. Pindahkan kembali ke root repo

Dari root project (`Projectbatika/`):

```powershell
# Windows PowerShell
$items = @('app','bootstrap','config','database','login_google','public','resources','routes','storage','tests','stubs','api','scripts','artisan','composer.json','composer.lock','phpunit.xml','vite.config.js','optimize.php','package.json','package-lock.json')
foreach ($item in $items) {
  if (Test-Path "archive\laravel\$item") {
    Move-Item "archive\laravel\$item" ".\$item" -Force
  }
}
```

### 2. Install dependencies

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

### 3. Environment

Salin atau sesuaikan `.env` dengan database MySQL/SQLite lokal Anda.

## Catatan

- Jangan hapus folder ini jika masih butuh referensi Blade/CSS asli.
- Next.js di `web/public/` sudah disalin asset dari Laravel `public/` saat arsip dibuat.
- File `README-LARAVEL.md` adalah README Laravel original.
