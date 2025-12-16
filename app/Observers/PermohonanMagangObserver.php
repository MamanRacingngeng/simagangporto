<?php

namespace App\Observers;

use App\Models\PermohonanMagang;
use App\Models\KuotaMagang;
use App\Models\JadwalMagang;
use Illuminate\Support\Facades\Log;

class PermohonanMagangObserver
{
    /**
     * Handle the PermohonanMagang "created" event.
     */
    public function created(PermohonanMagang $permohonanMagang): void
    {
        // Tidak perlu sinkronisasi saat dibuat karena status default adalah 'Diajukan'
        // Kuota akan terpakai hanya saat status menjadi 'Diterima'
    }

    /**
     * Handle the PermohonanMagang "updated" event.
     * Sinkronisasi kuota_terpakai dengan jumlah permohonan berstatus 'Diterima' untuk periode tersebut.
     */
    public function updated(PermohonanMagang $permohonanMagang): void
    {
        // Hanya proses jika status berubah
        if (!$permohonanMagang->wasChanged('status')) {
            return;
        }

        $statusLama = $permohonanMagang->getOriginal('status');
        $statusBaru = $permohonanMagang->status;

        // Jika status berubah menjadi 'Diterima'
        if ($statusBaru === 'Diterima' && $statusLama !== 'Diterima') {
            $this->incrementKuota($permohonanMagang);
        }
        
        // Jika status berubah dari 'Diterima' ke status lain
        if ($statusLama === 'Diterima' && $statusBaru !== 'Diterima') {
            $this->decrementKuota($permohonanMagang);
        }
    }

    /**
     * Increment kuota_terpakai saat status menjadi 'Diterima'
     */
    private function incrementKuota(PermohonanMagang $permohonan): void
    {
        $kuota = $this->findKuotaForPermohonan($permohonan);
        
        if ($kuota && $kuota->kuota_terpakai < $kuota->kuota_max) {
            $kuota->increment('kuota_terpakai');
            
            // Hubungkan permohonan ke kuota jika belum terhubung
            if (!$permohonan->kuotaMagang()->where('kuota_magang.id', $kuota->id)->exists()) {
                $permohonan->kuotaMagang()->attach($kuota->id);
            }
            
            Log::info("Kuota incremented for permohonan {$permohonan->id}, kuota: {$kuota->id}, terpakai: {$kuota->kuota_terpakai}");
        }
    }

    /**
     * Decrement kuota_terpakai saat status berubah dari 'Diterima'
     */
    private function decrementKuota(PermohonanMagang $permohonan): void
    {
        $kuota = $this->findKuotaForPermohonan($permohonan);
        
        if ($kuota && $kuota->kuota_terpakai > 0) {
            $kuota->decrement('kuota_terpakai');
            Log::info("Kuota decremented for permohonan {$permohonan->id}, kuota: {$kuota->id}, terpakai: {$kuota->kuota_terpakai}");
        }
    }

    /**
     * Find kuota yang terkait dengan permohonan
     * Mempertimbangkan periode + posisi untuk mendukung periode yang sama dengan divisi berbeda
     */
    private function findKuotaForPermohonan(PermohonanMagang $permohonan): ?KuotaMagang
    {
        // Coba ambil dari relasi langsung
        $kuota = $permohonan->kuotaMagang()->first();
        
        if (!$kuota) {
            // Cari berdasarkan periode + posisi aktif
            $today = now()->toDateString();
            $jadwalAktif = JadwalMagang::where('tgl_mulai', '<=', $today)
                ->where('tgl_selesai', '>=', $today)
                ->orderBy('created_at', 'desc')
                ->first();
            
            if ($jadwalAktif) {
                // Cari kuota berdasarkan periode + posisi (mendukung periode sama dengan divisi berbeda)
                $kuota = KuotaMagang::where('periode', $jadwalAktif->periode)
                    ->where('posisi', $jadwalAktif->posisi)
                    ->first();
            }
            
            // Jika masih belum ada, ambil kuota pertama yang tersedia
            if (!$kuota) {
                $kuota = KuotaMagang::whereColumn('kuota_terpakai', '<', 'kuota_max')
                    ->orderBy('created_at', 'desc')
                    ->first();
            }
        }
        
        return $kuota;
    }

    /**
     * Handle the PermohonanMagang "deleted" event.
     */
    public function deleted(PermohonanMagang $permohonanMagang): void
    {
        // Jika permohonan dihapus dan statusnya 'Diterima', kurangi kuota
        if ($permohonanMagang->status === 'Diterima') {
            $this->decrementKuota($permohonanMagang);
        }
    }

    /**
     * Handle the PermohonanMagang "restored" event.
     */
    public function restored(PermohonanMagang $permohonanMagang): void
    {
        //
    }

    /**
     * Handle the PermohonanMagang "force deleted" event.
     */
    public function forceDeleted(PermohonanMagang $permohonanMagang): void
    {
        //
    }
}
