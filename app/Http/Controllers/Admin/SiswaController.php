<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $tahunAjaranAktif = TahunAjaran::aktif()->first();
        $query = Siswa::with(['user', 'kelas']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'aktif');
        }

        $siswa = $query->latest()->paginate(15);
        $kelasList = $tahunAjaranAktif ? Kelas::where('tahun_ajaran_id', $tahunAjaranAktif->id)->get() : collect();

        return view('admin.siswa.index', compact('siswa', 'kelasList'));
    }

    public function create()
    {
        $tahunAjaranAktif = TahunAjaran::aktif()->first();
        $kelasList = $tahunAjaranAktif ? Kelas::where('tahun_ajaran_id', $tahunAjaranAktif->id)->get() : collect();
        return view('admin.siswa.create', compact('kelasList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|unique:siswa,nis',
            'email' => 'required|email|unique:users,email',
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->nis),
                'role' => 'siswa',
            ]);

            Siswa::create([
                'user_id' => $user->id,
                'kelas_id' => $request->kelas_id,
                'nis' => $request->nis,
                'nama' => $request->nama,
                'jenis_kelamin' => $request->jenis_kelamin,
                'status' => 'aktif',
            ]);
        });

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan. Password default: NIS');
    }

    public function edit(Siswa $siswa)
    {
        $siswa->load('user', 'kelas');
        $tahunAjaranAktif = TahunAjaran::aktif()->first();
        $kelasList = $tahunAjaranAktif ? Kelas::where('tahun_ajaran_id', $tahunAjaranAktif->id)->get() : collect();
        return view('admin.siswa.edit', compact('siswa', 'kelasList'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|unique:siswa,nis,' . $siswa->id,
            'email' => 'required|email|unique:users,email,' . $siswa->user_id,
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        DB::transaction(function () use ($request, $siswa) {
            $siswa->user->update([
                'name' => $request->nama,
                'email' => $request->email,
            ]);

            $siswa->update([
                'nis' => $request->nis,
                'nama' => $request->nama,
                'kelas_id' => $request->kelas_id,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);
        });

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->user->delete();
        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }

    public function resetPassword(Siswa $siswa)
    {
        $siswa->user->update([
            'password' => Hash::make($siswa->nis),
        ]);

        return redirect()->route('admin.siswa.index')
            ->with('success', "Password siswa {$siswa->nama} direset ke NIS.");
    }
}
