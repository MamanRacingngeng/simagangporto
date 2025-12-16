<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PermohonanMagang extends Model
{
    protected $table = 'permohonan_magang';

    protected $fillable = [
        'user_id',
        'dokumen_id', // Sesuai ERD: dokumen_id (FK) - one-to-one dengan Dokumen
        'tanggal_pengajuan', // Sesuai ERD: tanggal_pengajuan (date)
        'status', // Sesuai ERD: "Diajukan", "Diverifikasi", "Diterima", atau "Ditolak"
        'alasan_penolakan', // Alasan penolakan jika status = "Ditolak"
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // One-to-one relationship dengan Dokumen
    // Dokumen 1:1 PermohonanMagang (dilampirkan pada) - sesuai Class Diagram
    public function dokumen(): BelongsTo
    {
        return $this->belongsTo(Dokumen::class);
    }

    // PermohonanMagang 1:0..* KuotaMagang - sesuai Class Diagram
    public function kuotaMagang()
    {
        return $this->belongsToMany(KuotaMagang::class, 'permohonan_kuota', 'permohonan_magang_id', 'kuota_magang_id');
    }

    /**
     * Class Diagram: PermohonanMagang.ajukanPermohonan()
     * Method untuk mengajukan permohonan magang
     */
    public static function ajukanPermohonan(int $userId, int $dokumenId)
    {
        return self::create([
            'user_id' => $userId,
            'dokumen_id' => $dokumenId,
            'tanggal_pengajuan' => now()->toDateString(),
            'status' => 'Diajukan',
        ]);
    }

    /**
     * Helper method untuk mengecek apakah user bisa mendaftar lagi
     * Satu akun hanya dapat mendaftar untuk 1 divisi lowongan magang
     * Kecuali masa berlakunya sudah habis dan dia ditolak, bisa mendaftar lagi
     * 
     * @param int $userId
     * @return array ['bisa_daftar' => bool, 'alasan' => string]
     */
    public static function cekBisaDaftar(int $userId): array
    {
        $today = now()->toDateString();
        
        // Cek apakah ada permohonan dengan status 'Diajukan' atau 'Diverifikasi'
        $permohonanProses = self::where('user_id', $userId)
            ->whereIn('status', ['Diajukan', 'Diverifikasi'])
            ->first();
        
        if ($permohonanProses) {
            return [
                'bisa_daftar' => false,
                'alasan' => 'Anda sudah memiliki permohonan yang sedang diproses dengan status: ' . $permohonanProses->status . '. Silakan tunggu hingga proses verifikasi selesai.'
            ];
        }
        
        // Cek apakah ada permohonan dengan status 'Diterima' yang masih dalam masa berlaku
        $permohonanDiterima = self::where('user_id', $userId)
            ->where('status', 'Diterima')
            ->with(['kuotaMagang'])
            ->get();
        
        foreach ($permohonanDiterima as $permohonan) {
            $kuotaList = $permohonan->kuotaMagang;
            
            foreach ($kuotaList as $kuota) {
                // Load jadwal secara manual karena relasi menggunakan where clause
                $jadwal = \App\Models\JadwalMagang::where('periode', $kuota->periode)
                    ->where('posisi', $kuota->posisi)
                    ->first();
                
                if ($jadwal && $jadwal->tgl_selesai >= $today) {
                    // Masih dalam masa berlaku
                    return [
                        'bisa_daftar' => false,
                        'alasan' => 'Anda sudah memiliki permohonan yang diterima untuk periode ' . $kuota->periode . ' (Divisi: ' . $kuota->posisi . ') yang masih aktif hingga ' . $jadwal->tgl_selesai->format('d/m/Y') . '. Satu akun hanya dapat mendaftar untuk 1 divisi lowongan magang.'
                    ];
                }
            }
        }
        
        // Cek apakah ada permohonan dengan status 'Ditolak'
        $permohonanDitolak = self::where('user_id', $userId)
            ->where('status', 'Ditolak')
            ->with(['kuotaMagang'])
            ->orderBy('created_at', 'desc')
            ->first();
        
        if ($permohonanDitolak) {
            $kuotaList = $permohonanDitolak->kuotaMagang;
            
            foreach ($kuotaList as $kuota) {
                // Load jadwal untuk cek masa berlaku
                $jadwal = \App\Models\JadwalMagang::where('periode', $kuota->periode)
                    ->where('posisi', $kuota->posisi)
                    ->first();
                
                if ($jadwal) {
                    // Jika masa berlaku belum habis (masih dalam periode yang sama), tidak bisa daftar
                    if ($jadwal->tgl_selesai >= $today) {
                        return [
                            'bisa_daftar' => false,
                            'alasan' => 'Anda sudah memiliki permohonan yang ditolak untuk periode ' . $kuota->periode . ' (Divisi: ' . $kuota->posisi . ') yang masih dalam masa berlaku hingga ' . $jadwal->tgl_selesai->format('d/m/Y') . '. Anda dapat mendaftar lagi setelah masa berlaku periode tersebut berakhir. Satu akun hanya dapat mendaftar untuk 1 divisi lowongan magang.'
                        ];
                    }
                    // Jika masa berlaku sudah habis, boleh daftar lagi (tidak return false, lanjut ke return true di bawah)
                }
            }
        }
        
        // Jika tidak ada permohonan aktif, atau permohonan ditolak dan masa berlaku sudah habis, boleh mendaftar
        return [
            'bisa_daftar' => true,
            'alasan' => ''
        ];
    }

    /**
     * Class Diagram: PermohonanMagang.updateStatus()
     * Method untuk update status permohonan
     * Status "Diterima" dan "Ditolak" bersifat final dan tidak dapat diubah
     */
    public function updateStatus(string $status)
    {
        // Validasi: Status "Diterima" dan "Ditolak" bersifat final
        $statusFinal = ['Diterima', 'Ditolak'];
        
        // Jika status saat ini sudah final, hanya bisa diubah ke status yang sama
        if (in_array($this->status, $statusFinal) && $status !== $this->status) {
            throw new \Exception("Status '{$this->status}' bersifat final dan tidak dapat diubah menjadi '{$status}'.");
        }
        
        $this->update(['status' => $status]);
        return $this;
    }

    /**
     * Helper method untuk mengecek apakah status bersifat final
     */
    public function isStatusFinal(): bool
    {
        return in_array($this->status, ['Diterima', 'Ditolak']);
    }
}
