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
        'kelas_id',
        'mapel_id',
        'jawaban_1_id',
        'jawaban_2_id',
        'student_1_id',
        'student_2_id',
        'similarity_percentage',
        'status',
        'checked_at',
        'tahun_ajaran_id',
    ];

    protected function casts(): array
    {
        return [
            'similarity_percentage' => 'decimal:2',
            'checked_at' => 'datetime',
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

    public function student1()
    {
        return $this->belongsTo(Siswa::class, 'student_1_id');
    }

    public function student2()
    {
        return $this->belongsTo(Siswa::class, 'student_2_id');
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

    // ── Helpers ──────────────────────────────────────────

    /**
     * Badge warna sesuai spesifikasi:
     * 0-39% Rendah (hijau), 40-69% Sedang (kuning), 70-100% Tinggi (merah)
     */
    public function getBadgeColorAttribute(): string
    {
        if ($this->similarity_percentage < 40) {
            return 'green';
        } elseif ($this->similarity_percentage < 70) {
            return 'yellow';
        }
        return 'red';
    }

    /**
     * Label kategori similarity
     */
    public function getSimilarityCategoryAttribute(): string
    {
        if ($this->similarity_percentage < 40) {
            return 'Rendah';
        } elseif ($this->similarity_percentage < 70) {
            return 'Sedang';
        }
        return 'Tinggi';
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
