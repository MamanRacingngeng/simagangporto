<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';

    protected $fillable = [
        'user_id',
        'permohonan_magang_id',
        'admin_id',
        'judul',
        'pesan',
        'tipe',
        'dibaca',
        'dibaca_at',
    ];

    protected $casts = [
        'dibaca' => 'boolean',
        'dibaca_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function permohonanMagang(): BelongsTo
    {
        return $this->belongsTo(PermohonanMagang::class);
    }

    // Scope untuk notifikasi yang belum dibaca
    public function scopeBelumDibaca($query)
    {
        return $query->where('dibaca', false);
    }

    // Scope untuk notifikasi berdasarkan tipe
    public function scopeTipe($query, $tipe)
    {
        return $query->where('tipe', $tipe);
    }

    // Method untuk menandai sebagai sudah dibaca
    public function tandaiDibaca()
    {
        $this->update([
            'dibaca' => true,
            'dibaca_at' => now(),
        ]);
    }
}
