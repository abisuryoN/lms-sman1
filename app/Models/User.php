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

    public function getGreetingData(): array
    {
        $hour = now()->hour;
        $time = '';
        if ($hour >= 5 && $hour < 11) { $time = 'pagi'; }
        elseif ($hour >= 11 && $hour < 15) { $time = 'siang'; }
        elseif ($hour >= 15 && $hour < 19) { $time = 'sore'; }
        else { $time = 'malam'; }

        $greeting = 'Halo';
        if ($time == 'pagi') $greeting = 'Selamat Pagi';
        if ($time == 'siang') $greeting = 'Selamat Siang';
        if ($time == 'sore') $greeting = 'Selamat Sore';
        if ($time == 'malam') $greeting = 'Selamat Malam';

        $name = explode(' ', $this->name)[0];

        $message = '';
        if ($this->isAdmin()) {
            $message = match($time) {
                'pagi' => 'Mari kelola sistem dengan baik hari ini!',
                'siang' => 'Pastikan semua data berjalan lancar!',
                'sore' => 'Selesaikan administrasi hari ini dengan rapi!',
                'malam' => 'Sistem berjalan aman, selamat beristirahat!',
            };
        } elseif ($this->isGuru()) {
            $message = match($time) {
                'pagi' => 'Semoga hari ini menyenangkan dalam mendidik!',
                'siang' => 'Tetap semangat membimbing siswa-siswi!',
                'sore' => 'Terima kasih atas dedikasinya hari ini!',
                'malam' => 'Selamat beristirahat, Bapak/Ibu Guru!',
            };
        } elseif ($this->isSiswa()) {
            $message = match($time) {
                'pagi' => 'Semangat belajarnya hari ini!',
                'siang' => 'Tetap semangat belajarnya!',
                'sore' => 'Jangan lupa istirahat setelah belajar!',
                'malam' => 'Waktunya istirahat agar besok segar kembali!',
            };
        }

        return [
            'title' => "$greeting, $name!",
            'message' => $message,
            'iconHtml' => match($time) {
                'pagi' => '<i class="fas fa-sun" style="color: #FBBF24;"></i>',
                'siang' => '<i class="fas fa-cloud-sun" style="color: #FB923C;"></i>',
                'sore' => '<i class="fas fa-cloud-moon" style="color: #6366F1;"></i>',
                'malam' => '<i class="fas fa-moon" style="color: #4F46E5;"></i>',
            }
        ];
    }

    public function getTimeGreeting(): string
    {
        $data = $this->getGreetingData();
        return $data['title'] . ' ' . $data['message'];
    }
}
