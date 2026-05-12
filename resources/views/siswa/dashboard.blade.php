@extends('layouts.app')
@section('title', 'Dashboard Siswa')
@section('page-title', 'Dashboard')

@section('content')
{{-- Mobile Dashboard View (MySIKA Style) --}}
<div class="m-dashboard-container">
    {{-- 1. Profile Section --}}
    <section class="m-profile-section">
        <div class="m-avatar-wrapper">
            <div class="m-avatar">
                @if($siswa && auth()->user()->photo_url)
                    <img src="{{ auth()->user()->photo_url }}" alt="Avatar">
                @else
                    <i class="fas fa-user"></i>
                @endif
            </div>
            <div class="m-privacy-overlay" id="privacyOverlay">
                <i class="fas fa-eye-slash"></i>
            </div>
        </div>
        <h2 class="m-greeting">Halo, {{ strtoupper($siswa->nama ?? auth()->user()->name) }}!</h2>
        <p class="m-subtext" id="privacyStatus">Mode Privasi Aktif : Data disembunyikan</p>
    </section>

    {{-- 2. Quick Access Section --}}
    <div class="m-section-label">Akses Cepat</div>
    <section class="m-grid-menu">
        <a href="#" class="m-grid-item">
            <div class="m-grid-icon bg-blue-soft"><i class="fas fa-calendar-alt"></i></div>
            <span class="m-grid-label">Jadwal</span>
        </a>
        <a href="{{ route('siswa.materi.index') }}" class="m-grid-item">
            <div class="m-grid-icon bg-purple-soft"><i class="fas fa-book"></i></div>
            <span class="m-grid-label">Materi</span>
        </a>
        <a href="{{ route('siswa.nilai.index') }}" class="m-grid-item">
            <div class="m-grid-icon bg-green-soft"><i class="fas fa-file-invoice"></i></div>
            <span class="m-grid-label">Nilai</span>
        </a>
        <a href="#" class="m-grid-item">
            <div class="m-grid-icon bg-orange-soft"><i class="fas fa-wallet"></i></div>
            <span class="m-grid-label">SPP</span>
        </a>
        <a href="#" class="m-grid-item">
            <div class="m-grid-icon bg-pink-soft"><i class="fas fa-id-card"></i></div>
            <span class="m-grid-label">Kartu Ujian</span>
        </a>
        <a href="#" class="m-grid-item">
            <div class="m-grid-icon bg-purple-soft"><i class="fas fa-comment-dots"></i></div>
            <span class="m-grid-label">Forum</span>
            <span class="m-badge-new">New</span>
        </a>
        <a href="#" class="m-grid-item">
            <div class="m-grid-icon bg-indigo-soft"><i class="fas fa-sparkles"></i></div>
            <span class="m-grid-label">Rekap</span>
            <span class="m-badge-new">New</span>
        </a>
        <a href="#" class="m-grid-item">
            <div class="m-grid-icon bg-pink-soft"><i class="fas fa-edit"></i></div>
            <span class="m-grid-label">Kuesioner</span>
        </a>
    </section>

    {{-- 3. Study Summary Section --}}
    <div class="m-section-label" style="display: flex; justify-content: space-between; align-items: center;">
        <span>Ringkasan Studi</span>
        <button onclick="togglePrivacy()" style="background:none;border:none;color:#94A3B8;cursor:pointer;">
            <i class="fas fa-eye" id="privacyIcon"></i>
        </button>
    </div>
    <section class="m-summary-section">
        <div class="m-summary-cards">
            <div class="m-summary-card blue">
                <div class="m-card-label">Rata-rata Nilai</div>
                <div class="m-card-value privacy-target">{{ number_format($stats['rata_nilai'] ?? 0, 1) }}</div>
            </div>
            <div class="m-summary-card green">
                <div class="m-card-label">Kehadiran</div>
                <div class="m-card-value privacy-target">98%</div>
            </div>
        </div>
    </section>

    {{-- 4. Tasks Table (Hidden on Mobile, or shown at bottom) --}}
    <div class="hide-mobile" style="padding: 0 28px;">
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
