<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use App\Models\Tugas;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $siswa = $user->siswa;
        $tahunAktif = TahunAjaran::aktif()->first();

        $stats = ['total_tugas' => 0, 'tugas_selesai' => 0, 'tugas_belum' => 0, 'rata_nilai' => 0];

        if ($siswa && $siswa->kelas_id) {
            $tugasQuery = Tugas::where('kelas_id', $siswa->kelas_id)->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id));
            $stats['total_tugas'] = $tugasQuery->count();
            $stats['tugas_selesai'] = $siswa->jawabanTugas()->whereIn('tugas_id', $tugasQuery->pluck('id'))->count();
            $stats['tugas_belum'] = $stats['total_tugas'] - $stats['tugas_selesai'];
            $stats['rata_nilai'] = $siswa->nilai()->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))->avg('nilai') ?? 0;
        }

        $tugasTerbaru = collect();
        $jadwal = collect();
        if ($siswa && $siswa->kelas_id) {
            $tugasTerbaru = Tugas::where('kelas_id', $siswa->kelas_id)
                ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
                ->aktif()->with('mapel')->orderBy('deadline')->take(5)->get();

            $jadwal = \App\Models\GuruKelas::where('kelas_id', $siswa->kelas_id)
                ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
                ->whereNotNull('hari')
                ->with(['guru', 'mapel'])
                ->get();
        }

        return view('siswa.dashboard', compact('stats', 'siswa', 'tugasTerbaru', 'jadwal'));
    }
}
