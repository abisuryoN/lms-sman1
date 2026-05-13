@extends('layouts.app')
@section('title', 'Edit Jadwal Guru')
@section('page-title', 'Edit Jadwal Guru & Mapel')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.guru-kelas.index') }}" class="btn btn-outline" style="background:#fff;border:1px solid #E2E8F0"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h3>Edit Penugasan: {{ $guruKela->guru->nama }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.guru-kelas.update', $guruKela) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid-2" style="gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Guru</label>
                    <select name="guru_id" class="form-control" required>
                        @foreach($guruList as $g)
                            <option value="{{ $g->id }}" {{ $guruKela->guru_id == $g->id ? 'selected' : '' }}>
                                {{ $g->nama }} ({{ $g->nip }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Kelas</label>
                    <select name="kelas_id" class="form-control" required>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}" {{ $guruKela->kelas_id == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Mapel</label>
                    <select name="mapel_id" class="form-control" required>
                        @foreach($mapelList as $m)
                            <option value="{{ $m->id }}" {{ $guruKela->mapel_id == $m->id ? 'selected' : '' }}>
                                {{ $m->nama_mapel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Tahun Ajaran</label>
                    <select name="tahun_ajaran_id" class="form-control" required>
                        @foreach($tahunList as $t)
                            <option value="{{ $t->id }}" {{ $guruKela->tahun_ajaran_id == $t->id ? 'selected' : '' }}>
                                {{ $t->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr style="margin: 24px 0; border: 0; border-top: 1px solid var(--border);">

            <div class="grid-3" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Hari</label>
                    <select name="hari" class="form-control" required>
                        <option value="Senin" {{ $guruKela->hari == 'Senin' ? 'selected' : '' }}>Senin</option>
                        <option value="Selasa" {{ $guruKela->hari == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                        <option value="Rabu" {{ $guruKela->hari == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                        <option value="Kamis" {{ $guruKela->hari == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                        <option value="Jumat" {{ $guruKela->hari == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                        <option value="Sabtu" {{ $guruKela->hari == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Jam Mulai</label>
                    <input type="text" name="jam_mulai" class="form-control" value="{{ $guruKela->jam_mulai ? \Carbon\Carbon::parse($guruKela->jam_mulai)->format('H.i') : '' }}" placeholder="08.30" pattern="([01]?[0-9]|2[0-3])[\.:][0-5][0-9]" title="Format 24 jam (contoh: 08.30)" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Jam Selesai</label>
                    <input type="text" name="jam_selesai" class="form-control" value="{{ $guruKela->jam_selesai ? \Carbon\Carbon::parse($guruKela->jam_selesai)->format('H.i') : '' }}" placeholder="10.45" pattern="([01]?[0-9]|2[0-3])[\.:][0-5][0-9]" title="Format 24 jam (contoh: 10.45)" required>
                </div>
            </div>

            <div style="margin-top: 32px; display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                <a href="{{ route('admin.guru-kelas.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
