<?php

namespace App\Jobs;

use App\Models\JawabanTugas;
use App\Models\SimilarityResult;
use App\Models\Tugas;
use App\Services\CosineSimilarityService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckAssignmentSimilarityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $timeout = 600; // 10 menit max

    public function __construct(
        private int $tugasId
    ) {}

    public function handle(): void
    {
        $tugas = Tugas::find($this->tugasId);
        if (!$tugas) {
            Log::warning("CheckAssignmentSimilarityJob: Tugas #{$this->tugasId} tidak ditemukan.");
            return;
        }

        // Update status ke processing
        $tugas->update(['similarity_status' => 'processing']);

        try {
            $service = new CosineSimilarityService();
            $service->compareAnswers($tugas->id);

            // Update status ke completed
            $tugas->update([
                'similarity_status' => 'completed',
                'similarity_checked_at' => now(),
            ]);

            Log::info("CheckAssignmentSimilarityJob: Similarity check selesai untuk tugas #{$this->tugasId}");
        } catch (\Exception $e) {
            Log::error("CheckAssignmentSimilarityJob: Error untuk tugas #{$this->tugasId}: {$e->getMessage()}");

            $tugas->update(['similarity_status' => 'failed']);
        }
    }

    /**
     * Handler jika job gagal sepenuhnya
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("CheckAssignmentSimilarityJob: Job FAILED untuk tugas #{$this->tugasId}: {$exception->getMessage()}");

        $tugas = Tugas::find($this->tugasId);
        if ($tugas) {
            $tugas->update(['similarity_status' => 'failed']);
        }
    }
}
