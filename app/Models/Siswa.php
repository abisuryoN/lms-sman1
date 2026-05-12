<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'user_id',
        'kelas_id',
        'nis',
        'nama',
        'jenis_kelamin',
        'status',
        'telepon',
        'alamat',
    ];

    // ── Scopes ───────────────────────────────────────────
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeAlumni($query)
    {
        return $query->where('status', 'alumni');
    }

    // ── Relationships ────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function jawabanTugas()
    {
        return $this->hasMany(JawabanTugas::class, 'siswa_id');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'siswa_id');
    }
}
