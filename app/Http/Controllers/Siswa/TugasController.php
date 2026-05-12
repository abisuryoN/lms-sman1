<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\JawabanTugas;
use App\Models\TahunAjaran;
use App\Models\Tugas;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;
        $tahunAktif = TahunAjaran::aktif()->first();

        $tugas = collect();
        if ($siswa && $siswa->kelas_id) {
            $tugas = Tugas::where('kelas_id', $siswa->kelas_id)
                ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
                ->with('mapel')->latest()->paginate(10);
        }

        $submitted = $siswa ? JawabanTugas::where('siswa_id', $siswa->id)->pluck('tugas_id')->toArray() : [];

        return view('siswa.tugas.index', compact('tugas', 'submitted'));
    }

    public function show(Tugas $tuga)
    {
        $siswa = auth()->user()->siswa;
        $jawaban = JawabanTugas::where('tugas_id', $tuga->id)->where('siswa_id', $siswa->id)->first();
        return view('siswa.tugas.show', compact('tuga', 'jawaban'));
    }

    public function submit(Request $request, Tugas $tuga)
    {
        $siswa = auth()->user()->siswa;

        $request->validate([
            'jawaban_text' => 'required|string|min:10',
            'file' => 'nullable|file|mimes:pdf,docx,doc,txt|max:5120',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('uploads/jawaban', 'public');
        }

        JawabanTugas::updateOrCreate(
            ['tugas_id' => $tuga->id, 'siswa_id' => $siswa->id],
            ['jawaban_text' => $request->jawaban_text, 'file_path' => $filePath, 'submitted_at' => now()]
        );

        return redirect()->route('siswa.tugas.index')->with('success', 'Jawaban berhasil dikumpulkan.');
    }
}
