<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessSubmissionTextJob;
use App\Models\JawabanTugas;
use App\Models\TahunAjaran;
use App\Models\Tugas;
use App\Services\SupabaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TugasController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;
        $tahunAktif = TahunAjaran::aktif()->first();

        $tugas = collect();
        if ($siswa && $siswa->kelas_id) {
            $tugas = Tugas::where('kelas_id', $siswa->kelas_id)
                ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
                ->with('mapel')->latest()->paginate(10);
        }

        $submitted = $siswa ? JawabanTugas::where('siswa_id', $siswa->id)->pluck('tugas_id')->toArray() : [];

        return view('siswa.tugas.index', compact('tugas', 'submitted'));
    }

    public function show(Tugas $tuga)
    {
        $siswa = auth()->user()->siswa;
        $jawaban = JawabanTugas::where('tugas_id', $tuga->id)->where('siswa_id', $siswa->id)->first();
        return view('siswa.tugas.show', compact('tuga', 'jawaban'));
    }

    public function submit(Request $request, Tugas $tuga)
    {
        $siswa = auth()->user()->siswa;

        $request->validate([
            'jawaban_text' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        $filePath = null;
        $storagePath = null;
        $originalFilename = null;
        $mimeType = null;
        $fileSize = null;
        $ocrStatus = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalFilename = $file->getClientOriginalName();
            $mimeType = $file->getMimeType();
            $fileSize = $file->getSize();

            // Buat path terstruktur di Supabase Storage
            $kelas = $tuga->kelas;
            $extension = $file->getClientOriginalExtension();
            $safeName = Str::slug(pathinfo($originalFilename, PATHINFO_FILENAME));
            $storagePath = sprintf(
                'ta-%d/kelas-%d/tugas-%d/siswa-%d/%s_%s.%s',
                $tuga->tahun_ajaran_id,
                $tuga->kelas_id,
                $tuga->id,
                $siswa->id,
                $safeName,
                Str::random(8),
                $extension
            );

            // Upload ke Supabase Storage
            $supabase = new SupabaseStorageService();
            $tempPath = $file->getRealPath();
            $uploaded = $supabase->upload($tempPath, $storagePath, $mimeType);

            if (!$uploaded) {
                return back()->withErrors(['file' => 'Gagal mengupload file ke penyimpanan. Silakan coba lagi.'])->withInput();
            }

            $ocrStatus = 'pending';
        }

        $jawaban = JawabanTugas::updateOrCreate(
            ['tugas_id' => $tuga->id, 'siswa_id' => $siswa->id],
            array_filter([
                'jawaban_text' => $request->jawaban_text,
                'file_path' => $filePath, // null karena tidak disimpan lokal
                'storage_path' => $storagePath,
                'original_filename' => $originalFilename,
                'mime_type' => $mimeType,
                'file_size' => $fileSize,
                'ocr_status' => $ocrStatus,
                'submitted_at' => now(),
            ], fn($v) => $v !== null)
        );

        // Dispatch job OCR jika ada file yang diupload
        if ($storagePath) {
            ProcessSubmissionTextJob::dispatch($jawaban->id);
        }

        return redirect()->route('siswa.tugas.index')->with('success', 'Jawaban berhasil dikumpulkan.');
    }

    /**
     * Generate signed URL agar siswa bisa melihat file miliknya sendiri.
     */
    public function viewFile(Request $request, JawabanTugas $jawaban)
    {
        $siswa = auth()->user()->siswa;

        // Validasi: siswa hanya boleh lihat file miliknya
        if ($jawaban->siswa_id !== $siswa->id) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }

        if (!$jawaban->storage_path) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        $downloadFilename = $request->query('action') === 'download' ? ($jawaban->original_filename ?? basename($jawaban->storage_path)) : null;

        $supabase = new SupabaseStorageService();
        $signedUrl = $supabase->getSignedUrl($jawaban->storage_path, null, $downloadFilename);

        if (!$signedUrl) {
            return back()->with('error', 'Gagal membuat link akses file. Silakan coba lagi.');
        }

        return redirect($signedUrl);
    }

    public function download(Tugas $tuga)
    {
        if ($tuga->tipe === 'file' && $tuga->file_url) {
            if (Storage::disk('public')->exists($tuga->file_url)) {
                return Storage::disk('public')->download(
                    $tuga->file_url,
                    $tuga->original_filename ?? basename($tuga->file_url)
                );
            }
        }
        return back()->with('error', 'File lampiran tidak ditemukan.');
    }
}
