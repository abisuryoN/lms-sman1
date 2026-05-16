<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LMS SMAN 1 Tajurhalang')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo-sekolah.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <!-- Tippy.js for Tooltips -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/animations/shift-away.css">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navigation.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
    <style>
        .flatpickr-calendar {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
            border: 1px solid #F1F5F9 !important;
            border-radius: 16px !important;
            padding: 8px !important;
            font-family: 'Outfit', sans-serif !important;
        }
        .flatpickr-day.selected {
            background: var(--primary) !important;
            border-color: var(--primary) !important;
        }
        .flatpickr-months .flatpickr-month { height: 40px !important; }
        .flatpickr-current-month { padding-top: 10px !important; }

        /* Custom Tippy Theme */
        .tippy-box[data-theme~='premium'] {
            background-color: #1e293b;
            color: white;
            border-radius: 8px;
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 500;
            padding: 4px 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .tippy-box[data-theme~='premium'][data-placement^='top'] > .tippy-arrow::before {
            border-top-color: #1e293b;
        }
        .tippy-box[data-theme~='premium'][data-placement^='bottom'] > .tippy-arrow::before {
            border-bottom-color: #1e293b;
        }
    </style>
</head>

<body>
    {{-- Custom Premium Sidebar Wrapper --}}
    <aside class="sidebar-wrapper" id="sidebarWrapper">
        <div class="sidebar-card">
            {{-- Collapse Button --}}
            <button class="sidebar-collapse-btn" onclick="toggleSidebarCollapse()" title="Toggle Sidebar">
                <i class="fas fa-chevron-left"></i>
            </button>

            {{-- Mobile Header --}}
            <div class="sidebar-mobile-header">
                <div class="m-user-avatar">
                    @if(auth()->user()->photo_url)
                        <img src="{{ auth()->user()->photo_url }}" alt="Avatar">
                    @else
                        <span>{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    @endif
                </div>
                <div class="m-user-details">
                    <span class="m-user-name">{{ auth()->user()->name }}</span>
                    <span class="m-user-id">{{ auth()->user()->identifier }} @if(auth()->user()->isSiswa()) | {{ auth()->user()->siswa->kelas->nama_kelas ?? '-' }} @endif</span>
                </div>
                <div class="m-header-actions">
                    <form action="{{ route('logout') }}" method="POST" id="m-logout-form">
                        @csrf
                        <button type="submit" class="m-logout-icon-btn" title="Keluar">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                    <button class="m-close-btn" onclick="toggleSidebar()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="sidebar-brand">
                <img src="{{ asset('assets/logo-sekolah.png') }}" alt="Logo"
                    onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 40 40%22><rect fill=%22%232563EB%22 width=%2240%22 height=%2240%22 rx=%228%22/><text x=%2220%22 y=%2226%22 fill=%22white%22 font-size=%2218%22 text-anchor=%22middle%22 font-weight=%22bold%22>S1</text></svg>'">
                <div class="sidebar-brand-title">LMS SMAN 1</div>
                <div class="sidebar-brand-badge">Official</div>
            </div>

            {{-- Menu Navigation --}}
            <nav class="sidebar-menu">
                @if(auth()->user()->isAdmin())
                    <div class="sidebar-label">Menu Utama</div>
                    <a href="{{ route('admin.dashboard') }}"
                        class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i> <span>Dashboard</span>
                    </a>

                    <div class="sidebar-label">Manajemen Data</div>
                    <a href="{{ route('admin.guru.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.guru.*') ? 'active' : '' }}">
                        <i class="fas fa-chalkboard-teacher"></i> <span>Data Guru</span>
                    </a>
                    <a href="{{ route('admin.siswa.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
                        <i class="fas fa-user-graduate"></i> <span>Data Siswa</span>
                    </a>
                    <a href="{{ route('admin.kelas.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
                        <i class="fas fa-school"></i> <span>Data Kelas</span>
                    </a>
                    <a href="{{ route('admin.mapel.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.mapel.*') ? 'active' : '' }}">
                        <i class="fas fa-book"></i> <span>Mata Pelajaran</span>
                    </a>
                    <a href="{{ route('admin.guru-kelas.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.guru-kelas.*') ? 'active' : '' }}">
                        <i class="fas fa-tasks"></i> <span>Assign Guru</span>
                    </a>

                    <div class="sidebar-label">Import & Akademik</div>
                    <a href="{{ route('admin.import.siswa') }}"
                        class="sidebar-link {{ request()->routeIs('admin.import.siswa*') ? 'active' : '' }}">
                        <i class="fas fa-file-excel"></i> <span>Import Siswa</span>
                    </a>
                    <a href="{{ route('admin.import.guru') }}"
                        class="sidebar-link {{ request()->routeIs('admin.import.guru*') ? 'active' : '' }}">
                        <i class="fas fa-file-upload"></i> <span>Import Guru</span>
                    </a>
                    <a href="{{ route('admin.tahun-ajaran.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.tahun-ajaran.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i> <span>Tahun Ajaran</span>
                    </a>

                    <div class="sidebar-label">Akun</div>
                    <a href="{{ route('admin.profil.edit') }}"
                        class="sidebar-link {{ request()->routeIs('admin.profil.*') ? 'active' : '' }}">
                        <i class="fas fa-user-cog"></i> <span>Profil Saya</span>
                    </a>

                @elseif(auth()->user()->isGuru())
                    <div class="sidebar-label">Menu Utama</div>
                    <a href="{{ route('guru.dashboard') }}"
                        class="sidebar-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i> <span>Dashboard</span>
                    </a>

                    <div class="sidebar-label">Pembelajaran</div>
                    <a href="{{ route('guru.materi.index') }}"
                        class="sidebar-link {{ request()->routeIs('guru.materi.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i> <span>Materi</span>
                    </a>
                    <a href="{{ route('guru.tugas.index') }}"
                        class="sidebar-link {{ request()->routeIs('guru.tugas.*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list"></i> <span>Tugas</span>
                    </a>
                    <a href="{{ route('guru.nilai.index') }}"
                        class="sidebar-link {{ request()->routeIs('guru.nilai.*') ? 'active' : '' }}">
                        <i class="fas fa-star"></i> <span>Penilaian</span>
                    </a>

                    <div class="sidebar-label">Analisis</div>
                    <a href="{{ route('guru.kelas.index') }}"
                        class="sidebar-link {{ request()->routeIs('guru.kelas.*') ? 'active' : '' }}">
                        <i class="fas fa-school"></i> <span>Data Kelas</span>
                    </a>
                    <a href="{{ route('guru.similarity.index') }}"
                        class="sidebar-link {{ request()->routeIs('guru.similarity.*') ? 'active' : '' }}">
                        <i class="fas fa-search-plus"></i> <span>Deteksi Similarity</span>
                    </a>

                    <div class="sidebar-label">Akun</div>
                    <a href="{{ route('guru.profil.edit') }}"
                        class="sidebar-link {{ request()->routeIs('guru.profil.*') ? 'active' : '' }}">
                        <i class="fas fa-user-cog"></i> <span>Profil Saya</span>
                    </a>

                @elseif(auth()->user()->isSiswa())
                    <div class="sidebar-label">Menu Utama</div>
                    <a href="{{ route('siswa.dashboard') }}"
                        class="sidebar-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i> <span>Dashboard</span>
                    </a>

                    <div class="sidebar-label">Pembelajaran</div>
                    <a href="{{ route('siswa.materi.index') }}"
                        class="sidebar-link {{ request()->routeIs('siswa.materi.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i> <span>Materi</span>
                    </a>
                    <a href="{{ route('siswa.tugas.index') }}"
                        class="sidebar-link {{ request()->routeIs('siswa.tugas.*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list"></i> <span>Tugas</span>
                    </a>
                    <a href="{{ route('siswa.nilai.index') }}"
                        class="sidebar-link {{ request()->routeIs('siswa.nilai.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i> <span>Nilai</span>
                    </a>

                    <div class="sidebar-label">Akun</div>
                    <a href="{{ route('siswa.profil.edit') }}"
                        class="sidebar-link {{ request()->routeIs('siswa.profil.*') ? 'active' : '' }}">
                        <i class="fas fa-user-cog"></i> <span>Profil Saya</span>
                    </a>
                @endif
            </nav>

            {{-- Footer Note --}}
            <div class="sidebar-footer-note">
                <i class="fas fa-code"></i> Dibuat untuk <span>@sman1tajurhalang</span>
            </div>

            {{-- Bottom User Profile Card --}}
            <div class="sidebar-user-section" id="sidebarUserSection" onclick="toggleBottomUserDropdown(event)">
                <div class="user-avatar-box">
                    @if(auth()->user()->photo_url)
                        <img src="{{ auth()->user()->photo_url }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">
                    @else
                        <span>{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    @endif
                    <div class="online-dot"></div>
                </div>
                <div class="user-info-box">
                    <div class="user-name-text">{{ auth()->user()->name }}</div>
                    @if(auth()->user()->isSiswa())
                        <div class="user-sub-text">{{ auth()->user()->siswa->kelas->nama_kelas ?? 'Tanpa Kelas' }}</div>
                    @else
                        <div class="user-sub-text">Klik untuk menu</div>
                    @endif
                </div>
                <i class="fas fa-chevron-down chevron-icon"></i>

                {{-- Upward Dropdown Menu --}}
                <div class="bottom-user-dropdown" id="bottomUserDropdown">
                    @if(auth()->user()->isGuru())
                        <a href="{{ route('guru.profil.edit') }}"><i class="fas fa-user-circle" style="width:20px"></i>
                            Profil Saya</a>
                    @elseif(auth()->user()->isSiswa())
                        <a href="{{ route('siswa.profil.edit') }}"><i class="fas fa-user-circle" style="width:20px"></i>
                            Profil Saya</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"><i class="fas fa-sign-out-alt" style="width:20px"></i> Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    {{-- Mobile Sidebar Overlay --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    {{-- Mobile Header --}}
    <header class="mobile-header">
        <div class="mobile-header-left">
            <img src="{{ asset('assets/logo-sekolah.png') }}" class="mobile-header-logo" alt="Logo">
            <div class="mobile-header-title">LMS SMAN 1</div>
        </div>
        <div class="mobile-header-right">
            <button class="mobile-header-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
        </div>
    </header>

    {{-- Navbar (Desktop Only) --}}
    <header class="navbar">
        <div class="flex items-center gap-2">
            <button class="hamburger" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
            <div style="width: 1px; height: 20px; background: #E2E8F0; margin: 0 8px;"></div>
            <h1 class="navbar-title">@yield('page-title', 'Dashboard')</h1>
        </div>
        <div class="navbar-right">
            <div class="navbar-user" onclick="document.getElementById('userDropdown').classList.toggle('show')">
                <img src="{{ auth()->user()->photo_url }}" alt="Avatar" class="navbar-avatar">
                <div class="navbar-user-info">
                    <div class="navbar-user-name">{{ auth()->user()->name }}</div>
                    <div class="navbar-user-role">{{ auth()->user()->role }}</div>
                </div>
                <i class="fas fa-chevron-down" style="font-size:11px;color:var(--text-muted)"></i>
                <div class="user-dropdown" id="userDropdown">
                    @if(auth()->user()->isGuru())
                        <a href="{{ route('guru.profil.edit') }}"><i class="fas fa-user"></i> Profil</a>
                    @elseif(auth()->user()->isSiswa())
                        <a href="{{ route('siswa.profil.edit') }}"><i class="fas fa-user"></i> Profil</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"><i class="fas fa-sign-out-alt"></i> Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="main-content">
        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <div>@foreach($errors->all() as $e) <div>{{ $e }}</div> @endforeach</div>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- Bottom Navigation --}}
    <nav class="bottom-nav">
        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i><span>Dash</span>
            </a>
            <a href="{{ route('admin.guru.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.guru.*') ? 'active' : '' }}">
                <i class="fas fa-chalkboard-teacher"></i><span>Guru</span>
            </a>
            <a href="{{ route('admin.siswa.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
                <i class="fas fa-user-graduate"></i><span>Siswa</span>
            </a>
            <a href="{{ route('admin.kelas.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
                <i class="fas fa-school"></i><span>Kelas</span>
            </a>
            <a href="{{ route('admin.mapel.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.mapel.*') ? 'active' : '' }}">
                <i class="fas fa-book"></i><span>Mapel</span>
            </a>
        @elseif(auth()->user()->isGuru())
            <a href="{{ route('guru.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i><span>Dash</span>
            </a>
            <a href="{{ route('guru.materi.index') }}" class="bottom-nav-item {{ request()->routeIs('guru.materi.*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i><span>Materi</span>
            </a>
            <a href="{{ route('guru.tugas.index') }}" class="bottom-nav-item {{ request()->routeIs('guru.tugas.*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i><span>Tugas</span>
            </a>
            <a href="{{ route('guru.nilai.index') }}" class="bottom-nav-item {{ request()->routeIs('guru.nilai.*') ? 'active' : '' }}">
                <i class="fas fa-star"></i><span>Nilai</span>
            </a>
            <a href="{{ route('guru.profil.edit') }}" class="bottom-nav-item {{ request()->routeIs('guru.profil.*') ? 'active' : '' }}">
                <i class="fas fa-user-cog"></i><span>Profil</span>
            </a>
        @elseif(auth()->user()->isSiswa())
            <a href="{{ route('siswa.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i><span>Dash</span>
            </a>
            <a href="{{ route('siswa.materi.index') }}" class="bottom-nav-item {{ request()->routeIs('siswa.materi.*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i><span>Materi</span>
            </a>
            <a href="{{ route('siswa.tugas.index') }}" class="bottom-nav-item {{ request()->routeIs('siswa.tugas.*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i><span>Tugas</span>
            </a>
            <a href="{{ route('siswa.nilai.index') }}" class="bottom-nav-item {{ request()->routeIs('siswa.nilai.*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i><span>Nilai</span>
            </a>
            <a href="{{ route('siswa.profil.edit') }}" class="bottom-nav-item {{ request()->routeIs('siswa.profil.*') ? 'active' : '' }}">
                <i class="fas fa-user-cog"></i><span>Profil</span>
            </a>
        @endif
    </nav>

    <!-- Core JavaScript -->
    <script src="{{ asset('js/app.js') }}"></script>

    <script>
        // Initialize Tippy Tooltips
        document.addEventListener('DOMContentLoaded', function() {
            tippy('[title]', {
                theme: 'premium',
                animation: 'shift-away',
                placement: 'top',
                arrow: true,
                onShow(instance) {
                    const title = instance.reference.getAttribute('title');
                    if (title) {
                        instance.setContent(title);
                        instance.reference.removeAttribute('title');
                    }
                },
                onHidden(instance) {
                    // Restore title attribute if needed or handle cleanup
                }
            });
        });

        // Global Auto-Search for all search inputs
        document.addEventListener('DOMContentLoaded', function() {
            const searchInputs = document.querySelectorAll('input[name="search"]');
            
            searchInputs.forEach(input => {
                let timeout = null;
                input.addEventListener('input', function() {
                    clearTimeout(timeout);
                    const form = this.closest('form');
                    
                    if (form) {
                        timeout = setTimeout(() => {
                            // Submit if length is 0 (cleared) or >= 3
                            if (this.value.length === 0 || this.value.length >= 3) {
                                form.submit();
                            }
                        }, 1000);
                    }
                });
            });
        });

        // Dynamic Time-Based Greeting Toast (Blade Dependent)
        @if(request()->routeIs('*.dashboard'))
            document.addEventListener('DOMContentLoaded', function() {
                const greeting = @json(auth()->user()->getGreetingData());
                const isMobile = window.innerWidth < 768;
                
                const Toast = Swal.mixin({
                    toast: true,
                    position: isMobile ? 'top' : 'top-end',
                    showConfirmButton: false,
                    showCloseButton: true,
                    timer: 6000,
                    timerProgressBar: true,
                    width: isMobile ? 'calc(100% - 40px)' : '350px'
                });

                Toast.fire({
                    iconHtml: greeting.iconHtml,
                    title: greeting.title,
                    text: greeting.message,
                    padding: isMobile ? '12px' : '20px',
                    customClass: {
                        icon: 'greeting-icon-no-border'
                    }
                });
            });
        @endif
    </script>
    @stack('scripts')
</body>

</html>