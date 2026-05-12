<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\SimilarityResult;
use App\Models\TahunAjaran;
use App\Models\Tugas;
use App\Services\CosineSimilarityService;

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
            ->latest()->get();

        $plagiatCount = 0;
        if ($guru && $tahunAktif) {
            $tugasIds = Tugas::where('guru_id', $guru->id)->where('tahun_ajaran_id', $tahunAktif->id)->pluck('id');
            $plagiatCount = SimilarityResult::whereIn('tugas_id', $tugasIds)->where('status', 'plagiat')->count();
        }

        return view('guru.similarity.index', compact('tugas', 'plagiatCount'));
    }

    public function detail(Tugas $tuga)
    {
        $results = SimilarityResult::where('tugas_id', $tuga->id)
            ->with(['jawaban1.siswa', 'jawaban2.siswa'])
            ->orderByDesc('similarity_percentage')
            ->get();

        return view('guru.similarity.detail', compact('tuga', 'results'));
    }

    public function runCheck(Tugas $tuga)
    {
        $service = new CosineSimilarityService();
        $service->compareAnswers($tuga->id);

        return redirect()->route('guru.similarity.detail', $tuga->id)->with('success', 'Analisis similarity selesai.');
    }
}
