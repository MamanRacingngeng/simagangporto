<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PermohonanMagang;
use App\Models\KuotaMagang;
use App\Models\JadwalMagang;
use Illuminate\Support\Facades\DB;

class FillBackupDataPermohonan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permohonan:fill-backup-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengisi backup data kuota/jadwal untuk permohonan yang sudah ada';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai pengisian backup data untuk permohonan yang sudah ada...');
        
        // Ambil semua permohonan yang belum punya backup data
        $permohonanList = PermohonanMagang::whereNull('periode_backup')
            ->orWhere(function($q) {
                $q->whereNotNull('periode_backup')
                  ->where('periode_backup', '');
            })
            ->with('kuotaMagang')
            ->get();
        
        $this->info("Ditemukan {$permohonanList->count()} permohonan yang perlu diisi backup data.");
        
        $success = 0;
        $failed = 0;
        
        foreach ($permohonanList as $permohonan) {
            try {
                // Coba ambil dari relasi kuotaMagang
                $kuota = $permohonan->kuotaMagang->first();
                
                if ($kuota) {
                    // Cari jadwal yang sesuai
                    $jadwal = JadwalMagang::where('periode', $kuota->periode)
                        ->where(function($q) use ($kuota) {
                            $posisi = $kuota->posisi ?? 'Umum';
                            $q->where('posisi', $posisi)
                              ->orWhere(function($q2) use ($posisi) {
                                  $q2->whereNull('posisi')->where('posisi', 'Umum');
                              });
                        })
                        ->first();
                    
                    // Jika tidak ditemukan dengan posisi, coba cari hanya dengan periode
                    if (!$jadwal) {
                        $jadwal = JadwalMagang::where('periode', $kuota->periode)->first();
                    }
                    
                    // Update backup data
                    $permohonan->update([
                        'periode_backup' => $kuota->periode,
                        'posisi_backup' => $kuota->posisi ?? 'Umum',
                        'tgl_mulai_backup' => $jadwal ? $jadwal->tgl_mulai : null,
                        'tgl_selesai_backup' => $jadwal ? $jadwal->tgl_selesai : null,
                    ]);
                    
                    $success++;
                    } else {
                        // Jika tidak ada kuota dari relasi, coba ambil dari pivot table (jika masih ada)
                        $pivotData = DB::table('permohonan_kuota')
                            ->where('permohonan_magang_id', $permohonan->id)
                            ->join('kuota_magang', 'permohonan_kuota.kuota_magang_id', '=', 'kuota_magang.id')
                            ->select('kuota_magang.periode', 'kuota_magang.posisi')
                            ->first();
                        
                        if ($pivotData) {
                            $jadwal = JadwalMagang::where('periode', $pivotData->periode)
                                ->where(function($q) use ($pivotData) {
                                    $posisi = $pivotData->posisi ?? 'Umum';
                                    $q->where('posisi', $posisi)
                                      ->orWhere(function($q2) use ($posisi) {
                                          $q2->whereNull('posisi')->where('posisi', 'Umum');
                                      });
                                })
                                ->first();
                            
                            if (!$jadwal) {
                                $jadwal = JadwalMagang::where('periode', $pivotData->periode)->first();
                            }
                            
                            $permohonan->update([
                                'periode_backup' => $pivotData->periode,
                                'posisi_backup' => $pivotData->posisi ?? 'Umum',
                                'tgl_mulai_backup' => $jadwal ? $jadwal->tgl_mulai : null,
                                'tgl_selesai_backup' => $jadwal ? $jadwal->tgl_selesai : null,
                            ]);
                            
                            $success++;
                        } else {
                            // Jika pivot table juga kosong, berarti kuota sudah dihapus
                            // Coba cari dari jadwal berdasarkan tanggal pengajuan atau created_at
                            $tanggalPengajuan = $permohonan->tanggal_pengajuan ?? $permohonan->created_at;
                            
                            // Cari jadwal yang aktif pada saat permohonan dibuat
                            $jadwal = JadwalMagang::where('tgl_mulai', '<=', $tanggalPengajuan)
                                ->where('tgl_selesai', '>=', $tanggalPengajuan)
                                ->orderBy('created_at', 'desc')
                                ->first();
                            
                            if ($jadwal) {
                                $permohonan->update([
                                    'periode_backup' => $jadwal->periode,
                                    'posisi_backup' => $jadwal->posisi ?? 'Umum',
                                    'tgl_mulai_backup' => $jadwal->tgl_mulai,
                                    'tgl_selesai_backup' => $jadwal->tgl_selesai,
                                ]);
                                
                                $success++;
                                $this->info("Permohonan ID {$permohonan->id} diisi dari jadwal berdasarkan tanggal pengajuan.");
                            } else {
                                $failed++;
                                $this->warn("Permohonan ID {$permohonan->id} tidak memiliki kuota/jadwal yang terkait dan tidak dapat diisi backup data.");
                            }
                        }
                    }
            } catch (\Exception $e) {
                $failed++;
                $this->error("Error pada permohonan ID {$permohonan->id}: " . $e->getMessage());
            }
        }
        
        $this->info("\nSelesai!");
        $this->info("Berhasil: {$success}");
        $this->info("Gagal: {$failed}");
        
        return Command::SUCCESS;
    }
}
