<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Jobs\CheckAssignmentSimilarityJob;
use App\Models\GuruKelas;
use App\Models\JawabanTugas;
use App\Models\SimilarityResult;
use App\Models\TahunAjaran;
use App\Models\Tugas;
use App\Services\SupabaseStorageService;
use Illuminate\Http\Request;

class SimilarityController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;
        $tahunAktif = TahunAjaran::aktif()->first();

        $tugas = Tugas::where('guru_id', $guru->id)
            ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->withCount(['jawabanTugas', 'similarityResults'])
            ->with(['kelas', 'mapel'])
            ->latest()->paginate(5)->withQueryString();

        $plagiatCount = 0;
        if ($guru && $tahunAktif) {
            $tugasIds = Tugas::where('guru_id', $guru->id)->where('tahun_ajaran_id', $tahunAktif->id)->pluck('id');
            $plagiatCount = SimilarityResult::whereIn('tugas_id', $tugasIds)->where('status', 'plagiat')->count();
        }

        return view('guru.similarity.index', compact('tugas', 'plagiatCount'));
    }

    public function detail(Tugas $tuga)
    {
        // Validasi guru punya akses ke tugas ini
        $guru = auth()->user()->guru;
        if ($tuga->guru_id !== $guru->id) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

        $results = SimilarityResult::where('tugas_id', $tuga->id)
            ->with(['jawaban1.siswa', 'jawaban2.siswa'])
            ->orderByDesc('similarity_percentage')
            ->paginate(5)->withQueryString();

        return view('guru.similarity.detail', compact('tuga', 'results'));
    }

    public function runCheck(Tugas $tuga)
    {
        // Validasi guru punya akses
        $guru = auth()->user()->guru;
        if ($tuga->guru_id !== $guru->id) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        // Dispatch job background
        CheckAssignmentSimilarityJob::dispatch($tuga->id);
        $tuga->update(['similarity_status' => 'processing']);

        return redirect()->route('guru.similarity.detail', $tuga->id)
            ->with('success', 'Pengecekan kemiripan sedang diproses di background.');
    }

    /**
     * API endpoint untuk poll status similarity (AJAX).
     */
    public function checkStatus(Tugas $tuga)
    {
        $guru = auth()->user()->guru;
        if ($tuga->guru_id !== $guru->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'status' => $tuga->similarity_status,
            'label' => $tuga->similarity_status_label,
            'checked_at' => $tuga->similarity_checked_at?->format('d M Y H:i'),
            'results_count' => $tuga->similarityResults()->count(),
        ]);
    }

    /**
     * Generate signed URL untuk guru melihat file jawaban siswa.
     * Validasi: guru hanya bisa lihat file dari kelas/mapel yang dia ajar.
     */
    public function viewFile(Request $request, JawabanTugas $jawaban)
    {
        $guru = auth()->user()->guru;
        $tugas = $jawaban->tugas;

        // Validasi: guru mengajar tugas ini
        if ($tugas->guru_id !== $guru->id) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }

        if (!$jawaban->storage_path) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        $downloadFilename = $request->query('action') === 'download' ? ($jawaban->original_filename ?? basename($jawaban->storage_path)) : null;

        $supabase = new SupabaseStorageService();
        $signedUrl = $supabase->getSignedUrl($jawaban->storage_path, null, $downloadFilename);

        if (!$signedUrl) {
            return back()->with('error', 'Gagal membuat link akses file. Silakan coba lagi.');
        }

        return redirect($signedUrl);
    }

    /**
     * Tampilkan hasil teks OCR siswa.
     */
    public function viewOcrText(JawabanTugas $jawaban)
    {
        $guru = auth()->user()->guru;
        $tugas = $jawaban->tugas;

        // Validasi akses guru
        if ($tugas->guru_id !== $guru->id) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $siswa = $jawaban->siswa;

        return response()->json([
            'siswa_nama' => $siswa->nama ?? '-',
            'ocr_status' => $jawaban->ocr_status,
            'ocr_status_label' => $jawaban->ocr_status_label,
            'extracted_text' => $jawaban->extracted_text ?? '(Tidak ada teks)',
            'processed_text' => $jawaban->processed_text ?? '(Tidak ada teks terproses)',
        ]);
    }
}
