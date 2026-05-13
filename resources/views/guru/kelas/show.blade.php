@extends('layouts.app')
@section('title', 'Daftar Siswa - ' . $kela->nama_kelas)
@section('page-title', 'Detail Kelas')

@section('content')
<div class="mb-4">
    <a href="{{ route('guru.kelas.index') }}" class="btn btn-outline" style="background:#fff;border:1px solid #E2E8F0"><i class="fas fa-arrow-left"></i> Kembali</a>
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
        <div class="stat-icon purple"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <h4>Total Siswa</h4>
            <div class="stat-value" style="font-size: 20px;">{{ $kela->siswa->count() }} Orang</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-user-graduate"></i> Daftar Siswa di Kelas {{ $kela->nama_kelas }}</h3>
    </div>
    <div class="card-body">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama Lengkap</th>
                        <th>Jenis Kelamin</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kela->siswa as $i => $s)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                @if($s->user && $s->user->photo_url)
                                    <img src="{{ $s->user->photo_url }}" alt="Avatar" style="width:36px;height:36px;border-radius:50%;object-fit:cover">
                                @else
                                    <div style="width:36px;height:36px;border-radius:50%;background:#F1F5F9;display:flex;align-items:center;justify-content:center;color:#94A3B8">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                            </td>
                            <td><strong>{{ $s->nama }}</strong></td>
                            <td>{{ $s->jenis_kelamin }}</td>
                            <td><span class="badge {{ $s->status == 'aktif' ? 'badge-green' : 'badge-yellow' }}">{{ ucfirst($s->status) }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center" style="padding:40px;color:var(--text-muted)">
                                Belum ada siswa yang terdaftar di kelas ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
