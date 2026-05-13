<?php

namespace App\Services;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaService
{
    public function getPaginated(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        $query = Siswa::with(['user', 'kelas']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'aktif');
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }
}
