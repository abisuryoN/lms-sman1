<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'tahun_ajaran_id',
        'wali_kelas_id',
    ];

    // ── Relationships ────────────────────────────────────
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function waliKelas()
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }

    public function guruKelas()
    {
        return $this->hasMany(GuruKelas::class, 'kelas_id');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'kelas_id');
    }

    public function materi()
    {
        return $this->hasMany(Materi::class, 'kelas_id');
    }
}
