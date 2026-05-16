@extends('layouts.app')
@section('title', 'Detail Mapel - ' . $mapel->nama_mapel)
@section('page-title', 'Detail Mata Pelajaran')
@section('content')
<div class="mb-4">
    <a href="{{ route('admin.mapel.index') }}" class="btn btn-outline" style="background:#fff;border:1px solid #E2E8F0"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

{{-- Header Info Card --}}
<div class="card mb-4">
    <div class="card-body responsive-flex-card" style="display: flex; align-items: center; gap: 16px; padding: 16px;">
        <div class="header-icon-box" style="width: 52px; height: 52px; background: linear-gradient(135deg, #3B82F6, #1D4ED8); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <i class="fas fa-book" style="font-size: 24px; color: #fff;"></i>
        </div>
        <div style="flex: 1;">
            <h2 style="font-size: 18px; font-weight: 800; color: #0F172A; margin-bottom: 2px;">{{ $mapel->nama_mapel }}</h2>
            <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                <span class="badge badge-blue" style="font-size: 10px; padding: 2px 8px;">{{ $mapel->kode_mapel }}</span>
                @if($mapel->tingkat)
                    <span class="badge badge-green" style="font-size: 10px; padding: 2px 8px;">Kelas {{ $mapel->tingkat }}</span>
                @else
                    <span class="badge badge-purple" style="font-size: 10px; padding: 2px 8px;">Semua Tingkat</span>
                @endif
            </div>
        </div>
        <div class="desktop-only">
            <a href="{{ route('admin.mapel.edit', $mapel) }}" class="btn btn-warning btn-sm" title="Edit Mapel"><i class="fas fa-edit"></i> Edit</a>
        </div>
    </div>
</div>

{{-- Tingkat Selector (Only for "Semua Tingkat" mapel) --}}
@if(!$mapel->tingkat)
<div class="card mb-4">
    <div class="card-header">
        <h3><i class="fas fa-layer-group"></i> Pilih Tingkat Kelas</h3>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
            @foreach(['10', '11', '12'] as $t)
                <a href="{{ route('admin.mapel.show', [$mapel, 'tingkat' => $t]) }}" 
                   style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 16px 8px; border-radius: 12px; text-decoration: none; transition: all 0.2s ease; border: 2px solid {{ $selectedTingkat == $t ? '#3B82F6' : '#E2E8F0' }}; background: {{ $selectedTingkat == $t ? 'linear-gradient(135deg, #EFF6FF, #DBEAFE)' : '#FFFFFF' }};">
                    <div style="width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; margin-bottom: 6px; background: {{ $selectedTingkat == $t ? '#3B82F6' : '#F1F5F9' }}; color: {{ $selectedTingkat == $t ? '#fff' : '#64748B' }};">
                        <i class="fas fa-school"></i>
                    </div>
                    <div style="font-weight: 700; font-size: 13px; color: {{ $selectedTingkat == $t ? '#1D4ED8' : '#0F172A' }};">Kelas {{ $t }}</div>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- Assignment Details Table --}}
