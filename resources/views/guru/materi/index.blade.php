@extends('layouts.app')
@section('title', 'Materi Pembelajaran')
@section('page-title', 'Materi Pembelajaran')
@section('content')
<div class="card">
    <div class="card-header"><h3>Daftar Materi</h3><a href="{{ route('guru.materi.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Upload Materi</a></div>
    <div class="card-body table-wrapper">
        <table>
            <thead><tr><th>Judul</th><th>Kelas</th><th>Mapel</th><th>Tipe</th><th>Tanggal</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($materi as $m)
                <tr>
                    <td><strong>{{ $m->judul }}</strong></td><td>{{ $m->kelas->nama_kelas }}</td><td>{{ $m->mapel->nama_mapel }}</td>
                    <td><span class="badge {{ $m->tipe === 'file' ? 'badge-blue' : 'badge-green' }}">{{ ucfirst($m->tipe) }}</span></td>
                    <td>{{ $m->created_at->format('d M Y') }}</td>
                    <td class="flex gap-2">
                        @if($m->tipe === 'link')<a href="{{ $m->file_url }}" target="_blank" class="btn btn-outline btn-sm"><i class="fas fa-external-link-alt"></i></a>@else<a href="{{ asset('storage/'.$m->file_url) }}" target="_blank" class="btn btn-outline btn-sm"><i class="fas fa-download"></i></a>@endif
                        <form action="{{ route('guru.materi.destroy', $m) }}" method="POST" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></form>
                    </td>
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
