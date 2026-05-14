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
            ->latest()->paginate(10);

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
            ->get();

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
            'file' => 'required_if:tipe,file|file|mimes:pdf,docx,pptx,ppt,doc|max:10240',
            'link_url' => 'required_if:tipe,link|nullable|url',
        ]);

        $fileUrl = null;
        $originalFilename = null;
        if ($request->tipe === 'file' && $request->hasFile('file')) {
            $fileUrl = $request->file('file')->store('uploads/materi', 'public');
            $originalFilename = $request->file('file')->getClientOriginalName();
        } elseif ($request->tipe === 'link') {
            $fileUrl = $request->link_url;
        }

        Materi::create([
            'guru_id' => $guru->id,
            'kelas_id' => $request->kelas_id,
            'mapel_id' => $request->mapel_id,
            'tahun_ajaran_id' => $tahunAktif->id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file_url' => $fileUrl,
            'original_filename' => $originalFilename,
            'tipe' => $request->tipe,
        ]);

        return redirect()->route('guru.materi.index')->with('success', 'Materi berhasil diupload.');
    }

    public function destroy(Materi $materi)
    {
        if ($materi->tipe === 'file' && $materi->file_url) {
            Storage::disk('public')->delete($materi->file_url);
        }
        $materi->delete();
        return back()->with('success', 'Materi berhasil dihapus.');
    }
}
