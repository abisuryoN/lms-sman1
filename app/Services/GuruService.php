<?php

namespace App\Services;

use App\Models\Guru;
use Illuminate\Http\Request;

class GuruService
{
    public function getPaginated(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        $query = Guru::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }
}
