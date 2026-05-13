@extends('layouts.app')
@section('title', 'Assign Guru ke Kelas')
@section('page-title', 'Assign Guru ke Kelas & Mapel')
@section('content')
<div class="card mb-4">
    <div class="card-header"><h3>Tambah Penugasan</h3></div>
    <div class="card-body">
        <form action="{{ route('admin.guru-kelas.store') }}" method="POST" class="flex gap-2" style="flex-wrap:wrap;align-items:flex-end">
            @csrf
            <div class="form-group" style="flex:1;min-width:180px"><label class="form-label">Guru</label><select name="guru_id" class="form-control" required><option value="">Pilih Guru</option>@foreach($guruList as $g)<option value="{{ $g->id }}">{{ $g->nama }} ({{ $g->nip }})</option>@endforeach</select></div>
            <div class="form-group" style="flex:1;min-width:180px"><label class="form-label">Kelas</label><select name="kelas_id" class="form-control" required><option value="">Pilih Kelas</option>@foreach($kelasList as $k)<option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>@endforeach</select></div>
            <div class="form-group" style="flex:1;min-width:180px"><label class="form-label">Mapel</label><select name="mapel_id" class="form-control" required><option value="">Pilih Mapel</option>@foreach($mapelList as $m)<option value="{{ $m->id }}">{{ $m->nama_mapel }}</option>@endforeach</select></div>
            <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAktif->id ?? '' }}">
            <div class="form-group"><button class="btn btn-primary"><i class="fas fa-plus"></i> Assign</button></div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3>Daftar Penugasan</h3></div>
    <div class="card-body">
        <div class="table-wrapper">
            <table>
                <thead><tr><th>Guru</th><th>NIP</th><th>Kelas</th><th>Mapel</th><th>Tahun Ajaran</th><th>Aksi</th></tr></thead>
                <tbody>
                @forelse($guruKelas as $gk)
                    <tr>
                        <td>{{ $gk->guru->nama }}</td><td><code>{{ $gk->guru->nip }}</code></td>
                        <td>{{ $gk->kelas->nama_kelas }}</td><td>{{ $gk->mapel->nama_mapel }}</td>
                        <td>{{ $gk->tahunAjaran->full_name }}</td>
                        <td><form action="{{ route('admin.guru-kelas.destroy', $gk) }}" method="POST" onsubmit="return confirm('Hapus penugasan?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></form></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center" style="padding:32px;color:var(--text-muted)">Belum ada penugasan.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $guruKelas->links('pagination.custom') }}
    </div>
</div>
@endsection
