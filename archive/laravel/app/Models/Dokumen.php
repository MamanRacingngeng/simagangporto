<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dokumen extends Model
{
    protected $table = 'dokumen';
    
    protected $fillable = [
        'user_id',
        'cv', // Sesuai ERD: cv (path atau reference)
        'surat_pengantar', // Sesuai ERD: surat_pengantar (path atau reference)
        'proposal', // Sesuai ERD: proposal (path atau reference)
        'tanggal_upload', // Sesuai ERD: tanggal_upload (date)
    ];

    protected $casts = [
        'tanggal_upload' => 'date',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // One-to-one relationship dengan PermohonanMagang
    // Dokumen 1:1 PermohonanMagang (dilampirkan pada) - sesuai Class Diagram
    public function permohonanMagang()
    {
        return $this->hasOne(PermohonanMagang::class);
    }

    /**
     * Class Diagram: Dokumen.uploadDokumen()
     * Method untuk upload dokumen
     */
    public function uploadDokumen(array $data)
    {
        $this->update([
            'cv' => $data['cv'] ?? $this->cv,
            'surat_pengantar' => $data['surat_pengantar'] ?? $this->surat_pengantar,
            'proposal' => $data['proposal'] ?? $this->proposal,
            'tanggal_upload' => $data['tanggal_upload'] ?? now()->toDateString(),
        ]);

        return $this;
    }
}
