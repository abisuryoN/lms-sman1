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
        $guruId = request('guru_id');

        if (!$siswa || !$siswa->kelas_id) {
            return view('siswa.materi.index', ['teachers' => collect(), 'materi' => collect()]);
        }

        // Jika tidak ada guru_id, tampilkan daftar guru yang mengupload materi untuk kelas ini
        if (!$guruId) {
            $teachers = \App\Models\Guru::whereHas('materi', function($q) use ($siswa, $tahunAktif) {
                $q->where('kelas_id', $siswa->kelas_id)
                  ->when($tahunAktif, fn($q2) => $q2->where('tahun_ajaran_id', $tahunAktif->id));
            })->with(['user', 'materi' => function($q) use ($siswa, $tahunAktif) {
                $q->where('kelas_id', $siswa->kelas_id)
                  ->when($tahunAktif, fn($q2) => $q2->where('tahun_ajaran_id', $tahunAktif->id))
                  ->with('mapel');
            }])->get();

            return view('siswa.materi.index', compact('teachers'));
        }

        // Jika ada guru_id, tampilkan materi dari guru tersebut
        $selectedGuru = \App\Models\Guru::findOrFail($guruId);
        $materi = Materi::where('kelas_id', $siswa->kelas_id)
            ->where('guru_id', $guruId)
            ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->with(['mapel', 'guru'])->latest()->paginate(5);

        return view('siswa.materi.index', compact('materi', 'selectedGuru'));
    }

    public function download(Materi $materi)
    {
        $siswa = auth()->user()->siswa;
        
        if ($siswa) {
            \App\Models\MateriLog::firstOrCreate([
                'materi_id' => $materi->id,
                'siswa_id' => $siswa->id
            ]);
        }

        if ($materi->tipe === 'link') {
            return redirect($materi->storage_path ?? '#');
        }

        if (!$materi->storage_path) {
            return back()->with('error', 'Materi tidak memiliki file.');
        }

        $url = $materi->download_url;
        if ($url === '#') {
            return back()->with('error', 'Gagal menghasilkan link download.');
        }

        return redirect($url);
    }
}
