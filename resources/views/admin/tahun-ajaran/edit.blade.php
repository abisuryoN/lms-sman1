@extends('layouts.app')
@section('title', 'Edit Tahun Ajaran')
@section('page-title', 'Edit Tahun Ajaran')
@section('content')
<div class="mb-4">
    <a href="{{ route('admin.tahun-ajaran.index') }}" class="btn btn-outline" style="background:#fff;border:1px solid #E2E8F0"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width:500px">
    <div class="card-header"><h3>Edit Tahun Ajaran</h3></div>
    <div class="card-body">
        <form action="{{ route('admin.tahun-ajaran.update', $tahunAjaran) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group"><label class="form-label">Nama Tahun</label><input type="text" name="nama_tahun" class="form-control" value="{{ old('nama_tahun', $tahunAjaran->nama_tahun) }}" placeholder="2025/2026" required></div>
            <div class="form-group"><label class="form-label">Semester</label><select name="semester" class="form-control"><option value="Ganjil" {{ $tahunAjaran->semester == 'Ganjil' ? 'selected' : '' }}>Ganjil</option><option value="Genap" {{ $tahunAjaran->semester == 'Genap' ? 'selected' : '' }}>Genap</option></select></div>
            <div class="flex gap-2"><button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui</button><a href="{{ route('admin.tahun-ajaran.index') }}" class="btn btn-outline">Batal</a></div>
        </form>
    </div>
</div>
@endsection
