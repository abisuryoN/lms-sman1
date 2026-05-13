<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\GuruKelas;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;
        $tahunAktif = TahunAjaran::aktif()->first();

        if (!$guru) {
            return back()->with('error', 'Profil Guru tidak ditemukan.');
        }

        $kelasIds = GuruKelas::where('guru_id', $guru->id)
            ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->distinct('kelas_id')
            ->pluck('kelas_id');

        $kelasList = Kelas::whereIn('id', $kelasIds)
            ->withCount('siswa')
            ->with('tahunAjaran')
            ->get();

        return view('guru.kelas.index', compact('kelasList', 'tahunAktif'));
    }

    public function show(Kelas $kela)
    {
        $guru = auth()->user()->guru;
        $tahunAktif = TahunAjaran::aktif()->first();

        // Security check: ensure guru teaches this class
        $isTeaching = GuruKelas::where('guru_id', $guru->id)
            ->where('kelas_id', $kela->id)
            ->exists();

        if (!$isTeaching && !auth()->user()->isAdmin()) {
            abort(403, 'Anda tidak mengajar di kelas ini.');
        }

        $kela->load(['tahunAjaran', 'waliKelas', 'siswa' => function($q) {
            $q->orderBy('nama');
        }]);

        return view('guru.kelas.show', compact('kela', 'tahunAktif'));
    }
}
