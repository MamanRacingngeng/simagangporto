<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GaleriMagang extends Model
{
    protected $table = 'galeri_magang';

    protected $fillable = [
        'judul',
        'deskripsi',
        'foto',
        'aktif',
        'urutan',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'urutan' => 'integer',
    ];

    /**
     * Scope untuk galeri yang aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    /**
     * Scope untuk mengurutkan berdasarkan urutan
     */
    public function scopeTerurut($query)
    {
        return $query->orderBy('urutan', 'asc')->orderBy('created_at', 'desc');
    }

    /**
     * Get URL foto
     */
    public function getFotoUrlAttribute()
    {
        return $this->foto ? \Storage::disk('public')->url($this->foto) : null;
    }
}
