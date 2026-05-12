@extends('layouts.app')
@section('title', 'Profil Siswa')
@section('page-title', 'Profil Saya')
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header"><h3>Edit Profil</h3></div>
    <div class="card-body">
        <div style="text-align:center;margin-bottom:20px">
            <img src="{{ $user->photo_url }}" alt="Avatar" style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:3px solid var(--primary-light)">
        </div>
        <form action="{{ route('siswa.profil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="form-group"><label class="form-label">Foto Profil</label><input type="file" name="photo" class="form-control" accept=".jpg,.jpeg,.png"><small style="color:var(--text-muted);font-size:11px">Maks 2MB (JPG/PNG)</small></div>
            <div class="form-group"><label class="form-label">Nama</label><input type="text" class="form-control" value="{{ $siswa->nama ?? $user->name }}" disabled></div>
            <div class="form-group"><label class="form-label">NIS</label><input type="text" class="form-control" value="{{ $siswa->nis ?? '-' }}" disabled></div>
            <div class="form-group"><label class="form-label">Kelas</label><input type="text" class="form-control" value="{{ $siswa->kelas->nama_kelas ?? '-' }}" disabled></div>
            <div class="form-group"><label class="form-label">Telepon</label><input type="text" name="telepon" class="form-control" value="{{ $siswa->telepon ?? '' }}"></div>
            <div class="form-group"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control">{{ $siswa->alamat ?? '' }}</textarea></div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        </form>
    </div>
</div>
@endsection
