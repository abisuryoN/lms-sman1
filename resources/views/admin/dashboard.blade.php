@extends('layouts.app')
@section('title', 'Dashboard Admin')
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
            <p style="font-size: 14px; color: #64748B; margin-top: 8px;">ID: {{ auth()->user()->identifier }} | LMS SMAN 1</p>
        </div>
    </div>
    
    <div class="alert alert-warning" style="margin: 0 16px 16px 16px; font-size: 11px; border-left: 4px solid var(--warning); background: #FFFBEB;">
        <i class="fas fa-shield-alt"></i>
        <div>
            Password default Anda adalah <code>admin123</code>. Silakan <a href="{{ route('admin.profil.edit') }}#password-section" style="color:var(--warning);font-weight:700">ganti password</a>.
        </div>
    </div>

    <div class="quick-access-section">
        <div class="section-label">Akses Cepat</div>
        <div class="quick-grid">
            <a href="{{ route('admin.guru.index') }}" class="quick-item">
                <div class="quick-icon-box bg-blue-soft"><i class="fas fa-chalkboard-teacher"></i></div>
                <div class="quick-label">Guru</div>
            </a>
            <a href="{{ route('admin.siswa.index') }}" class="quick-item">
                <div class="quick-icon-box bg-purple-soft"><i class="fas fa-user-graduate"></i></div>
                <div class="quick-label">Siswa</div>
            </a>
            <a href="{{ route('admin.kelas.index') }}" class="quick-item">
                <div class="quick-icon-box bg-green-soft"><i class="fas fa-school"></i></div>
                <div class="quick-label">Kelas</div>
            </a>
            <a href="{{ route('admin.mapel.index') }}" class="quick-item">
                <div class="quick-icon-box bg-orange-soft"><i class="fas fa-book"></i></div>
                <div class="quick-label">Mapel</div>
            </a>
            <a href="{{ route('admin.import.siswa') }}" class="quick-item">
                <div class="quick-icon-box bg-red-soft"><i class="fas fa-file-excel"></i></div>
                <div class="quick-label">Import</div>
            </a>
            <a href="{{ route('admin.tahun-ajaran.index') }}" class="quick-item">
                <div class="quick-icon-box bg-cyan-soft"><i class="fas fa-calendar-alt"></i></div>
                <div class="quick-label">Tahun</div>
            </a>
            <a href="{{ route('admin.guru-kelas.index') }}" class="quick-item">
                <div class="quick-icon-box bg-pink-soft"><i class="fas fa-tasks"></i></div>
                <div class="quick-label">Assign</div>
            </a>
            <a href="{{ route('admin.profil.edit') }}" class="quick-item">
                <div class="quick-icon-box bg-indigo-soft"><i class="fas fa-user-cog"></i></div>
                <div class="quick-label">Profil</div>
            </a>
        </div>
    </div>
    <div class="quick-access-section" style="padding-top: 0;">
        <div class="section-label">Statistik Dashboard</div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
            {{-- Siswa --}}
            <div style="background: linear-gradient(135deg, #3B82F6, #2563EB); padding: 12px; border-radius: 16px; color: #FFFFFF; position: relative; overflow: hidden; height: 90px;">
                <div style="font-size: 10px; font-weight: 700; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.5px;">Siswa Aktif</div>
                <div style="font-size: 22px; font-weight: 800; margin-top: 2px;">{{ $stats['total_siswa'] }}</div>
                <i class="fas fa-user-graduate" style="position: absolute; bottom: -8px; right: -5px; font-size: 40px; opacity: 0.15; transform: rotate(-10deg);"></i>
            </div>
            {{-- Guru --}}
            <div style="background: linear-gradient(135deg, #10B981, #059669); padding: 12px; border-radius: 16px; color: #FFFFFF; position: relative; overflow: hidden; height: 90px;">
                <div style="font-size: 10px; font-weight: 700; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.5px;">Total Guru</div>
                <div style="font-size: 22px; font-weight: 800; margin-top: 2px;">{{ $stats['total_guru'] }}</div>
                <i class="fas fa-chalkboard-teacher" style="position: absolute; bottom: -8px; right: -5px; font-size: 40px; opacity: 0.15; transform: rotate(-10deg);"></i>
            </div>
            {{-- Kelas --}}
            <div style="background: linear-gradient(135deg, #A855F7, #9333EA); padding: 12px; border-radius: 16px; color: #FFFFFF; position: relative; overflow: hidden; height: 90px;">
                <div style="font-size: 10px; font-weight: 700; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.5px;">Total Kelas</div>
                <div style="font-size: 22px; font-weight: 800; margin-top: 2px;">{{ $stats['total_kelas'] }}</div>
                <i class="fas fa-school" style="position: absolute; bottom: -8px; right: -5px; font-size: 40px; opacity: 0.15; transform: rotate(-10deg);"></i>
            </div>
            {{-- Mapel --}}
            <div style="background: linear-gradient(135deg, #14B8A6, #0D9488); padding: 12px; border-radius: 16px; color: #FFFFFF; position: relative; overflow: hidden; height: 90px;">
                <div style="font-size: 10px; font-weight: 700; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.5px;">Mata Pelajaran</div>
                <div style="font-size: 22px; font-weight: 800; margin-top: 2px;">{{ $stats['total_mapel'] }}</div>
                <i class="fas fa-book" style="position: absolute; bottom: -8px; right: -5px; font-size: 40px; opacity: 0.15; transform: rotate(-10deg);"></i>
            </div>
        </div>
    </div>

    </div>
</div>

