@extends('layouts.app')
@section('title', 'Data Kelas')
@section('page-title', 'Data Kelas')
@section('content')
<div class="card">
    <div class="card-header responsive-header">
        <h3>Daftar Kelas</h3>
        <div class="header-actions">
            <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah</a>
        </div>
    </div>
    <div class="card-body">
        <div class="desktop-table table-wrapper">
            <table>
                <thead><tr><th>No</th><th>Nama Kelas</th><th>Tahun Ajaran</th><th>Wali Kelas</th><th>Jumlah Siswa</th><th>Aksi</th></tr></thead>
                <tbody>
                @forelse($kelas as $i => $k)
                    <tr>
                        <td>{{ $kelas->firstItem() + $i }}</td>
                        <td><strong>{{ $k->nama_kelas }}</strong></td>
                        <td>{{ $k->tahunAjaran->full_name ?? '-' }}</td>
                        <td>{{ $k->waliKelas->name ?? '-' }}</td>
                        <td><span class="badge badge-blue">{{ $k->siswa_count }}</span></td>
                        <td class="flex gap-2">
                            <a href="{{ route('admin.kelas.show', $k) }}" class="btn btn-primary btn-sm" title="Lihat Siswa"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.kelas.edit', $k) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.kelas.destroy', $k) }}" method="POST" class="form-delete">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm btn-delete" title="Hapus"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center" style="padding:32px;color:var(--text-muted)">Belum ada data kelas.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mobile-cards">
            @forelse($kelas as $k)
                <div class="mobile-card">
                    <div class="mobile-card-title">{{ $k->nama_kelas }}</div>
                    
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Tahun Ajaran</span>
                        <span class="mobile-card-value">{{ $k->tahunAjaran->full_name ?? '-' }}</span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Wali Kelas</span>
                        <span class="mobile-card-value">{{ $k->waliKelas->name ?? '-' }}</span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Jumlah Siswa</span>
                        <span class="mobile-card-value"><span class="badge badge-blue">{{ $k->siswa_count }}</span></span>
                    </div>

                    <div class="mobile-card-actions">
                        <a href="{{ route('admin.kelas.show', $k) }}" class="btn btn-primary btn-sm" title="Lihat Siswa"><i class="fas fa-eye"></i> Detail</a>
                        <a href="{{ route('admin.kelas.edit', $k) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i> Edit</a>
                        <form action="{{ route('admin.kelas.destroy', $k) }}" method="POST" class="form-delete">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm btn-delete" title="Hapus"><i class="fas fa-trash"></i> Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center" style="padding:32px;color:var(--text-muted)">Belum ada data kelas.</div>
            @endforelse
        </div>
        {{ $kelas->withQueryString()->links('pagination.custom') }}
    </div>
</div>
@endsection
