@extends('layouts.app')
@section('title', 'Daftar Tugas')
@section('page-title', 'Daftar Tugas')
@section('content')
<div class="card">
    <div class="card-header"><h3>Tugas Saya</h3><a href="{{ route('guru.tugas.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Buat Tugas</a></div>
    <div class="card-body table-wrapper">
        <table>
            <thead><tr><th>Judul</th><th>Kelas</th><th>Mapel</th><th>Deadline</th><th>Dikumpulkan</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($tugas as $t)
                <tr>
                    <td><a href="{{ route('guru.tugas.show', $t) }}" style="color:var(--primary);text-decoration:none;font-weight:500">{{ $t->judul }}</a></td>
                    <td>{{ $t->kelas->nama_kelas }}</td><td>{{ $t->mapel->nama_mapel }}</td>
                    <td>{{ $t->deadline->format('d M Y H:i') }}</td>
                    <td><span class="badge badge-blue">{{ $t->jawaban_tugas_count }}</span></td>
                    <td><span class="badge {{ $t->isExpired() ? 'badge-red' : 'badge-green' }}">{{ $t->isExpired() ? 'Berakhir' : 'Aktif' }}</span></td>
                    <td class="flex gap-2">
                        <a href="{{ route('guru.tugas.show', $t) }}" class="btn btn-outline btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('guru.tugas.edit', $t) }}" class="btn btn-primary btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                        <a href="{{ route('guru.nilai.edit', $t) }}" class="btn btn-warning btn-sm" title="Nilai"><i class="fas fa-star"></i></a>
                        <a href="{{ route('guru.similarity.detail', $t) }}" class="btn btn-outline btn-sm" title="Similarity" style="background:#fff; border:1px solid #E2E8F0;"><i class="fas fa-search-plus"></i></a>
                        <form action="{{ route('guru.tugas.destroy', $t) }}" method="POST" onsubmit="return confirm('Hapus tugas ini? Semua jawaban siswa juga akan terhapus.')" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center" style="padding:32px;color:var(--text-muted)">Belum ada tugas.</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="pagination">{{ $tugas->links('pagination.simple') }}</div>
    </div>
</div>
@endsection
