<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimilarityResult extends Model
{
    use HasFactory;

    protected $table = 'similarity_results';

    protected $fillable = [
        'tugas_id',
        'jawaban_1_id',
        'jawaban_2_id',
        'similarity_percentage',
        'status',
        'tahun_ajaran_id',
    ];

    protected function casts(): array
    {
        return [
            'similarity_percentage' => 'decimal:2',
        ];
    }

    // ── Relationships ────────────────────────────────────
    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'tugas_id');
    }

    public function jawaban1()
    {
        return $this->belongsTo(JawabanTugas::class, 'jawaban_1_id');
    }

    public function jawaban2()
    {
        return $this->belongsTo(JawabanTugas::class, 'jawaban_2_id');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    // ── Helpers ──────────────────────────────────────────
    public function getBadgeColorAttribute(): string
    {
        if ($this->similarity_percentage < 30) {
            return 'green';
        } elseif ($this->similarity_percentage <= 70) {
            return 'yellow';
        }
        return 'red';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'safe' => 'Aman',
            'warning' => 'Perlu Review',
            'plagiat' => 'Terindikasi Plagiarisme',
            default => 'Unknown',
        };
    }
}