@if($selectedTingkat)
<div class="card">
    <div class="card-header" style="flex-direction: column; align-items: stretch; gap: 8px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3><i class="fas fa-chalkboard-teacher"></i> Pengajar {{ $mapel->nama_mapel }} — Kelas {{ $selectedTingkat }}</h3>
            <span class="badge badge-blue" style="font-size: 12px;">{{ $assignments->count() }} Penugasan</span>
        </div>
    </div>
    <div class="card-body compact-mobile-body">
        @if($assignments->count() > 0)
            {{-- Desktop Table --}}
            <div class="desktop-table table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 40px;">No</th>
                            <th>Guru</th>
                            <th>Kelas</th>
                            <th>Hari</th>
                            <th>Jam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignments as $i => $a)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 32px; height: 32px; border-radius: 8px; background: #EFF6FF; color: #3B82F6; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        @if($a->guru && $a->guru->user && $a->guru->user->photo_url)
                                            <img src="{{ $a->guru->user->photo_url }}" alt="" style="width: 100%; height: 100%; border-radius: 8px; object-fit: cover;">
                                        @else
                                            <i class="fas fa-user" style="font-size: 14px;"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; color: #0F172A; font-size: 13px;">{{ $a->guru->nama ?? '-' }}</div>
                                        <div style="font-size: 10px; color: #64748B;">NIP: {{ $a->guru->nip ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-blue" style="font-size: 10px;">{{ $a->kelas->nama_kelas ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="badge badge-purple" style="font-size: 10px;">{{ $a->hari ?? '-' }}</span>
                            </td>
                            <td style="font-size: 12px; font-weight: 600;">
                                {{ $a->jam_mulai ? \Carbon\Carbon::parse($a->jam_mulai)->format('H:i') : '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards --}}
            <div class="mobile-only">
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    @foreach($assignments as $i => $a)
                        <div style="background: #F8FAFC; padding: 10px; border-radius: 12px; border: 1px solid #F1F5F9; display: flex; align-items: center; gap: 12px;">
                            <div style="width: 36px; height: 36px; border-radius: 10px; background: #FFFFFF; display: flex; align-items: center; justify-content: center; border: 1px solid #E2E8F0; flex-shrink: 0;">
                                @if($a->guru && $a->guru->user && $a->guru->user->photo_url)
                                    <img src="{{ $a->guru->user->photo_url }}" alt="" style="width: 100%; height: 100%; border-radius: 10px; object-fit: cover;">
                                @else
                                    <i class="fas fa-chalkboard-teacher" style="color: #3B82F6; font-size: 16px;"></i>
                                @endif
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 700; color: #0F172A; font-size: 13px;">{{ $a->guru->nama ?? '-' }}</div>
                                <div style="display: flex; gap: 6px; margin-top: 2px; flex-wrap: wrap;">
                                    <span class="badge badge-blue" style="font-size: 8px; padding: 1px 4px;">{{ $a->kelas->nama_kelas ?? '-' }}</span>
                                    <span class="badge badge-gray" style="font-size: 8px; padding: 1px 4px;">{{ $a->hari ?? '-' }}</span>
                                    <span style="font-size: 9px; color: #64748B; font-weight: 600;"><i class="far fa-clock"></i> {{ $a->jam_mulai ? \Carbon\Carbon::parse($a->jam_mulai)->format('H:i') : '' }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Summary Cards --}}
            <div class="summary-stats-grid mobile-mt-sm mobile-pt-sm" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin-top: 20px; padding-top: 20px; border-top: 1px solid #E2E8F0;">
                <div style="padding: 10px 4px; background: #EFF6FF; border-radius: 12px; text-align: center;">
                    <div style="font-size: 18px; font-weight: 800; color: #1D4ED8;">{{ $assignments->unique('guru_id')->count() }}</div>
                    <div style="font-size: 8px; font-weight: 700; text-transform: uppercase; color: #3B82F6; margin-top: 1px;">Guru</div>
                </div>
                <div style="padding: 10px 4px; background: #ECFDF5; border-radius: 12px; text-align: center;">
                    <div style="font-size: 18px; font-weight: 800; color: #065F46;">{{ $assignments->unique('kelas_id')->count() }}</div>
                    <div style="font-size: 8px; font-weight: 700; text-transform: uppercase; color: #10B981; margin-top: 1px;">Kelas</div>
                </div>
                <div style="padding: 10px 4px; background: #F5F3FF; border-radius: 12px; text-align: center;">
                    <div style="font-size: 18px; font-weight: 800; color: #5B21B6;">{{ $assignments->whereNotNull('hari')->count() }}</div>
                    <div style="font-size: 8px; font-weight: 700; text-transform: uppercase; color: #8B5CF6; margin-top: 1px;">Jadwal</div>
                </div>
            </div>
        @else
            <div style="padding: 48px; text-align: center; color: var(--text-muted);">
                <i class="fas fa-user-slash" style="font-size: 40px; display: block; margin-bottom: 16px; opacity: 0.15;"></i>
                <p style="font-weight: 600; font-size: 15px; margin-bottom: 4px;">Belum Ada Penugasan</p>
                <p style="font-size: 13px;">Belum ada guru yang ditugaskan mengajar <strong>{{ $mapel->nama_mapel }}</strong> di kelas {{ $selectedTingkat }}.</p>
                <a href="{{ route('admin.guru-kelas.index') }}" class="btn btn-primary btn-sm" style="margin-top: 12px;" title="Assign Guru Baru">
                    <i class="fas fa-plus"></i> Assign Guru
                </a>
            </div>
        @endif
    </div>
</div>
@elseif($mapel->tingkat)
    {{-- This shouldn't happen since selectedTingkat defaults to mapel->tingkat for specific mapel --}}
@else
    <div class="card">
        <div class="card-body" style="padding: 48px; text-align: center; color: var(--text-muted);">
            <i class="fas fa-hand-pointer" style="font-size: 40px; display: block; margin-bottom: 16px; opacity: 0.15;"></i>
            <p style="font-weight: 600; font-size: 15px;">Pilih tingkat kelas di atas untuk melihat data pengajar.</p>
        </div>
    </div>
@endif

@endsection
