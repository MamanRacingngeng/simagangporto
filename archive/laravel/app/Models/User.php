<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama', // Sesuai ERD: nama bukan name
        'email',
        'password',
        'role',
        'no_telepon',
        'instansi', // Sesuai ERD: instansi (bukan universitas/jurusan terpisah)
        'google_id', // Untuk Google OAuth
        'avatar', // Avatar dari Google
        'email_verification_token', // Token untuk verifikasi email
        'is_active', // Status aktivasi akun
        // Field profil lengkap
        'nama_panggilan',
        'ttl',
        'domisili',
        'nim',
        'semester',
        'ipk',
        'program',
        'universitas',
        'software_tools',
        'portofolio',
        'kompetensi_utama',
        'foto_profil',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships sesuai Class Diagram
    // User 1:0..* PermohonanMagang (mengajukan)
    public function permohonanMagang(): HasMany
    {
        return $this->hasMany(PermohonanMagang::class);
    }

    // User 1:1 Dokumen (memiliki) - sesuai Class Diagram
    public function dokumen()
    {
        return $this->hasOne(Dokumen::class);
    }

    // User 1:0..* Notifikasi (menerima)
    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class);
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    // Alias untuk backward compatibility
    public function isPendaftar()
    {
        return $this->role === 'user';
    }

    /**
     * Class Diagram: User.register()
     * Method untuk registrasi user
     */
    public static function register(array $data)
    {
        return self::create([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => $data['role'] ?? 'user',
            'no_telepon' => $data['no_telepon'] ?? null,
            'instansi' => $data['instansi'] ?? null,
        ]);
    }

    /**
     * Class Diagram: User.login()
     * Method untuk login user
     */
    public function login()
    {
        Auth::login($this);
        session()->regenerate();
        return $this;
    }

    /**
     * Class Diagram: Admin extends User (jika role = admin)
     * Method untuk mendapatkan instance Admin dari User
     */
    public function asAdmin()
    {
        if ($this->isAdmin()) {
            return \App\Models\Admin::find($this->id);
        }
        return null;
    }
}
