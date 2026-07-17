<?php

namespace App\Models;

use App\Models\KuotaMagang;
use App\Models\PermohonanMagang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

/**
 * Class Diagram: Admin extends User (jika role = admin)
 * Admin adalah User dengan role = 'admin'
 * Menggunakan scope dan method untuk mengakses User dengan role admin
 */
class Admin extends Model
{
    /**
     * Admin menggunakan tabel users dengan role = 'admin'
     * Class Diagram: Admin attributes: id, nama, email, password
     */
    protected $table = 'users';

    protected $fillable = [
        'nama',
        'email',
        'password',
    ];

    /**
     * Scope untuk mendapatkan hanya user dengan role admin
     */
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Get Admin instance dari User yang sedang login
     */
    public static function current()
    {
        $user = Auth::user();
        if ($user && $user->role === 'admin') {
            return self::find($user->id);
        }
        return null;
    }

    // Relationships sesuai Class Diagram
    // Admin 1:0..* PermohonanMagang (memverifikasi)
    public function permohonanMagang()
    {
        return $this->hasMany(PermohonanMagang::class, 'user_id');
    }

    // Admin 1:0..* KuotaMagang (membatasi)
    public function kuotaMagang()
    {
        return $this->hasMany(KuotaMagang::class);
    }

    /**
     * Class Diagram: Admin.verifikasiPermohonan()
     * Method untuk memverifikasi permohonan magang
     */
    public function verifikasiPermohonan(PermohonanMagang $permohonan, bool $dokumenValid, bool $kuotaTersedia): bool
    {
        if (!$dokumenValid) {
            // Dokumen tidak valid
            $permohonan->updateStatus('Ditolak');
            return false;
        }

        // Dokumen valid, ubah status menjadi Diverifikasi
        $permohonan->updateStatus('Diverifikasi');

        if (!$kuotaTersedia) {
            // Kuota penuh
            $permohonan->updateStatus('Ditolak');
            return false;
        }

        // Kuota tersedia, terima permohonan
        $permohonan->updateStatus('Diterima');
        
        // Update kuota terpakai
        $kuota = KuotaMagang::first();
        if ($kuota && $kuota->cekKuota()) {
            $kuota->increment('kuota_terpakai');
        }

        return true;
    }

    /**
     * Class Diagram: Admin.setStatus()
     * Method untuk mengubah status permohonan
     */
    public function setStatus(PermohonanMagang $permohonan, string $status): void
    {
        $permohonan->updateStatus($status);
    }
}
