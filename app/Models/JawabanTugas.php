<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanTugas extends Model
{
    use HasFactory;

    protected $table = 'jawaban_tugas';

    protected $fillable = [
        'tugas_id',
        'siswa_id',
        'file_path',
        'storage_path',
        'original_filename',
        'mime_type',
        'file_size',
        'jawaban_text',
        'extracted_text',
        'processed_text',
        'ocr_status',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
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

    public function similarityAsJawaban1()
    {
        return $this->hasMany(SimilarityResult::class, 'jawaban_1_id');
    }

    public function similarityAsJawaban2()
    {
        return $this->hasMany(SimilarityResult::class, 'jawaban_2_id');
    }

    // ── Accessors ────────────────────────────────────────
    public function getOcrStatusLabelAttribute(): string
    {
        return match ($this->ocr_status) {
            'pending' => 'Menunggu',
            'processing' => 'Sedang Diproses',
            'success' => 'Berhasil',
            'failed' => 'Gagal',
            default => '-',
        };
    }

    public function getOcrBadgeColorAttribute(): string
    {
        return match ($this->ocr_status) {
            'pending' => 'gray',
            'processing' => 'blue',
            'success' => 'green',
            'failed' => 'red',
            default => 'gray',
        };
    }

    public function getFileSizeFormattedAttribute(): string
    {
        if (!$this->file_size) return '-';
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $i = 0;
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }
        return round($size, 2) . ' ' . $units[$i];
    }
}
