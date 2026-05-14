<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jawaban_tugas', function (Blueprint $table) {
            $table->string('storage_path')->nullable()->after('file_path');
            $table->string('mime_type')->nullable()->after('original_filename');
            $table->unsignedBigInteger('file_size')->nullable()->after('mime_type');
            $table->longText('extracted_text')->nullable()->after('jawaban_text');
            $table->longText('processed_text')->nullable()->after('extracted_text');
            $table->enum('ocr_status', ['pending', 'processing', 'success', 'failed'])
                  ->default('pending')->after('processed_text');
        });
    }

    public function down(): void
    {
        Schema::table('jawaban_tugas', function (Blueprint $table) {
            $table->dropColumn([
                'storage_path', 'mime_type', 'file_size',
                'extracted_text', 'processed_text', 'ocr_status',
            ]);
        });
    }
};
