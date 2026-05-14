@extends('layouts.app')
@section('title', 'Detail Tugas')
@section('page-title', 'Detail Tugas')
@section('content')
<div class="mb-4">
    <a href="{{ route('guru.tugas.index') }}" class="btn btn-outline" style="background:#fff;border:1px solid #E2E8F0"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card mb-4">
    <div class="card-header"><h3>{{ $tuga->judul }}</h3>
        <div class="flex gap-2">
            <form action="{{ route('guru.similarity.run', $tuga) }}" method="POST"><@csrf><button class="btn btn-primary btn-sm"><i class="fas fa-search-plus"></i> Cek Similarity</button></form>
            <a href="{{ route('guru.nilai.edit', $tuga) }}" class="btn btn-warning btn-sm"><i class="fas fa-star"></i> Nilai</a>
        </div>
    </div>
    <div class="card-body">
        <div class="grid-2">
            <div><strong>Kelas:</strong> {{ $tuga->kelas->nama_kelas }}</div>
            <div><strong>Mapel:</strong> {{ $tuga->mapel->nama_mapel }}</div>
            <div><strong>Deadline:</strong> {{ $tuga->deadline->format('d M Y H:i') }}</div>
            <div><strong>Status:</strong> <span class="badge {{ $tuga->isExpired() ? 'badge-red' : 'badge-green' }}">{{ $tuga->isExpired() ? 'Berakhir' : 'Aktif' }}</span></div>
        </div>
        @if($tuga->deskripsi)<div style="margin-top:16px;padding:12px;background:var(--primary-50);border-radius:8px">{{ $tuga->deskripsi }}</div>@endif
    </div>
</div>

<div class="card">
    <div class="card-header"><h3>Jawaban Siswa ({{ $jawaban->count() }})</h3></div>
    <div class="card-body table-wrapper">
        <table>
            <thead><tr><th>Siswa</th><th>Waktu Submit</th><th>Deskripsi</th><th>File Jawaban</th></tr></thead>
            <tbody>
            @forelse($jawaban as $j)
                <tr>
                    <td>{{ $j->siswa->nama }}</td>
                    <td>{{ $j->submitted_at ? $j->submitted_at->format('d M Y H:i') : '-' }}</td>
                    <td style="max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ Str::limit($j->jawaban_text, 80) }}</td>
                    <td>@if($j->file_path)<a href="{{ asset('storage/'.$j->file_path) }}" target="_blank" class="btn btn-outline btn-sm"><i class="fas fa-download"></i></a>@else — @endif</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center" style="padding:24px;color:var(--text-muted)">Belum ada jawaban.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
