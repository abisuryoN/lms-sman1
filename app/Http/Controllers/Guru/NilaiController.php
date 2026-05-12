<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\JawabanTugas;
use App\Models\TahunAjaran;
use App\Models\Tugas;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $guru = auth()->user()->guru;
        $tahunAktif = TahunAjaran::aktif()->first();

        $tugas = Tugas::where('guru_id', $guru->id)
            ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->with(['kelas', 'mapel'])
            ->latest()->get();

        return view('guru.nilai.index', compact('tugas'));
    }

    public function edit(Tugas $tuga)
    {
        $jawaban = JawabanTugas::where('tugas_id', $tuga->id)->with('siswa')->get();
        $nilai = Nilai::where('tugas_id', $tuga->id)->pluck('nilai', 'siswa_id');

        return view('guru.nilai.edit', compact('tuga', 'jawaban', 'nilai'));
    }

    public function update(Request $request, Tugas $tuga)
    {
        $request->validate([
            'nilai' => 'required|array',
            'nilai.*' => 'nullable|numeric|min:0|max:100',
            'komentar' => 'nullable|array',
        ]);

        $tahunAktif = TahunAjaran::aktif()->first();

        foreach ($request->nilai as $siswaId => $nilaiValue) {
            if ($nilaiValue !== null) {
                Nilai::updateOrCreate(
                    ['tugas_id' => $tuga->id, 'siswa_id' => $siswaId],
                    ['nilai' => $nilaiValue, 'komentar' => $request->komentar[$siswaId] ?? null, 'tahun_ajaran_id' => $tahunAktif->id]
                );
            }
        }

        return back()->with('success', 'Nilai berhasil disimpan.');
    }
}
