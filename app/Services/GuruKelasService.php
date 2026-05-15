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

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('guru', function($q) use ($searchTerm) {
                    $q->where('nama', 'like', "%{$searchTerm}%");
                })
                ->orWhereHas('kelas', function($q) use ($searchTerm) {
                    $q->where('nama_kelas', 'like', "%{$searchTerm}%");
                })
                ->orWhereHas('mapel', function($q) use ($searchTerm) {
                    $q->where('nama_mapel', 'like', "%{$searchTerm}%");
                });
            });
        }

        if ($request->filled('hari')) {
            $query->where('hari', $request->hari);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }
}
