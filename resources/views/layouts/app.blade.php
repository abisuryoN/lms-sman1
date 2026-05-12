<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LMS SMAN 1 Tajurhalang')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary: #3B82F6;
            --primary-dark: #1E40AF;
            --primary-light: #DBEAFE;
            --primary-50: #EFF6FF;
            --accent: #0EA5E9;
            --bg: #F8FAFC;
            --bg-card: #FFFFFF;
            --text: #1E293B;
            --text-secondary: #64748B;
            --text-muted: #94A3B8;
            --border: #E2E8F0;
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
            --sidebar-w: 260px;
            --navbar-h: 64px;
            --radius: 12px;
            --shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.07), 0 2px 4px -1px rgba(0,0,0,0.04);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.08), 0 4px 6px -2px rgba(0,0,0,0.04);
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Sidebar ────────────────────────────── */
        .sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: linear-gradient(180deg, #1E293B 0%, #0F172A 100%);
            color: #CBD5E1;
            z-index: 100;
            display: flex; flex-direction: column;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .sidebar-brand {
            display: flex; align-items: center; gap: 12px;
            padding: 16px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-brand img { width: 40px; height: 40px; border-radius: 8px; }
        .sidebar-brand-text { font-size: 14px; font-weight: 600; color: #F1F5F9; line-height: 1.3; }
        .sidebar-brand-sub { font-size: 11px; color: #64748B; font-weight: 400; }

        .sidebar-menu { padding: 16px 12px; flex: 1; }
        .sidebar-label {
            font-size: 10px; text-transform: uppercase; letter-spacing: 1.2px;
            color: #475569; padding: 8px 12px 6px; margin-top: 8px;
            font-weight: 600;
        }

        .sidebar-link {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 12px; border-radius: 8px;
            color: #94A3B8; text-decoration: none; font-size: 13.5px;
            transition: var(--transition); font-weight: 450;
            margin-bottom: 2px;
        }

        .sidebar-link:hover { background: rgba(255,255,255,0.06); color: #E2E8F0; }
        .sidebar-link.active { background: var(--primary); color: #fff; font-weight: 500; }
        .sidebar-link i { width: 20px; text-align: center; font-size: 15px; }

        /* ── Navbar ──────────────────────────────── */
        .navbar {
            position: fixed; top: 0; right: 0;
            left: var(--sidebar-w);
            height: var(--navbar-h);
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 28px;
            z-index: 90;
        }

        .navbar-title { font-size: 17px; font-weight: 600; color: var(--text); }
        .navbar-right { display: flex; align-items: center; gap: 16px; }

        .navbar-user {
            display: flex; align-items: center; gap: 10px;
            cursor: pointer; position: relative;
        }

        .navbar-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            object-fit: cover; border: 2px solid var(--primary-light);
        }

        .navbar-user-info { font-size: 13px; }
        .navbar-user-name { font-weight: 600; color: var(--text); }
        .navbar-user-role { font-size: 11px; color: var(--text-muted); text-transform: capitalize; }

        .user-dropdown {
            display: none; position: absolute; top: 100%; right: 0;
            background: white; border-radius: var(--radius);
            box-shadow: var(--shadow-lg); min-width: 180px;
            border: 1px solid var(--border); margin-top: 8px;
            overflow: hidden; z-index: 999;
        }

        .user-dropdown.show { display: block; }

        .user-dropdown a, .user-dropdown button {
            display: block; width: 100%; padding: 10px 16px;
            font-size: 13px; color: var(--text); text-decoration: none;
            text-align: left; border: none; background: none; cursor: pointer;
            transition: var(--transition);
        }

        .user-dropdown a:hover, .user-dropdown button:hover { background: var(--primary-50); color: var(--primary); }

        /* ── Main Content ────────────────────────── */
        .main-content {
            margin-left: var(--sidebar-w);
            margin-top: var(--navbar-h);
            padding: 28px;
            min-height: calc(100vh - var(--navbar-h));
        }

        /* ── Cards ───────────────────────────────── */
        .card {
            background: var(--bg-card);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .card:hover { box-shadow: var(--shadow-md); }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }

        .card-header h3 { font-size: 15px; font-weight: 600; }
        .card-body { padding: 20px; }

        /* ── Stat Cards ──────────────────────────── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px; margin-bottom: 24px;
        }

        .stat-card {
            background: var(--bg-card); border-radius: var(--radius);
            padding: 20px; border: 1px solid var(--border);
            box-shadow: var(--shadow); transition: var(--transition);
            display: flex; align-items: flex-start; gap: 16px;
        }

        .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }

        .stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; flex-shrink: 0;
        }

        .stat-icon.blue { background: var(--primary-light); color: var(--primary); }
        .stat-icon.green { background: #D1FAE5; color: var(--success); }
        .stat-icon.yellow { background: #FEF3C7; color: var(--warning); }
        .stat-icon.red { background: #FEE2E2; color: var(--danger); }
        .stat-icon.purple { background: #EDE9FE; color: #8B5CF6; }
        .stat-icon.cyan { background: #CFFAFE; color: #06B6D4; }

        .stat-info h4 { font-size: 12px; color: var(--text-muted); font-weight: 500; margin-bottom: 4px; }
        .stat-value { font-size: 26px; font-weight: 700; color: var(--text); line-height: 1; }

        /* ── Tables ──────────────────────────────── */
        .table-wrapper { overflow-x: auto; }

        table { width: 100%; border-collapse: collapse; }
        thead th {
            padding: 12px 16px; text-align: left; font-size: 12px;
            font-weight: 600; color: var(--text-muted); text-transform: uppercase;
            letter-spacing: 0.5px; border-bottom: 2px solid var(--border);
            background: var(--primary-50);
        }

        tbody td {
            padding: 12px 16px; font-size: 13.5px; border-bottom: 1px solid var(--border);
            color: var(--text); vertical-align: middle;
        }

        tbody tr { transition: var(--transition); }
        tbody tr:hover { background: var(--primary-50); }
        tbody tr:last-child td { border-bottom: none; }

        /* ── Buttons ─────────────────────────────── */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px; border-radius: 8px; font-size: 13px;
            font-weight: 500; border: none; cursor: pointer;
            text-decoration: none; transition: var(--transition);
            font-family: inherit;
        }

        .btn-sm { padding: 5px 10px; font-size: 12px; border-radius: 6px; }

        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-dark); transform: translateY(-1px); }

        .btn-success { background: var(--success); color: #fff; }
        .btn-success:hover { background: #059669; }

        .btn-warning { background: var(--warning); color: #fff; }
        .btn-warning:hover { background: #D97706; }

        .btn-danger { background: var(--danger); color: #fff; }
        .btn-danger:hover { background: #DC2626; }

        .btn-outline {
            background: transparent; border: 1px solid var(--border);
            color: var(--text-secondary);
        }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); background: var(--primary-50); }

        /* ── Forms ────────────────────────────────── */
        .form-group { margin-bottom: 16px; }
        .form-label { display: block; font-size: 13px; font-weight: 500; margin-bottom: 6px; color: var(--text); }

        .form-control {
            width: 100%; padding: 10px 14px; border: 1px solid var(--border);
            border-radius: 8px; font-size: 14px; font-family: inherit;
            transition: var(--transition); background: #fff; color: var(--text);
        }

        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }

        select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748B' d='M6 8L1 3h10z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; padding-right: 32px; }

        textarea.form-control { resize: vertical; min-height: 100px; }

        .form-error { color: var(--danger); font-size: 12px; margin-top: 4px; }

        /* ── Badges ───────────────────────────────── */
        .badge {
            display: inline-flex; align-items: center;
            padding: 3px 10px; border-radius: 20px;
            font-size: 11px; font-weight: 600;
        }

        .badge-green { background: #D1FAE5; color: #065F46; }
        .badge-yellow { background: #FEF3C7; color: #92400E; }
        .badge-red { background: #FEE2E2; color: #991B1B; }
        .badge-blue { background: var(--primary-light); color: var(--primary-dark); }
        .badge-gray { background: #F1F5F9; color: #475569; }

        /* ── Alerts ──────────────────────────────── */
        .alert {
            padding: 12px 16px; border-radius: 8px; margin-bottom: 16px;
            font-size: 13.5px; display: flex; align-items: center; gap: 10px;
            animation: slideDown 0.3s ease;
        }

        .alert-success { background: #D1FAE5; color: #065F46; border: 1px solid #A7F3D0; }
        .alert-error { background: #FEE2E2; color: #991B1B; border: 1px solid #FECACA; }
        .alert-warning { background: #FEF3C7; color: #92400E; border: 1px solid #FDE68A; }

        /* ── Pagination ──────────────────────────── */
        .pagination { display: flex; gap: 4px; justify-content: center; margin-top: 20px; }
        .pagination a, .pagination span {
            padding: 6px 12px; border-radius: 6px; font-size: 13px;
            text-decoration: none; transition: var(--transition);
            border: 1px solid var(--border); color: var(--text-secondary);
        }
        .pagination a:hover { background: var(--primary-50); border-color: var(--primary); color: var(--primary); }
        .pagination .active { background: var(--primary); color: #fff; border-color: var(--primary); }
        .pagination .disabled { opacity: 0.5; pointer-events: none; }

        /* ── Modal ───────────────────────────────── */
        .modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.5); z-index: 200;
            align-items: center; justify-content: center;
            backdrop-filter: blur(4px);
        }

        .modal-overlay.show { display: flex; }

        .modal {
            background: white; border-radius: var(--radius);
            padding: 24px; max-width: 480px; width: 90%;
            box-shadow: var(--shadow-lg);
            animation: modalIn 0.2s ease;
        }

        .modal h3 { font-size: 16px; margin-bottom: 12px; }
        .modal p { color: var(--text-secondary); font-size: 14px; margin-bottom: 20px; }
        .modal-actions { display: flex; gap: 8px; justify-content: flex-end; }

        /* ── Animations ──────────────────────────── */
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes modalIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }

        /* ── Hamburger ───────────────────────────── */
        .hamburger {
            display: none; background: none; border: none;
            font-size: 20px; cursor: pointer; color: var(--text);
            padding: 8px;
        }

        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.5); z-index: 99;
        }

        /* ── Responsive ──────────────────────────── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.show { display: block; }
            .navbar { left: 0; }
            .main-content { margin-left: 0; padding: 16px; }
            .hamburger { display: block; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
        }

        /* ── Utilities ───────────────────────────── */
        .text-center { text-align: center; }
        .mb-4 { margin-bottom: 16px; }
        .mt-4 { margin-top: 16px; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-2 { gap: 8px; }
        .gap-4 { gap: 16px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        @media (max-width: 768px) { .grid-2 { grid-template-columns: 1fr; } }
    </style>
    @stack('styles')
</head>
<body>
    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('assets/logo-sekolah.png') }}" alt="Logo" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 40 40%22><rect fill=%22%233B82F6%22 width=%2240%22 height=%2240%22 rx=%228%22/><text x=%2220%22 y=%2226%22 fill=%22white%22 font-size=%2218%22 text-anchor=%22middle%22 font-weight=%22bold%22>S1</text></svg>'">
            <div>
                <div class="sidebar-brand-text">LMS SMAN 1</div>
                <div class="sidebar-brand-sub">Tajurhalang</div>
            </div>
        </div>
        <nav class="sidebar-menu">
            @if(auth()->user()->isAdmin())
                <div class="sidebar-label">Menu Utama</div>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
                <div class="sidebar-label">Manajemen Data</div>
                <a href="{{ route('admin.guru.index') }}" class="sidebar-link {{ request()->routeIs('admin.guru.*') ? 'active' : '' }}">
                    <i class="fas fa-chalkboard-teacher"></i> Data Guru
                </a>
                <a href="{{ route('admin.siswa.index') }}" class="sidebar-link {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
                    <i class="fas fa-user-graduate"></i> Data Siswa
                </a>
                <a href="{{ route('admin.kelas.index') }}" class="sidebar-link {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
                    <i class="fas fa-school"></i> Data Kelas
                </a>
                <a href="{{ route('admin.mapel.index') }}" class="sidebar-link {{ request()->routeIs('admin.mapel.*') ? 'active' : '' }}">
                    <i class="fas fa-book"></i> Mata Pelajaran
                </a>
                <a href="{{ route('admin.guru-kelas.index') }}" class="sidebar-link {{ request()->routeIs('admin.guru-kelas.*') ? 'active' : '' }}">
                    <i class="fas fa-tasks"></i> Assign Guru
                </a>
                <div class="sidebar-label">Import & Akademik</div>
                <a href="{{ route('admin.import.siswa') }}" class="sidebar-link {{ request()->routeIs('admin.import.siswa*') ? 'active' : '' }}">
                    <i class="fas fa-file-excel"></i> Import Siswa
                </a>
                <a href="{{ route('admin.import.guru') }}" class="sidebar-link {{ request()->routeIs('admin.import.guru*') ? 'active' : '' }}">
                    <i class="fas fa-file-upload"></i> Import Guru
                </a>
                <a href="{{ route('admin.tahun-ajaran.index') }}" class="sidebar-link {{ request()->routeIs('admin.tahun-ajaran.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i> Tahun Ajaran
                </a>

            @elseif(auth()->user()->isGuru())
                <div class="sidebar-label">Menu Utama</div>
                <a href="{{ route('guru.dashboard') }}" class="sidebar-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
                <div class="sidebar-label">Pembelajaran</div>
                <a href="{{ route('guru.materi.index') }}" class="sidebar-link {{ request()->routeIs('guru.materi.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i> Materi
                </a>
                <a href="{{ route('guru.tugas.index') }}" class="sidebar-link {{ request()->routeIs('guru.tugas.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list"></i> Tugas
                </a>
                <a href="{{ route('guru.nilai.index') }}" class="sidebar-link {{ request()->routeIs('guru.nilai.*') ? 'active' : '' }}">
                    <i class="fas fa-star"></i> Penilaian
                </a>
                <div class="sidebar-label">Analisis</div>
                <a href="{{ route('guru.similarity.index') }}" class="sidebar-link {{ request()->routeIs('guru.similarity.*') ? 'active' : '' }}">
                    <i class="fas fa-search-plus"></i> Deteksi Similarity
                </a>
                <div class="sidebar-label">Akun</div>
                <a href="{{ route('guru.profil.edit') }}" class="sidebar-link {{ request()->routeIs('guru.profil.*') ? 'active' : '' }}">
                    <i class="fas fa-user-cog"></i> Profil Saya
                </a>

            @elseif(auth()->user()->isSiswa())
                <div class="sidebar-label">Menu Utama</div>
                <a href="{{ route('siswa.dashboard') }}" class="sidebar-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
                <div class="sidebar-label">Pembelajaran</div>
                <a href="{{ route('siswa.materi.index') }}" class="sidebar-link {{ request()->routeIs('siswa.materi.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i> Materi
                </a>
                <a href="{{ route('siswa.tugas.index') }}" class="sidebar-link {{ request()->routeIs('siswa.tugas.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list"></i> Tugas
                </a>
                <a href="{{ route('siswa.nilai.index') }}" class="sidebar-link {{ request()->routeIs('siswa.nilai.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i> Nilai
                </a>
                <div class="sidebar-label">Akun</div>
                <a href="{{ route('siswa.profil.edit') }}" class="sidebar-link {{ request()->routeIs('siswa.profil.*') ? 'active' : '' }}">
                    <i class="fas fa-user-cog"></i> Profil Saya
                </a>
            @endif
        </nav>
    </aside>

    {{-- Mobile Sidebar Overlay --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    {{-- Navbar --}}
    <header class="navbar">
        <div class="flex items-center gap-2">
            <button class="hamburger" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
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

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const dd = document.getElementById('userDropdown');
            if (dd && !e.target.closest('.navbar-user')) dd.classList.remove('show');
        });
    </script>
    @stack('scripts')
</body>
</html>
