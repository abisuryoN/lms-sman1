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
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kelas</th>
                        <th>Tahun Ajaran</th>
                        <th>Jumlah Siswa</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelasList as $i => $k)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><strong>{{ $k->nama_kelas }}</strong></td>
                            <td>{{ $k->tahunAjaran->full_name ?? '-' }}</td>
                            <td><span class="badge badge-blue">{{ $k->siswa_count }} Siswa</span></td>
                            <td>
                                <a href="{{ route('guru.kelas.show', $k) }}" class="btn btn-primary btn-sm" title="Lihat Daftar Siswa">
                                    <i class="fas fa-users"></i> Lihat Siswa
                                </a>
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
    </div>
</div>
@endsection
