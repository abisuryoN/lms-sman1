<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Jobs\CheckAssignmentSimilarityJob;
use App\Models\GuruKelas;
use App\Models\JawabanTugas;
use App\Models\TahunAjaran;
use App\Models\Tugas;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;
        $tahunAktif = TahunAjaran::aktif()->first();

        $tugas = Tugas::where('guru_id', $guru->id)
            ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->withCount('jawabanTugas')
            ->with(['kelas', 'mapel'])
            ->latest()->paginate(5);

        return view('guru.tugas.index', compact('tugas'));
    }

    public function create()
    {
        $guru = auth()->user()->guru;
        $tahunAktif = TahunAjaran::aktif()->first();
        $guruKelas = GuruKelas::where('guru_id', $guru->id)
            ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->with(['kelas', 'mapel'])->get();

        return view('guru.tugas.create', compact('guruKelas', 'tahunAktif'));
    }

    public function store(Request $request)
    {
        $guru = auth()->user()->guru;
        $tahunAktif = TahunAjaran::aktif()->first();

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mapel,id',
            'deadline' => 'required|date|after:now',
            'tipe' => 'nullable|in:file,link',
            'file' => 'required_if:tipe,file|file|mimes:pdf,docx,pptx,ppt,doc,jpg,jpeg,png|max:1024',
            'link_url' => 'required_if:tipe,link|nullable|url',
        ]);

        $soalStoragePath = null;
        $soalOriginalFilename = null;
        $soalMimeType = null;
        $soalFileSize = null;

        if ($request->tipe === 'file' && $request->hasFile('file')) {
            $file = $request->file('file');
            $soalOriginalFilename = $file->getClientOriginalName();
            $soalMimeType = $file->getMimeType();
            $soalFileSize = $file->getSize();
            $timestamp = time();
            $extension = $file->getClientOriginalExtension();
            $soalStoragePath = "soal/{$guru->id}/{$timestamp}-" . \Illuminate\Support\Str::slug(pathinfo($soalOriginalFilename, PATHINFO_FILENAME)) . ".{$extension}";

            $supabase = new \App\Services\SupabaseStorageService(config('services.supabase.soal_bucket'));
            
            if (!$supabase->upload($file->getPathname(), $soalStoragePath, $soalMimeType)) {
                return back()->with('error', 'Gagal mengunggah file soal ke Supabase Storage.');
            }
        } elseif ($request->tipe === 'link') {
            $soalStoragePath = $request->link_url;
        }

        Tugas::create([
            'guru_id' => $guru->id,
            'kelas_id' => $request->kelas_id,
            'mapel_id' => $request->mapel_id,
            'tahun_ajaran_id' => $tahunAktif->id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'soal_storage_path' => $soalStoragePath,
            'soal_original_filename' => $soalOriginalFilename,
            'soal_mime_type' => $soalMimeType,
            'soal_file_size' => $soalFileSize,
            'tipe' => $request->tipe,
            'deadline' => $request->deadline,
        ]);

        return redirect()->route('guru.tugas.index')->with('success', 'Tugas berhasil dibuat.');
    }

    public function show(Tugas $tuga)
    {
        $tuga->load(['kelas', 'mapel', 'jawabanTugas.siswa', 'similarityResults']);
        $jawaban = JawabanTugas::where('tugas_id', $tuga->id)->with('siswa')->paginate(5)->withQueryString();
        return view('guru.tugas.show', compact('tuga', 'jawaban'));
    }

    public function edit(Tugas $tuga)
    {
        if ($tuga->guru_id !== auth()->user()->guru->id) {
            abort(403);
        }

        $guru = auth()->user()->guru;
        $tahunAktif = TahunAjaran::aktif()->first();
        $guruKelas = GuruKelas::where('guru_id', $guru->id)
            ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->with(['kelas', 'mapel'])->get();

        return view('guru.tugas.edit', compact('tuga', 'guruKelas', 'tahunAktif'));
    }

    public function update(Request $request, Tugas $tuga)
    {
        if ($tuga->guru_id !== auth()->user()->guru->id) {
            abort(403);
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mapel,id',
            'deadline' => 'required|date',
            'tipe' => 'nullable|in:file,link',
            'file' => 'nullable|file|mimes:pdf,docx,pptx,ppt,doc,jpg,jpeg,png|max:1024',
            'link_url' => 'required_if:tipe,link|nullable|url',
        ]);

        $data = [
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'kelas_id' => $request->kelas_id,
            'mapel_id' => $request->mapel_id,
            'deadline' => $request->deadline,
            'tipe' => $request->tipe,
        ];

        $oldPath = null;
        if ($tuga->soal_storage_path && $tuga->tipe === 'file') {
            $oldPath = $tuga->soal_storage_path;
        }

        if ($request->tipe === 'file' && $request->hasFile('file')) {
            $file = $request->file('file');
            $data['soal_original_filename'] = $file->getClientOriginalName();
            $data['soal_mime_type'] = $file->getMimeType();
            $data['soal_file_size'] = $file->getSize();
            
            $timestamp = time();
            $extension = $file->getClientOriginalExtension();
            $data['soal_storage_path'] = "soal/{$tuga->guru_id}/{$timestamp}-" . \Illuminate\Support\Str::slug(pathinfo($data['soal_original_filename'], PATHINFO_FILENAME)) . ".{$extension}";

            $supabase = new \App\Services\SupabaseStorageService(config('services.supabase.soal_bucket'));
            if (!$supabase->upload($file->getPathname(), $data['soal_storage_path'], $data['soal_mime_type'])) {
                return back()->with('error', 'Gagal mengunggah file soal baru.');
            }

            // Hapus file lama jika upload baru sukses
            if ($oldPath) {
                $supabase->delete($oldPath);
            }
        } elseif ($request->tipe === 'link') {
            // Hapus file lama jika sebelumnya tipe file
            if ($oldPath) {
                $supabase = new \App\Services\SupabaseStorageService(config('services.supabase.soal_bucket'));
                $supabase->delete($oldPath);
            }
            $data['soal_storage_path'] = $request->link_url;
            $data['soal_original_filename'] = null;
            $data['soal_mime_type'] = null;
            $data['soal_file_size'] = null;
        } elseif (!$request->tipe) {
            // Hapus lampiran jika diubah jadi tanpa lampiran
            if ($oldPath) {
                $supabase = new \App\Services\SupabaseStorageService(config('services.supabase.soal_bucket'));
                $supabase->delete($oldPath);
            }
            $data['soal_storage_path'] = null;
            $data['soal_original_filename'] = null;
            $data['soal_mime_type'] = null;
            $data['soal_file_size'] = null;
        }

        $tuga->update($data);

        return redirect()->route('guru.tugas.index')->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy(Tugas $tuga)
    {
        if ($tuga->guru_id !== auth()->user()->guru->id) {
            abort(403);
        }

        if ($tuga->tipe === 'file' && $tuga->soal_storage_path) {
            $supabase = new \App\Services\SupabaseStorageService(config('services.supabase.soal_bucket'));
            $supabase->delete($tuga->soal_storage_path);
        }

        $tuga->delete();
        return redirect()->route('guru.tugas.index')->with('success', 'Tugas berhasil dihapus.');
    }

    public function checkSimilarity(Tugas $tuga)
    {
        // Dispatch job background agar tidak ngelag
        CheckAssignmentSimilarityJob::dispatch($tuga->id);

        $tuga->update(['similarity_status' => 'processing']);

        return redirect()->route('guru.similarity.detail', $tuga->id)
            ->with('success', 'Pengecekan kemiripan sedang diproses di background. Halaman akan diperbarui otomatis.');
    }
}
