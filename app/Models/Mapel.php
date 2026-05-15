<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    protected $table = 'mapel';

    protected $fillable = [
        'nama_mapel',
        'kode_mapel',
        'tingkat',
    ];

    // ── Relationships ────────────────────────────────────
    public function guruKelas()
    {
        return $this->hasMany(GuruKelas::class, 'mapel_id');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'mapel_id');
    }

    public function materi()
    {
        return $this->hasMany(Materi::class, 'mapel_id');
    }
}
