@extends('layouts.app')
@section('title', 'Daftar Tugas')
@section('page-title', 'Daftar Tugas')
@section('content')
<div class="card">
    <div class="card-header responsive-header">
        <h3>Tugas Saya</h3>
        <div class="header-actions">
            <a href="{{ route('guru.tugas.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Buat Tugas</a>
        </div>
    </div>
    <div class="card-body">
        <div class="desktop-table table-wrapper">
            <table>
                <thead><tr><th>Judul</th><th style="text-align: center;">Kelas</th><th style="text-align: center;">Mapel</th><th style="text-align: center;">Deadline</th><th style="text-align: center;">Dikumpulkan</th><th style="text-align: center;">Status</th><th style="text-align: center;">Aksi</th></tr></thead>
                <tbody>
                @forelse($tugas as $t)
                    <tr>
                        <td><a href="{{ route('guru.tugas.show', $t) }}" style="color:var(--primary);text-decoration:none;font-weight:500">{{ $t->judul }}</a></td>
                        <td style="text-align: center;">{{ $t->kelas->nama_kelas }}</td>
                        <td style="text-align: center;">{{ $t->mapel->nama_mapel }}</td>
                        <td style="text-align: center;">{{ $t->deadline->format('d M Y H:i') }}</td>
                        <td style="text-align: center;"><span class="badge badge-blue">{{ $t->jawaban_tugas_count }}</span></td>
                        <td style="text-align: center;"><span class="badge {{ $t->isExpired() ? 'badge-red' : 'badge-green' }}">{{ $t->isExpired() ? 'Berakhir' : 'Aktif' }}</span></td>
                        <td>
                            <div style="display: flex; gap: 6px; align-items: center; justify-content: center;">
                                <a href="{{ route('guru.tugas.show', $t) }}" class="btn btn-outline btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('guru.tugas.edit', $t) }}" class="btn btn-primary btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="{{ route('guru.nilai.edit', $t) }}" class="btn btn-warning btn-sm" title="Nilai"><i class="fas fa-star"></i></a>
                                <a href="{{ route('guru.similarity.detail', $t) }}" class="btn btn-outline btn-sm" title="Similarity" style="background:#fff; border:1px solid #E2E8F0;"><i class="fas fa-search-plus"></i></a>
                                <form action="{{ route('guru.tugas.destroy', $t) }}" method="POST" onsubmit="return confirm('Hapus tugas ini? Semua jawaban siswa juga akan terhapus.')" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center" style="padding:32px;color:var(--text-muted)">Belum ada tugas.</td></tr>
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
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Deadline</span>
                        <span class="mobile-card-value" style="font-size: 11px;">{{ $t->deadline->format('d M Y H:i') }}</span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Dikumpulkan</span>
                        <span class="mobile-card-value"><span class="badge badge-blue">{{ $t->jawaban_tugas_count }}</span></span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Status</span>
                        <span class="mobile-card-value"><span class="badge {{ $t->isExpired() ? 'badge-red' : 'badge-green' }}">{{ $t->isExpired() ? 'Berakhir' : 'Aktif' }}</span></span>
                    </div>

                    <div class="mobile-card-actions">
                        <a href="{{ route('guru.tugas.show', $t) }}" class="btn btn-outline btn-sm"><i class="fas fa-eye"></i> Detail</a>
                        <a href="{{ route('guru.tugas.edit', $t) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edit</a>
                        <a href="{{ route('guru.nilai.edit', $t) }}" class="btn btn-warning btn-sm"><i class="fas fa-star"></i> Nilai</a>
                        <a href="{{ route('guru.similarity.detail', $t) }}" class="btn btn-outline btn-sm" style="background:#fff; border:1px solid #E2E8F0;"><i class="fas fa-search-plus"></i> Sim</a>
                        <form action="{{ route('guru.tugas.destroy', $t) }}" method="POST" onsubmit="return confirm('Hapus tugas ini?')" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button>
                        </form>
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
