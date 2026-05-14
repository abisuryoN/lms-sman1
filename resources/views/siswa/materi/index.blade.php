@extends('layouts.app')
@section('title', 'Materi')
@section('page-title', 'Materi Pembelajaran')
@section('content')
<div class="materi-container">
    @if(!isset($selectedGuru))
        {{-- View Daftar Guru --}}
        <div class="section-header" style="margin-bottom: 24px;">
            <h2 style="font-size: 24px; font-weight: 700; color: #0F172A;">Pilih Guru Pengajar</h2>
            <p style="color: #64748B; margin-top: 4px;">Pilih guru untuk melihat materi yang telah diunggah.</p>
        </div>

        <div class="teacher-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
            @forelse($teachers as $guru)
                <a href="{{ route('siswa.materi.index', ['guru_id' => $guru->id]) }}" class="teacher-card" style="text-decoration: none; display: block;">
                    <div class="card teacher-item-card" style="transition: all 0.3s ease; border: 1px solid #F1F5F9; border-radius: 24px; overflow: hidden; background: #FFFFFF;">
                        <div class="card-body" style="padding: 24px; text-align: center;">
                            <div class="teacher-avatar-box" style="width: 80px; height: 80px; margin: 0 auto 16px; border-radius: 50%; overflow: hidden; background: #F8FAFC; display: flex; align-items: center; justify-content: center; border: 3px solid #F1F5F9;">
                                @if($guru->user->photo_url)
                                    <img src="{{ $guru->user->photo_url }}" alt="{{ $guru->nama }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <i class="fas fa-chalkboard-teacher" style="font-size: 32px; color: #94A3B8;"></i>
                                @endif
                            </div>
                            <h4 style="font-size: 18px; font-weight: 700; color: #1E293B; margin-bottom: 4px;">{{ $guru->nama }}</h4>
                            <p style="font-size: 13px; color: #64748B; margin-bottom: 4px;">{{ $guru->telepon ?? 'No HP tidak tersedia' }}</p>
                            <div class="teacher-subjects" style="font-size: 12px; font-weight: 600; color: #3B82F6; background: #EFF6FF; display: inline-block; padding: 2px 10px; border-radius: 20px; margin-bottom: 16px;">
                                {{ $guru->materi->pluck('mapel.nama_mapel')->unique()->implode(', ') }}
                            </div>
                            
                            <div class="view-btn" style="display: inline-flex; align-items: center; justify-content: center; padding: 8px 16px; background: #EFF6FF; color: #3B82F6; border-radius: 12px; font-weight: 600; font-size: 13px; gap: 8px;">
                                <span>Lihat Materi</span>
                                <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="empty-state" style="grid-column: 1 / -1; padding: 60px 20px; text-align: center; background: #F8FAFC; border-radius: 24px; border: 2px dashed #E2E8F0;">
                    <i class="fas fa-user-slash" style="font-size: 48px; color: #CBD5E1; margin-bottom: 16px;"></i>
                    <h3 style="color: #64748B; font-weight: 600;">Belum ada materi tersedia</h3>
                    <p style="color: #94A3B8; margin-top: 8px;">Guru-guru pengajar belum mengunggah materi untuk kelas Anda.</p>
                </div>
            @endforelse
        </div>
    @else
        {{-- View Daftar Materi per Guru --}}
        <div class="section-header" style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: flex-end;">
            <div>
                <a href="{{ route('siswa.materi.index') }}" style="display: inline-flex; align-items: center; color: #64748B; text-decoration: none; font-size: 14px; font-weight: 500; margin-bottom: 8px; gap: 6px;">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Daftar Guru</span>
                </a>
                <h2 style="font-size: 24px; font-weight: 700; color: #0F172A;">Materi dari {{ $selectedGuru->nama }}</h2>
            </div>
        </div>

        <div class="card" style="border-radius: 24px; overflow: hidden; border: 1px solid #F1F5F9;">
            <div class="card-body table-wrapper" style="padding: 0;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #F8FAFC;">
                            <th style="text-align: left; padding: 16px 24px; color: #64748B; font-weight: 600; font-size: 13px; border-bottom: 1px solid #F1F5F9;">JUDUL MATERI</th>
                            <th style="text-align: left; padding: 16px 24px; color: #64748B; font-weight: 600; font-size: 13px; border-bottom: 1px solid #F1F5F9;">MATA PELAJARAN</th>
                            <th style="text-align: center; padding: 16px 24px; color: #64748B; font-weight: 600; font-size: 13px; border-bottom: 1px solid #F1F5F9;">UKURAN</th>
                            <th style="text-align: center; padding: 16px 24px; color: #64748B; font-weight: 600; font-size: 13px; border-bottom: 1px solid #F1F5F9;">TIPE</th>
                            <th style="text-align: left; padding: 16px 24px; color: #64748B; font-weight: 600; font-size: 13px; border-bottom: 1px solid #F1F5F9;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($materi as $m)
                        <tr style="border-bottom: 1px solid #F8FAFC;">
                            <td style="padding: 16px 24px;">
                                <div style="font-weight: 700; color: #1E293B;">{{ $m->judul }}</div>
                                @if($m->deskripsi)
                                    <div style="font-size: 12px; color: #94A3B8; margin-top: 4px;">{{ Str::limit($m->deskripsi, 60) }}</div>
                                @endif
                                <div style="font-size: 11px; color: #CBD5E1; margin-top: 2px;">{{ $m->created_at->format('d M Y') }}</div>
                            </td>
                            <td style="padding: 16px 24px; color: #475569; font-weight: 500;">{{ $m->mapel->nama_mapel }}</td>
                            <td style="padding: 16px 24px; text-align: center; color: #64748B; font-size: 12px;">{{ $m->tipe === 'file' ? $m->file_size_human : '-' }}</td>
                            <td style="padding: 16px 24px; text-align: center;">
                                <span style="display: inline-block; padding: 4px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; text-transform: uppercase; {{ $m->tipe === 'file' ? 'background: #EFF6FF; color: #3B82F6;' : 'background: #ECFDF5; color: #10B981;' }}">
                                    {{ $m->tipe }}
                                </span>
                            </td>
                            <td style="padding: 16px 24px; text-align: right;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    @if($m->tipe === 'link')
                                        <a href="{{ route('siswa.materi.download', $m) }}" target="_blank" class="btn btn-primary btn-sm" style="border-radius: 10px; padding: 6px 12px; font-size: 12px;">
                                            <i class="fas fa-external-link-alt"></i> Buka
                                        </a>
                                    @else
                                        <a href="{{ $m->file_full_url }}" target="_blank" class="btn btn-outline btn-sm" style="border-radius: 10px; padding: 6px 12px; font-size: 12px;" title="Pratinjau">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                        <a href="{{ route('siswa.materi.download', $m) }}" class="btn btn-primary btn-sm" style="border-radius: 10px; padding: 6px 12px; font-size: 12px;" title="Unduh File">
                                            <i class="fas fa-download"></i> Unduh
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 48px 24px; text-align: center; color: #94A3B8;">
                                Belum ada materi dari guru ini.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                @if($materi->hasPages())
                    <div class="pagination" style="padding: 16px 24px; border-top: 1px solid #F1F5F9;">
                        {{ $materi->appends(['guru_id' => $selectedGuru->id])->links('pagination.simple') }}
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

<style>
    .teacher-item-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px -10px rgba(0,0,0,0.1);
        border-color: #3B82F6 !important;
    }
    .teacher-item-card:hover .teacher-avatar-box {
        border-color: #3B82F6 !important;
    }
    .teacher-item-card:hover .view-btn {
        background: #3B82F6 !important;
        color: #FFFFFF !important;
    }
</style>
@endsection
