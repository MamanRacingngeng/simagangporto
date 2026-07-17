<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class KuotaMagang extends Model
{
    protected $table = 'kuota_magang';

    protected $fillable = [
        'periode', // Sesuai ERD: periode (varchar)
        'posisi', // Posisi/Departemen yang dibuka
        'kuota_max', // Sesuai ERD: kuota_max (int)
        'kuota_terpakai', // Sesuai ERD: kuota_terpakai (int)
    ];

    // Relationships sesuai Class Diagram
    // KuotaMagang 1:1 JadwalMagang (terkait periode + posisi) - sesuai Class Diagram
    // Memungkinkan periode yang sama dengan divisi berbeda
    public function jadwalMagang()
    {
        return $this->hasOne(JadwalMagang::class, 'periode', 'periode')
            ->where('posisi', $this->posisi);
    }

    // KuotaMagang 0..*:1 PermohonanMagang - sesuai Class Diagram
    public function permohonanMagang()
    {
        return $this->belongsToMany(PermohonanMagang::class, 'permohonan_kuota', 'kuota_magang_id', 'permohonan_magang_id');
    }

    /**
     * Class Diagram: KuotaMagang.aturKuota()
     * Method untuk mengatur kuota magang
     */
    public function aturKuota(int $kuotaMax, int $kuotaTerpakai = 0)
    {
        $this->update([
            'kuota_max' => $kuotaMax,
            'kuota_terpakai' => $kuotaTerpakai,
        ]);
        return $this;
    }

    /**
     * Class Diagram: KuotaMagang.cekKuota()
     * Method untuk mengecek ketersediaan kuota
     */
    public function cekKuota(): bool
    {
        return $this->kuota_terpakai < $this->kuota_max;
    }

    /**
     * Helper method untuk mendapatkan kuota tersedia
     */
    public function getKuotaTersediaAttribute(): int
    {
        return max(0, $this->kuota_max - $this->kuota_terpakai);
    }
}
