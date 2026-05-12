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
            <thead><tr><th>Tugas</th><th>Kelas</th><th>Jawaban</th><th>Hasil</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($tugas as $t)
                <tr>
                    <td><strong>{{ $t->judul }}</strong></td><td>{{ $t->kelas->nama_kelas }}</td>
                    <td><span class="badge badge-blue">{{ $t->jawaban_tugas_count }}</span></td>
                    <td>@if($t->similarity_results_count > 0)<span class="badge badge-green">{{ $t->similarity_results_count }}</span>@else<span class="badge badge-gray">Belum</span>@endif</td>
                    <td class="flex gap-2">
                        <form action="{{ route('guru.similarity.run', $t) }}" method="POST">@csrf<button class="btn btn-primary btn-sm"><i class="fas fa-play"></i> Cek</button></form>
                        <a href="{{ route('guru.similarity.detail', $t) }}" class="btn btn-outline btn-sm"><i class="fas fa-eye"></i></a>
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
