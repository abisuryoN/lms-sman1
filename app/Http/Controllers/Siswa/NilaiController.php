<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\TahunAjaran;

class NilaiController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;
        $tahunAktif = TahunAjaran::aktif()->first();

        $nilai = collect();
        if ($siswa) {
            $nilai = Nilai::where('siswa_id', $siswa->id)
                ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
                ->with(['tugas.mapel'])->latest()->paginate(5);
        }

        return view('siswa.nilai.index', compact('nilai'));
    }
}
