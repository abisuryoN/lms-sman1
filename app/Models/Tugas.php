<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $table = 'tugas';

    protected $fillable = [
        'guru_id',
        'kelas_id',
        'mapel_id',
        'tahun_ajaran_id',
        'judul',
        'deskripsi',
        'file_url',
        'original_filename',
        'tipe',
        'deadline',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
        ];
    }

    // ── Scopes ───────────────────────────────────────────
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

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

    public function jawabanTugas()
    {
        return $this->hasMany(JawabanTugas::class, 'tugas_id');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'tugas_id');
    }

    public function similarityResults()
    {
        return $this->hasMany(SimilarityResult::class, 'tugas_id');
    }

    // ── Helpers ──────────────────────────────────────────
    public function isExpired(): bool
    {
        return now()->gt($this->deadline);
    }

    public function getSubmissionCountAttribute(): int
    {
        return $this->jawabanTugas()->count();
    }
}
