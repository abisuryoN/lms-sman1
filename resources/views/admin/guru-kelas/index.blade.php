@extends('layouts.app')
@section('title', 'Assign Guru ke Kelas')
@section('page-title', 'Assign Guru ke Kelas & Mapel')
@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <h3>Tambah Penugasan</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.guru-kelas.store') }}" method="POST" class="flex gap-2"
                style="flex-wrap:wrap;align-items:flex-end">
                @csrf
                <div class="form-group" style="flex:1;min-width:180px"><label class="form-label">Guru</label><select
                        name="guru_id" class="form-control" required>
                        <option value="">Pilih Guru</option>@foreach($guruList as $g)<option value="{{ $g->id }}">
                        {{ $g->nama }} ({{ $g->nip }})</option>@endforeach
                    </select></div>
                <div class="form-group" style="flex:1;min-width:180px"><label class="form-label">Kelas</label><select
                        name="kelas_id" class="form-control" required>
                        <option value="">Pilih Kelas</option>@foreach($kelasList as $k)<option value="{{ $k->id }}">
                        {{ $k->nama_kelas }}</option>@endforeach
                    </select></div>
                <div class="form-group" style="flex:1;min-width:180px"><label class="form-label">Mapel</label><select
                        name="mapel_id" class="form-control" required>
                        <option value="">Pilih Mapel</option>@foreach($mapelList as $m)<option value="{{ $m->id }}">
                        {{ $m->nama_mapel }}</option>@endforeach
                    </select></div>

                <div class="form-group" style="flex:1;min-width:120px"><label class="form-label">Hari</label><select
                        name="hari" class="form-control" required>
                        <option value="">Pilih Hari</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                    </select></div>
                <div class="form-group" style="flex:1;min-width:110px">
                    <label class="form-label">Jam Mulai</label>
                    <input type="text" name="jam_mulai" class="form-control" placeholder="08.30"
                        pattern="([01]?[0-9]|2[0-3])[\.:][0-5][0-9]" title="Format 24 jam (contoh: 08.30)" required>
                </div>
                <div class="form-group" style="flex:1;min-width:110px">
                    <label class="form-label">Jam Selesai</label>
                    <input type="text" name="jam_selesai" class="form-control" placeholder="10.45"
                        pattern="([01]?[0-9]|2[0-3])[\.:][0-5][0-9]" title="Format 24 jam (contoh: 10.45)" required>
                </div>

                <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAktif->id ?? '' }}">
                <div class="form-group"><button class="btn btn-primary"><i class="fas fa-plus"></i> Assign</button></div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header" style="flex-direction: column; align-items: stretch; gap: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h3><i class="fas fa-list"></i> Daftar Penugasan & Jadwal</h3>
            </div>

            <form action="{{ route('admin.guru-kelas.index') }}" method="GET"
                style="display: grid; grid-template-columns: 1fr auto auto; gap: 10px; background: #f8fafc; padding: 12px; border-radius: 8px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama guru, kelas, atau mapel..."
                        value="{{ request('search') }}">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <select name="hari" class="form-control" data-no-custom="true" style="min-width: 130px;">
                        <option value="">Semua Hari</option>
                        <option value="Senin" {{ request('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                        <option value="Selasa" {{ request('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                        <option value="Rabu" {{ request('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                        <option value="Kamis" {{ request('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                        <option value="Jumat" {{ request('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                        <option value="Sabtu" {{ request('hari') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                    </select>
                </div>
                <div style="display: flex; gap: 4px;">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                    <a href="{{ route('admin.guru-kelas.index') }}" class="btn btn-outline" style="background: #fff;"><i
                            class="fas fa-sync"></i></a>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Guru</th>
                            <th>Kelas</th>
                            <th>Mapel</th>
                            <th>Hari</th>
                            <th>Jam</th>
                            <th>Tahun Ajaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($guruKelas as $gk)
                            <tr>
                                <td><strong>{{ $gk->guru->nama }}</strong><br><small
                                        class="text-muted">{{ $gk->guru->nip }}</small></td>
                                <td>{{ $gk->kelas->nama_kelas }}</td>
                                <td>{{ $gk->mapel->nama_mapel }}</td>
                                <td><span class="badge badge-purple">{{ $gk->hari ?? '-' }}</span></td>
                                <td><code>{{ $gk->jam_mulai ? \Carbon\Carbon::parse($gk->jam_mulai)->format('H.i') : '-' }} - {{ $gk->jam_selesai ? \Carbon\Carbon::parse($gk->jam_selesai)->format('H.i') : '-' }}</code>
                                </td>
                                <td>{{ $gk->tahunAjaran->full_name }}</td>
                                <td style="display: flex; gap: 8px; justify-content: center;">
                                    <a href="{{ route('admin.guru-kelas.edit', $gk) }}" class="btn btn-warning btn-sm"
                                        title="Edit"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.guru-kelas.destroy', $gk) }}" method="POST"
                                        onsubmit="return confirm('Hapus penugasan?')">@csrf @method('DELETE')<button
                                            class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center" style="padding:48px;color:var(--text-muted)">
                                    <i class="fas fa-calendar-times"
                                        style="font-size: 32px; display: block; margin-bottom: 12px; opacity: 0.2;"></i>
                                    Data penugasan tidak ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $guruKelas->links('pagination.custom') }}
        </div>
    </div>
@endsection