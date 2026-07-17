<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class JadwalMagang extends Model
{
    protected $table = 'jadwal_magang';

    protected $fillable = [
        'periode', // Sesuai ERD: periode (varchar)
        'posisi', // Posisi/Divisi untuk jadwal
        'tgl_mulai', // Sesuai ERD: tgl_mulai (date)
        'tgl_selesai', // Sesuai ERD: tgl_selesai (date)
    ];

    protected $casts = [
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
    ];

    // Relationships sesuai Class Diagram
    // JadwalMagang 1:1 KuotaMagang (terkait periode dan posisi) - sesuai Class Diagram
    public function kuotaMagang()
    {
        return $this->hasOne(KuotaMagang::class, 'periode', 'periode')
            ->where('posisi', $this->posisi);
    }

    /**
     * Class Diagram: JadwalMagang.aturJadwal()
     * Method untuk mengatur jadwal magang per divisi
     * Setiap divisi dapat memiliki jadwal mulai dan selesai sendiri
     */
    public function aturJadwal(string $periode, $tglMulai, $tglSelesai, ?string $posisi = null)
    {
        $updateData = [
            'periode' => $periode,
            'tgl_mulai' => $tglMulai,
            'tgl_selesai' => $tglSelesai,
        ];
        
        if ($posisi !== null) {
            $updateData['posisi'] = $posisi;
        }
        
        $this->update($updateData);
        return $this;
    }
}
