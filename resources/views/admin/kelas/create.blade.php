@extends('layouts.app')
@section('title', 'Tambah Kelas')
@section('page-title', 'Tambah Kelas')
@section('content')
<div class="card" style="max-width:500px">
    <div class="card-header"><h3>Form Tambah Kelas</h3></div>
    <div class="card-body">
        <form action="{{ route('admin.kelas.store') }}" method="POST">
            @csrf
            <div class="form-group"><label class="form-label">Nama Kelas</label><input type="text" name="nama_kelas" class="form-control" value="{{ old('nama_kelas') }}" placeholder="Contoh: X IPA 1" required></div>
            <div class="form-group"><label class="form-label">Tahun Ajaran</label><input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaranAktif->id ?? '' }}"><input type="text" class="form-control" value="{{ $tahunAjaranAktif->full_name ?? 'Tidak ada tahun ajaran aktif' }}" disabled></div>
            <div class="form-group"><label class="form-label">Wali Kelas</label><select name="wali_kelas_id" class="form-control"><option value="">Belum ditentukan</option>@foreach($guruList as $g)<option value="{{ $g->id }}">{{ $g->name }}</option>@endforeach</select></div>
            <div class="flex gap-2"><button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button><a href="{{ route('admin.kelas.index') }}" class="btn btn-outline">Batal</a></div>
        </form>
    </div>
</div>
@endsection
