<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai';

    protected $fillable = [
        'tugas_id',
        'siswa_id',
        'nilai',
        'komentar',
        'tahun_ajaran_id',
    ];

    protected function casts(): array
    {
        return [
            'nilai' => 'decimal:2',
        ];
    }

    // ── Relationships ────────────────────────────────────
    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'tugas_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
}
