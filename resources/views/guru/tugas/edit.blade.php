@extends('layouts.app')
@section('title', 'Edit Tugas')
@section('page-title', 'Edit Tugas')
@section('content')
<div class="mb-4">
    <a href="{{ route('guru.tugas.index') }}" class="btn btn-outline" style="background:#fff;border:1px solid #E2E8F0"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width:600px">
    <div class="card-header"><h3>Form Edit Tugas</h3></div>
    <div class="card-body">
        <form action="{{ route('guru.tugas.update', $tuga) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Judul Tugas</label>
                <input type="text" name="judul" class="form-control" value="{{ old('judul', $tuga->judul) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Kelas & Mapel</label>
                <select name="kelas_id" class="form-control" required id="kelasSelect2">
                    <option value="">Pilih</option>
                    @foreach($guruKelas as $gk)
                        <option value="{{ $gk->kelas_id }}" data-mapel="{{ $gk->mapel_id }}" {{ old('kelas_id', $tuga->kelas_id) == $gk->kelas_id ? 'selected' : '' }}>
                            {{ $gk->kelas->nama_kelas }} — {{ $gk->mapel->nama_mapel }}
                        </option>
                    @endforeach
                </select>
                <input type="hidden" name="mapel_id" id="mapelInput2" value="{{ old('mapel_id', $tuga->mapel_id) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi', $tuga->deskripsi) }}</textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Lampiran Soal (Opsional)</label>
                <select name="tipe" class="form-control mb-2" id="tipeSelect">
                    <option value="" {{ old('tipe', $tuga->tipe) == '' ? 'selected' : '' }}>Tanpa Lampiran</option>
                    <option value="file" {{ old('tipe', $tuga->tipe) == 'file' ? 'selected' : '' }}>Upload File (PDF, Docx, dsb)</option>
                    <option value="link" {{ old('tipe', $tuga->tipe) == 'link' ? 'selected' : '' }}>Link Eksternal (Google Drive, dsb)</option>
                </select>
            </div>

            <div id="fileInputWrapper" class="form-group {{ old('tipe', $tuga->tipe) == 'file' ? '' : 'hidden' }}">
                @if($tuga->tipe == 'file' && $tuga->soal_storage_path)
                    <div style="margin-bottom: 12px; padding: 10px; background: #F0F9FF; border-radius: 8px; border: 1px solid #BAE6FD; font-size: 13px;">
                        <i class="fas fa-file-alt"></i> File saat ini: <strong>{{ $tuga->soal_original_filename }}</strong>
                    </div>
                @endif
                <label class="form-label">Ganti File (Kosongkan jika tidak ingin mengubah)</label>
                <input type="file" name="file" class="form-control" accept=".pdf,.docx,.pptx,.doc,.jpg,.jpeg,.png">
                <small class="text-muted">Maksimal 1MB. Format: pdf, docx, doc, jpg, png</small>
            </div>

            <div id="linkInputWrapper" class="form-group {{ old('tipe', $tuga->tipe) == 'link' ? '' : 'hidden' }}">
                <label class="form-label">Masukkan Link URL</label>
                <input type="url" name="link_url" class="form-control" placeholder="https://..." value="{{ old('link_url', $tuga->tipe == 'link' ? $tuga->soal_storage_path : '') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Deadline</label>
                <input type="text" name="deadline" id="deadlinePicker" class="form-control" value="{{ old('deadline', $tuga->deadline->format('Y-m-d H:i')) }}" placeholder="Pilih Tanggal & Waktu" required>
            </div>
            
            <div class="flex gap-2" style="margin-top: 24px;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                <a href="{{ route('guru.tugas.index') }}" class="btn btn-outline">Batal</a>
            </div>
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

    function updateVisibility() {
        fileWrapper.classList.add('hidden');
        linkWrapper.classList.add('hidden');
        
        if (tipeSelect.value === 'file') {
            fileWrapper.classList.remove('hidden');
        } else if (tipeSelect.value === 'link') {
            linkWrapper.classList.remove('hidden');
        }
    }

    tipeSelect.addEventListener('change', updateVisibility);

    flatpickr("#deadlinePicker", {
        enableTime: true,
        time_24hr: true,
        dateFormat: "Y-m-d H:i",
        minDate: "today",
        locale: "id",
    });
</script>
@endpush
