<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\GuruKelas;
use App\Models\TahunAjaran;
use App\Models\Tugas;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $guru = $user->guru;
        $tahunAktif = TahunAjaran::aktif()->first();

        $stats = ['total_kelas' => 0, 'total_tugas' => 0, 'total_materi' => 0, 'pending_submissions' => 0];

        if ($guru && $tahunAktif) {
            $stats['total_kelas'] = GuruKelas::where('guru_id', $guru->id)->where('tahun_ajaran_id', $tahunAktif->id)->distinct('kelas_id')->count('kelas_id');
            $stats['total_tugas'] = Tugas::where('guru_id', $guru->id)->where('tahun_ajaran_id', $tahunAktif->id)->count();
            $stats['total_materi'] = $guru->materi()->where('tahun_ajaran_id', $tahunAktif->id)->count();

            $tugasIds = Tugas::where('guru_id', $guru->id)->where('tahun_ajaran_id', $tahunAktif->id)->pluck('id');
            $stats['pending_submissions'] = \App\Models\JawabanTugas::whereIn('tugas_id', $tugasIds)->count();
        }

        $recentTugas = $guru ? Tugas::where('guru_id', $guru->id)->with(['kelas', 'mapel'])->latest()->take(5)->get() : collect();
        $jadwal = $guru ? \App\Models\GuruKelas::where('guru_id', $guru->id)
            ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->whereNotNull('hari')
            ->with(['kelas', 'mapel'])
            ->get() : collect();

        return view('guru.dashboard', compact('stats', 'tahunAktif', 'recentTugas', 'jadwal'));
    }
}
