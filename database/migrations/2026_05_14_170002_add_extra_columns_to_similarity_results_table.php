<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('similarity_results', function (Blueprint $table) {
            $table->foreignId('kelas_id')->nullable()->after('tugas_id')
                  ->constrained('kelas')->nullOnDelete();
            $table->foreignId('mapel_id')->nullable()->after('kelas_id')
                  ->constrained('mapel')->nullOnDelete();
            $table->foreignId('student_1_id')->nullable()->after('jawaban_1_id')
                  ->constrained('siswa')->nullOnDelete();
            $table->foreignId('student_2_id')->nullable()->after('jawaban_2_id')
                  ->constrained('siswa')->nullOnDelete();
            $table->datetime('checked_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('similarity_results', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->dropForeign(['mapel_id']);
            $table->dropForeign(['student_1_id']);
            $table->dropForeign(['student_2_id']);
            $table->dropColumn(['kelas_id', 'mapel_id', 'student_1_id', 'student_2_id', 'checked_at']);
        });
    }
};
