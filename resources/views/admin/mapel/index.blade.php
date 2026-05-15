@extends('layouts.app')
@section('title', 'Mata Pelajaran')
@section('page-title', 'Mata Pelajaran')
@section('content')
<div class="card">
    <div class="card-header" style="flex-direction: column; align-items: stretch; gap: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3><i class="fas fa-book"></i> Daftar Mata Pelajaran</h3>
            <a href="{{ route('admin.mapel.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Mapel</a>
        </div>
        
        <form action="{{ route('admin.mapel.index') }}" method="GET" style="display: grid; grid-template-columns: 1fr auto auto; gap: 10px; background: #f8fafc; padding: 12px; border-radius: 8px;">
            <div class="form-group" style="margin-bottom: 0;">
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau kode mapel..." value="{{ request('search') }}">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <select name="tingkat" class="form-control">
                    <option value="">Semua Tingkat</option>
                    <option value="10" {{ request('tingkat') == '10' ? 'selected' : '' }}>Kelas 10</option>
                    <option value="11" {{ request('tingkat') == '11' ? 'selected' : '' }}>Kelas 11</option>
                    <option value="12" {{ request('tingkat') == '12' ? 'selected' : '' }}>Kelas 12</option>
                </select>
            </div>
            <div style="display: flex; gap: 4px;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                <a href="{{ route('admin.mapel.index') }}" class="btn btn-outline" style="background: #fff;"><i class="fas fa-sync"></i></a>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Kode</th>
                        <th>Nama Mapel</th>
                        <th>Tingkat</th>
                        <th>Kelas & Guru</th>
                        <th style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($mapel as $i => $m)
                    <tr>
                        <td>{{ $mapel->firstItem() + $i }}</td>
                        <td><code>{{ $m->kode_mapel }}</code></td>
                        <td style="font-weight: 600; color: var(--primary);">{{ $m->nama_mapel }}</td>
                        <td>
                            @if($m->tingkat)
                                <span class="badge badge-blue">Kelas {{ $m->tingkat }}</span>
                            @else
                                <span class="badge badge-gray">Semua</span>
                            @endif
                        </td>
                        <td>
                            <div style="font-size: 11px; max-width: 250px;">
                                @php $activeAssignments = $m->guruKelas()->with(['kelas', 'guru'])->get(); @endphp
                                @forelse($activeAssignments->take(3) as $ak)
                                    <div style="margin-bottom: 4px; padding-bottom: 4px; border-bottom: 1px dashed #f1f5f9;">
                                        <span style="font-weight: 700; color: #1e293b;">{{ $ak->kelas->nama_kelas }}</span>: 
                                        <span style="color: #64748b;">{{ $ak->guru->name }}</span>
                                    </div>
                                @empty
                                    <span style="color: var(--text-muted);">Belum ada penugasan</span>
                                @endforelse
                                @if($activeAssignments->count() > 3)
                                    <div style="font-size: 10px; font-style: italic; color: var(--primary);">+ {{ $activeAssignments->count() - 3 }} kelas lainnya...</div>
                                @endif
                            </div>
                        </td>
                        <td class="flex gap-2">
                            <a href="{{ route('admin.mapel.show', $m) }}" class="btn btn-primary btn-sm" title="Lihat Detail Pengajar"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.mapel.edit', $m) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.mapel.destroy', $m) }}" method="POST" onsubmit="return confirm('Hapus mata pelajaran ini?')">
                                @csrf 
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center" style="padding:48px; color:var(--text-muted)">
                            <i class="fas fa-book-open" style="font-size: 32px; display: block; margin-bottom: 12px; opacity: 0.2;"></i>
                            Data mata pelajaran tidak ditemukan.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top: 16px;">
            {{ $mapel->links('pagination.custom') }}
        </div>
    </div>
</div>
@endsection
