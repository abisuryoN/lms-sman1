@extends('layouts.app')
@section('title', 'Nilai')
@section('page-title', 'Nilai Saya')
@section('content')
<div class="card">
    <div class="card-header"><h3>Riwayat Nilai</h3></div>
    <div class="card-body table-wrapper">
        <table>
            <thead><tr><th>No</th><th>Tugas</th><th>Mapel</th><th>Nilai</th><th>Komentar</th></tr></thead>
            <tbody>
            @forelse($nilai as $i => $n)
                <tr>
                    <td>{{ $nilai->firstItem() + $i }}</td>
                    <td><strong>{{ $n->tugas->judul }}</strong></td>
                    <td>{{ $n->tugas->mapel->nama_mapel ?? '-' }}</td>
                    <td><span style="font-size:18px;font-weight:700;color:{{ $n->nilai >= 75 ? 'var(--success)' : ($n->nilai >= 50 ? 'var(--warning)' : 'var(--danger)') }}">{{ $n->nilai }}</span></td>
                    <td>{{ $n->komentar ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center" style="padding:32px;color:var(--text-muted)">Belum ada nilai.</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="pagination">{{ $nilai->links('pagination.simple') }}</div>
    </div>
</div>
@endsection
