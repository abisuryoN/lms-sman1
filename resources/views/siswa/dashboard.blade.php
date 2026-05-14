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
            <p style="font-size: 14px; color: #64748B; margin-top: 8px;">NIS: {{ auth()->user()->identifier }} | Kelas: {{ $siswa->kelas->nama_kelas ?? '-' }}</p>
        </div>
    </div>

    <div class="alert alert-warning" style="margin: 0 20px 20px 20px; font-size: 12px; border-left: 4px solid var(--warning); background: #FFFBEB;">
        <i class="fas fa-shield-alt"></i>
        <div>
            Password default Anda adalah **NIS** Anda. Silakan <a href="{{ route('siswa.profil.edit') }}#password-section" style="color:var(--warning);font-weight:700">ganti password</a>.
        </div>
    </div>

    <div class="quick-access-section">
        <div class="section-label">Manajemen Pembelajaran</div>
        <div class="quick-grid">
            <a href="{{ route('siswa.materi.index') }}" class="quick-item">
                <div class="quick-icon-box bg-purple-soft"><i class="fas fa-file-alt"></i></div>
                <div class="quick-label">Materi</div>
            </a>
            <a href="{{ route('siswa.tugas.index') }}" class="quick-item">
                <div class="quick-icon-box bg-green-soft"><i class="fas fa-clipboard-list"></i></div>
                <div class="quick-label">Tugas</div>
            </a>
            <a href="{{ route('siswa.nilai.index') }}" class="quick-item">
                <div class="quick-icon-box bg-blue-soft"><i class="fas fa-chart-bar"></i></div>
                <div class="quick-label">Nilai</div>
            </a>
            <a href="{{ route('siswa.profil.edit') }}" class="quick-item">
                <div class="quick-icon-box bg-orange-soft"><i class="fas fa-user-cog"></i></div>
                <div class="quick-label">Profil</div>
            </a>
        </div>
    </div>

    {{-- Ringkasan Studi Section --}}
    <div class="quick-access-section" style="padding-top: 0;">
        <div class="section-label">Ringkasan Studi</div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div style="background: #EFF6FF; padding: 20px; border-radius: 20px; text-align: center;">
                <div style="font-size: 11px; font-weight: 700; color: #3B82F6; text-transform: uppercase;">Total Tugas</div>
                <div style="font-size: 24px; font-weight: 800; color: #1E3A8A; margin-top: 5px;">{{ $stats['total_tugas'] }}</div>
            </div>
            <div style="background: #ECFDF5; padding: 20px; border-radius: 20px; text-align: center;">
                <div style="font-size: 11px; font-weight: 700; color: #10B981; text-transform: uppercase;">Selesai</div>
                <div style="font-size: 24px; font-weight: 800; color: #064E3B; margin-top: 5px;">{{ $stats['tugas_selesai'] }}</div>
            </div>
        </div>
    </div>

    {{-- Tugas Mendatang Section --}}
    <div class="quick-access-section" style="padding-top: 0;">
        <div class="section-label">Tugas Mendatang</div>
        @forelse($tugasTerbaru as $t)
            <div class="schedule-item" style="background: #FFFFFF; border-radius: 20px; padding: 20px; margin-bottom: 12px; border: 1px solid #F1F5F9; display: flex; align-items: center; gap: 16px;">
                <div class="schedule-icon" style="background: #FEF2F2; color: #EF4444;"><i class="fas fa-bell"></i></div>
                <div class="schedule-info" style="flex: 1;">
                    <h4 style="font-size: 16px; margin: 0;">{{ $t->judul }}</h4>
                    <p style="font-size: 12px; color: #64748B; margin: 4px 0;">{{ $t->mapel->nama_mapel }}</p>
                    <div style="display: flex; align-items: center; gap: 8px; margin-top: 8px;">
                        <span class="badge {{ $t->isExpired() ? 'badge-red' : 'badge-green' }}" style="font-size: 10px;">
                            {{ $t->isExpired() ? 'Berakhir' : $t->deadline->diffForHumans() }}
                        </span>
                    </div>
                </div>
                <a href="{{ route('siswa.tugas.show', $t) }}" style="color: #3B82F6;"><i class="fas fa-chevron-right"></i></a>
            </div>
        @empty
            <div style="background: #FFFFFF; border-radius: 20px; padding: 30px; text-align: center; border: 1px dashed #CBD5E1;">
                <p style="color: #94A3B8; font-size: 14px; margin: 0;">Tidak ada tugas mendatang.</p>
            </div>
        @endforelse
    </div>

    <div class="quick-access-section" style="padding-top: 0;">
        <div class="section-label">Jadwal Mendatang</div>
        @forelse($jadwal as $j)
            <div class="schedule-item" style="background: #FFFFFF; border-radius: 20px; padding: 20px; margin-bottom: 12px; border: 1px solid #F1F5F9; display: flex; align-items: center; gap: 16px;">
                <div class="schedule-icon" style="background: #FFF7ED; color: #F97316;"><i class="far fa-edit"></i></div>
                <div class="schedule-info">
                    <h4 style="font-size: 16px; margin: 0;">{{ $j->mapel->nama_mapel ?? '-' }}</h4>
                    <div class="schedule-meta" style="margin-top: 5px; display: flex; gap: 12px; font-size: 12px; color: #64748B;">
                        <span><i class="far fa-calendar"></i> {{ $j->hari }}</span>
                        <span><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H.i') }}</span>
                    </div>
                    <div class="schedule-tags" style="margin-top: 8px;">
                        <span class="badge badge-purple" style="font-size: 10px;">{{ strtoupper($j->hari) }}</span>
                        <span class="badge badge-gray" style="font-size: 10px;">{{ $j->guru->nama }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div style="background: #FFFFFF; border-radius: 20px; padding: 30px; text-align: center; border: 1px dashed #CBD5E1;">
                <p style="color: #94A3B8; font-size: 14px; margin: 0;">Belum ada jadwal.</p>
            </div>
        @endforelse
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
            <h2 style="font-size: 26px; font-weight: 700; color: #0F172A;">Halo, {{ strtoupper($siswa->nama ?? auth()->user()->name) }}!</h2>
            <p style="font-size: 16px; color: #64748B; margin-top: 4px; font-weight: 500;">Kelas: {{ $siswa->kelas->nama_kelas ?? '-' }}</p>
            <div class="alert alert-warning" style="margin-top: 12px; border-left: 4px solid var(--warning); background: #FFFBEB;">
                <i class="fas fa-shield-alt"></i>
                <div>
                    <strong>Peringatan Keamanan:</strong> Password default Anda adalah **NIS** Anda. 
                    Harap segera <a href="{{ route('siswa.profil.edit') }}#password-section" style="color:var(--warning);font-weight:700">ganti password</a> demi keamanan akun Anda.
                </div>
            </div>
        </div>
    </div>

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

    <div class="card mt-4">
        <div class="card-header">
            <h3><i class="fas fa-calendar-check" style="color:var(--primary)"></i> Jadwal Mendatang</h3>
            <span class="badge badge-blue">{{ $jadwal->count() }} Acara</span>
        </div>
        <div class="card-body">
            @forelse($jadwal as $j)
                <div class="schedule-item">
                    <div class="schedule-icon"><i class="far fa-edit"></i></div>
                    <div class="schedule-info">
                        <h4>{{ $j->mapel->nama_mapel ?? '-' }}</h4>
                        <div class="schedule-meta">
                            <span><i class="far fa-calendar"></i> {{ $j->hari }}</span>
                            <span><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H.i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H.i') }}</span>
                            <span><i class="fas fa-chalkboard-teacher" style="font-size: 11px;"></i> {{ $j->guru->nama }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div style="padding: 24px; text-align: center; color: var(--text-muted);">
                    <i class="fas fa-calendar-times" style="font-size: 32px; opacity: 0.2; margin-bottom: 12px;"></i>
                    <p>Belum ada jadwal untuk kelas Anda.</p>
                </div>
            @endforelse
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
