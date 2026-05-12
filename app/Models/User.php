<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'photo_profile',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ── Role Helpers ─────────────────────────────────────
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    // ── Relationships ────────────────────────────────────
    public function guru()
    {
        return $this->hasOne(Guru::class);
    }

    public function siswa()
    {
        return $this->hasOne(Siswa::class);
    }

    // ── Accessors ────────────────────────────────────────
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo_profile) {
            return asset('storage/' . $this->photo_profile);
        }
        return asset('assets/default-avatar.png');
    }

    public function getIdentifierAttribute(): string
    {
        if ($this->isSiswa()) {
            return $this->siswa ? $this->siswa->nis : 'NIS Not Found';
        }
        if ($this->isGuru()) {
            return $this->guru ? $this->guru->nip : 'NIP Not Found';
        }
        return 'Administrator';
    }
}
