@extends('layouts.app')
@section('title', 'Data Kelas')
@section('page-title', 'Data Kelas')
@section('content')
<div class="card">
    <div class="card-header"><h3>Daftar Kelas</h3><a href="{{ route('admin.kelas.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah</a></div>
    <div class="card-body">
        <div class="table-wrapper">
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
                            <a href="{{ route('admin.kelas.edit', $k) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.kelas.destroy', $k) }}" method="POST" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center" style="padding:32px;color:var(--text-muted)">Belum ada data kelas.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $kelas->withQueryString()->links('pagination.custom') }}
    </div>
</div>
@endsection
