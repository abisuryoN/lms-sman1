@extends('layouts.app')
@section('title', 'Buat Tahun Ajaran')
@section('page-title', 'Buat Tahun Ajaran')
@section('content')
<div class="mb-4">
    <a href="{{ route('admin.tahun-ajaran.index') }}" class="btn btn-outline" style="background:#fff;border:1px solid #E2E8F0"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width:500px">
    <div class="card-header"><h3>Form Tahun Ajaran Baru</h3></div>
    <div class="card-body">
        <form action="{{ route('admin.tahun-ajaran.store') }}" method="POST">
            @csrf
            <div class="form-group"><label class="form-label">Nama Tahun</label><input type="text" name="nama_tahun" class="form-control" value="{{ old('nama_tahun') }}" placeholder="2025/2026" required></div>
            <div class="form-group"><label class="form-label">Semester</label><select name="semester" class="form-control"><option value="Ganjil">Ganjil</option><option value="Genap">Genap</option></select></div>
            <div class="flex gap-2"><button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button><a href="{{ route('admin.tahun-ajaran.index') }}" class="btn btn-outline">Batal</a></div>
        </form>
    </div>
</div>
@endsection
