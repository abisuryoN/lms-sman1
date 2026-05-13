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
            'hari' => 'nullable|string',
            'jam_mulai' => 'nullable',
            'jam_selesai' => 'nullable',
        ]);

        $data = $request->only('guru_id', 'kelas_id', 'mapel_id', 'tahun_ajaran_id', 'hari', 'jam_mulai', 'jam_selesai');
        
        // Normalisasi format jam (titik ke titik dua untuk DB)
        if (isset($data['jam_mulai'])) $data['jam_mulai'] = str_replace('.', ':', $data['jam_mulai']);
        if (isset($data['jam_selesai'])) $data['jam_selesai'] = str_replace('.', ':', $data['jam_selesai']);

        $exists = GuruKelas::where($request->only('guru_id', 'kelas_id', 'mapel_id', 'tahun_ajaran_id', 'hari'))
            ->where('jam_mulai', $data['jam_mulai'])
            ->exists();
            
        if ($exists) {
            return back()->with('error', 'Penugasan/Jadwal ini sudah ada.');
        }

        GuruKelas::create($data);
        return redirect()->route('admin.guru-kelas.index')->with('success', 'Jadwal guru berhasil disimpan.');
    }

    public function edit(GuruKelas $guruKela)
    {
        $tahunAktif = TahunAjaran::aktif()->first();
        $guruList = Guru::all();
        $kelasList = $tahunAktif ? Kelas::where('tahun_ajaran_id', $tahunAktif->id)->get() : collect();
        $mapelList = Mapel::all();
        $tahunList = TahunAjaran::orderByDesc('id')->get();

        return view('admin.guru-kelas.edit', compact('guruKela', 'guruList', 'kelasList', 'mapelList', 'tahunAktif', 'tahunList'));
    }

    public function update(Request $request, GuruKelas $guruKela)
    {
        $request->validate([
            'guru_id' => 'required|exists:guru,id',
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mapel,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'hari' => 'nullable|string',
            'jam_mulai' => 'nullable',
            'jam_selesai' => 'nullable',
        ]);
        $data = $request->only('guru_id', 'kelas_id', 'mapel_id', 'tahun_ajaran_id', 'hari', 'jam_mulai', 'jam_selesai');
        
        // Normalisasi format jam (titik ke titik dua untuk DB)
        if (isset($data['jam_mulai'])) $data['jam_mulai'] = str_replace('.', ':', $data['jam_mulai']);
        if (isset($data['jam_selesai'])) $data['jam_selesai'] = str_replace('.', ':', $data['jam_selesai']);

        $guruKela->update($data);
        return redirect()->route('admin.guru-kelas.index')->with('success', 'Jadwal guru berhasil diperbarui.');
    }

    public function destroy(GuruKelas $guruKela)
    {
        $guruKela->delete();
        return redirect()->route('admin.guru-kelas.index')->with('success', 'Penugasan berhasil dihapus.');
    }
}
