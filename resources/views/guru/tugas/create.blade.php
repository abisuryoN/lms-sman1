@extends('layouts.app')
@section('title', 'Buat Tugas')
@section('page-title', 'Buat Tugas Baru')
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header"><h3>Form Buat Tugas</h3></div>
    <div class="card-body">
        <form action="{{ route('guru.tugas.store') }}" method="POST">
            @csrf
            <div class="form-group"><label class="form-label">Judul Tugas</label><input type="text" name="judul" class="form-control" value="{{ old('judul') }}" required></div>
            <div class="form-group"><label class="form-label">Kelas & Mapel</label><select name="kelas_id" class="form-control" required id="kelasSelect2"><option value="">Pilih</option>@foreach($guruKelas as $gk)<option value="{{ $gk->kelas_id }}" data-mapel="{{ $gk->mapel_id }}">{{ $gk->kelas->nama_kelas }} — {{ $gk->mapel->nama_mapel }}</option>@endforeach</select><input type="hidden" name="mapel_id" id="mapelInput2"></div>
            <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="deskripsi" class="form-control">{{ old('deskripsi') }}</textarea></div>
            <div class="form-group"><label class="form-label">Deadline</label><input type="datetime-local" name="deadline" class="form-control" value="{{ old('deadline') }}" required></div>
            <div class="flex gap-2"><button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Buat Tugas</button><a href="{{ route('guru.tugas.index') }}" class="btn btn-outline">Batal</a></div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>document.getElementById('kelasSelect2').addEventListener('change', function(){ document.getElementById('mapelInput2').value = this.selectedOptions[0]?.dataset?.mapel || ''; });</script>
@endpush
