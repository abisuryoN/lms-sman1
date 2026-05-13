<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\GuruKelas;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use App\Services\GuruKelasService;

class GuruKelasController extends Controller
{
    protected $guruKelasService;

    public function __construct(GuruKelasService $guruKelasService)
    {
        $this->guruKelasService = $guruKelasService;
    }

    public function index(Request $request)
    {
        $tahunAktif = TahunAjaran::aktif()->first();
        $guruKelas = $this->guruKelasService->getPaginated($request);
        $guruList = Guru::all();
        $kelasList = $tahunAktif ? Kelas::where('tahun_ajaran_id', $tahunAktif->id)->get() : collect();
        $mapelList = Mapel::all();
        $tahunList = TahunAjaran::orderByDesc('id')->get();

        return view('admin.guru-kelas.index', compact('guruKelas', 'guruList', 'kelasList', 'mapelList', 'tahunAktif', 'tahunList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guru_id' => 'required|exists:guru,id',
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mapel,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
        ]);

        $exists = GuruKelas::where($request->only('guru_id', 'kelas_id', 'mapel_id', 'tahun_ajaran_id'))->exists();
        if ($exists) {
            return back()->with('error', 'Penugasan ini sudah ada.');
        }

        GuruKelas::create($request->only('guru_id', 'kelas_id', 'mapel_id', 'tahun_ajaran_id'));
        return redirect()->route('admin.guru-kelas.index')->with('success', 'Guru berhasil di-assign ke kelas.');
    }

    public function destroy(GuruKelas $guruKela)
    {
        $guruKela->delete();
        return redirect()->route('admin.guru-kelas.index')->with('success', 'Penugasan berhasil dihapus.');
    }
}
