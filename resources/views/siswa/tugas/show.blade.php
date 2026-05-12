@extends('layouts.app')
@section('title', 'Detail Tugas')
@section('page-title', $tuga->judul)
@section('content')
<div class="card mb-4">
    <div class="card-header"><h3>{{ $tuga->judul }}</h3><span class="badge {{ $tuga->isExpired() ? 'badge-red' : 'badge-green' }}">{{ $tuga->isExpired() ? 'Berakhir' : 'Aktif' }}</span></div>
    <div class="card-body">
        <div class="grid-2">
            <div><strong>Mapel:</strong> {{ $tuga->mapel->nama_mapel }}</div>
            <div><strong>Kelas:</strong> {{ $tuga->kelas->nama_kelas }}</div>
            <div><strong>Deadline:</strong> {{ $tuga->deadline->format('d M Y H:i') }}</div>
            <div><strong>Guru:</strong> {{ $tuga->guru->nama }}</div>
        </div>
        @if($tuga->deskripsi)<div style="margin-top:16px;padding:12px;background:var(--primary-50);border-radius:8px">{{ $tuga->deskripsi }}</div>@endif
    </div>
</div>

<div class="card" style="max-width:700px">
    <div class="card-header"><h3>{{ $jawaban ? 'Jawaban Anda (Sudah Dikumpulkan)' : 'Kumpulkan Jawaban' }}</h3></div>
    <div class="card-body">
        @if($jawaban)
            <div style="padding:12px;background:#D1FAE5;border-radius:8px;margin-bottom:16px"><i class="fas fa-check-circle" style="color:var(--success)"></i> Dikumpulkan pada {{ $jawaban->submitted_at->format('d M Y H:i') }}</div>
            <div style="padding:12px;background:var(--bg);border-radius:8px">{{ $jawaban->jawaban_text }}</div>
        @endif

        <form action="{{ route('siswa.tugas.submit', $tuga) }}" method="POST" enctype="multipart/form-data" style="margin-top:16px">
            @csrf
            <div class="form-group"><label class="form-label">Jawaban Anda</label><textarea name="jawaban_text" class="form-control" rows="8" required placeholder="Tulis jawaban Anda di sini...">{{ $jawaban->jawaban_text ?? old('jawaban_text') }}</textarea></div>
            <div class="form-group"><label class="form-label">File Pendukung (opsional)</label><input type="file" name="file" class="form-control" accept=".pdf,.docx,.doc,.txt"><small style="color:var(--text-muted);font-size:11px">PDF, DOCX, TXT (maks 5MB)</small></div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> {{ $jawaban ? 'Update Jawaban' : 'Kumpulkan' }}</button>
        </form>
    </div>
</div>
@endsection
