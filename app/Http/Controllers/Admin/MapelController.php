<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function index(Request $request)
    {
        $query = Mapel::query();

        if ($request->filled('search')) {
            $query->where('nama_mapel', 'like', "%{$request->search}%")
                  ->orWhere('kode_mapel', 'like', "%{$request->search}%");
        }

        $mapel = $query->orderBy('nama_mapel')->paginate(15);
        return view('admin.mapel.index', compact('mapel'));
    }

    public function create()
    {
        return view('admin.mapel.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:255',
            'kode_mapel' => 'required|string|max:20|unique:mapel,kode_mapel',
        ]);

        Mapel::create($request->only('nama_mapel', 'kode_mapel'));

        return redirect()->route('admin.mapel.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(Mapel $mapel)
    {
        return view('admin.mapel.edit', compact('mapel'));
    }

    public function update(Request $request, Mapel $mapel)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:255',
            'kode_mapel' => 'required|string|max:20|unique:mapel,kode_mapel,' . $mapel->id,
        ]);

        $mapel->update($request->only('nama_mapel', 'kode_mapel'));

        return redirect()->route('admin.mapel.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Mapel $mapel)
    {
        $mapel->delete();
        return redirect()->route('admin.mapel.index')
            ->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
