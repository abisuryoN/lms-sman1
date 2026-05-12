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
        'jawaban_text',
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
}
