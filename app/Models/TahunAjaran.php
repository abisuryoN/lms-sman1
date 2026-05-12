<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'nama_tahun',
        'semester',
        'status',
    ];

    // ── Scope ────────────────────────────────────────────
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // ── Relationships ────────────────────────────────────
    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'tahun_ajaran_id');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'tahun_ajaran_id');
    }

    public function materi()
    {
        return $this->hasMany(Materi::class, 'tahun_ajaran_id');
    }

    // ── Helper ───────────────────────────────────────────
    public function getFullNameAttribute(): string
    {
        return $this->nama_tahun . ' - ' . $this->semester;
    }
}
