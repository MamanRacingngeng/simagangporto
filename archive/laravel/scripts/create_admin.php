<?php
$autoload = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoload)) {
  echo "autoload not found: $autoload\n";
  exit(1);
}
require $autoload;

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Buat atau Update Admin
$admin = User::firstOrCreate(
  ['email' => 'admin@bbkb.go.id'],
  [
    'nama' => 'Administrator BBKB',
    'email' => 'admin@bbkb.go.id',
    'password' => Hash::make('admin123'),
    'role' => 'admin',
    'email_verified_at' => now(),
    'instansi' => 'BBKB Yogyakarta',
  ]
);

// Update password dan role jika admin sudah ada (untuk memastikan password benar)
if (!$admin->wasRecentlyCreated) {
  $admin->update([
    'password' => Hash::make('admin123'),
    'role' => 'admin',
    'email_verified_at' => now(),
  ]);
}

if ($admin->wasRecentlyCreated) {
  echo "\n";
  echo "========================================\n";
  echo "ADMIN BARU BERHASIL DIBUAT!\n";
  echo "========================================\n";
  echo "Email: admin@bbkb.go.id\n";
  echo "Password: admin123\n";
  echo "Login di: /admin/login\n";
  echo "========================================\n";
} else {
  echo "\n";
  echo "========================================\n";
  echo "ADMIN SUDAH ADA - PASSWORD TELAH DIPERBARUI!\n";
  echo "========================================\n";
  echo "Email: admin@bbkb.go.id\n";
  echo "Password: admin123 (telah diperbarui)\n";
  echo "Login di: /admin/login\n";
  echo "========================================\n";
}

