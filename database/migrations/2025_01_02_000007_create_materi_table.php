<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('guru')->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('mapel_id')->constrained('mapel')->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('file_url')->nullable();
            $table->enum('tipe', ['file', 'link'])->default('file');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materi');
    }
};
