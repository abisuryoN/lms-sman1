<?php

namespace App\Services;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranService
{
    public function getPaginated(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        return TahunAjaran::withCount('kelas')->orderByDesc('id')->paginate($perPage)->withQueryString();
    }
}
