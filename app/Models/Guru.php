<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';

    protected $fillable = [
        'user_id',
        'nip',
        'nama',
        'telepon',
        'alamat',
    ];

    // ── Relationships ────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function guruKelas()
    {
        return $this->hasMany(GuruKelas::class, 'guru_id');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'guru_id');
    }

    public function materi()
    {
        return $this->hasMany(Materi::class, 'guru_id');
    }

    public function kelasYangDiajar()
    {
        return $this->belongsToMany(Kelas::class, 'guru_kelas', 'guru_id', 'kelas_id')
                    ->withPivot('mapel_id', 'tahun_ajaran_id')
                    ->withTimestamps();
    }

    public function waliKelas()
    {
        return $this->hasMany(Kelas::class, 'wali_kelas_id', 'user_id');
    }
}
