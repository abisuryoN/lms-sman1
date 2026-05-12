<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjaran = TahunAjaran::withCount('kelas')->orderByDesc('id')->paginate(10);
        return view('admin.tahun-ajaran.index', compact('tahunAjaran'));
    }

    public function create()
    {
        return view('admin.tahun-ajaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tahun' => 'required|string|max:20',
            'semester' => 'required|in:Ganjil,Genap',
        ]);

        TahunAjaran::create([
            'nama_tahun' => $request->nama_tahun,
            'semester' => $request->semester,
            'status' => 'nonaktif',
        ]);

        return redirect()->route('admin.tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function activate(TahunAjaran $tahunAjaran)
    {
        DB::transaction(function () use ($tahunAjaran) {
            TahunAjaran::where('status', 'aktif')->update(['status' => 'nonaktif']);
            $tahunAjaran->update(['status' => 'aktif']);
        });

        return redirect()->route('admin.tahun-ajaran.index')->with('success', "Tahun ajaran {$tahunAjaran->full_name} diaktifkan.");
    }

    public function akhiriTahunAjaran()
    {
        $tahunAktif = TahunAjaran::aktif()->first();
        if (!$tahunAktif) return back()->with('error', 'Tidak ada tahun ajaran aktif.');

        DB::transaction(function () use ($tahunAktif) {
            $tahunAktif->update(['status' => 'nonaktif']);
            $parts = explode('/', $tahunAktif->nama_tahun);
            $newNama = (intval($parts[0]) + 1) . '/' . (intval($parts[1]) + 1);
            $tahunBaru = TahunAjaran::create(['nama_tahun' => $newNama, 'semester' => 'Ganjil', 'status' => 'aktif']);

            $kelasLama = Kelas::where('tahun_ajaran_id', $tahunAktif->id)->get();
            $map = [];

            foreach ($kelasLama as $k) {
                $baru = $this->promoteClassName($k->nama_kelas);
                if ($baru) {
                    $new = Kelas::create(['nama_kelas' => $baru, 'tahun_ajaran_id' => $tahunBaru->id]);
                    $map[$k->id] = $new->id;
                }
            }

            foreach (Siswa::aktif()->whereNotNull('kelas_id')->get() as $s) {
                $kl = $kelasLama->firstWhere('id', $s->kelas_id);
                if (!$kl) continue;
                if (preg_match('/^XII\b/i', $kl->nama_kelas)) {
                    $s->update(['status' => 'alumni', 'kelas_id' => null]);
                } elseif (isset($map[$s->kelas_id])) {
                    $s->update(['kelas_id' => $map[$s->kelas_id]]);
                }
            }
        });

        return redirect()->route('admin.tahun-ajaran.index')->with('success', 'Tahun ajaran diakhiri. Kelas dinaikkan otomatis.');
    }

    private function promoteClassName(string $n): ?string
    {
        if (preg_match('/^XII\b/i', $n)) return null;
        if (preg_match('/^XI\b/i', $n)) return preg_replace('/^XI\b/i', 'XII', $n);
        if (preg_match('/^X\b/i', $n)) return preg_replace('/^X\b/i', 'XI', $n);
        return $n;
    }
}
