@extends('layouts.app')
@section('title', 'Tambah Guru')
@section('page-title', 'Tambah Guru')
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header"><h3>Form Tambah Guru</h3></div>
    <div class="card-body">
        <form action="{{ route('admin.guru.store') }}" method="POST">
            @csrf
            <div class="form-group"><label class="form-label">Nama Lengkap</label><input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required></div>
            <div class="form-group"><label class="form-label">NIP</label><input type="text" name="nip" class="form-control" value="{{ old('nip') }}" required><small style="color:var(--text-muted);font-size:11px">NIP akan menjadi password default</small></div>
            <div class="form-group"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email') }}" required></div>
            <div class="form-group"><label class="form-label">Telepon</label><input type="text" name="telepon" class="form-control" value="{{ old('telepon') }}"></div>
            <div class="form-group"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control">{{ old('alamat') }}</textarea></div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="{{ route('admin.guru.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
