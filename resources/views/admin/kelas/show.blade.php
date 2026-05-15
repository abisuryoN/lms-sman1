@extends('layouts.app')
@section('title', 'Detail Kelas - ' . $kela->nama_kelas)
@section('page-title', 'Detail Kelas')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.kelas.index') }}" class="btn btn-outline" style="background:#fff;border:1px solid #E2E8F0"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="stats-grid" style="margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-school"></i></div>
        <div class="stat-info">
            <h4>Nama Kelas</h4>
            <div class="stat-value" style="font-size: 20px;">{{ $kela->nama_kelas }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-user-tie"></i></div>
        <div class="stat-info">
            <h4>Wali Kelas</h4>
            <div class="stat-value" style="font-size: 20px;">{{ $kela->waliKelas->name ?? '-' }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <h4>Total Siswa</h4>
            <div class="stat-value" style="font-size: 20px;">{{ $siswa->total() }} Orang</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header responsive-header">
        <h3><i class="fas fa-user-graduate"></i> Daftar Siswa di Kelas {{ $kela->nama_kelas }}</h3>
    </div>
    <div class="card-body">
        <div class="desktop-table table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>NIS</th>
                        <th>Nama Lengkap</th>
                        <th>Jenis Kelamin</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswa as $i => $s)
                        <tr>
                            <td>{{ $siswa->firstItem() + $i }}</td>
                            <td>
                                @if($s->user && $s->user->photo_url)
                                    <img src="{{ $s->user->photo_url }}" alt="Avatar" style="width:36px;height:36px;border-radius:50%;object-fit:cover">
                                @else
                                    <div style="width:36px;height:36px;border-radius:50%;background:#F1F5F9;display:flex;align-items:center;justify-content:center;color:#94A3B8">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                            </td>
                            <td><code>{{ $s->nis }}</code></td>
                            <td><strong>{{ $s->nama }}</strong></td>
                            <td>{{ $s->jenis_kelamin }}</td>
                            <td><span class="badge {{ $s->status == 'aktif' ? 'badge-green' : 'badge-yellow' }}">{{ ucfirst($s->status) }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center" style="padding:40px;color:var(--text-muted)">
                                <i class="fas fa-user-slash" style="font-size:32px;display:block;margin-bottom:12px;opacity:0.2"></i>
                                Belum ada siswa yang terdaftar di kelas ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mobile-cards">
            @forelse($siswa as $s)
                <div class="mobile-card" style="display: flex; gap: 12px; align-items: flex-start;">
                    @if($s->user && $s->user->photo_url)
                        <img src="{{ $s->user->photo_url }}" alt="Avatar" style="width:48px;height:48px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                    @else
                        <div style="width:48px;height:48px;border-radius:50%;background:#F1F5F9;display:flex;align-items:center;justify-content:center;color:#94A3B8;flex-shrink:0;">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                    <div style="flex: 1;">
                        <div class="mobile-card-title" style="border-bottom:none; margin-bottom: 4px; padding-bottom: 0;">{{ $s->nama }}</div>
                        <div class="mobile-card-row">
                            <span class="mobile-card-label">NIS</span>
                            <span class="mobile-card-value"><code>{{ $s->nis }}</code></span>
                        </div>
                        <div class="mobile-card-row">
                            <span class="mobile-card-label">Jenis Kelamin</span>
                            <span class="mobile-card-value">{{ $s->jenis_kelamin }}</span>
                        </div>
                        <div class="mobile-card-row">
                            <span class="mobile-card-label">Status</span>
                            <span class="mobile-card-value"><span class="badge {{ $s->status == 'aktif' ? 'badge-green' : 'badge-yellow' }}">{{ ucfirst($s->status) }}</span></span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center" style="padding:32px;color:var(--text-muted)">Belum ada siswa yang terdaftar di kelas ini.</div>
            @endforelse
        </div>
        {{ $siswa->links() }}
    </div>
</div>
@endsection
