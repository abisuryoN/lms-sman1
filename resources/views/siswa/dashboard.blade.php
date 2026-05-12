@extends('layouts.app')
@section('title', 'Dashboard Siswa')
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
            <h2 style="font-size: 24px; font-weight: 800; color: #0F172A; letter-spacing: -0.5px;">Halo, {{ strtoupper($siswa->nama ?? auth()->user()->name) }}!</h2>
            <p style="font-size: 14px; color: #64748B; margin-top: 8px;">NIS: {{ auth()->user()->identifier }} | LMS SMAN 1</p>
        </div>
    </div>

    <div class="quick-access-section">
        <div class="section-label">Akses Cepat</div>
        <div class="quick-grid">
            <a href="#" class="quick-item">
                <div class="quick-icon-box bg-blue-soft"><i class="fas fa-calendar-alt"></i></div>
                <div class="quick-label">Jadwal</div>
            </a>
            <a href="{{ route('siswa.materi.index') }}" class="quick-item">
                <div class="quick-icon-box bg-purple-soft"><i class="fas fa-book"></i></div>
                <div class="quick-label">Materi</div>
            </a>
            <a href="{{ route('siswa.nilai.index') }}" class="quick-item">
                <div class="quick-icon-box bg-green-soft"><i class="fas fa-file-invoice"></i></div>
                <div class="quick-label">Nilai</div>
            </a>
            <a href="#" class="quick-item">
                <div class="quick-icon-box bg-orange-soft"><i class="fas fa-wallet"></i></div>
                <div class="quick-label">SPP</div>
            </a>
            <a href="#" class="quick-item">
                <div class="quick-icon-box bg-pink-soft"><i class="fas fa-id-card"></i></div>
                <div class="quick-label">Kartu Ujian</div>
            </a>
            <a href="#" class="quick-item">
                <div class="quick-icon-box bg-purple-soft"><i class="fas fa-comment-dots"></i></div>
                <div class="quick-label">Forum</div>
            </a>
            <a href="#" class="quick-item">
                <div class="quick-icon-box bg-indigo-soft"><i class="fas fa-sparkles"></i></div>
                <div class="quick-label">Rekap</div>
            </a>
            <a href="#" class="quick-item">
                <div class="quick-icon-box bg-pink-soft"><i class="fas fa-edit"></i></div>
                <div class="quick-label">Kuesioner</div>
            </a>
        </div>
    </div>

    {{-- Ringkasan Studi Section --}}
    <div class="quick-access-section" style="padding-top: 0;">
        <div class="section-label">Ringkasan Studi</div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div style="background: #EFF6FF; padding: 20px; border-radius: 20px; text-align: center;">
                <div style="font-size: 11px; font-weight: 700; color: #3B82F6; text-transform: uppercase;">IPK</div>
                <div style="font-size: 24px; font-weight: 800; color: #1E3A8A; margin-top: 5px;">{{ number_format($stats['rata_nilai'] ?? 0, 1) }}</div>
            </div>
            <div style="background: #ECFDF5; padding: 20px; border-radius: 20px; text-align: center;">
                <div style="font-size: 11px; font-weight: 700; color: #10B981; text-transform: uppercase;">Kehadiran</div>
                <div style="font-size: 24px; font-weight: 800; color: #064E3B; margin-top: 5px;">98%</div>
            </div>
        </div>
    </div>
</div>

{{-- Original Desktop Dashboard (Untouched) --}}
<div class="desktop-only-ui">
    <div class="stats-grid">
        <div class="stat-card"><div class="stat-icon blue"><i class="fas fa-clipboard-list"></i></div><div class="stat-info"><h4>Total Tugas</h4><div class="stat-value">{{ $stats['total_tugas'] }}</div></div></div>
        <div class="stat-card"><div class="stat-icon green"><i class="fas fa-check-circle"></i></div><div class="stat-info"><h4>Sudah Dikerjakan</h4><div class="stat-value">{{ $stats['tugas_selesai'] }}</div></div></div>
        <div class="stat-card"><div class="stat-icon yellow"><i class="fas fa-clock"></i></div><div class="stat-info"><h4>Belum Dikerjakan</h4><div class="stat-value">{{ $stats['tugas_belum'] }}</div></div></div>
    </div>

    <div class="card mt-4">
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
</div>

<script>
    let privacyMode = true;

    function togglePrivacy() {
        privacyMode = !privacyMode;
        const overlay = document.getElementById('privacyOverlay');
        const status = document.getElementById('privacyStatus');
        const icon = document.getElementById('privacyIcon');
        const targets = document.querySelectorAll('.privacy-target');

        if (privacyMode) {
            overlay.classList.add('active');
            status.innerText = "Mode Privasi Aktif : Data disembunyikan";
            icon.classList.replace('fa-eye-slash', 'fa-eye');
            targets.forEach(t => t.innerText = "***");
        } else {
            overlay.classList.remove('active');
            status.innerText = "Mode Privasi Nonaktif : Data ditampilkan";
            icon.classList.replace('fa-eye', 'fa-eye-slash');
            // Restore values
            location.reload(); // Simple way to restore values for now
        }
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        if(privacyMode) {
            document.getElementById('privacyOverlay').classList.add('active');
            document.querySelectorAll('.privacy-target').forEach(t => {
                t.setAttribute('data-value', t.innerText);
                t.innerText = "***";
            });
        }
    });
</script>
@endsection
