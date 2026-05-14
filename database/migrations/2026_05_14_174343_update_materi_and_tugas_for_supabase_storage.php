<?php
/* Migration generated to update materi and tugas tables for Supabase Storage */
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
        // Update tabel materi
        Schema::table('materi', function (Blueprint $table) {
            // Rename file_url ke storage_path jika ada
            if (Schema::hasColumn('materi', 'file_url')) {
                $table->renameColumn('file_url', 'storage_path');
            } else {
                $table->string('storage_path')->nullable()->after('deskripsi');
            }

            // Tambahkan metadata file jika belum ada
            if (!Schema::hasColumn('materi', 'mime_type')) {
                $table->string('mime_type')->nullable()->after('storage_path');
            }
            if (!Schema::hasColumn('materi', 'file_size')) {
                $table->unsignedBigInteger('file_size')->nullable()->after('mime_type');
            }
        });

        // Update tabel tugas
        Schema::table('tugas', function (Blueprint $table) {
            // Rename file_url ke soal_storage_path jika ada
            if (Schema::hasColumn('tugas', 'file_url')) {
                $table->renameColumn('file_url', 'soal_storage_path');
            } else {
                $table->string('soal_storage_path')->nullable()->after('deskripsi');
            }

            // Rename original_filename ke soal_original_filename jika ada
            if (Schema::hasColumn('tugas', 'original_filename')) {
                $table->renameColumn('original_filename', 'soal_original_filename');
            } else {
                $table->string('soal_original_filename')->nullable()->after('soal_storage_path');
            }

            // Tambahkan metadata file jika belum ada
            if (!Schema::hasColumn('tugas', 'soal_mime_type')) {
                $table->string('soal_mime_type')->nullable()->after('soal_original_filename');
            }
            if (!Schema::hasColumn('tugas', 'soal_file_size')) {
                $table->unsignedBigInteger('soal_file_size')->nullable()->after('soal_mime_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materi', function (Blueprint $table) {
            $table->renameColumn('storage_path', 'file_url');
            $table->dropColumn(['mime_type', 'file_size']);
        });

        Schema::table('tugas', function (Blueprint $table) {
            $table->renameColumn('soal_storage_path', 'file_url');
            $table->renameColumn('soal_original_filename', 'original_filename');
            $table->dropColumn(['soal_mime_type', 'soal_file_size']);
        });
    }
};
