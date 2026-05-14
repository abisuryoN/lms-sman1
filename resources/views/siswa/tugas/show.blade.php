@extends('layouts.app')
@section('title', 'Detail Tugas')
@section('page-title', $tuga->judul)
@section('content')
<div class="mb-4">
    <a href="{{ route('siswa.tugas.index') }}" class="btn btn-outline" style="background:#fff;border:1px solid #E2E8F0"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card mb-4">
    <div class="card-header"><h3>{{ $tuga->judul }}</h3><span class="badge {{ $tuga->isExpired() ? 'badge-red' : 'badge-green' }}">{{ $tuga->isExpired() ? 'Berakhir' : 'Aktif' }}</span></div>
    <div class="card-body">
        <div class="grid-2">
            <div><strong>Mapel:</strong> {{ $tuga->mapel->nama_mapel }}</div>
            <div><strong>Kelas:</strong> {{ $tuga->kelas->nama_kelas }}</div>
            <div><strong>Deadline:</strong> {{ $tuga->deadline->format('d M Y H:i') }}</div>
            <div><strong>Guru:</strong> {{ $tuga->guru->nama }}</div>
        </div>
        @if($tuga->deskripsi)
            <div style="margin-top:16px;padding:16px;background:var(--primary-50);border-radius:12px;color:var(--text);line-height:1.6">
                <div style="font-weight:700;margin-bottom:8px;color:var(--primary);font-size:13px;text-transform:uppercase;letter-spacing:1px">Instruksi / Deskripsi:</div>
                {{ $tuga->deskripsi }}
            </div>
        @endif

        @if($tuga->file_url)
            <div style="margin-top:20px; padding:20px; background:#F8FAFC; border-radius:16px; border:1px solid #E2E8F0;">
                <div style="display:flex; align-items:center; gap:16px;">
                    <div style="width:48px; height:48px; background:{{ $tuga->tipe == 'file' ? '#EFF6FF' : '#F5F3FF' }}; color:{{ $tuga->tipe == 'file' ? '#3B82F6' : '#8B5CF6' }}; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:24px;">
                        <i class="fas {{ $tuga->tipe == 'file' ? 'fa-file-pdf' : 'fa-link' }}"></i>
                    </div>
                    <div style="flex:1">
                        <div style="font-weight:700; font-size:15px; color:#0F172A;">{{ $tuga->tipe == 'file' ? 'Lampiran Soal' : 'Link Eksternal' }}</div>
                        <div style="font-size:12px; color:#64748B;">{{ $tuga->tipe == 'file' ? 'Dokumen soal dari Guru' : 'Klik tombol untuk membuka link' }}</div>
                    </div>
                    <div style="display:flex; gap:10px;">
                        @if($tuga->tipe == 'file')
                            <a href="{{ Storage::url($tuga->file_url) }}" target="_blank" class="btn btn-outline btn-sm" style="background:#fff"><i class="fas fa-eye"></i> Lihat</a>
                            <a href="{{ route('siswa.tugas.download', $tuga) }}" class="btn btn-primary btn-sm"><i class="fas fa-download"></i> Unduh</a>
                        @else
                            <a href="{{ $tuga->file_url }}" target="_blank" class="btn btn-primary btn-sm"><i class="fas fa-external-link-alt"></i> Buka Link</a>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="card" style="max-width:700px">
    <div class="card-header"><h3>{{ $jawaban ? 'Jawaban Anda (Sudah Dikumpulkan)' : 'Kumpulkan Jawaban' }}</h3></div>
    <div class="card-body">
        @if($jawaban)
            <div style="padding:12px;background:#D1FAE5;border-radius:8px;margin-bottom:16px"><i class="fas fa-check-circle" style="color:var(--success)"></i> Dikumpulkan pada {{ $jawaban->submitted_at->format('d M Y H:i') }}</div>
            <div style="padding:16px;background:var(--bg);border-radius:12px;border:1px solid #E2E8F0;">
                <div style="font-weight:700; font-size:13px; color:var(--primary); text-transform:uppercase; margin-bottom:8px;">Deskripsi Jawaban:</div>
                <div style="color:var(--text); line-height:1.6;">{{ $jawaban->jawaban_text ?: '(Tidak ada jawaban teks)' }}</div>
                
                @if($jawaban->file_path)
                    <div style="margin-top:16px; padding-top:16px; border-top:1px dashed #E2E8F0;">
                        <div style="font-weight:700; font-size:13px; color:var(--primary); text-transform:uppercase; margin-bottom:8px;">File Terlampir:</div>
                        <a href="{{ Storage::url($jawaban->file_path) }}" target="_blank" class="btn btn-outline btn-sm" style="background:#fff">
                            <i class="fas fa-file-pdf"></i> Lihat File Jawaban
                        </a>
                    </div>
                @endif
            </div>
        @endif

        <form action="{{ route('siswa.tugas.submit', $tuga) }}" method="POST" enctype="multipart/form-data" style="margin-top:16px">
            @csrf
            <div class="form-group"><label class="form-label">Deskripsi Jawaban Anda</label><textarea name="jawaban_text" class="form-control" rows="8" placeholder="Tulis jawaban Anda di sini...">{{ $jawaban->jawaban_text ?? old('jawaban_text') }}</textarea></div>
            <div class="form-group"><label class="form-label">File Jawaban Mu</label><input type="file" name="file" class="form-control" accept=".pdf,.docx,.doc,.txt"><small style="color:var(--text-muted);font-size:11px">PDF, DOCX, TXT (maks 5MB)</small></div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> {{ $jawaban ? 'Update Jawaban' : 'Kumpulkan' }}</button>
        </form>
    </div>
</div>
@endsection
