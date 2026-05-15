<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuruKelas;
use App\Models\Mapel;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use App\Services\MapelService;

class MapelController extends Controller
{
    protected $mapelService;

    public function __construct(MapelService $mapelService)
    {
        $this->mapelService = $mapelService;
    }

    public function index(Request $request)
    {
        $mapel = $this->mapelService->getPaginated($request);
        return view('admin.mapel.index', compact('mapel'));
    }

    public function show(Request $request, Mapel $mapel)
    {
        $tahunAktif = TahunAjaran::aktif()->first();
        $selectedTingkat = $request->query('tingkat', $mapel->tingkat);

        // Map tingkat angka ke prefix romawi nama kelas
        $tingkatMap = [
            '10' => 'X ',
            '11' => 'XI ',
            '12' => 'XII ',
        ];

        $assignments = collect();
        if ($selectedTingkat && isset($tingkatMap[$selectedTingkat])) {
            $prefix = $tingkatMap[$selectedTingkat];
            $query = GuruKelas::where('mapel_id', $mapel->id)
                ->with(['guru.user', 'kelas', 'tahunAjaran'])
                ->whereHas('kelas', function($q) use ($prefix) {
                    $q->where('nama_kelas', 'like', "{$prefix}%");
                });

            if ($tahunAktif) {
                $query->where('tahun_ajaran_id', $tahunAktif->id);
            }

            $assignments = $query->orderBy('hari')->paginate(5)->withQueryString();
        } else {
            $assignments = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 5);
        }

        return view('admin.mapel.show', compact('mapel', 'selectedTingkat', 'assignments', 'tahunAktif'));
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
            'tingkat' => 'nullable|string|max:10',
        ]);

        Mapel::create($request->only('nama_mapel', 'kode_mapel', 'tingkat'));

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
            'tingkat' => 'nullable|string|max:10',
        ]);

        $mapel->update($request->only('nama_mapel', 'kode_mapel', 'tingkat'));

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
