<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\JawabanTugas;
use App\Models\TahunAjaran;
use App\Models\Tugas;
use App\Services\SupabaseStorageService;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $guru = auth()->user()->guru;
        $tahunAktif = TahunAjaran::aktif()->first();

        $tugas = Tugas::where('guru_id', $guru->id)
            ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->with(['kelas', 'mapel'])
            ->latest()->paginate(5)->withQueryString();

        return view('guru.nilai.index', compact('tugas'));
    }

    public function edit(Tugas $tuga)
    {
        $jawaban = JawabanTugas::where('tugas_id', $tuga->id)->with('siswa')->paginate(5)->withQueryString();
        $nilai = Nilai::where('tugas_id', $tuga->id)->pluck('nilai', 'siswa_id');

        return view('guru.nilai.edit', compact('tuga', 'jawaban', 'nilai'));
    }

    public function update(Request $request, Tugas $tuga)
    {
        $request->validate([
            'nilai' => 'required|array',
            'nilai.*' => 'nullable|numeric|min:0|max:100',
            'komentar' => 'nullable|array',
        ]);

        $tahunAktif = TahunAjaran::aktif()->first();

        foreach ($request->nilai as $siswaId => $nilaiValue) {
            if ($nilaiValue !== null) {
                Nilai::updateOrCreate(
                    ['tugas_id' => $tuga->id, 'siswa_id' => $siswaId],
                    ['nilai' => $nilaiValue, 'komentar' => $request->komentar[$siswaId] ?? null, 'tahun_ajaran_id' => $tahunAktif->id]
                );
            }
        }

        return back()->with('success', 'Nilai berhasil disimpan.');
    }

    /**
     * Download file jawaban siswa via Supabase signed URL.
     * Fallback ke local storage jika storage_path kosong.
     */
    public function downloadJawaban(JawabanTugas $jawaban)
    {
        $guru = auth()->user()->guru;
        $tugas = $jawaban->tugas;

        // Validasi: guru mengajar tugas ini
        if ($tugas->guru_id !== $guru->id) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }

        // Coba Supabase Storage dulu
        if ($jawaban->storage_path) {
            $filename = $jawaban->original_filename ?? basename($jawaban->storage_path);
            $supabase = new SupabaseStorageService();
            $signedUrl = $supabase->getSignedUrl($jawaban->storage_path, null, $filename);

            if ($signedUrl) {
                return redirect($signedUrl);
            }

            return back()->with('error', 'Gagal membuat link download file. Silakan coba lagi.');
        }

        // Fallback: local storage (untuk file lama sebelum migrasi ke Supabase)
        if ($jawaban->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($jawaban->file_path)) {
            $siswa = $jawaban->siswa;
            $kelas = $tugas->kelas;

            $filename = sprintf(
                "[%s]_[%s]_%s_%s",
                str_replace([' ', '/', '\\'], '_', $kelas->nama_kelas),
                str_replace([' ', '/', '\\'], '_', $tugas->judul),
                str_replace([' ', '/', '\\'], '_', $siswa->nama),
                $jawaban->original_filename ?? basename($jawaban->file_path)
            );

            return \Illuminate\Support\Facades\Storage::disk('public')->download($jawaban->file_path, $filename);
        }

        return back()->with('error', 'File jawaban tidak ditemukan.');
    }
}
