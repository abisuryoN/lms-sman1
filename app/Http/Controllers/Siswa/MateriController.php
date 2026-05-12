<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\TahunAjaran;

class MateriController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;
        $tahunAktif = TahunAjaran::aktif()->first();

        $materi = collect();
        if ($siswa && $siswa->kelas_id) {
            $materi = Materi::where('kelas_id', $siswa->kelas_id)
                ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
                ->with(['mapel', 'guru'])->latest()->paginate(10);
        }

        return view('siswa.materi.index', compact('materi'));
    }
}
