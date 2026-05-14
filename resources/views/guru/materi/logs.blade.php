@extends('layouts.app')
@section('title', 'Log Pengunduh Materi')
@section('page-title', 'Log Pengunduh Materi')
@section('content')
<div class="materi-log-container">
    <div class="section-header" style="margin-bottom: 24px;">
        <a href="{{ route('guru.materi.index') }}" style="display: inline-flex; align-items: center; color: #64748B; text-decoration: none; font-size: 14px; font-weight: 500; margin-bottom: 8px; gap: 6px;">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Daftar Materi</span>
        </a>
        <h2 style="font-size: 24px; font-weight: 700; color: #0F172A;">Riwayat Download: {{ $materi->judul }}</h2>
        <p style="color: #64748B; margin-top: 4px;">Daftar siswa yang telah mengunduh materi ini.</p>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Riwayat Download: {{ $materi->judul }}</h3>
        </div>
        <div class="card-body table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th class="text-right">Waktu Download</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>
                            <strong>{{ $log->siswa->nama }}</strong>
                        </td>
                        <td>{{ $log->siswa->kelas->nama_kelas }}</td>
                        <td class="text-right">
                            {{ $log->created_at->format('d M Y, H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center" style="padding:48px; color:var(--text-muted);">
                            Belum ada siswa yang mengunduh materi ini.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
