<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\GuruKelas;
use App\Models\JawabanTugas;
use App\Models\TahunAjaran;
use App\Models\Tugas;
use App\Services\CosineSimilarityService;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;
        $tahunAktif = TahunAjaran::aktif()->first();

        $tugas = Tugas::where('guru_id', $guru->id)
            ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->withCount('jawabanTugas')
            ->with(['kelas', 'mapel'])
            ->latest()->paginate(10);

        return view('guru.tugas.index', compact('tugas'));
    }

    public function create()
    {
        $guru = auth()->user()->guru;
        $tahunAktif = TahunAjaran::aktif()->first();
        $guruKelas = GuruKelas::where('guru_id', $guru->id)
            ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->with(['kelas', 'mapel'])->get();

        return view('guru.tugas.create', compact('guruKelas', 'tahunAktif'));
    }

    public function store(Request $request)
    {
        $guru = auth()->user()->guru;
        $tahunAktif = TahunAjaran::aktif()->first();

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mapel,id',
            'deadline' => 'required|date|after:now',
        ]);

        Tugas::create([
            'guru_id' => $guru->id,
            'kelas_id' => $request->kelas_id,
            'mapel_id' => $request->mapel_id,
            'tahun_ajaran_id' => $tahunAktif->id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'deadline' => $request->deadline,
        ]);

        return redirect()->route('guru.tugas.index')->with('success', 'Tugas berhasil dibuat.');
    }

    public function show(Tugas $tuga)
    {
        $tuga->load(['kelas', 'mapel', 'jawabanTugas.siswa', 'similarityResults']);
        $jawaban = JawabanTugas::where('tugas_id', $tuga->id)->with('siswa')->get();
        return view('guru.tugas.show', compact('tuga', 'jawaban'));
    }

    public function checkSimilarity(Tugas $tuga)
    {
        $service = new CosineSimilarityService();
        $results = $service->compareAnswers($tuga->id);

        return redirect()->route('guru.similarity.detail', $tuga->id)
            ->with('similarity_results', $results);
    }
}
