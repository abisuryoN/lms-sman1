@extends('layouts.app')
@section('title', 'Materi')
@section('page-title', 'Materi Pembelajaran')
@section('content')
<div class="card">
    <div class="card-header"><h3>Daftar Materi</h3></div>
    <div class="card-body table-wrapper">
        <table>
            <thead><tr><th>Judul</th><th>Mapel</th><th>Guru</th><th>Tipe</th><th>Tanggal</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($materi as $m)
                <tr>
                    <td><strong>{{ $m->judul }}</strong>@if($m->deskripsi)<br><small style="color:var(--text-muted)">{{ Str::limit($m->deskripsi, 60) }}</small>@endif</td>
                    <td>{{ $m->mapel->nama_mapel }}</td><td>{{ $m->guru->nama }}</td>
                    <td><span class="badge {{ $m->tipe === 'file' ? 'badge-blue' : 'badge-green' }}">{{ ucfirst($m->tipe) }}</span></td>
                    <td>{{ $m->created_at->format('d M Y') }}</td>
                    <td>@if($m->tipe === 'link')<a href="{{ $m->file_url }}" target="_blank" class="btn btn-primary btn-sm"><i class="fas fa-external-link-alt"></i> Buka</a>@else<a href="{{ asset('storage/'.$m->file_url) }}" target="_blank" class="btn btn-primary btn-sm"><i class="fas fa-download"></i> Download</a>@endif</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center" style="padding:32px;color:var(--text-muted)">Belum ada materi.</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="pagination">{{ $materi->links('pagination.simple') }}</div>
    </div>
</div>
@endsection
