@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
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
        <p>Selamat datang di dashboard LMS SMAN 1 Tajurhalang. Silakan kelola data akademik Anda.</p>
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
                        <a href="{{ route('admin.tahun-ajaran.create') }}" class="btn btn-outline btn-sm"><i class="fas fa-plus"></i> Tahun Baru</a>
                        <button onclick="document.getElementById('modalAkhiri').classList.add('show')" class="btn btn-danger btn-sm"><i class="fas fa-stop-circle"></i> Akhiri</button>
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
