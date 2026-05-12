@extends('layouts.app')
@section('title', 'Dashboard Guru')
@section('page-title', 'Dashboard')
@section('content')
<div class="stats-grid">
    <div class="stat-card"><div class="stat-icon blue"><i class="fas fa-school"></i></div><div class="stat-info"><h4>Kelas Diampu</h4><div class="stat-value">{{ $stats['total_kelas'] }}</div></div></div>
    <div class="stat-card"><div class="stat-icon yellow"><i class="fas fa-clipboard-list"></i></div><div class="stat-info"><h4>Total Tugas</h4><div class="stat-value">{{ $stats['total_tugas'] }}</div></div></div>
    <div class="stat-card"><div class="stat-icon green"><i class="fas fa-file-alt"></i></div><div class="stat-info"><h4>Total Materi</h4><div class="stat-value">{{ $stats['total_materi'] }}</div></div></div>
    <div class="stat-card"><div class="stat-icon purple"><i class="fas fa-inbox"></i></div><div class="stat-info"><h4>Tugas Dikumpulkan</h4><div class="stat-value">{{ $stats['pending_submissions'] }}</div></div></div>
</div>

<div class="card">
    <div class="card-header"><h3><i class="fas fa-clock"></i> Tugas Terbaru</h3><a href="{{ route('guru.tugas.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Buat Tugas</a></div>
    <div class="card-body table-wrapper">
        <table>
            <thead><tr><th>Judul</th><th>Kelas</th><th>Mapel</th><th>Deadline</th><th>Status</th></tr></thead>
            <tbody>
            @forelse($recentTugas as $t)
                <tr>
                    <td><a href="{{ route('guru.tugas.show', $t) }}" style="color:var(--primary);text-decoration:none;font-weight:500">{{ $t->judul }}</a></td>
                    <td>{{ $t->kelas->nama_kelas ?? '-' }}</td><td>{{ $t->mapel->nama_mapel ?? '-' }}</td>
                    <td>{{ $t->deadline->format('d M Y H:i') }}</td>
                    <td><span class="badge {{ $t->isExpired() ? 'badge-red' : 'badge-green' }}">{{ $t->isExpired() ? 'Berakhir' : 'Aktif' }}</span></td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center" style="padding:24px;color:var(--text-muted)">Belum ada tugas.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
