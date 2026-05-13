<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('guru_kelas', function (Blueprint $table) {
            $table->string('hari')->nullable()->after('tahun_ajaran_id');
            $table->time('jam_mulai')->nullable()->after('hari');
            $table->time('jam_selesai')->nullable()->after('jam_mulai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guru_kelas', function (Blueprint $table) {
            $table->dropColumn(['hari', 'jam_mulai', 'jam_selesai']);
        });
    }
};
