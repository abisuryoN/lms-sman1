@extends('layouts.app')
@section('title', 'Tambah Mapel')
@section('page-title', 'Tambah Mata Pelajaran')
@section('content')
<div class="mb-4">
    <a href="{{ route('admin.mapel.index') }}" class="btn btn-outline" style="background:#fff;border:1px solid #E2E8F0"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width:500px">
    <div class="card-header"><h3>Form Tambah Mapel</h3></div>
    <div class="card-body">
        <form action="{{ route('admin.mapel.store') }}" method="POST">
            @csrf
            <div class="form-group"><label class="form-label">Kode Mapel</label><input type="text" name="kode_mapel" class="form-control" value="{{ old('kode_mapel') }}" placeholder="MTK" required></div>
            <div class="form-group"><label class="form-label">Nama Mapel</label><input type="text" name="nama_mapel" class="form-control" value="{{ old('nama_mapel') }}" placeholder="Matematika" required></div>
            <div class="flex gap-2"><button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button><a href="{{ route('admin.mapel.index') }}" class="btn btn-outline">Batal</a></div>
        </form>
    </div>
</div>
@endsection
