@extends('layouts.app')
@section('title', 'Deteksi Similarity')
@section('page-title', 'Deteksi Similarity')
@section('content')
<div class="stats-grid">
    <div class="stat-card"><div class="stat-icon red"><i class="fas fa-exclamation-triangle"></i></div><div class="stat-info"><h4>Terindikasi Plagiat</h4><div class="stat-value">{{ $plagiatCount }}</div></div></div>
</div>
<div class="card">
    <div class="card-header"><h3>Analisis per Tugas</h3></div>
    <div class="card-body table-wrapper">
        <table>
            <thead><tr><th>Tugas</th><th>Kelas</th><th>Jawaban</th><th>Status Terakhir</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($tugas as $t)
                <tr>
                    <td><strong>{{ $t->judul }}</strong></td>
                    <td>{{ $t->kelas->nama_kelas }}</td>
                    <td><span class="badge badge-blue">{{ $t->jawaban_tugas_count }}</span></td>
                    <td>
                        <span class="badge badge-{{ $t->similarity_badge_color }}">
                            <i class="fas {{ $t->similarity_status == 'completed' ? 'fa-check' : ($t->similarity_status == 'processing' ? 'fa-spinner fa-spin' : 'fa-clock') }}"></i>
                            {{ $t->similarity_status_label }}
                        </span>
                        @if($t->similarity_results_count > 0)
                            <div style="font-size:11px; color:var(--text-muted); margin-top:4px;">{{ $t->similarity_results_count }} pasangan diuji</div>
                        @endif
                    </td>
                    <td class="flex gap-2">
                        <form action="{{ route('guru.similarity.run', $t) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm" {{ $t->similarity_status == 'processing' ? 'disabled' : '' }} title="Jalankan Uji Kemiripan">
                                <i class="fas {{ $t->similarity_status == 'processing' ? 'fa-spinner fa-spin' : 'fa-play' }}"></i> Cek
                            </button>
                        </form>
                        <a href="{{ route('guru.similarity.detail', $t) }}" class="btn btn-outline btn-sm" style="background:#fff;" title="Lihat Hasil Detail">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center" style="padding:24px;color:var(--text-muted)">Belum ada tugas.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
