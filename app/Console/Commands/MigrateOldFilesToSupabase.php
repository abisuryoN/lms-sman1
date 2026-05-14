<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JawabanTugas;
use App\Services\SupabaseStorageService;
use App\Jobs\ProcessSubmissionTextJob;
use Illuminate\Support\Facades\Storage;

class MigrateOldFilesToSupabase extends Command
{
    /**
     * Nama dan signature dari console command.
     *
     * @var string
     */
    protected $signature = 'supabase:migrate-files';

    /**
     * Deskripsi console command.
     *
     * @var string
     */
    protected $description = 'Migrasi file jawaban lama dari penyimpanan lokal ke Supabase Storage dan jalankan ekstraksi OCR';

    /**
     * Eksekusi console command.
     */
    public function handle()
    {
        $this->info('Memulai pencarian file jawaban lokal yang belum dimigrasi ke Supabase...');

        // Cari jawaban tugas yang punya file lokal tapi belum punya storage_path Supabase
        $jawabans = JawabanTugas::whereNotNull('file_path')->whereNull('storage_path')->get();

        if ($jawabans->isEmpty()) {
            $this->info('Semua file jawaban sudah memiliki storage_path Supabase atau tidak ada file lokal yang perlu dimigrasi.');
            return 0;
        }

        $this->info("Ditemukan {$jawabans->count()} file jawaban untuk dimigrasi.");
        $supabase = new SupabaseStorageService();

        $bar = $this->output->createProgressBar($jawabans->count());
        $bar->start();

        $berhasil = 0;
        $gagal = 0;

        foreach ($jawabans as $jawaban) {
            // Periksa eksistensi file di public disk
            if (Storage::disk('public')->exists($jawaban->file_path)) {
                $localFilePath = Storage::disk('public')->path($jawaban->file_path);
                
                // Susun path unik di Supabase: "jawaban/{tugas_id}/{siswa_id}/{filename}"
                $filename = basename($jawaban->file_path);
                $storagePath = "jawaban/{$jawaban->tugas_id}/{$jawaban->siswa_id}/{$filename}";

                // Deteksi MIME type
                $mimeType = @mime_content_type($localFilePath) ?: 'application/octet-stream';

                // Upload file murni ke Supabase Storage
                if ($supabase->upload($localFilePath, $storagePath, $mimeType)) {
                    // Update database record
                    $jawaban->update([
                        'storage_path' => $storagePath,
                        'original_filename' => $jawaban->original_filename ?: $filename,
                        'ocr_status' => 'pending',
                    ]);

                    // Daftarkan ke antrean pemrosesan OCR latar belakang
                    ProcessSubmissionTextJob::dispatch($jawaban->id);
                    $berhasil++;
                } else {
                    $gagal++;
                }
            } else {
                $gagal++;
                $this->newLine();
                $this->error("File lokal tidak ditemukan di path: {$jawaban->file_path}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Migrasi selesai! Berhasil: {$berhasil} file | Gagal: {$gagal} file.");
        $this->info("Proses ekstraksi teks (OCR/NLP Preprocessing) untuk file yang berhasil dimigrasi langsung didaftarkan ke background Queue.");
        $this->warn("Pastikan Anda menjalankan perintah: php artisan queue:work agar ekstraksi teks dan uji kemiripan dapat diproses.");

        return 0;
    }
}
