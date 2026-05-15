<?php

namespace App\Services;

use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class KelasService
{
    public function getPaginated(Request $request, $perPage = 5)
    {
        $tahunAjaranAktif = TahunAjaran::aktif()->first();
        $query = Kelas::with(['tahunAjaran', 'waliKelas', 'siswa']);

        if ($tahunAjaranAktif && !$request->filled('tahun_ajaran_id')) {
            $query->where('tahun_ajaran_id', $tahunAjaranAktif->id);
        } elseif ($request->filled('tahun_ajaran_id')) {
            $query->where('tahun_ajaran_id', $request->tahun_ajaran_id);
        }

        return $query->withCount('siswa')->latest()->paginate(5)->withQueryString();
    }
}
