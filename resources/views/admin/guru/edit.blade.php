@extends('layouts.app')
@section('title', 'Edit Guru')
@section('page-title', 'Edit Guru')
@section('content')
<div class="mb-4">
    <a href="{{ route('admin.guru.index') }}" class="btn btn-outline" style="background:#fff;border:1px solid #E2E8F0"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width:600px">
    <div class="card-header"><h3>Form Edit Guru</h3></div>
    <div class="card-body">
        <form action="{{ route('admin.guru.update', $guru) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group"><label class="form-label">Nama Lengkap</label><input type="text" name="nama" class="form-control" value="{{ old('nama', $guru->nama) }}" required></div>
            <div class="form-group"><label class="form-label">NIP</label><input type="text" name="nip" class="form-control" value="{{ old('nip', $guru->nip) }}" required></div>
            <div class="form-group"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $guru->user->email) }}" required></div>
            <div class="form-group"><label class="form-label">Telepon</label><input type="text" name="telepon" class="form-control" value="{{ old('telepon', $guru->telepon) }}"></div>
            <div class="form-group"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control">{{ old('alamat', $guru->alamat) }}</textarea></div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                <a href="{{ route('admin.guru.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
