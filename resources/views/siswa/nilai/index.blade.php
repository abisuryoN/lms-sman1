@extends('layouts.app')
@section('title', 'Nilai')
@section('page-title', 'Nilai Saya')
@section('content')
<div class="card">
    <div class="card-header responsive-header">
        <h3>Riwayat Nilai</h3>
    </div>
    <div class="card-body">
        <div class="desktop-table table-wrapper">
            <table>
                <thead><tr><th style="width: 50px;">No</th><th>Tugas</th><th>Mapel</th><th style="text-align: center;">Nilai</th><th>Komentar</th></tr></thead>
                <tbody>
                @forelse($nilai as $i => $n)
                    <tr>
                        <td>{{ $nilai->firstItem() + $i }}</td>
                        <td><strong>{{ $n->tugas->judul }}</strong></td>
                        <td>{{ $n->tugas->mapel->nama_mapel ?? '-' }}</td>
                        <td style="text-align: center;"><span style="font-size:18px;font-weight:700;color:{{ $n->nilai >= 75 ? 'var(--success)' : ($n->nilai >= 50 ? 'var(--warning)' : 'var(--danger)') }}">{{ $n->nilai }}</span></td>
                        <td>{{ $n->komentar ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center" style="padding:32px;color:var(--text-muted)">Belum ada nilai.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mobile-cards">
            @forelse($nilai as $n)
                <div class="mobile-card">
                    <div class="mobile-card-title">{{ $n->tugas->judul }}</div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Mapel</span>
                        <span class="mobile-card-value">{{ $n->tugas->mapel->nama_mapel ?? '-' }}</span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Nilai</span>
                        <span class="mobile-card-value">
                            <span style="font-size:18px;font-weight:700;color:{{ $n->nilai >= 75 ? 'var(--success)' : ($n->nilai >= 50 ? 'var(--warning)' : 'var(--danger)') }}">
                                {{ $n->nilai }}
                            </span>
                        </span>
                    </div>
                    <div class="mobile-card-row" style="flex-direction: column; align-items: flex-start; gap: 4px;">
                        <span class="mobile-card-label">Komentar Guru</span>
                        <span class="mobile-card-value" style="text-align: left; max-width: 100%; font-weight: normal; font-style: italic; color: var(--text-muted);">
                            {{ $n->komentar ?? 'Tidak ada komentar' }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center" style="padding:32px;color:var(--text-muted)">Belum ada nilai.</div>
            @endforelse
        </div>
        <div class="pagination">{{ $nilai->links() }}</div>
    </div>
</div>
@endsection
