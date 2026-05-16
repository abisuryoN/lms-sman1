<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\GuruKelas;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Materi;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    public function index(Request $request)
    {
        $guru = auth()->user()->guru;
        $tahunAktif = TahunAjaran::aktif()->first();

        $materi = Materi::where('guru_id', $guru->id)
            ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->with(['kelas', 'mapel'])
            ->withCount('logs')
            ->latest()->paginate(5);

        return view('guru.materi.index', compact('materi'));
    }

    public function logs(Materi $materi)
    {
        // Pastikan guru ini adalah pemilik materi
        if ($materi->guru_id !== auth()->user()->guru->id) {
            abort(403);
        }

        $logs = \App\Models\MateriLog::where('materi_id', $materi->id)
            ->with('siswa.kelas')
            ->latest()
            ->paginate(5)->withQueryString();

        return view('guru.materi.logs', compact('materi', 'logs'));
    }

    public function create()
    {
        $guru = auth()->user()->guru;
        $tahunAktif = TahunAjaran::aktif()->first();
        $guruKelas = GuruKelas::where('guru_id', $guru->id)
            ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->with(['kelas', 'mapel'])->get();

        return view('guru.materi.create', compact('guruKelas', 'tahunAktif'));
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
            'tipe' => 'required|in:file,link',
            'file' => 'required_if:tipe,file|file|mimes:pdf,docx,pptx,ppt,doc,jpg,jpeg,png|max:20480',
            'link_url' => 'required_if:tipe,link|nullable|url',
        ]);

        $storagePath = null;
        $originalFilename = null;
        $mimeType = null;
        $fileSize = null;

        if ($request->tipe === 'file' && $request->hasFile('file')) {
            $file = $request->file('file');
            $originalFilename = $file->getClientOriginalName();
            $mimeType = $file->getMimeType();
            $fileSize = $file->getSize();
            $timestamp = time();
            $extension = $file->getClientOriginalExtension();
            $storagePath = "materi/{$guru->id}/{$timestamp}-" . \Illuminate\Support\Str::slug(pathinfo($originalFilename, PATHINFO_FILENAME)) . ".{$extension}";

            $supabase = new \App\Services\SupabaseStorageService(config('services.supabase.materi_bucket'));
            
            if (!$supabase->upload($file->getPathname(), $storagePath, $mimeType)) {
                return back()->with('error', 'Gagal mengunggah materi ke Supabase Storage.');
            }
        } elseif ($request->tipe === 'link') {
            $storagePath = $request->link_url;
        }

        Materi::create([
            'guru_id' => $guru->id,
            'kelas_id' => $request->kelas_id,
            'mapel_id' => $request->mapel_id,
            'tahun_ajaran_id' => $tahunAktif->id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'storage_path' => $storagePath,
            'original_filename' => $originalFilename,
            'mime_type' => $mimeType,
            'file_size' => $fileSize,
            'tipe' => $request->tipe,
        ]);

        return redirect()->route('guru.materi.index')->with('success', 'Materi berhasil diupload.');
    }

    public function destroy(Materi $materi)
    {
        // Pastikan guru ini adalah pemilik materi
        if ($materi->guru_id !== auth()->user()->guru->id) {
            abort(403);
        }

        if ($materi->tipe === 'file' && $materi->storage_path) {
            $supabase = new \App\Services\SupabaseStorageService(config('services.supabase.materi_bucket'));
            $supabase->delete($materi->storage_path);
        }
        
        $materi->delete();
        return back()->with('success', 'Materi berhasil dihapus.');
    }
}
