<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\TahunAjaranService;

class TahunAjaranController extends Controller
{
    protected $tahunAjaranService;

    public function __construct(TahunAjaranService $tahunAjaranService)
    {
        $this->tahunAjaranService = $tahunAjaranService;
    }

    public function index(Request $request)
    {
        $tahunAjaran = $this->tahunAjaranService->getPaginated($request);
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

    public function edit(TahunAjaran $tahunAjaran)
    {
        return view('admin.tahun-ajaran.edit', compact('tahunAjaran'));
    }

    public function update(Request $request, TahunAjaran $tahunAjaran)
    {
        $request->validate([
            'nama_tahun' => 'required|string|max:20',
            'semester' => 'required|in:Ganjil,Genap',
        ]);

        $tahunAjaran->update([
            'nama_tahun' => $request->nama_tahun,
            'semester' => $request->semester,
        ]);

        return redirect()->route('admin.tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function destroy(TahunAjaran $tahunAjaran)
    {
        if ($tahunAjaran->status === 'aktif') {
            return redirect()->route('admin.tahun-ajaran.index')->with('error', 'Tahun ajaran aktif tidak dapat dihapus.');
        }

        $tahunAjaran->delete();
        return redirect()->route('admin.tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil dihapus.');
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

    public function archive(TahunAjaran $tahunAjaran)
    {
        $tahunAjaran->update(['is_archived' => !$tahunAjaran->is_archived]);
        $msg = $tahunAjaran->is_archived ? 'diarsipkan' : 'dipulihkan dari arsip';
        return back()->with('success', "Tahun ajaran {$tahunAjaran->full_name} berhasil {$msg}.");
    }

    public function cleanupFiles(Request $request, TahunAjaran $tahunAjaran)
    {
        if ($tahunAjaran->status === 'aktif') {
            return back()->with('error', 'Tidak dapat menghapus file dari tahun ajaran yang masih aktif.');
        }

        $request->validate([
            'confirmation' => 'required|in:HAPUS FILE',
        ]);

        $includeSubmissions = $request->has('include_submissions');
        
        $results = [
            'materi' => ['count' => 0, 'deleted' => 0, 'missing' => 0],
            'tugas' => ['count' => 0, 'deleted' => 0, 'missing' => 0],
            'jawaban' => ['count' => 0, 'deleted' => 0, 'missing' => 0],
        ];

        $supabase = new \App\Services\SupabaseStorageService();

        // 1. Cleanup Materi
        $materiItems = \App\Models\Materi::where('tahun_ajaran_id', $tahunAjaran->id)
            ->where('tipe', 'file')
            ->whereNotNull('storage_path')
            ->get();
        
        $results['materi']['count'] = $materiItems->count();
        $materiPaths = $materiItems->pluck('storage_path')->toArray();
        if (!empty($materiPaths)) {
            $deleteRes = $supabase->deleteMultiple($materiPaths, config('services.supabase.materi_bucket'));
            $results['materi']['deleted'] = $deleteRes['count'];
            $results['materi']['missing'] = $results['materi']['count'] - $deleteRes['count'];
            
            \App\Models\Materi::whereIn('id', $materiItems->pluck('id'))
                ->update([
                    'storage_path' => null,
                    'original_filename' => null,
                    'mime_type' => null,
                    'file_size' => null
                ]);
        }

        // 2. Cleanup Tugas (Soal)
        $tugasItems = \App\Models\Tugas::where('tahun_ajaran_id', $tahunAjaran->id)
            ->where('tipe', 'file')
            ->whereNotNull('soal_storage_path')
            ->get();
        
        $results['tugas']['count'] = $tugasItems->count();
        $tugasPaths = $tugasItems->pluck('soal_storage_path')->toArray();
        if (!empty($tugasPaths)) {
            $deleteRes = $supabase->deleteMultiple($tugasPaths, config('services.supabase.soal_bucket'));
            $results['tugas']['deleted'] = $deleteRes['count'];
            $results['tugas']['missing'] = $results['tugas']['count'] - $deleteRes['count'];
            
            \App\Models\Tugas::whereIn('id', $tugasItems->pluck('id'))
                ->update([
                    'soal_storage_path' => null,
                    'soal_original_filename' => null,
                    'soal_mime_type' => null,
                    'soal_file_size' => null
                ]);
        }

        // 3. Cleanup Jawaban Siswa (Optional)
        if ($includeSubmissions) {
            $jawabanItems = \App\Models\JawabanTugas::whereHas('tugas', function($q) use ($tahunAjaran) {
                $q->where('tahun_ajaran_id', $tahunAjaran->id);
            })->whereNotNull('storage_path')->get();

            $results['jawaban']['count'] = $jawabanItems->count();
            $jawabanPaths = $jawabanItems->pluck('storage_path')->toArray();
            
            if (!empty($jawabanPaths)) {
                // Chunk deletion for large volume of submissions
                $chunkSize = 100;
                foreach (array_chunk($jawabanPaths, $chunkSize) as $chunk) {
                    $deleteRes = $supabase->deleteMultiple($chunk, config('services.supabase.bucket'));
                    $results['jawaban']['deleted'] += $deleteRes['count'];
                }
                $results['jawaban']['missing'] = $results['jawaban']['count'] - $results['jawaban']['deleted'];

                \App\Models\JawabanTugas::whereIn('id', $jawabanItems->pluck('id'))
                    ->update([
                        'storage_path' => null,
                        'original_filename' => null,
                        'mime_type' => null,
                        'file_size' => null,
                        'ocr_status' => null,
                        'extracted_text' => null,
                        'processed_text' => null
                    ]);
            }
        }

        $summary = "Cleanup selesai untuk {$tahunAjaran->full_name}.\\n";
        $summary .= "- Materi: {$results['materi']['deleted']} dihapus, {$results['materi']['missing']} dilewati.\\n";
        $summary .= "- Soal Tugas: {$results['tugas']['deleted']} dihapus, {$results['tugas']['missing']} dilewati.";
        if ($includeSubmissions) {
            $summary .= "\\n- Jawaban Siswa: {$results['jawaban']['deleted']} dihapus, {$results['jawaban']['missing']} dilewati.";
        }

        return back()->with('success', $summary);
    }

    private function promoteClassName(string $n): ?string
    {
        if (preg_match('/^XII\b/i', $n)) return null;
        if (preg_match('/^XI\b/i', $n)) return preg_replace('/^XI\b/i', 'XII', $n);
        if (preg_match('/^X\b/i', $n)) return preg_replace('/^X\b/i', 'XI', $n);
        return $n;
    }
}
