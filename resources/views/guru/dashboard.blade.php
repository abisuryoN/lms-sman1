@extends('layouts.app')
@section('title', 'Dashboard Guru')
@section('page-title', 'Dashboard')

@section('content')
{{-- Premium Mobile Dashboard (MySIKA Aesthetic) --}}
<div class="mobile-only-ui">
    <div class="welcome-card-mobile">
        <div class="privacy-icon-box" style="overflow: hidden; background: #F1F5F9;">
            @if(auth()->user()->photo_url)
                <img src="{{ auth()->user()->photo_url }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
            @else
                <i class="fas fa-user" style="font-size: 32px; color: #94A3B8;"></i>
            @endif
        </div>
        <div class="welcome-text-mobile">
            <h2 style="font-size: 24px; font-weight: 800; color: #0F172A; letter-spacing: -0.5px;">Halo, {{ strtoupper(auth()->user()->name) }}!</h2>
            <p style="font-size: 14px; color: #64748B; margin-top: 8px;">NIP: {{ auth()->user()->identifier }} | LMS SMAN 1</p>
        </div>
    </div>

    <div class="alert alert-warning" style="margin: 0 20px 20px 20px; font-size: 12px; border-left: 4px solid var(--warning); background: #FFFBEB;">
        <i class="fas fa-shield-alt"></i>
        <div>
            Password default Anda adalah **NIP** Anda. Silakan <a href="{{ route('guru.profil.edit') }}#password-section" style="color:var(--warning);font-weight:700">ganti password</a>.
        </div>
    </div>

    <div class="quick-access-section">
        <div class="section-label">Manajemen Pembelajaran</div>
        <div class="quick-grid">
            <a href="{{ route('guru.materi.index') }}" class="quick-item">
                <div class="quick-icon-box bg-purple-soft"><i class="fas fa-file-alt"></i></div>
                <div class="quick-label">Materi</div>
            </a>
            <a href="{{ route('guru.tugas.index') }}" class="quick-item">
                <div class="quick-icon-box bg-green-soft"><i class="fas fa-clipboard-list"></i></div>
                <div class="quick-label">Tugas</div>
            </a>
            <a href="{{ route('guru.nilai.index') }}" class="quick-item">
                <div class="quick-icon-box bg-blue-soft"><i class="fas fa-star"></i></div>
                <div class="quick-label">Nilai</div>
            </a>
            <a href="{{ route('guru.similarity.index') }}" class="quick-item">
                <div class="quick-icon-box bg-indigo-soft"><i class="fas fa-search-plus"></i></div>
                <div class="quick-label">Similarity</div>
            </a>
            <a href="{{ route('guru.profil.edit') }}" class="quick-item">
                <div class="quick-icon-box bg-orange-soft"><i class="fas fa-user-cog"></i></div>
                <div class="quick-label">Profil</div>
            </a>
            <a href="#" class="quick-item">
                <div class="quick-icon-box bg-pink-soft"><i class="fas fa-book"></i></div>
                <div class="quick-label">Mapel</div>
            </a>
            <a href="#" class="quick-item">
                <div class="quick-icon-box bg-blue-soft"><i class="fas fa-school"></i></div>
                <div class="quick-label">Kelas</div>
            </a>
            <a href="#" class="quick-item">
                <div class="quick-icon-box bg-green-soft"><i class="fas fa-chart-pie"></i></div>
                <div class="quick-label">Laporan</div>
            </a>
        </div>
    </div>
</div>

<div class="desktop-only-ui">
    <div class="welcome-header">
        <div class="welcome-avatar">
            @if(auth()->user()->photo_url)
                <img src="{{ auth()->user()->photo_url }}" alt="Avatar">
            @else
                <i class="fas fa-user-circle" style="font-size: 80px; color: #CBD5E1;"></i>
            @endif
        </div>
        <div class="welcome-text">
            <h2 style="font-size: 26px; font-weight: 700; color: #0F172A;">Halo, {{ strtoupper(auth()->user()->name) }}!</h2>
            <div class="alert alert-warning" style="margin-top: 12px; border-left: 4px solid var(--warning); background: #FFFBEB;">
                <i class="fas fa-shield-alt"></i>
                <div>
                    <strong>Peringatan Keamanan:</strong> Password default Anda adalah **NIP** Anda. 
                    Harap segera <a href="{{ route('guru.profil.edit') }}#password-section" style="color:var(--warning);font-weight:700">ganti password</a> demi keamanan akun Anda.
                </div>
            </div>
        </div>
    </div>

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
@endsection
