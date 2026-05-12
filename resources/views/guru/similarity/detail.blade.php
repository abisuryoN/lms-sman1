@extends('layouts.app')
@section('title', 'Hasil Similarity')
@section('page-title', 'Hasil Similarity — '.$tuga->judul)
@section('content')
<div class="card mb-4">
    <div class="card-header">
        <h3>{{ $tuga->judul }} — {{ $tuga->kelas->nama_kelas ?? '' }}</h3>
        <form action="{{ route('guru.similarity.run', $tuga) }}" method="POST">@csrf<button class="btn btn-primary btn-sm"><i class="fas fa-sync-alt"></i> Re-Analisis</button></form>
    </div>
    <div class="card-body table-wrapper">
        @if($results->count() > 0)
        <table>
            <thead><tr><th>Siswa 1</th><th>Siswa 2</th><th>Similarity</th><th>Status</th></tr></thead>
            <tbody>
            @foreach($results as $r)
                <tr>
                    <td>{{ $r->jawaban1->siswa->nama ?? '-' }}</td>
                    <td>{{ $r->jawaban2->siswa->nama ?? '-' }}</td>
                    <td><strong style="font-size:16px">{{ $r->similarity_percentage }}%</strong></td>
                    <td>
                        @if($r->status === 'plagiat')<span class="badge badge-red">🔴 Terindikasi Plagiarisme</span>
                        @elseif($r->status === 'warning')<span class="badge badge-yellow">🟡 Perlu Review</span>
                        @else<span class="badge badge-green">🟢 Aman</span>@endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @else
            <div style="text-align:center;padding:40px;color:var(--text-muted)">
                <i class="fas fa-search" style="font-size:40px;margin-bottom:12px;display:block"></i>
                <p>Belum ada hasil analisis. Klik tombol Analisis untuk memulai.</p>
            </div>
        @endif
    </div>
</div>
@endsection
