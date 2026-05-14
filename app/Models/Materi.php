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
        'storage_path',
        'original_filename',
        'mime_type',
        'file_size',
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

    // ── Accessors ────────────────────────────────────────
    public function getFileFullUrlAttribute(): string
    {
        if ($this->tipe === 'link') {
            return $this->storage_path ?? '#';
        }

        if (!$this->storage_path) return '#';

        // Gunakan cache untuk Signed URL agar performa tetap terjaga
        return \Illuminate\Support\Facades\Cache::remember(
            "materi_url_{$this->id}",
            540, // 9 menit (Signed URL 10 menit)
            function () {
                $supabase = new \App\Services\SupabaseStorageService(config('services.supabase.materi_bucket'));
                return $supabase->getSignedUrl($this->storage_path) ?? '#';
            }
        );
    }

    public function getDownloadUrlAttribute(): string
    {
        if ($this->tipe === 'link' || !$this->storage_path) return '#';

        // Paksa download dengan parameter download=filename
        $url = $this->file_full_url;
        if ($url !== '#') {
            $separator = str_contains($url, '?') ? '&' : '?';
            return $url . $separator . 'download=' . urlencode($this->original_filename);
        }
        return '#';
    }

    public function getFileSizeHumanAttribute(): string
    {
        if (!$this->file_size) return '0 B';
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = floor(log($this->file_size, 1024));
        return round($this->file_size / pow(1024, $i), 2) . ' ' . $units[$i];
    }
}
