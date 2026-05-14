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
        // Gunakan UI Avatars sebagai fallback yang cantik dan dinamis (berdasarkan inisial nama)
        $defaultAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=6366f1&color=fff&bold=true';

        if ($this->photo_profile) {
            // Jika menggunakan Supabase (path diawali 'users/')
            if (str_starts_with($this->photo_profile, 'users/')) {
                return \Illuminate\Support\Facades\Cache::remember(
                    "user_photo_{$this->id}",
                    540, // Cache selama 9 menit (URL aktif 10 menit)
                    function () use ($defaultAvatar) {
                        try {
                            $supabase = new \App\Services\SupabaseStorageService(config('services.supabase.profile_bucket'));
                            $url = $supabase->getSignedUrl($this->photo_profile);
                            
                            // Jika Supabase gagal memberikan URL (misal karena auth error), gunakan default
                            return $url ?: $defaultAvatar;
                        } catch (\Exception $e) {
                            return $defaultAvatar;
                        }
                    }
                );
            }
            // Fallback untuk storage lokal lama jika file masih ada
            return asset('storage/' . $this->photo_profile);
        }

        return $defaultAvatar;
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
        $now = now();
        $year = $now->year;
        $date = $now->format('m-d');
        $dayOfWeek = $now->dayOfWeek; // 0 (Sun) - 6 (Sat)
        $hour = $now->hour;

        // --- 1. DETEKSI HARI LIBUR NASIONAL (2026-2030) ---
        $holidays = [
            '2026' => [
                '01-01' => ['title' => 'Selamat Tahun Baru 2026!', 'message' => 'Semoga tahun ini penuh dengan prestasi.', 'icon' => '<i class="fas fa-calendar-check" style="color:#3B82F6"></i>'],
                '01-16' => ['title' => 'Isra Miraj Nabi Muhammad SAW', 'message' => 'Selamat memperingati perjalanan suci Rasulullah.', 'icon' => '<i class="fas fa-mosque" style="color:#10B981"></i>'],
                '02-17' => ['title' => 'Selamat Tahun Baru Imlek!', 'message' => 'Gong Xi Fa Cai! Semoga sukses selalu.', 'icon' => '<i class="fas fa-dragon" style="color:#EF4444"></i>'],
                '03-19' => ['title' => 'Selamat Hari Raya Nyepi!', 'message' => 'Selamat menjalankan Catur Brata Penyepian.', 'icon' => '<i class="fas fa-om" style="color:#F59E0B"></i>'],
                '03-21' => ['title' => 'Selamat Hari Raya Idul Fitri!', 'message' => 'Minal Aidin Wal Faizin, Mohon Maaf Lahir dan Batin.', 'icon' => '<i class="fas fa-kaaba" style="color:#059669"></i>'],
                '03-22' => ['title' => 'Selamat Hari Raya Idul Fitri!', 'message' => 'Minal Aidin Wal Faizin, Mohon Maaf Lahir dan Batin.', 'icon' => '<i class="fas fa-kaaba" style="color:#059669"></i>'],
                '05-01' => ['title' => 'Selamat Hari Buruh!', 'message' => 'Selamat Hari Buruh Internasional.', 'icon' => '<i class="fas fa-hammer" style="color:#64748B"></i>'],
                '05-27' => ['title' => 'Selamat Hari Raya Idul Adha!', 'message' => 'Selamat berkurban dan berbagi keberkahan.', 'icon' => '<i class="fas fa-mosque" style="color:#059669"></i>'],
                '06-01' => ['title' => 'Hari Lahir Pancasila', 'message' => 'Selamat memperingati Hari Lahir Pancasila.', 'icon' => '<i class="fas fa-flag" style="color:#EF4444"></i>'],
                '08-17' => ['title' => 'HUT Republik Indonesia!', 'message' => 'Merdeka! Selamat Hari Kemerdekaan RI.', 'icon' => '<i class="fas fa-flag" style="color:#EF4444"></i>'],
                '12-25' => ['title' => 'Selamat Hari Raya Natal!', 'message' => 'Selamat merayakan Natal bagi yang merayakan.', 'icon' => '<i class="fas fa-gift" style="color:#EF4444"></i>'],
            ],
            '2027' => [
                '01-01' => ['title' => 'Selamat Tahun Baru 2027!', 'message' => 'Semoga tahun ini lebih baik dari sebelumnya.', 'icon' => '<i class="fas fa-calendar-check" style="color:#3B82F6"></i>'],
                '01-05' => ['title' => 'Isra Miraj Nabi Muhammad SAW', 'message' => 'Selamat memperingati Isra Miraj.', 'icon' => '<i class="fas fa-mosque" style="color:#10B981"></i>'],
                '02-06' => ['title' => 'Selamat Tahun Baru Imlek!', 'message' => 'Gong Xi Fa Cai! 2578.', 'icon' => '<i class="fas fa-dragon" style="color:#EF4444"></i>'],
                '03-09' => ['title' => 'Selamat Hari Raya Nyepi!', 'message' => 'Tahun Baru Saka 1949.', 'icon' => '<i class="fas fa-om" style="color:#F59E0B"></i>'],
                '03-10' => ['title' => 'Selamat Hari Raya Idul Fitri!', 'message' => '1448 H. Mohon Maaf Lahir dan Batin.', 'icon' => '<i class="fas fa-kaaba" style="color:#059669"></i>'],
                '03-11' => ['title' => 'Selamat Hari Raya Idul Fitri!', 'message' => '1448 H. Mohon Maaf Lahir dan Batin.', 'icon' => '<i class="fas fa-kaaba" style="color:#059669"></i>'],
                '05-17' => ['title' => 'Selamat Hari Raya Idul Adha!', 'message' => '1448 H. Selamat berkurban.', 'icon' => '<i class="fas fa-mosque" style="color:#059669"></i>'],
                '08-17' => ['title' => 'HUT Republik Indonesia!', 'message' => 'Merdeka! Dirgahayu Indonesia.', 'icon' => '<i class="fas fa-flag" style="color:#EF4444"></i>'],
                '12-25' => ['title' => 'Selamat Hari Raya Natal!', 'message' => 'Merry Christmas!', 'icon' => '<i class="fas fa-gift" style="color:#EF4444"></i>'],
            ],
            '2028' => [
                '01-01' => ['title' => 'Selamat Tahun Baru 2028!', 'message' => 'Happy New Year!', 'icon' => '<i class="fas fa-calendar-check"></i>'],
                '02-28' => ['title' => 'Selamat Hari Raya Idul Fitri!', 'message' => '1449 H. Mohon Maaf Lahir dan Batin.', 'icon' => '<i class="fas fa-kaaba"></i>'],
                '05-05' => ['title' => 'Selamat Hari Raya Idul Adha!', 'message' => '1449 H. Selamat berkurban.', 'icon' => '<i class="fas fa-mosque"></i>'],
                '08-17' => ['title' => 'HUT Republik Indonesia!', 'message' => 'Merdeka!', 'icon' => '<i class="fas fa-flag"></i>'],
                '12-25' => ['title' => 'Selamat Hari Raya Natal!', 'message' => 'Selamat Natal.', 'icon' => '<i class="fas fa-gift"></i>'],
            ],
            '2029' => [
                '01-01' => ['title' => 'Selamat Tahun Baru 2029!', 'message' => 'Happy New Year!', 'icon' => '<i class="fas fa-calendar-check"></i>'],
                '02-15' => ['title' => 'Selamat Hari Raya Idul Fitri!', 'message' => '1450 H. Mohon Maaf Lahir dan Batin.', 'icon' => '<i class="fas fa-kaaba"></i>'],
                '04-25' => ['title' => 'Selamat Hari Raya Idul Adha!', 'message' => '1450 H. Selamat berkurban.', 'icon' => '<i class="fas fa-mosque"></i>'],
                '08-17' => ['title' => 'HUT Republik Indonesia!', 'message' => 'Merdeka!', 'icon' => '<i class="fas fa-flag"></i>'],
                '12-25' => ['title' => 'Selamat Hari Raya Natal!', 'message' => 'Selamat Natal.', 'icon' => '<i class="fas fa-gift"></i>'],
            ],
            '2030' => [
                '01-01' => ['title' => 'Selamat Tahun Baru 2030!', 'message' => 'Satu dekade baru dimulai!', 'icon' => '<i class="fas fa-calendar-check"></i>'],
                '02-05' => ['title' => 'Selamat Hari Raya Idul Fitri!', 'message' => '1451 H. Mohon Maaf Lahir dan Batin.', 'icon' => '<i class="fas fa-kaaba"></i>'],
                '04-15' => ['title' => 'Selamat Hari Raya Idul Adha!', 'message' => '1451 H. Selamat berkurban.', 'icon' => '<i class="fas fa-mosque"></i>'],
                '08-17' => ['title' => 'HUT Republik Indonesia!', 'message' => 'Merdeka!', 'icon' => '<i class="fas fa-flag"></i>'],
                '12-25' => ['title' => 'Selamat Hari Raya Natal!', 'message' => 'Selamat Natal.', 'icon' => '<i class="fas fa-gift"></i>'],
            ]
        ];

        // Cek jika hari ini adalah libur nasional
        if (isset($holidays[$year][$date])) {
            $h = $holidays[$year][$date];
            return [
                'title' => $h['title'],
                'message' => $h['message'],
                'iconHtml' => $h['icon']
            ];
        }

        // --- 2. DETEKSI AKHIR PEKAN (SABTU-MINGGU) ---
        if ($dayOfWeek == 0 || $dayOfWeek == 6) {
            return [
                'title' => 'Selamat Berakhir Pekan!',
                'message' => 'Selamat beristirahat sejenak dari aktivitas rutin.',
                'iconHtml' => '<i class="fas fa-mug-hot" style="color:#9333EA"></i>'
            ];
        }

        // --- 3. UCAPAN HARIAN NORMAL ---
        $timeStr = '';
        if ($hour >= 5 && $hour < 11) { $timeStr = 'pagi'; }
        elseif ($hour >= 11 && $hour < 15) { $timeStr = 'siang'; }
        elseif ($hour >= 15 && $hour < 19) { $timeStr = 'sore'; }
        else { $timeStr = 'malam'; }

        $greeting = 'Halo';
        if ($timeStr == 'pagi') $greeting = 'Selamat Pagi';
        if ($timeStr == 'siang') $greeting = 'Selamat Siang';
        if ($timeStr == 'sore') $greeting = 'Selamat Sore';
        if ($timeStr == 'malam') $greeting = 'Selamat Malam';

        $name = explode(' ', $this->name)[0];

        $message = '';
        if ($this->isAdmin()) {
            $message = match($timeStr) {
                'pagi' => 'Mari kelola sistem dengan baik hari ini!',
                'siang' => 'Pastikan semua data berjalan lancar!',
                'sore' => 'Selesaikan administrasi hari ini dengan rapi!',
                'malam' => 'Sistem berjalan aman, selamat beristirahat!',
            };
        } elseif ($this->isGuru()) {
            $message = match($timeStr) {
                'pagi' => 'Semoga hari ini menyenangkan dalam mendidik!',
                'siang' => 'Tetap semangat membimbing siswa-siswi!',
                'sore' => 'Terima kasih atas dedikasinya hari ini!',
                'malam' => 'Selamat beristirahat, Bapak/Ibu Guru!',
            };
        } elseif ($this->isSiswa()) {
            $message = match($timeStr) {
                'pagi' => 'Semangat belajarnya hari ini!',
                'siang' => 'Tetap semangat belajarnya!',
                'sore' => 'Jangan lupa istirahat setelah belajar!',
                'malam' => 'Waktunya istirahat agar besok segar kembali!',
            };
        }

        return [
            'title' => "$greeting, $name!",
            'message' => $message,
            'iconHtml' => match($timeStr) {
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
