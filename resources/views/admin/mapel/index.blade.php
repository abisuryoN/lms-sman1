@extends('layouts.app')
@section('title', 'Mata Pelajaran')
@section('page-title', 'Mata Pelajaran')
@section('content')
<div class="card">
    <div class="card-header"><h3>Daftar Mata Pelajaran</h3><a href="{{ route('admin.mapel.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah</a></div>
    <div class="card-body">
        <div class="table-wrapper">
            <table>
                <thead><tr><th>No</th><th>Kode</th><th>Nama Mapel</th><th>Aksi</th></tr></thead>
                <tbody>
                @forelse($mapel as $i => $m)
                    <tr><td>{{ $mapel->firstItem() + $i }}</td><td><code>{{ $m->kode_mapel }}</code></td><td>{{ $m->nama_mapel }}</td>
                    <td class="flex gap-2"><a href="{{ route('admin.mapel.edit', $m) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a><form action="{{ route('admin.mapel.destroy', $m) }}" method="POST" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></form></td></tr>
                @empty
                    <tr><td colspan="4" class="text-center" style="padding:32px;color:var(--text-muted)">Belum ada data.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $mapel->links('pagination.custom') }}
    </div>
</div>
@endsection
