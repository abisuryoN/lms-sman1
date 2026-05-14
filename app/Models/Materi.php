<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materi';

    protected $fillable = [
        'guru_id',
        'kelas_id',
        'mapel_id',
        'tahun_ajaran_id',
        'judul',
        'deskripsi',
        'file_url',
        'tipe',
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

    public function logs()
    {
        return $this->hasMany(MateriLog::class, 'materi_id');
    }

    // ── Accessor ─────────────────────────────────────────
    public function getFileFullUrlAttribute(): string
    {
        if ($this->tipe === 'link') {
            return $this->file_url;
        }
        return asset('storage/' . $this->file_url);
    }
}
