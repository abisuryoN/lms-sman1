@extends('layouts.app')
@section('title', 'Penilaian')
@section('page-title', 'Penilaian')
@section('content')
<div class="card">
    <div class="card-header"><h3>Pilih Tugas untuk Penilaian</h3></div>
    <div class="card-body table-wrapper">
        <table>
            <thead><tr><th>Tugas</th><th>Kelas</th><th>Mapel</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($tugas as $t)
                <tr><td>{{ $t->judul }}</td><td>{{ $t->kelas->nama_kelas }}</td><td>{{ $t->mapel->nama_mapel }}</td>
                <td><a href="{{ route('guru.nilai.edit', $t) }}" class="btn btn-primary btn-sm" title="Beri Nilai"><i class="fas fa-star"></i> Beri Nilai</a></td></tr>
            @empty
                <tr><td colspan="4" class="text-center" style="padding:24px;color:var(--text-muted)">Belum ada tugas.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
