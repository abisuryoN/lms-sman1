@extends('layouts.app')
@section('title', 'Profil Guru')
@section('page-title', 'Profil Saya')

@section('content')
<div class="profile-container" style="display: grid; grid-template-columns: 320px 1fr; gap: 24px; align-items: start;">
    {{-- Left Card: Photo & Action --}}
    <div class="card profile-card-left" style="padding: 32px 24px; text-align: center;">
        <div class="profile-avatar-wrapper" style="position: relative; display: inline-block; margin-bottom: 24px;">
            <img src="{{ $user->photo_url }}" alt="Avatar" 
                style="width: 160px; height: 160px; border-radius: 50%; object-fit: cover; border: 6px solid #FFFFFF; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
            <div style="position: absolute; bottom: 8px; right: 8px; width: 32px; height: 32px; background: var(--primary); border: 4px solid #FFFFFF; border-radius: 50%;"></div>
        </div>
        
        <h3 style="font-size: 20px; font-weight: 700; color: #0F172A; margin-bottom: 4px;">{{ $guru->nama ?? $user->name }}</h3>
        <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 24px;">{{ strtoupper($user->role) }} • NIP {{ $guru->nip ?? '-' }}</p>

        <form action="{{ route('guru.profil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div style="margin-bottom: 16px;">
                <label class="btn btn-outline btn-sm" style="cursor: pointer; display: inline-block; width: 100%;">
                    <i class="fas fa-camera"></i> Ganti Foto Profil
                    <input type="file" name="photo" style="display: none;" onchange="this.form.submit()">
                </label>
                <small style="display: block; margin-top: 8px; font-size: 11px; color: var(--text-muted);">Format: JPG, PNG (Maks 2MB)</small>
            </div>
        </form>
    </div>

    {{-- Right Card: Information --}}
    <div class="card">
        <div class="card-header" style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
            <h3 style="font-size: 16px; font-weight: 700;"><i class="fas fa-id-card" style="margin-right: 8px; color: var(--primary);"></i> Biodata Tenaga Pengajar</h3>
        </div>
        <div class="card-body" style="padding: 32px 24px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                <div class="form-group">
                    <label class="form-label" style="color: var(--text-muted); font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Nama Lengkap</label>
                    <div style="padding: 12px 0; border-bottom: 1px solid #F1F5F9; font-weight: 600; color: #1E293B;">{{ $guru->nama ?? $user->name }}</div>
                </div>
                <div class="form-group">
                    <label class="form-label" style="color: var(--text-muted); font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Email</label>
                    <div style="padding: 12px 0; border-bottom: 1px solid #F1F5F9; font-weight: 600; color: #1E293B;">{{ $user->email }}</div>
                </div>
                <div class="form-group">
                    <label class="form-label" style="color: var(--text-muted); font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Nomor Induk Pegawai (NIP)</label>
                    <div style="padding: 12px 0; border-bottom: 1px solid #F1F5F9; font-weight: 600; color: #1E293B;">{{ $guru->nip ?? '-' }}</div>
                </div>
                <div class="form-group">
                    <label class="form-label" style="color: var(--text-muted); font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Jabatan</label>
                    <div style="padding: 12px 0; border-bottom: 1px solid #F1F5F9; font-weight: 600; color: #1E293B;">Guru Mata Pelajaran</div>
                </div>
                <div class="form-group">
                    <label class="form-label" style="color: var(--text-muted); font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Telepon</label>
                    <div style="padding: 12px 0; border-bottom: 1px solid #F1F5F9; font-weight: 600; color: #1E293B;">{{ $guru->telepon ?? '-' }}</div>
                </div>
                <div class="form-group">
                    <label class="form-label" style="color: var(--text-muted); font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Status Kepegawaian</label>
                    <div style="padding: 12px 0; border-bottom: 1px solid #F1F5F9;"><span class="badge badge-blue">Aktif</span></div>
                </div>
            </div>

            <div style="margin-top: 32px; padding: 16px; background: #F8FAFC; border-radius: 12px; display: flex; gap: 16px; align-items: flex-start;">
                <i class="fas fa-info-circle" style="color: var(--primary); margin-top: 2px;"></i>
                <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.5; margin: 0;">
                    Biodata guru dikelola langsung oleh administrator. Jika terdapat ketidaksesuaian data NIP atau Nama, silakan hubungi bagian TU untuk perbaikan data.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
