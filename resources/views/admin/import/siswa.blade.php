@extends('layouts.app')
@section('title', 'Import Siswa')
@section('page-title', 'Import Data Siswa')
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header"><h3><i class="fas fa-file-excel" style="color:var(--success)"></i> Import Siswa dari Excel</h3></div>
    <div class="card-body">
        <div style="background:var(--primary-50);border:1px solid var(--primary-light);border-radius:8px;padding:16px;margin-bottom:20px">
            <h4 style="font-size:13px;font-weight:600;margin-bottom:8px"><i class="fas fa-info-circle" style="color:var(--primary)"></i> Format File Excel</h4>
            <p style="font-size:12px;color:var(--text-secondary);line-height:1.6">File harus memiliki header kolom:<br><code>nama | nis | email | kelas | jenis_kelamin</code><br><br>• Password otomatis = NIS<br>• Kelas otomatis dibuat jika belum ada<br>• Format: .xlsx, .xls, .csv (maks 10MB)</p>
        </div>
        <form action="{{ route('admin.import.siswa.process') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group"><label class="form-label">Pilih File Excel</label><input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required></div>
            <div class="flex gap-2"><button type="submit" class="btn btn-success"><i class="fas fa-upload"></i> Import Sekarang</button><a href="{{ route('admin.siswa.index') }}" class="btn btn-outline">Batal</a></div>
        </form>
    </div>
</div>
@endsection
