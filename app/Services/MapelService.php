<?php

namespace App\Services;

use App\Models\Mapel;
use Illuminate\Http\Request;

class MapelService
{
    public function getPaginated(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        $query = Mapel::query();

        if ($request->filled('search')) {
            $query->where('nama_mapel', 'like', "%{$request->search}%")
                  ->orWhere('kode_mapel', 'like', "%{$request->search}%");
        }

        return $query->orderBy('nama_mapel')->paginate($perPage)->withQueryString();
    }
}
