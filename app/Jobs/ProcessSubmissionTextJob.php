<?php

namespace App\Jobs;

use App\Models\JawabanTugas;
use App\Services\OcrService;
use App\Services\SupabaseStorageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessSubmissionTextJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $timeout = 300; // 5 menit max

    public function __construct(
        private int $jawabanId
    ) {}

    public function handle(): void
    {
        $jawaban = JawabanTugas::find($this->jawabanId);
        if (!$jawaban || !$jawaban->storage_path) {
            Log::warning("ProcessSubmissionTextJob: Jawaban #{$this->jawabanId} tidak ditemukan atau tidak punya file.");
            return;
        }

        // Update status ke processing
        $jawaban->update(['ocr_status' => 'processing']);

        $supabase = new SupabaseStorageService();
        $ocrService = new OcrService();

        // Tentukan ekstensi dari mime_type atau storage_path
        $extension = pathinfo($jawaban->storage_path, PATHINFO_EXTENSION);
        $tempPath = sys_get_temp_dir() . '/lms_ocr_' . uniqid() . '.' . $extension;

        try {
            // 1. Download file dari Supabase ke temp lokal
            $downloaded = $supabase->download($jawaban->storage_path, $tempPath);
            if (!$downloaded) {
                throw new \Exception("Gagal download file dari Supabase Storage: {$jawaban->storage_path}");
            }

            // 2. Ekstrak teks berdasarkan mime type
            $extractedText = '';
            try {
                $extractedText = $ocrService->extractText($tempPath, $jawaban->mime_type ?? '');
            } catch (\Exception $e) {
                // OCR gagal (misalnya Tesseract belum terinstal)
                Log::warning("ProcessSubmissionTextJob: OCR gagal untuk jawaban #{$this->jawabanId}: {$e->getMessage()}");

                $jawaban->update([
                    'ocr_status' => 'failed',
                    'extracted_text' => null,
                    'processed_text' => null,
                ]);
                return;
            }

            // 3. Jika tidak ada teks yang berhasil diekstrak
            if (empty(trim($extractedText))) {
                // Cek apakah ada jawaban_text manual
                if (!empty(trim($jawaban->jawaban_text ?? ''))) {
                    $extractedText = $jawaban->jawaban_text;
                } else {
                    $jawaban->update([
                        'extracted_text' => '',
                        'processed_text' => '',
                        'ocr_status' => 'success', // Berhasil tapi kosong
                    ]);
                    return;
                }
            }

            // 4. Preprocessing teks
            $processedText = $this->preprocessText($extractedText);

            // 5. Simpan hasil ke database
            $jawaban->update([
                'extracted_text' => $extractedText,
                'processed_text' => $processedText,
                'ocr_status' => 'success',
            ]);

            Log::info("ProcessSubmissionTextJob: Berhasil proses jawaban #{$this->jawabanId}");
        } catch (\Exception $e) {
            Log::error("ProcessSubmissionTextJob: Error untuk jawaban #{$this->jawabanId}: {$e->getMessage()}");

            $jawaban->update([
                'ocr_status' => 'failed',
            ]);
        } finally {
            // 6. Hapus file temp dari server Laravel
            if (file_exists($tempPath)) {
                @unlink($tempPath);
            }
        }
    }

    /**
     * Preprocessing teks:
     * 1. Case folding (lowercase)
     * 2. Cleaning (hapus karakter tidak penting)
     * 3. Tokenizing (pecah jadi kata)
     * 4. Filtering (hapus stopwords bahasa Indonesia)
     * 5. Gabungkan kembali jadi string
     */
    private function preprocessText(string $text): string
    {
        // 1. Case folding
        $text = mb_strtolower($text, 'UTF-8');

        // 2. Cleaning: hapus karakter khusus, simbol berlebihan, spasi ganda
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text);
        $text = preg_replace('/\s+/', ' ', trim($text));

        // 3. Tokenizing
        $tokens = explode(' ', $text);

        // 4. Filtering: hapus stopwords dan kata pendek
        $stopwords = $this->getStopwords();
        $tokens = array_filter($tokens, function ($token) use ($stopwords) {
            return strlen($token) >= 2 && !in_array($token, $stopwords);
        });

        // 5. Gabungkan kembali
        return implode(' ', array_values($tokens));
    }

    /**
     * Daftar stopwords bahasa Indonesia + English
     */
    private function getStopwords(): array
    {
        return [
            // Bahasa Indonesia
            'dan', 'di', 'ke', 'dari', 'yang', 'untuk', 'pada', 'dengan', 'adalah',
            'ini', 'itu', 'atau', 'juga', 'sudah', 'saya', 'anda', 'kami', 'kita',
            'mereka', 'dia', 'akan', 'bisa', 'ada', 'tidak', 'bukan', 'hanya',
            'lebih', 'sangat', 'telah', 'sedang', 'masih', 'belum', 'harus',
            'dapat', 'oleh', 'karena', 'jika', 'maka', 'saat', 'ketika',
            'setelah', 'sebelum', 'antara', 'dalam', 'luar', 'atas', 'bawah',
            'seperti', 'sama', 'lain', 'semua', 'setiap', 'para', 'tersebut',
            'yaitu', 'bahwa', 'tentang', 'tanpa', 'melalui', 'secara', 'serta',
            'namun', 'tetapi', 'melainkan', 'selain', 'maupun', 'agar', 'supaya',
            'apabila', 'adapun', 'demikian', 'begitu', 'sehingga', 'meskipun',
            'walaupun', 'biarpun', 'sejak', 'sampai', 'hingga', 'terhadap',
            'mengenai', 'perihal', 'yakni', 'sebagai', 'merupakan',
            // English
            'the', 'a', 'an', 'is', 'are', 'was', 'were', 'be', 'been', 'being',
            'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'could',
            'should', 'may', 'might', 'shall', 'can', 'of', 'in', 'to', 'for',
            'with', 'on', 'at', 'by', 'from', 'as', 'into', 'through', 'during',
            'before', 'after', 'above', 'below', 'between', 'under', 'again',
            'further', 'then', 'once', 'here', 'there', 'when', 'where', 'why',
            'how', 'all', 'each', 'every', 'both', 'few', 'more', 'most', 'other',
            'some', 'such', 'no', 'nor', 'not', 'only', 'own', 'same', 'so',
            'than', 'too', 'very', 'just', 'because', 'but', 'and', 'if', 'or',
            'while', 'about', 'against', 'it', 'this', 'that', 'these', 'those',
        ];
    }

    /**
     * Handler jika job gagal sepenuhnya
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("ProcessSubmissionTextJob: Job FAILED untuk jawaban #{$this->jawabanId}: {$exception->getMessage()}");

        $jawaban = JawabanTugas::find($this->jawabanId);
        if ($jawaban) {
            $jawaban->update(['ocr_status' => 'failed']);
        }
    }
}
