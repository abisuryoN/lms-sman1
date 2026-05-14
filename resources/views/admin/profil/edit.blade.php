@extends('layouts.app')
@section('title', 'Profil Admin')
@section('page-title', 'Profil Saya')

@section('content')
<div class="profile-container">
    {{-- Left Card: Photo & Action --}}
    <div class="card profile-card-left">
        <div class="profile-avatar-wrapper" style="position: relative; display: inline-block; margin-bottom: 24px;">
            <img src="{{ $user->photo_url }}" alt="Avatar" 
                style="width: 160px; height: 160px; border-radius: 50%; object-fit: cover; border: 6px solid #FFFFFF; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
            <div style="position: absolute; bottom: 8px; right: 8px; width: 32px; height: 32px; background: #0F172A; border: 4px solid #FFFFFF; border-radius: 50%;"></div>
        </div>
        
        <h3 style="font-size: 20px; font-weight: 700; color: #0F172A; margin-bottom: 4px;">{{ $user->name }}</h3>
        <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 24px;">{{ strtoupper($user->role) }} • Super User</p>

        <form action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div style="margin-bottom: 16px;">
                <label class="btn btn-outline btn-sm" style="cursor: pointer; display: inline-block; width: 100%;">
                    <i class="fas fa-camera"></i> Ganti Foto Profil
                    <input type="file" name="photo" style="display: none;" onchange="this.form.submit()">
                </label>
                <small style="display: block; margin-top: 8px; font-size: 11px; color: var(--text-muted);">Format: JPG, PNG (Maks 1MB)</small>
            </div>
        </form>
    </div>

    {{-- Right Card: Information & Password --}}
    <div style="display: flex; flex-direction: column; gap: 24px;">
        <div class="card">
            <div class="card-header">
                <h3 style="font-size: 16px; font-weight: 700;"><i class="fas fa-user-shield" style="margin-right: 8px; color: var(--primary);"></i> Akun Administrator</h3>
            </div>
            <div class="card-body">
                <div class="profile-info-grid">
                    <div class="form-group">
                        <label class="form-label" style="color: var(--text-muted); font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Username / Nama</label>
                        <div style="padding: 12px 0; border-bottom: 1px solid #F1F5F9; font-weight: 600; color: #1E293B;">{{ $user->name }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="color: var(--text-muted); font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Email Akses</label>
                        <div style="padding: 12px 0; border-bottom: 1px solid #F1F5F9; font-weight: 600; color: #1E293B;">{{ $user->email }}</div>
                    </div>
                </div>

                <div style="margin-top: 32px; padding: 16px; background: #F8FAFC; border-radius: 12px; display: flex; gap: 16px; align-items: flex-start;">
                    <i class="fas fa-lock" style="color: var(--warning); margin-top: 2px;"></i>
                    <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.5; margin: 0;">
                        Anda masuk sebagai Administrator. Akun ini memiliki wewenang penuh untuk mengelola seluruh data akademik di sistem ini. Harap menjaga keamanan kredensial Anda.
                    </p>
                </div>
            </div>
        </div>

        <div class="card" id="password-section">
            <div class="card-header">
                <h3 style="font-size: 16px; font-weight: 700;"><i class="fas fa-key" style="margin-right: 8px; color: var(--warning);"></i> Keamanan & Password</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.profil.update') }}" method="POST">
                    @csrf @method('PUT')
                    <div style="display: grid; grid-template-columns: 1fr; gap: 20px; max-width: 500px;">
                        <div class="form-group">
                            <label class="form-label">Password Saat Ini</label>
                            <input type="password" name="current_password" class="form-control" placeholder="Masukkan password lama">
                            @error('current_password') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="new_password" class="form-control" placeholder="Minimal 8 karakter">
                            @error('new_password') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" class="form-control" placeholder="Ulangi password baru">
                        </div>
                        <div style="margin-top: 10px;">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
