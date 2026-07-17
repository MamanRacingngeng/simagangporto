<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateSimagangDatabase extends Command
{
    protected $signature = 'simagang:create-db';

    protected $description = 'Membuat database simagang di MySQL jika belum ada (untuk perbaikan error login admin)';

    public function handle(): int
    {
        $db = env('DB_DATABASE', 'simagang');
        $host = env('DB_HOST', '127.0.0.1');
        $port = env('DB_PORT', '3306');
        $user = env('DB_USERNAME', 'root');
        $pass = env('DB_PASSWORD', '');

        $this->info("Membuat database '{$db}' di {$host}:{$port} ...");

        try {
            $dsn = "mysql:host={$host};port={$port};charset=utf8mb4";
            $pdo = new \PDO($dsn, $user, $pass, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ]);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . str_replace('`', '``', $db) . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->info("Database '{$db}' siap.");
            $this->line('');
            $this->info('Jalankan migrasi tabel:');
            $this->line('  php artisan migrate');
            return self::SUCCESS;
        } catch (\PDOException $e) {
            $this->error('Gagal membuat database: ' . $e->getMessage());
            $this->line('');
            $this->warn('Buat manual di MySQL:');
            $this->line("  CREATE DATABASE {$db} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            return self::FAILURE;
        }
    }
}
