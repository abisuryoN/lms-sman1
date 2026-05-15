@extends('layouts.app')
@section('title', 'Materi Pembelajaran')
@section('page-title', 'Materi Pembelajaran')
@section('content')
<div class="card">
    <div class="card-header responsive-header">
        <h3>Daftar Materi</h3>
        <div class="header-actions">
            <a href="{{ route('guru.materi.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Upload Materi</a>
        </div>
    </div>
    <div class="card-body">
        <div class="desktop-table table-wrapper">
            <table>
                <thead><tr><th>Judul</th><th style="text-align: center;">Kelas</th><th style="text-align: center;">Mapel</th><th style="text-align: center;">Ukuran</th><th style="text-align: center;">Tipe</th><th style="text-align: center;">Aksi</th></tr></thead>
                <tbody>
                @forelse($materi as $m)
                    <tr>
                        <td>
                            <div style="font-weight: 600; color: var(--text-main);">{{ $m->judul }}</div>
                            <div style="font-size: 11px; color: var(--text-muted);">{{ $m->created_at->format('d M Y H:i') }}</div>
                        </td>
                        <td style="text-align: center;">{{ $m->kelas->nama_kelas }}</td>
                        <td style="text-align: center;">{{ $m->mapel->nama_mapel }}</td>
                        <td style="text-align: center;"><span style="font-size: 12px; color: var(--text-secondary);">{{ $m->tipe === 'file' ? $m->file_size_human : '-' }}</span></td>
                        <td style="text-align: center;"><span class="badge {{ $m->tipe === 'file' ? 'badge-blue' : 'badge-green' }}">{{ ucfirst($m->tipe) }}</span></td>
                        <td>
                            <div style="display: flex; gap: 6px; align-items: center; justify-content: center;">
                                <a href="{{ route('guru.materi.logs', $m) }}" class="btn btn-outline btn-sm" title="Statistik Pengunduh">
                                    <i class="fas fa-chart-line"></i> <span style="font-size: 11px; margin-left: 4px;">{{ $m->logs_count }}</span>
                                </a>
                                @if($m->tipe === 'link')
                                    <a href="{{ $m->storage_path }}" target="_blank" class="btn btn-outline btn-sm" title="Buka Link"><i class="fas fa-external-link-alt"></i></a>
                                @else
                                    <a href="{{ $m->file_full_url }}" target="_blank" class="btn btn-outline btn-sm" title="Lihat File"><i class="fas fa-eye"></i></a>
                                    <a href="{{ $m->download_url }}" class="btn btn-primary btn-sm" title="Download File"><i class="fas fa-download"></i></a>
                                @endif
                                <form action="{{ route('guru.materi.destroy', $m) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus materi ini?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></button></form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center" style="padding:32px;color:var(--text-muted)">Belum ada materi.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mobile-cards">
            @forelse($materi as $m)
                <div class="mobile-card">
                    <div class="mobile-card-title">{{ $m->judul }}</div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Kelas</span>
                        <span class="mobile-card-value">{{ $m->kelas->nama_kelas }}</span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Mapel</span>
                        <span class="mobile-card-value">{{ $m->mapel->nama_mapel }}</span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Tipe / Ukuran</span>
                        <span class="mobile-card-value">
                            <span class="badge {{ $m->tipe === 'file' ? 'badge-blue' : 'badge-green' }}">{{ ucfirst($m->tipe) }}</span>
                            @if($m->tipe === 'file') <span style="font-size: 11px; color: var(--text-muted);">({{ $m->file_size_human }})</span> @endif
                        </span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Tanggal</span>
                        <span class="mobile-card-value" style="font-size: 11px;">{{ $m->created_at->format('d M Y H:i') }}</span>
                    </div>

                    <div class="mobile-card-actions">
                        <a href="{{ route('guru.materi.logs', $m) }}" class="btn btn-outline btn-sm">
                            <i class="fas fa-chart-line"></i> Logs ({{ $m->logs_count }})
                        </a>
                        @if($m->tipe === 'link')
                            <a href="{{ $m->storage_path }}" target="_blank" class="btn btn-outline btn-sm"><i class="fas fa-external-link-alt"></i> Buka Link</a>
                        @else
                            <a href="{{ $m->file_full_url }}" target="_blank" class="btn btn-outline btn-sm"><i class="fas fa-eye"></i> Lihat</a>
                            <a href="{{ $m->download_url }}" class="btn btn-primary btn-sm"><i class="fas fa-download"></i> Unduh</a>
                        @endif
                        <form action="{{ route('guru.materi.destroy', $m) }}" method="POST" onsubmit="return confirm('Hapus materi ini?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button></form>
                    </div>
                </div>
            @empty
                <div class="text-center" style="padding:32px;color:var(--text-muted)">Belum ada materi.</div>
            @endforelse
        </div>
        <div class="pagination">{{ $materi->links() }}</div>
    </div>
</div>
@endsection
