@extends('layouts.app')
@section('title', 'Upload Materi')
@section('page-title', 'Upload Materi')
@section('content')
<div class="mb-4">
    <a href="{{ route('guru.materi.index') }}" class="btn btn-outline" style="background:#fff;border:1px solid #E2E8F0"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width:600px">
    <div class="card-header"><h3>Form Upload Materi</h3></div>
    <div class="card-body">
        <form action="{{ route('guru.materi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group"><label class="form-label">Judul Materi</label><input type="text" name="judul" class="form-control" value="{{ old('judul') }}" required></div>
            <div class="form-group"><label class="form-label">Kelas & Mapel</label><select name="kelas_id" class="form-control" required id="kelasSelect"><option value="">Pilih Kelas & Mapel</option>@foreach($guruKelas as $gk)<option value="{{ $gk->kelas_id }}" data-mapel="{{ $gk->mapel_id }}">{{ $gk->kelas->nama_kelas }} — {{ $gk->mapel->nama_mapel }}</option>@endforeach</select><input type="hidden" name="mapel_id" id="mapelInput"></div>
            <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="deskripsi" class="form-control">{{ old('deskripsi') }}</textarea></div>
            <div class="form-group"><label class="form-label">Tipe</label><select name="tipe" class="form-control" id="tipeSelect" onchange="toggleTipe()"><option value="file">Upload File</option><option value="link">Link URL</option></select></div>
            <div class="form-group" id="fileGroup"><label class="form-label">File (PDF, DOCX, PPT, maks 10MB)</label><input type="file" name="file" class="form-control" accept=".pdf,.docx,.pptx,.ppt,.doc"></div>
            <div class="form-group" id="linkGroup" style="display:none"><label class="form-label">URL (Google Drive / YouTube)</label><input type="url" name="link_url" class="form-control" placeholder="https://..."></div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload</button>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.getElementById('kelasSelect').addEventListener('change', function(){
    document.getElementById('mapelInput').value = this.selectedOptions[0]?.dataset?.mapel || '';
});
function toggleTipe() {
    const t = document.getElementById('tipeSelect').value;
    document.getElementById('fileGroup').style.display = t === 'file' ? '' : 'none';
    document.getElementById('linkGroup').style.display = t === 'link' ? '' : 'none';
}
</script>
@endpush
