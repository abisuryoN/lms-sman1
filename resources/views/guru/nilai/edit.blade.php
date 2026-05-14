@extends('layouts.app')
@section('title', 'Beri Nilai')
@section('page-title', 'Beri Nilai — '.$tuga->judul)
@section('content')
<div class="card">
    <div class="card-header"><h3>{{ $tuga->judul }} — {{ $tuga->kelas->nama_kelas }}</h3></div>
    <div class="card-body">
        <form action="{{ route('guru.nilai.update', $tuga) }}" method="POST">
            @csrf @method('PUT')
            <div class="table-wrapper">
                <table>
                    <thead><tr><th>Siswa</th><th>Jawaban</th><th>Nilai (0-100)</th><th>Komentar</th></tr></thead>
                    <tbody>
                    @forelse($jawaban as $j)
                        <tr>
                            <td>{{ $j->siswa->nama }}</td>
                            <td>
                                <div style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; font-size:13px;">{{ Str::limit($j->jawaban_text, 50) }}</div>
                                @if($j->file_path)
                                    <div style="margin-top:4px;">
                                        <a href="{{ Storage::url($j->file_path) }}" target="_blank" class="btn btn-outline btn-sm" style="padding:2px 8px; font-size:11px; border-color:var(--primary); color:var(--primary);">
                                            <i class="fas fa-file-pdf"></i> Lihat Dokumen
                                        </a>
                                    </div>
                                @endif
                            </td>
                            <td><input type="number" name="nilai[{{ $j->siswa_id }}]" class="form-control" style="width:100px" min="0" max="100" step="0.01" value="{{ $nilai[$j->siswa_id] ?? '' }}"></td>
                            <td><input type="text" name="komentar[{{ $j->siswa_id }}]" class="form-control" style="width:200px" placeholder="Komentar..."></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center" style="padding:24px;color:var(--text-muted)">Belum ada jawaban.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if($jawaban->count())<div style="margin-top:16px"><button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Nilai</button></div>@endif
        </form>
    </div>
</div>
@endsection
