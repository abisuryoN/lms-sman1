<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuruKelas extends Model
{
    use HasFactory;

    protected $table = 'guru_kelas';

    protected $fillable = [
        'guru_id',
        'kelas_id',
        'mapel_id',
        'tahun_ajaran_id',
    ];

    // ── Relationships ────────────────────────────────────
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
}
