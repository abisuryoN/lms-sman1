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
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_mapel', 'like', "%{$searchTerm}%")
                  ->orWhere('kode_mapel', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }

        return $query->orderBy('tingkat')->orderBy('nama_mapel')->paginate($perPage)->withQueryString();
    }
}
