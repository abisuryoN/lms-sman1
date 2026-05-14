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
        'soal_storage_path',
        'soal_original_filename',
        'soal_mime_type',
        'soal_file_size',
        'tipe',
        'deadline',
        'status',
        'similarity_status',
        'similarity_checked_at',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
            'similarity_checked_at' => 'datetime',
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

    public function getSimilarityStatusLabelAttribute(): string
    {
        return match ($this->similarity_status) {
            'unchecked' => 'Belum Dicek',
            'processing' => 'Sedang Diproses',
            'completed' => 'Selesai',
            'failed' => 'Gagal',
            default => 'Belum Dicek',
        };
    }

    public function getSimilarityBadgeColorAttribute(): string
    {
        return match ($this->similarity_status) {
            'unchecked' => 'gray',
            'processing' => 'blue',
            'completed' => 'green',
            'failed' => 'red',
            default => 'gray',
        };
    }

    // ── File Accessors ───────────────────────────────────
    public function getSoalFullUrlAttribute(): string
    {
        if ($this->tipe === 'link') {
            return $this->soal_storage_path ?? '#';
        }

        if (!$this->soal_storage_path) return '#';

        return \Illuminate\Support\Facades\Cache::remember(
            "tugas_soal_url_{$this->id}",
            540,
            function () {
                $supabase = new \App\Services\SupabaseStorageService(config('services.supabase.soal_bucket'));
                return $supabase->getSignedUrl($this->soal_storage_path) ?? '#';
            }
        );
    }

    public function getSoalDownloadUrlAttribute(): string
    {
        if ($this->tipe === 'link' || !$this->soal_storage_path) return '#';

        $url = $this->soal_full_url;
        if ($url !== '#') {
            $separator = str_contains($url, '?') ? '&' : '?';
            return $url . $separator . 'download=' . urlencode($this->soal_original_filename);
        }
        return '#';
    }

    public function getSoalSizeHumanAttribute(): string
    {
        if (!$this->soal_file_size) return '0 B';
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = floor(log($this->soal_file_size, 1024));
        return round($this->soal_file_size / pow(1024, $i), 2) . ' ' . $units[$i];
    }
}
