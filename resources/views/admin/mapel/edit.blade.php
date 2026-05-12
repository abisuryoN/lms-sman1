@extends('layouts.app')
@section('title', 'Edit Mapel')
@section('page-title', 'Edit Mata Pelajaran')
@section('content')
<div class="card" style="max-width:500px">
    <div class="card-header"><h3>Form Edit Mapel</h3></div>
    <div class="card-body">
        <form action="{{ route('admin.mapel.update', $mapel) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group"><label class="form-label">Kode Mapel</label><input type="text" name="kode_mapel" class="form-control" value="{{ old('kode_mapel', $mapel->kode_mapel) }}" required></div>
            <div class="form-group"><label class="form-label">Nama Mapel</label><input type="text" name="nama_mapel" class="form-control" value="{{ old('nama_mapel', $mapel->nama_mapel) }}" required></div>
            <div class="flex gap-2"><button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button><a href="{{ route('admin.mapel.index') }}" class="btn btn-outline">Batal</a></div>
        </form>
    </div>
</div>
@endsection
