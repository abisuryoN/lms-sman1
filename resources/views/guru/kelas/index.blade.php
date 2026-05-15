@extends('layouts.app')
@section('title', 'Data Kelas Diampu')
@section('page-title', 'Data Kelas')

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-school"></i> Daftar Kelas yang Anda Ampu</h3>
        @if($tahunAktif)
            <span class="badge badge-blue">Tahun Ajaran: {{ $tahunAktif->full_name }}</span>
        @endif
    </div>
    <div class="card-body">
        <div class="desktop-table table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th style="text-align: center; width: 5%;">No</th>
                        <th style="text-align: center;">Nama Kelas</th>
                        <th style="text-align: center;">Tahun Ajaran</th>
                        <th style="text-align: center;">Jumlah Siswa</th>
                        <th style="text-align: center; width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelasList as $i => $k)
                        <tr>
                            <td style="text-align: center;">{{ $kelasList->firstItem() + $i }}</td>
                            <td style="text-align: center;"><strong>{{ $k->nama_kelas }}</strong></td>
                            <td style="text-align: center;">{{ $k->tahunAjaran->full_name ?? '-' }}</td>
                            <td style="text-align: center;"><span class="badge badge-blue">{{ $k->siswa_count }} Siswa</span></td>
                            <td>
                                <div style="display: flex; gap: 6px; align-items: center; justify-content: center;">
                                    <a href="{{ route('guru.kelas.show', $k) }}" class="btn btn-primary btn-sm" title="Lihat Daftar Siswa">
                                        <i class="fas fa-users"></i> Lihat Siswa
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center" style="padding:32px;color:var(--text-muted)">
                                Belum ada data kelas yang Anda ampu di tahun ajaran ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mobile-cards">
            @forelse($kelasList as $k)
                <div class="mobile-card">
                    <div class="mobile-card-title">{{ $k->nama_kelas }}</div>
                    
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Tahun Ajaran</span>
                        <span class="mobile-card-value">{{ $k->tahunAjaran->full_name ?? '-' }}</span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Jumlah Siswa</span>
                        <span class="mobile-card-value"><span class="badge badge-blue">{{ $k->siswa_count }} Siswa</span></span>
                    </div>

                    <div class="mobile-card-actions">
                        <a href="{{ route('guru.kelas.show', $k) }}" class="btn btn-primary btn-sm" title="Lihat Daftar Siswa">
                            <i class="fas fa-users"></i> Lihat Siswa
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center" style="padding:32px;color:var(--text-muted)">Belum ada data kelas yang Anda ampu di tahun ajaran ini.</div>
            @endforelse
        </div>
        {{ $kelasList->links() }}
    </div>
</div>
@endsection
