@extends('layouts.app')
@section('title', 'Edit Siswa')
@section('page-title', 'Edit Siswa')
@section('content')
<div class="mb-4">
    <a href="{{ route('admin.siswa.index') }}" class="btn btn-outline" style="background:#fff;border:1px solid #E2E8F0"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width:600px">
    <div class="card-header"><h3>Form Edit Siswa</h3></div>
    <div class="card-body">
        <form action="{{ route('admin.siswa.update', $siswa) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group"><label class="form-label">Nama</label><input type="text" name="nama" class="form-control" value="{{ old('nama', $siswa->nama) }}" required></div>
            <div class="form-group"><label class="form-label">NIS</label><input type="text" name="nis" class="form-control" value="{{ old('nis', $siswa->nis) }}" required></div>
            <div class="form-group"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $siswa->user->email) }}" required></div>
            <div class="form-group"><label class="form-label">Kelas</label><select name="kelas_id" class="form-control" required>@foreach($kelasList as $k)<option value="{{ $k->id }}" {{ $siswa->kelas_id == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>@endforeach</select></div>
            <div class="form-group"><label class="form-label">Jenis Kelamin</label><select name="jenis_kelamin" class="form-control"><option value="L" {{ $siswa->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option><option value="P" {{ $siswa->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option></select></div>
            <div class="flex gap-2"><button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button><a href="{{ route('admin.siswa.index') }}" class="btn btn-outline">Batal</a></div>
        </form>
    </div>
</div>
@endsection
