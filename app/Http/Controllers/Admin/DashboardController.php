<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\Tugas;
use App\Models\SimilarityResult;

class DashboardController extends Controller
{
    public function index()
    {
        $tahunAjaranAktif = TahunAjaran::aktif()->first();

        $stats = [
            'total_siswa' => Siswa::aktif()->count(),
            'total_guru' => Guru::count(),
            'total_kelas' => $tahunAjaranAktif ? Kelas::where('tahun_ajaran_id', $tahunAjaranAktif->id)->count() : 0,
            'total_mapel' => Mapel::count(),
            'total_alumni' => Siswa::alumni()->count(),
            'total_tugas' => $tahunAjaranAktif ? Tugas::where('tahun_ajaran_id', $tahunAjaranAktif->id)->count() : 0,
        ];

        // Data for charts — students per class
        $kelasData = [];
        if ($tahunAjaranAktif) {
            $kelasData = Kelas::where('tahun_ajaran_id', $tahunAjaranAktif->id)
                ->withCount('siswa')
                ->get()
                ->map(fn($k) => ['nama' => $k->nama_kelas, 'jumlah' => $k->siswa_count]);
        }

        // Recent similarity alerts
        $recentAlerts = SimilarityResult::where('status', 'plagiat')
            ->with(['jawaban1.siswa', 'jawaban2.siswa', 'tugas'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'tahunAjaranAktif', 'kelasData', 'recentAlerts'));
    }
}
