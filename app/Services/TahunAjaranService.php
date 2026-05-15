<?php

namespace App\Services;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranService
{
    public function getPaginated(Request $request, $perPage = 5)
    {
        return TahunAjaran::withCount('kelas')->orderByDesc('id')->paginate(5)->withQueryString();
    }
}
