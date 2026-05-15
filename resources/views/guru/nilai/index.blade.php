@extends('layouts.app')
@section('title', 'Penilaian')
@section('page-title', 'Penilaian')
@section('content')
<div class="card">
    <div class="card-header responsive-header">
        <h3>Pilih Tugas untuk Penilaian</h3>
    </div>
    <div class="card-body">
        <div class="desktop-table table-wrapper">
            <table>
                <thead><tr><th>Tugas</th><th>Kelas</th><th>Mapel</th><th style="text-align: center;">Aksi</th></tr></thead>
                <tbody>
                @forelse($tugas as $t)
                    <tr>
                        <td>{{ $t->judul }}</td>
                        <td>{{ $t->kelas->nama_kelas }}</td>
                        <td>{{ $t->mapel->nama_mapel }}</td>
                        <td>
                            <div style="display: flex; gap: 6px; align-items: center; justify-content: center;">
                                <a href="{{ route('guru.nilai.edit', $t) }}" class="btn btn-primary btn-sm" title="Beri Nilai"><i class="fas fa-star"></i> Beri Nilai</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center" style="padding:24px;color:var(--text-muted)">Belum ada tugas.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mobile-cards">
            @forelse($tugas as $t)
                <div class="mobile-card">
                    <div class="mobile-card-title">{{ $t->judul }}</div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Kelas</span>
                        <span class="mobile-card-value">{{ $t->kelas->nama_kelas }}</span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Mapel</span>
                        <span class="mobile-card-value">{{ $t->mapel->nama_mapel }}</span>
                    </div>

                    <div class="mobile-card-actions">
                        <a href="{{ route('guru.nilai.edit', $t) }}" class="btn btn-primary btn-sm"><i class="fas fa-star"></i> Beri Nilai</a>
                    </div>
                </div>
            @empty
                <div class="text-center" style="padding:24px;color:var(--text-muted)">Belum ada tugas.</div>
            @endforelse
        </div>
        {{ $tugas->links() }}
    </div>
</div>
@endsection
