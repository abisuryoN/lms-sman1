@extends('layouts.app')
@section('title', 'Buat Tugas')
@section('page-title', 'Buat Tugas Baru')
@section('content')
<div class="mb-4">
    <a href="{{ route('guru.tugas.index') }}" class="btn btn-outline" style="background:#fff;border:1px solid #E2E8F0"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width:600px">
    <div class="card-header"><h3>Form Buat Tugas</h3></div>
    <div class="card-body">
        <form action="{{ route('guru.tugas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group"><label class="form-label">Judul Tugas</label><input type="text" name="judul" class="form-control" value="{{ old('judul') }}" required></div>
            <div class="form-group"><label class="form-label">Kelas & Mapel</label><select name="kelas_id" class="form-control" required id="kelasSelect2"><option value="">Pilih</option>@foreach($guruKelas as $gk)<option value="{{ $gk->kelas_id }}" data-mapel="{{ $gk->mapel_id }}">{{ $gk->kelas->nama_kelas }} — {{ $gk->mapel->nama_mapel }}</option>@endforeach</select><input type="hidden" name="mapel_id" id="mapelInput2"></div>
            <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi') }}</textarea></div>
            
            <div class="form-group">
                <label class="form-label">Lampiran Soal (Opsional)</label>
                <select name="tipe" class="form-control mb-2" id="tipeSelect">
                    <option value="">Tanpa Lampiran</option>
                    <option value="file" {{ old('tipe') == 'file' ? 'selected' : '' }}>Upload File (PDF, Docx, dsb)</option>
                    <option value="link" {{ old('tipe') == 'link' ? 'selected' : '' }}>Link Eksternal (Google Drive, dsb)</option>
                </select>
            </div>

            <div id="fileInputWrapper" class="form-group {{ old('tipe') == 'file' ? '' : 'hidden' }}">
                <label class="form-label">Pilih File</label>
                <input type="file" name="file" class="form-control" accept=".pdf,.docx,.pptx,.doc,.jpg,.jpeg,.png">
                <small class="text-muted">Maksimal 1MB. Format: pdf, docx, doc, jpg, png</small>
            </div>

            <div id="linkInputWrapper" class="form-group {{ old('tipe') == 'link' ? '' : 'hidden' }}">
                <label class="form-label">Masukkan Link URL</label>
                <input type="url" name="link_url" class="form-control" placeholder="https://..." value="{{ old('link_url') }}">
            </div>

            <div class="form-group"><label class="form-label">Deadline</label><input type="text" name="deadline" id="deadlinePicker" class="form-control" value="{{ old('deadline') }}" placeholder="Pilih Tanggal & Waktu" required></div>
            <div class="flex gap-2" style="margin-top: 24px;"><button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Buat Tugas</button><a href="{{ route('guru.tugas.index') }}" class="btn btn-outline">Batal</a></div>
        </form>
    </div>
</div>

<style>
    .hidden { display: none; }
</style>
@endsection

@push('scripts')
<script>
    document.getElementById('kelasSelect2').addEventListener('change', function(){ 
        document.getElementById('mapelInput2').value = this.selectedOptions[0]?.dataset?.mapel || ''; 
    });

    const tipeSelect = document.getElementById('tipeSelect');
    const fileWrapper = document.getElementById('fileInputWrapper');
    const linkWrapper = document.getElementById('linkInputWrapper');

    tipeSelect.addEventListener('change', function() {
        fileWrapper.classList.add('hidden');
        linkWrapper.classList.add('hidden');
        
        if (this.value === 'file') {
            fileWrapper.classList.remove('hidden');
        } else if (this.value === 'link') {
            linkWrapper.classList.remove('hidden');
        }
    });

    flatpickr("#deadlinePicker", {
        enableTime: true,
        time_24hr: true,
        dateFormat: "Y-m-d H:i",
        minDate: "today",
        locale: "id", // Use Indonesian if possible or default
    });
</script>
@endpush
