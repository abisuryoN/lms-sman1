@extends('layouts.app')
@section('title', 'Edit Kelas')
@section('page-title', 'Edit Kelas')
@section('content')
<div class="card" style="max-width:500px">
    <div class="card-header"><h3>Form Edit Kelas</h3></div>
    <div class="card-body">
        <form action="{{ route('admin.kelas.update', $kela) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group"><label class="form-label">Nama Kelas</label><input type="text" name="nama_kelas" class="form-control" value="{{ old('nama_kelas', $kela->nama_kelas) }}" required></div>
            <div class="form-group"><label class="form-label">Tahun Ajaran</label><select name="tahun_ajaran_id" class="form-control">@foreach($tahunAjaranList as $t)<option value="{{ $t->id }}" {{ $kela->tahun_ajaran_id == $t->id ? 'selected' : '' }}>{{ $t->full_name }}</option>@endforeach</select></div>
            <div class="form-group"><label class="form-label">Wali Kelas</label><select name="wali_kelas_id" class="form-control"><option value="">Belum ditentukan</option>@foreach($guruList as $g)<option value="{{ $g->id }}" {{ $kela->wali_kelas_id == $g->id ? 'selected' : '' }}>{{ $g->name }}</option>@endforeach</select></div>
            <div class="flex gap-2"><button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button><a href="{{ route('admin.kelas.index') }}" class="btn btn-outline">Batal</a></div>
        </form>
    </div>
</div>
@endsection
