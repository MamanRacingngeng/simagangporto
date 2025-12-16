<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat User Default (Pendaftar)
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'nama' => 'User Test',
                'email' => 'user@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'no_telepon' => '081234567890',
                'instansi' => 'Universitas Test',
                'email_verified_at' => now(), // Langsung verified
            ]
        );

        if ($user->wasRecentlyCreated) {
            $this->command->info('User default berhasil dibuat!');
            $this->command->info('Email: user@example.com');
            $this->command->info('Password: password123');
        } else {
            $this->command->info('User default sudah ada.');
        }

        // Buat Admin Default
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'nama' => 'Admin Test',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'no_telepon' => '081234567891',
                'instansi' => 'BBKB Yogyakarta',
                'email_verified_at' => now(), // Langsung verified
            ]
        );

        if ($admin->wasRecentlyCreated) {
            $this->command->info('Admin default berhasil dibuat!');
            $this->command->info('Email: admin@example.com');
            $this->command->info('Password: admin123');
        } else {
            $this->command->info('Admin default sudah ada.');
        }

        $this->command->newLine();
        $this->command->info('========================================');
        $this->command->info('AKUN DEFAULT UNTUK LOGIN');
        $this->command->info('========================================');
        $this->command->newLine();
        $this->command->info('USER (Pendaftar):');
        $this->command->info('  Email: user@example.com');
        $this->command->info('  Password: password123');
        $this->command->info('  Login di: /login');
        $this->command->newLine();
        $this->command->info('ADMIN:');
        $this->command->info('  Email: admin@example.com');
        $this->command->info('  Password: admin123');
        $this->command->info('  Login di: /admin/login');
        $this->command->newLine();
        $this->command->info('========================================');
    }
}
