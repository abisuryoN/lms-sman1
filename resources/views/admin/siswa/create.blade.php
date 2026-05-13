@extends('layouts.app')
@section('title', 'Tambah Siswa')
@section('page-title', 'Tambah Siswa')
@section('content')
<div class="mb-4">
    <a href="{{ route('admin.siswa.index') }}" class="btn btn-outline" style="background:#fff;border:1px solid #E2E8F0"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width:600px">
    <div class="card-header"><h3>Form Tambah Siswa</h3></div>
    <div class="card-body">
        <form action="{{ route('admin.siswa.store') }}" method="POST">
            @csrf
            <div class="form-group"><label class="form-label">Nama Lengkap</label><input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required></div>
            <div class="form-group"><label class="form-label">NIS</label><input type="text" name="nis" class="form-control" value="{{ old('nis') }}" required><small style="color:var(--text-muted);font-size:11px">NIS menjadi password default</small></div>
            <div class="form-group"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email') }}" required></div>
            <div class="form-group"><label class="form-label">Kelas</label><select name="kelas_id" class="form-control" required><option value="">Pilih Kelas</option>@foreach($kelasList as $k)<option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>@endforeach</select></div>
            <div class="form-group"><label class="form-label">Jenis Kelamin</label><select name="jenis_kelamin" class="form-control" required><option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option><option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option></select></div>
            <div class="flex gap-2"><button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button><a href="{{ route('admin.siswa.index') }}" class="btn btn-outline">Batal</a></div>
        </form>
    </div>
</div>
@endsection
