@extends('layouts.app')
@section('title', 'Dashboard Siswa')
@section('page-title', 'Dashboard')
@section('content')
<div class="stats-grid">
    <div class="stat-card"><div class="stat-icon blue"><i class="fas fa-clipboard-list"></i></div><div class="stat-info"><h4>Total Tugas</h4><div class="stat-value">{{ $stats['total_tugas'] }}</div></div></div>
    <div class="stat-card"><div class="stat-icon green"><i class="fas fa-check-circle"></i></div><div class="stat-info"><h4>Sudah Dikerjakan</h4><div class="stat-value">{{ $stats['tugas_selesai'] }}</div></div></div>
    <div class="stat-card"><div class="stat-icon yellow"><i class="fas fa-clock"></i></div><div class="stat-info"><h4>Belum Dikerjakan</h4><div class="stat-value">{{ $stats['tugas_belum'] }}</div></div></div>
    <div class="stat-card"><div class="stat-icon purple"><i class="fas fa-chart-line"></i></div><div class="stat-info"><h4>Rata-rata Nilai</h4><div class="stat-value">{{ number_format($stats['rata_nilai'], 1) }}</div></div></div>
</div>

<div class="card">
    <div class="card-header"><h3><i class="fas fa-bell" style="color:var(--warning)"></i> Tugas Mendatang</h3></div>
    <div class="card-body table-wrapper">
        <table>
            <thead><tr><th>Tugas</th><th>Mapel</th><th>Deadline</th><th>Sisa Waktu</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($tugasTerbaru as $t)
                <tr>
                    <td><strong>{{ $t->judul }}</strong></td><td>{{ $t->mapel->nama_mapel }}</td>
                    <td>{{ $t->deadline->format('d M Y H:i') }}</td>
                    <td>@if($t->isExpired())<span class="badge badge-red">Berakhir</span>@else<span class="badge badge-green">{{ $t->deadline->diffForHumans() }}</span>@endif</td>
                    <td><a href="{{ route('siswa.tugas.show', $t) }}" class="btn btn-primary btn-sm"><i class="fas fa-arrow-right"></i> Kerjakan</a></td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center" style="padding:24px;color:var(--text-muted)">Tidak ada tugas mendatang.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($siswa && $siswa->kelas)
<div class="card mt-4">
    <div class="card-header"><h3><i class="fas fa-info-circle"></i> Info Saya</h3></div>
    <div class="card-body">
        <div class="grid-2">
            <div><strong>Nama:</strong> {{ $siswa->nama }}</div>
            <div><strong>NIS:</strong> {{ $siswa->nis }}</div>
            <div><strong>Kelas:</strong> {{ $siswa->kelas->nama_kelas }}</div>
            <div><strong>Status:</strong> <span class="badge badge-green">{{ ucfirst($siswa->status) }}</span></div>
        </div>
    </div>
</div>
@endif
@endsection
