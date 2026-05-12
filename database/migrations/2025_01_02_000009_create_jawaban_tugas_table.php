<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jawaban_tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->constrained('tugas')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->string('file_path')->nullable();
            $table->longText('jawaban_text')->nullable();
            $table->datetime('submitted_at')->nullable();
            $table->timestamps();

            $table->unique(['tugas_id', 'siswa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jawaban_tugas');
    }
};
