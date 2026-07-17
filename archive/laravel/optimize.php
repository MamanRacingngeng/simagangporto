<?php
/**
 * Script Optimasi Laravel
 * Jalankan: php optimize.php
 */

echo "🚀 Memulai optimasi Laravel...\n\n";

// 1. Route Caching
echo "📦 Mengaktifkan Route Caching...\n";
exec('php artisan route:cache', $output, $return);
if ($return === 0) {
    echo "✅ Route cache berhasil dibuat\n";
} else {
    echo "⚠️ Route cache gagal (mungkin sudah ada)\n";
}

// 2. Config Caching
echo "📦 Mengaktifkan Config Caching...\n";
exec('php artisan config:cache', $output, $return);
if ($return === 0) {
    echo "✅ Config cache berhasil dibuat\n";
} else {
    echo "⚠️ Config cache gagal\n";
}

// 3. View Caching
echo "📦 Mengaktifkan View Caching...\n";
exec('php artisan view:cache', $output, $return);
if ($return === 0) {
    echo "✅ View cache berhasil dibuat\n";
} else {
    echo "⚠️ View cache gagal\n";
}

// 4. Event Caching
echo "📦 Mengaktifkan Event Caching...\n";
exec('php artisan event:cache', $output, $return);
if ($return === 0) {
    echo "✅ Event cache berhasil dibuat\n";
} else {
    echo "⚠️ Event cache gagal (opsional)\n";
}

// 5. Optimize Autoloader
echo "📦 Mengoptimalkan Autoloader...\n";
exec('composer dump-autoload --optimize --no-dev', $output, $return);
if ($return === 0) {
    echo "✅ Autoloader berhasil dioptimalkan\n";
} else {
    echo "⚠️ Autoloader optimization gagal\n";
}

echo "\n✨ Optimasi selesai! Aplikasi sekarang lebih cepat.\n";
echo "💡 Tips: Jalankan script ini setelah deploy atau perubahan besar.\n";

