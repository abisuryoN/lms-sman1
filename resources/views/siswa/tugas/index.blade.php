@extends('layouts.app')
@section('title', 'Tugas')
@section('page-title', 'Daftar Tugas')
@section('content')
<div class="card">
    <div class="card-header responsive-header">
        <h3>Tugas Saya</h3>
    </div>
    <div class="card-body">
        <div class="desktop-table table-wrapper">
            <table>
                <thead><tr><th>Tugas</th><th>Mapel</th><th>Deadline</th><th>Status</th><th style="text-align: center;">Aksi</th></tr></thead>
                <tbody>
                @forelse($tugas as $t)
                    <tr>
                        <td><strong>{{ $t->judul }}</strong></td>
                        <td>{{ $t->mapel->nama_mapel }}</td>
                        <td>{{ $t->deadline->format('d M Y H:i') }}</td>
                        <td>
                            @if(in_array($t->id, $submitted))<span class="badge badge-green">Sudah Dikumpulkan</span>
                            @elseif($t->isExpired())<span class="badge badge-red">Terlambat</span>
                            @else<span class="badge badge-yellow">Belum Dikerjakan</span>@endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 6px; align-items: center; justify-content: center;">
                                <a href="{{ route('siswa.tugas.show', $t) }}" class="btn btn-primary btn-sm" title="Buka Tugas"><i class="fas fa-arrow-right"></i></a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center" style="padding:32px;color:var(--text-muted)">Belum ada tugas.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mobile-cards">
            @forelse($tugas as $t)
                <div class="mobile-card">
                    <div class="mobile-card-title">{{ $t->judul }}</div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Mapel</span>
                        <span class="mobile-card-value">{{ $t->mapel->nama_mapel }}</span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Deadline</span>
                        <span class="mobile-card-value" style="font-size: 11px;">{{ $t->deadline->format('d M Y H:i') }}</span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Status</span>
                        <span class="mobile-card-value">
                            @if(in_array($t->id, $submitted))<span class="badge badge-green">Sudah Dikumpulkan</span>
                            @elseif($t->isExpired())<span class="badge badge-red">Terlambat</span>
                            @else<span class="badge badge-yellow">Belum Dikerjakan</span>@endif
                        </span>
                    </div>

                    <div class="mobile-card-actions">
                        <a href="{{ route('siswa.tugas.show', $t) }}" class="btn btn-primary btn-sm"><i class="fas fa-arrow-right"></i> Buka Tugas</a>
                    </div>
                </div>
            @empty
                <div class="text-center" style="padding:32px;color:var(--text-muted)">Belum ada tugas.</div>
            @endforelse
        </div>
        <div class="pagination">{{ $tugas->links() }}</div>
    </div>
</div>
@endsection
