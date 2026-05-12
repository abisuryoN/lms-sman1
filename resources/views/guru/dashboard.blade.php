@extends('layouts.app')
@section('title', 'Dashboard Guru')
@section('page-title', 'Dashboard')

@section('content')
{{-- Mobile Dashboard View (MySIKA Style) --}}
<div class="m-dashboard-container">
    {{-- 1. Profile Section --}}
    <section class="m-profile-section">
        <div class="m-avatar-wrapper">
            <div class="m-avatar">
                @if(auth()->user()->photo_url)
                    <img src="{{ auth()->user()->photo_url }}" alt="Avatar">
                @else
                    <i class="fas fa-user"></i>
                @endif
            </div>
            <div class="m-privacy-overlay" id="privacyOverlay">
                <i class="fas fa-eye-slash"></i>
            </div>
        </div>
        <h2 class="m-greeting">Halo, {{ strtoupper(auth()->user()->name) }}!</h2>
        <p class="m-subtext">Selamat datang kembali di MyLMS</p>
    </section>

    {{-- 2. Quick Access Section --}}
    <div class="m-section-label">Manajemen Pembelajaran</div>
    <section class="m-grid-menu">
        <a href="{{ route('guru.materi.index') }}" class="m-grid-item">
            <div class="m-grid-icon bg-purple-soft"><i class="fas fa-file-alt"></i></div>
            <span class="m-grid-label">Materi</span>
        </a>
        <a href="{{ route('guru.tugas.index') }}" class="m-grid-item">
            <div class="m-grid-icon bg-green-soft"><i class="fas fa-clipboard-list"></i></div>
            <span class="m-grid-label">Tugas</span>
        </a>
        <a href="{{ route('guru.nilai.index') }}" class="m-grid-item">
            <div class="m-grid-icon bg-blue-soft"><i class="fas fa-star"></i></div>
            <span class="m-grid-label">Nilai</span>
        </a>
        <a href="{{ route('guru.similarity.index') }}" class="m-grid-item">
            <div class="m-grid-icon bg-indigo-soft"><i class="fas fa-search"></i></div>
            <span class="m-grid-label">Similarity</span>
        </a>
        <a href="{{ route('guru.profil.edit') }}" class="m-grid-item">
            <div class="m-grid-icon bg-orange-soft"><i class="fas fa-user-cog"></i></div>
            <span class="m-grid-label">Profil</span>
        </a>
        <a href="#" class="m-grid-item">
            <div class="m-grid-icon bg-pink-soft"><i class="fas fa-book"></i></div>
            <span class="m-grid-label">Mapel</span>
        </a>
        <a href="#" class="m-grid-item">
            <div class="m-grid-icon bg-purple-soft"><i class="fas fa-school"></i></div>
            <span class="m-grid-label">Kelas</span>
        </a>
        <a href="#" class="m-grid-item">
            <div class="m-grid-icon bg-green-soft"><i class="fas fa-chart-pie"></i></div>
            <span class="m-grid-label">Laporan</span>
        </a>
    </section>

    {{-- 3. Summary Section --}}
    <div class="m-section-label">Statistik Mengajar</div>
    <section class="m-summary-section">
        <div class="m-summary-cards">
            <div class="m-summary-card blue">
                <div class="m-card-label">Kelas Diampu</div>
                <div class="m-card-value">{{ $stats['total_kelas'] }}</div>
            </div>
            <div class="m-summary-card green">
                <div class="m-card-label">Pending Tugas</div>
                <div class="m-card-value">{{ $stats['pending_submissions'] }}</div>
            </div>
        </div>
    </section>

    {{-- 4. Desktop Fallback --}}
    <div class="hide-mobile" style="padding: 0 28px;">
        <div class="stats-grid">
            <div class="stat-card"><div class="stat-icon blue"><i class="fas fa-school"></i></div><div class="stat-info"><h4>Kelas Diampu</h4><div class="stat-value">{{ $stats['total_kelas'] }}</div></div></div>
            <div class="stat-card"><div class="stat-icon yellow"><i class="fas fa-clipboard-list"></i></div><div class="stat-info"><h4>Total Tugas</h4><div class="stat-value">{{ $stats['total_tugas'] }}</div></div></div>
            <div class="stat-card"><div class="stat-icon green"><i class="fas fa-file-alt"></i></div><div class="stat-info"><h4>Total Materi</h4><div class="stat-value">{{ $stats['total_materi'] }}</div></div></div>
        </div>

        <div class="card mt-4">
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
    </div>
</div>
@endsection
