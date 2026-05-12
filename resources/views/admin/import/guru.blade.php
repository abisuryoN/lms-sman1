@extends('layouts.app')
@section('title', 'Import Guru')
@section('page-title', 'Import Data Guru')
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header"><h3><i class="fas fa-file-upload" style="color:var(--success)"></i> Import Guru dari Excel</h3></div>
    <div class="card-body">
        <div style="background:var(--primary-50);border:1px solid var(--primary-light);border-radius:8px;padding:16px;margin-bottom:20px">
            <h4 style="font-size:13px;font-weight:600;margin-bottom:8px"><i class="fas fa-info-circle" style="color:var(--primary)"></i> Format File Excel</h4>
            <p style="font-size:12px;color:var(--text-secondary);line-height:1.6">File harus memiliki header kolom:<br><code>nama | nip | email</code><br><br>• Password otomatis = NIP<br>• Mapel dan kelas di-assign admin kemudian<br>• Format: .xlsx, .xls, .csv (maks 10MB)</p>
        </div>
        <form action="{{ route('admin.import.guru.process') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group"><label class="form-label">Pilih File Excel</label><input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required></div>
            <div class="flex gap-2"><button type="submit" class="btn btn-success"><i class="fas fa-upload"></i> Import Sekarang</button><a href="{{ route('admin.guru.index') }}" class="btn btn-outline">Batal</a></div>
        </form>
    </div>
</div>
@endsection
