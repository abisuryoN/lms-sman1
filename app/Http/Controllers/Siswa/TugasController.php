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
                ->with('mapel')->latest()->paginate(5);
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

    public function download(Tugas $tuga)
    {
        $siswa = auth()->user()->siswa;
        
        // Pastikan siswa ini ada di kelas yang sama dengan tugas
        if (!$siswa || $tuga->kelas_id !== $siswa->kelas_id) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

        if ($tuga->tipe === 'link') {
            return redirect($tuga->soal_storage_path ?? '#');
        }

        if (!$tuga->soal_storage_path) {
            return back()->with('error', 'Tugas tidak memiliki lampiran soal.');
        }

        $url = $tuga->soal_download_url;
        if ($url === '#') {
            return back()->with('error', 'Gagal menghasilkan link download soal.');
        }

        return redirect($url);
    }

    public function submit(Request $request, Tugas $tuga)
    {
        $siswa = auth()->user()->siswa;

        $request->validate([
            'jawaban_text' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:1024',
        ]);

        $jawabanLama = JawabanTugas::where('tugas_id', $tuga->id)->where('siswa_id', $siswa->id)->first();
        $oldStoragePath = $jawabanLama ? $jawabanLama->storage_path : null;

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
            $supabase = new SupabaseStorageService(config('services.supabase.bucket'));
            $tempPath = $file->getRealPath();
            $uploaded = $supabase->upload($tempPath, $storagePath, $mimeType);

            if (!$uploaded) {
                return back()->withErrors(['file' => 'Gagal mengupload file ke penyimpanan. Silakan coba lagi.'])->withInput();
            }

            // Jika upload baru berhasil, hapus file lama dari storage
            if ($oldStoragePath) {
                $supabase->delete($oldStoragePath);
            }

            $ocrStatus = 'pending';
        }

        $updateData = [
            'jawaban_text' => $request->jawaban_text,
            'storage_path' => $storagePath ?: ($jawabanLama->storage_path ?? null),
            'original_filename' => $originalFilename ?: ($jawabanLama->original_filename ?? null),
            'mime_type' => $mimeType ?: ($jawabanLama->mime_type ?? null),
            'file_size' => $fileSize ?: ($jawabanLama->file_size ?? null),
            'ocr_status' => $ocrStatus ?: ($jawabanLama->ocr_status ?? null),
            'submitted_at' => now(),
        ];

        // Jika upload file baru, kita harus update metadata
        if ($request->hasFile('file')) {
            $updateData['storage_path'] = $storagePath;
            $updateData['original_filename'] = $originalFilename;
            $updateData['mime_type'] = $mimeType;
            $updateData['file_size'] = $fileSize;
            $updateData['ocr_status'] = $ocrStatus;
        }

        $jawaban = JawabanTugas::updateOrCreate(
            ['tugas_id' => $tuga->id, 'siswa_id' => $siswa->id],
            $updateData
        );

        // Dispatch job OCR jika ada file baru yang diupload
        if ($request->hasFile('file') && $storagePath) {
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
}
