<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tugas', function (Blueprint $table) {
            $table->enum('similarity_status', ['unchecked', 'processing', 'completed', 'failed'])
                  ->default('unchecked')->after('status');
            $table->datetime('similarity_checked_at')->nullable()->after('similarity_status');
        });
    }

    public function down(): void
    {
        Schema::table('tugas', function (Blueprint $table) {
            $table->dropColumn(['similarity_status', 'similarity_checked_at']);
        });
    }
};
