<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $tahunAjaranAktif = TahunAjaran::aktif()->first();

        $query = Kelas::with(['tahunAjaran', 'waliKelas', 'siswa']);

        if ($tahunAjaranAktif && !$request->filled('tahun_ajaran_id')) {
            $query->where('tahun_ajaran_id', $tahunAjaranAktif->id);
        } elseif ($request->filled('tahun_ajaran_id')) {
            $query->where('tahun_ajaran_id', $request->tahun_ajaran_id);
        }

        $kelas = $query->withCount('siswa')->orderBy('nama_kelas')->paginate(15);
        $tahunAjaranList = TahunAjaran::orderByDesc('id')->get();

        // Guru list for wali kelas dropdown
        $guruList = User::where('role', 'guru')->get();

        return view('admin.kelas.index', compact('kelas', 'tahunAjaranList', 'tahunAjaranAktif', 'guruList'));
    }

    public function create()
    {
        $tahunAjaranAktif = TahunAjaran::aktif()->first();
        $guruList = User::where('role', 'guru')->get();
        return view('admin.kelas.create', compact('tahunAjaranAktif', 'guruList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'wali_kelas_id' => 'nullable|exists:users,id',
        ]);

        Kelas::create($request->only('nama_kelas', 'tahun_ajaran_id', 'wali_kelas_id'));

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kela)
    {
        $kela->load('tahunAjaran', 'waliKelas');
        $tahunAjaranList = TahunAjaran::orderByDesc('id')->get();
        $guruList = User::where('role', 'guru')->get();
        return view('admin.kelas.edit', compact('kela', 'tahunAjaranList', 'guruList'));
    }

    public function update(Request $request, Kelas $kela)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'wali_kelas_id' => 'nullable|exists:users,id',
        ]);

        $kela->update($request->only('nama_kelas', 'tahun_ajaran_id', 'wali_kelas_id'));

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kela)
    {
        $kela->delete();
        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }
}
