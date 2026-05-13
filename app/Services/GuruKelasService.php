<?php

namespace App\Services;

use App\Models\GuruKelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class GuruKelasService
{
    public function getPaginated(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        $tahunAktif = TahunAjaran::aktif()->first();
        $query = GuruKelas::with(['guru', 'kelas', 'mapel', 'tahunAjaran']);

        if ($tahunAktif && !$request->filled('tahun_ajaran_id')) {
            $query->where('tahun_ajaran_id', $tahunAktif->id);
        } elseif ($request->filled('tahun_ajaran_id')) {
            $query->where('tahun_ajaran_id', $request->tahun_ajaran_id);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }
}