{{-- Original Desktop Dashboard (Untouched) --}}
<div class="desktop-only-ui">
    <div class="welcome-header">
        <div class="welcome-avatar">
            @if(auth()->user()->photo_url)
                <img src="{{ auth()->user()->photo_url }}" alt="Avatar">
            @else
                <i class="fas fa-user-circle"></i>
            @endif
        </div>
        <div class="welcome-text">
            <h2>Halo, {{ strtoupper(auth()->user()->name) }}!</h2>
            <div class="alert alert-warning" style="margin-top: 12px; border-left: 4px solid var(--warning); background: #FFFBEB;">
                <i class="fas fa-shield-alt"></i>
                <div>
                    <strong>Peringatan Keamanan:</strong> Password default Anda adalah <code>admin123</code>. 
                    Harap segera <a href="{{ route('admin.profil.edit') }}#password-section" style="color:var(--warning);font-weight:700">ganti password</a> demi keamanan akun Anda.
                </div>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-user-graduate"></i></div>
            <div class="stat-info"><h4>Siswa Aktif</h4><div class="stat-value">{{ $stats['total_siswa'] }}</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-chalkboard-teacher"></i></div>
            <div class="stat-info"><h4>Total Guru</h4><div class="stat-value">{{ $stats['total_guru'] }}</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="fas fa-school"></i></div>
            <div class="stat-info"><h4>Total Kelas</h4><div class="stat-value">{{ $stats['total_kelas'] }}</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-book"></i></div>
            <div class="stat-info"><h4>Mata Pelajaran</h4><div class="stat-value">{{ $stats['total_mapel'] }}</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon cyan"><i class="fas fa-clipboard-list"></i></div>
            <div class="stat-info"><h4>Total Tugas</h4><div class="stat-value">{{ $stats['total_tugas'] }}</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-user-times"></i></div>
            <div class="stat-info"><h4>Alumni</h4><div class="stat-value">{{ $stats['total_alumni'] }}</div></div>
        </div>
    </div>
</div>

    <div class="grid-2">
        {{-- Tahun Ajaran Card --}}
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-calendar-alt"></i> Tahun Ajaran Aktif</h3></div>
            <div class="card-body">
                @if($tahunAjaranAktif)
                    <div style="text-align:center;padding:12px 0">
                        <div style="font-size:28px;font-weight:700;color:var(--primary)">{{ $tahunAjaranAktif->nama_tahun }}</div>
                        <div style="margin-top:4px"><span class="badge badge-blue">{{ $tahunAjaranAktif->semester }}</span></div>
                        <div style="margin-top:16px;display:flex;gap:8px;justify-content:center">
                            <a href="{{ route('admin.tahun-ajaran.create') }}" class="btn btn-outline btn-sm" title="Buat Tahun Ajaran Baru"><i class="fas fa-plus"></i> Tahun Baru</a>
                            <button onclick="document.getElementById('modalAkhiri').classList.add('show')" class="btn btn-danger btn-sm" title="Akhiri Tahun Ajaran Saat Ini"><i class="fas fa-stop-circle"></i> Akhiri</button>
                        </div>
                    </div>
                @else
                    <p style="text-align:center;color:var(--text-muted);padding:20px">Belum ada tahun ajaran aktif.</p>
                    <div style="text-align:center"><a href="{{ route('admin.tahun-ajaran.create') }}" class="btn btn-primary btn-sm">Buat Tahun Ajaran</a></div>
                @endif
            </div>
        </div>

        {{-- Chart --}}
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-chart-bar"></i> Distribusi Siswa per Kelas</h3></div>
            <div class="card-body">
                <canvas id="kelasChart" height="200"></canvas>
            </div>
        </div>
    </div>

    {{-- Recent Plagiarism Alerts --}}
    @if($recentAlerts->count())
    <div class="card mt-4">
        <div class="card-header"><h3><i class="fas fa-exclamation-triangle" style="color:var(--danger)"></i> Alert Plagiarisme Terbaru</h3></div>
        <div class="card-body table-wrapper">
            <table>
                <thead><tr><th>Tugas</th><th>Siswa 1</th><th>Siswa 2</th><th>Similarity</th><th>Status</th></tr></thead>
                <tbody>
                @foreach($recentAlerts as $alert)
                    <tr>
                        <td>{{ $alert->tugas->judul ?? '-' }}</td>
                        <td>{{ $alert->jawaban1->siswa->nama ?? '-' }}</td>
                        <td>{{ $alert->jawaban2->siswa->nama ?? '-' }}</td>
                        <td><strong>{{ $alert->similarity_percentage }}%</strong></td>
                        <td><span class="badge badge-red">Plagiat</span></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>

{{-- Modal Akhiri Tahun Ajaran --}}
<div class="modal-overlay" id="modalAkhiri">
    <div class="modal">
        <h3>⚠️ Akhiri Tahun Ajaran</h3>
        <p>Apakah yakin ingin mengakhiri tahun ajaran ini? Tindakan ini akan:<br>
        • Menonaktifkan tahun ajaran saat ini<br>
        • Membuat tahun ajaran baru otomatis<br>
        • Menaikkan kelas siswa (X→XI→XII)<br>
        • Siswa XII menjadi alumni</p>
        <div class="modal-actions">
            <button onclick="document.getElementById('modalAkhiri').classList.remove('show')" class="btn btn-outline">Batal</button>
            <form action="{{ route('admin.tahun-ajaran.akhiri') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">Ya, Akhiri</button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const ctx = document.getElementById('kelasChart');
if (ctx) {
    const data = @json($kelasData);
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.map(d => d.nama),
            datasets: [{
                label: 'Jumlah Siswa',
                data: data.map(d => d.jumlah),
                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                borderColor: '#3B82F6',
                borderWidth: 1, borderRadius: 6
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });
}
</script>
@endpush
