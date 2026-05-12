<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('similarity_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->constrained('tugas')->cascadeOnDelete();
            $table->foreignId('jawaban_1_id')->constrained('jawaban_tugas')->cascadeOnDelete();
            $table->foreignId('jawaban_2_id')->constrained('jawaban_tugas')->cascadeOnDelete();
            $table->decimal('similarity_percentage', 5, 2)->default(0);
            $table->enum('status', ['safe', 'warning', 'plagiat'])->default('safe');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('similarity_results');
    }
};
