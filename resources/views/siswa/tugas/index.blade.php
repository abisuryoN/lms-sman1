@extends('layouts.app')
@section('title', 'Tugas')
@section('page-title', 'Daftar Tugas')
@section('content')
<div class="card">
    <div class="card-header"><h3>Tugas Saya</h3></div>
    <div class="card-body table-wrapper">
        <table>
            <thead><tr><th>Tugas</th><th>Mapel</th><th>Deadline</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($tugas as $t)
                <tr>
                    <td><strong>{{ $t->judul }}</strong></td><td>{{ $t->mapel->nama_mapel }}</td>
                    <td>{{ $t->deadline->format('d M Y H:i') }}</td>
                    <td>
                        @if(in_array($t->id, $submitted))<span class="badge badge-green">Sudah Dikumpulkan</span>
                        @elseif($t->isExpired())<span class="badge badge-red">Terlambat</span>
                        @else<span class="badge badge-yellow">Belum Dikerjakan</span>@endif
                    </td>
                    <td><a href="{{ route('siswa.tugas.show', $t) }}" class="btn btn-primary btn-sm"><i class="fas fa-arrow-right"></i></a></td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center" style="padding:32px;color:var(--text-muted)">Belum ada tugas.</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="pagination">{{ $tugas->links('pagination.simple') }}</div>
    </div>
</div>
@endsection
