<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriLog extends Model
{
    use HasFactory;

    protected $fillable = ['materi_id', 'siswa_id'];

    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
