@extends('layouts.app')
@section('title', 'Data Siswa')
@section('page-title', 'Data Siswa')
@section('content')
<div class="card">
    <div class="card-header responsive-header">
        <h3>Daftar Siswa</h3>
        <div class="header-actions flex gap-2">
            <a href="{{ route('admin.import.siswa') }}" class="btn btn-outline btn-sm"><i class="fas fa-file-excel"></i> Import</a>
            <a href="{{ route('admin.siswa.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah</a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" style="margin-bottom:16px" class="flex gap-2" style="flex-wrap:wrap;">
            <input type="text" name="search" class="form-control" placeholder="Cari nama/NIS..." value="{{ request('search') }}" style="max-width:200px">
            <select name="kelas_id" class="form-control" style="max-width:180px">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $k)
                    <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                @endforeach
            </select>
            <select name="status" class="form-control" style="max-width:130px">
                <option value="aktif" {{ request('status','aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="alumni" {{ request('status') == 'alumni' ? 'selected' : '' }}>Alumni</option>
            </select>
            <button class="btn btn-primary btn-sm">Filter</button>
        </form>
        <div class="desktop-table table-wrapper">
            <table>
                <thead><tr><th>No</th><th>NIS</th><th>Nama</th><th>Kelas</th><th>JK</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                @forelse($siswa as $i => $s)
                    <tr>
                        <td>{{ $siswa->firstItem() + $i }}</td>
                        <td><code>{{ $s->nis }}</code></td>
                        <td>{{ $s->nama }}</td>
                        <td>{{ $s->kelas->nama_kelas ?? '-' }}</td>
                        <td>{{ $s->jenis_kelamin }}</td>
                        <td><span class="badge {{ $s->status === 'aktif' ? 'badge-green' : 'badge-gray' }}">{{ ucfirst($s->status) }}</span></td>
                        <td class="flex gap-2">
                            <a href="{{ route('admin.siswa.edit', $s) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.siswa.reset-password', $s) }}" method="POST">
                                @csrf
                                <button type="button" class="btn btn-outline btn-sm btn-delete" data-confirm="Password siswa akan dikembalikan ke NIS mereka. Lanjutkan?" title="Reset Password"><i class="fas fa-key"></i></button>
                            </form>
                            <form action="{{ route('admin.siswa.destroy', $s) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm btn-delete" data-confirm="Hapus data siswa ini secara permanen?" title="Hapus"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center" style="padding:32px;color:var(--text-muted)">Belum ada data siswa.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mobile-cards">
            @forelse($siswa as $s)
                <div class="mobile-card">
                    <div class="mobile-card-title">{{ $s->nama }}</div>
                    
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">NIS</span>
                        <span class="mobile-card-value"><code>{{ $s->nis }}</code></span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Kelas</span>
                        <span class="mobile-card-value">{{ $s->kelas->nama_kelas ?? '-' }}</span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Jenis Kelamin</span>
                        <span class="mobile-card-value">{{ $s->jenis_kelamin }}</span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Status</span>
                        <span class="mobile-card-value">
                            <span class="badge {{ $s->status === 'aktif' ? 'badge-green' : 'badge-gray' }}">{{ ucfirst($s->status) }}</span>
                        </span>
                    </div>

                    <div class="mobile-card-actions">
                        <a href="{{ route('admin.siswa.edit', $s) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i> Edit</a>
                        <form action="{{ route('admin.siswa.reset-password', $s) }}" method="POST">
                            @csrf
                            <button type="button" class="btn btn-outline btn-sm btn-delete" data-confirm="Password siswa akan dikembalikan ke NIS mereka. Lanjutkan?" title="Reset Password"><i class="fas fa-key"></i> Reset PW</button>
                        </form>
                        <form action="{{ route('admin.siswa.destroy', $s) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm btn-delete" data-confirm="Hapus data siswa ini secara permanen?" title="Hapus"><i class="fas fa-trash"></i> Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center" style="padding:32px;color:var(--text-muted)">Belum ada data siswa.</div>
            @endforelse
        </div>
        {{ $siswa->links() }}
    </div>
</div>
@endsection
