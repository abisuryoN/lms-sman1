<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LMS SMAN 1 Tajurhalang')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --primary: #3B82F6;
            --primary-dark: #2563EB;
            --primary-light: #EFF6FF;
            --secondary: #64748B;
            --success: #10B981;
            --danger: #EF4444;
            --warning: #F59E0B;
            --background: #F8FAFC;
            --sidebar-bg: #F8FAFC;
            --card-bg: #FFFFFF;
            --text-main: #1E293B;
            --text-secondary: #64748B;
            --text-muted: #94A3B8;
            --border: #E2E8F0;
            --navbar-h: 56px;
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -1px rgba(0, 0, 0, 0.04);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            --sidebar-w: 280px;
            font-family: 'Outfit', 'Inter', -apple-system, sans-serif;
            background: var(--background);
            color: var(--text-main);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        /* ── Custom Premium Sidebar (MySIKA Aesthetic - Light Mode) ────────────────────────────── */
        .sidebar-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-w);
            background: var(--background);
            z-index: 100;
            padding: 20px 0 16px 16px;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s ease;
        }

        .sidebar-card {
            background: #FFFFFF;
            /* Pristine pure white card background */
            border-radius: 22px;
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid #E2E8F0;
            position: relative;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        }

        /* Collapse Button overlapping right edge exactly like light screenshot */
        .sidebar-collapse-btn {
            position: absolute;
            right: -14px;
            top: 28px;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #FFFFFF;
            color: #64748B;
            border: 1px solid #E2E8F0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            cursor: pointer;
            transition: all 0.2s ease;
            z-index: 10;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .sidebar-collapse-btn:hover {
            background: var(--primary);
            color: #FFFFFF;
            transform: scale(1.1);
            border-color: var(--primary);
        }

        /* Brand Area */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 20px 20px 4px 20px;
            overflow: hidden;
            white-space: nowrap;
        }

        .sidebar-brand img {
            width: 48px;
            height: 48px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .sidebar-brand-title {
            font-size: 16px;
            font-weight: 700;
            color: #0F172A;
            letter-spacing: -0.3px;
        }

        .sidebar-brand-badge {
            border: 1px solid #FDE68A;
            background: #FEF3C7;
            color: #B45309;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 6px;
            margin-left: 4px;
        }

        /* Menu Container */
        .sidebar-menu {
            padding: 0 12px 10px 12px;
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        .sidebar-menu::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: #E2E8F0;
            border-radius: 4px;
        }

        .sidebar-label {
            font-size: 10px;
            font-weight: 600;
            color: #94A3B8;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            padding: 12px 12px 12px 12px;
            margin-top: 0;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 20px;
            border-radius: 14px;
            color: #64748B;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.2s ease;
            margin-bottom: 8px;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar-link i {
            font-size: 20px;
            width: 26px;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar-link:hover {
            color: #0F172A;
            background: #F8FAFC;
        }

        /* Active Link matching screenshot precisely */
        .sidebar-link.active {
            background: var(--primary);
            color: #FFFFFF;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        /* Footer Note inside menu */
        .sidebar-footer-note {
            margin-top: auto;
            padding: 16px 12px 8px 12px;
            font-size: 12px;
            color: #94A3B8;
            border-top: 1px solid #F1F5F9;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar-footer-note span {
            color: #0F172A;
            font-weight: 600;
        }

        /* Bottom User Profile Section */
        .sidebar-user-section {
            margin: 4px 12px 12px 12px;
            padding: 10px 12px;
            background: #F8FAFC;
            border-radius: 16px;
            border: 1px solid #E2E8F0;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
            user-select: none;
        }

        .sidebar-user-section:hover {
            border-color: #CBD5E1;
            background: #F1F5F9;
        }

        .user-avatar-box {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: #6366F1;
            color: #FFFFFF;
            font-weight: 700;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            flex-shrink: 0;
        }

        /* Green dot with white border matching light screenshot exactly */
        .online-dot {
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 12px;
            height: 12px;
            background: #10B981;
            border-radius: 50%;
            border: 2px solid #FFFFFF;
        }

        .user-info-box {
            flex: 1;
            min-width: 0;
            overflow: hidden;
        }

        .user-name-text {
            font-size: 13px;
            font-weight: 700;
            color: #0F172A;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.2;
            text-transform: uppercase;
        }

        .user-sub-text {
            font-size: 11px;
            color: #64748B;
            margin-top: 2px;
        }

        .chevron-icon {
            color: #64748B;
            font-size: 12px;
            transition: transform 0.2s;
            flex-shrink: 0;
        }

        .sidebar-user-section.open .chevron-icon {
            transform: rotate(180deg);
        }

        /* Mobile Drawer */
        .mobile-drawer {
            position: fixed;
            top: 0;
            right: -100%;
            width: 85%;
            max-width: 360px;
            height: 100%;
            background: #FFFFFF;
            z-index: 2000;
            transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 24px;
            box-shadow: -10px 0 30px rgba(0, 0, 0, 0.1);
        }

        .mobile-drawer.active {
            right: 0;
        }

        .drawer-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
            z-index: 1900;
            display: none;
        }

        .drawer-overlay.active {
            display: block;
        }

        .drawer-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .drawer-user-card {
            background: #F8FAFC;
            padding: 16px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid #E2E8F0;
        }

        .drawer-btn {
            width: 100%;
            padding: 14px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .drawer-btn-primary {
            background: var(--primary);
            color: #FFFFFF;
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
        }

        .drawer-btn-outline {
            background: #FFFFFF;
            color: #64748B;
            border: 1px solid #E2E8F0;
        }

        .drawer-btn-danger {
            background: #FFF1F2;
            color: #E11D48;
            border: 1px solid #FECDD3;
        }

        .nav-icon-btn {
            background: none;
            border: 1px solid #E2E8F0;
            padding: 8px;
            border-radius: 8px;
            cursor: pointer;
            color: #64748B;
            font-size: 16px;
            transition: all 0.2s ease;
        }

        .nav-icon-btn:hover {
            background: #F8FAFC;
        }

        /* Bottom User Dropdown */
        .bottom-user-dropdown {
            position: absolute;
            bottom: calc(100% + 8px);
            left: 0;
            right: 0;
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            z-index: 101;
            display: none;
            animation: menuUp 0.2s ease;
        }

        .bottom-user-dropdown.show {
            display: block;
        }

        @keyframes menuUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .bottom-user-dropdown a,
        .bottom-user-dropdown button {
            display: block;
            width: 100%;
            padding: 12px 16px;
            font-size: 13px;
            color: #1E293B;
            text-decoration: none;
            text-align: left;
            border: none;
            background: none;
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: 500;
            font-family: inherit;
        }

        .bottom-user-dropdown a:hover,
        .bottom-user-dropdown button:hover {
            background: #F1F5F9;
            color: var(--primary);
        }

        .bottom-user-dropdown form {
            margin: 0;
        }

        /* ── Collapsed Sidebar State Logic ───────────────────────────── */
        body.sidebar-collapsed {
            --sidebar-w: 104px;
        }

        body.sidebar-collapsed .sidebar-brand-title,
        body.sidebar-collapsed .sidebar-brand-badge,
        body.sidebar-collapsed .sidebar-label,
        body.sidebar-collapsed .sidebar-link span,
        body.sidebar-collapsed .sidebar-footer-note,
        body.sidebar-collapsed .user-info-box,
        body.sidebar-collapsed .chevron-icon {
            display: none;
        }

        body.sidebar-collapsed .sidebar-brand {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
            padding-bottom: 32px;
        }

        body.sidebar-collapsed .sidebar-link {
            justify-content: center;
            padding: 12px;
            margin-bottom: 22px;
        }

        body.sidebar-collapsed .sidebar-link i {
            font-size: 22px;
            width: auto;
        }

        body.sidebar-collapsed .sidebar-user-section {
            justify-content: center;
            padding: 10px;
            margin: 4px 8px 12px 8px;
        }

        body.sidebar-collapsed .sidebar-collapse-btn i {
            transform: rotate(180deg);
        }

        /* ── Navbar ──────────────────────────────── */
        .navbar {
            position: fixed;
            top: 0;
            right: 0;
            left: var(--sidebar-w);
            height: var(--navbar-h);
            background: #FFFFFF;
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            z-index: 90;
            transition: all 0.3s ease;
        }

        .navbar-title {
            font-size: 17px;
            font-weight: 600;
            color: var(--text);
        }

        .main-content {
            margin-left: var(--sidebar-w);
            margin-top: var(--navbar-h);
            padding: 28px;
            min-height: calc(100vh - var(--navbar-h));
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* UI Components */
        .card {
            background: var(--card-bg);
            border-radius: 16px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            margin-bottom: 24px;
        }

        .card-header {
            padding: 16px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header h3 {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-body {
            padding: 24px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-blue {
            background: #DBEAFE;
            color: #1E40AF;
        }

        .badge-green {
            background: #D1FAE5;
            color: #065F46;
        }

        .badge-red {
            background: #FEE2E2;
            color: #991B1B;
        }

        .badge-yellow {
            background: #FEF3C7;
            color: #92400E;
        }

        .alert {
            padding: 16px 24px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
        }

        .alert-success {
            background: #ECFDF5;
            color: #065F46;
            border: 1px solid #A7F3D0;
        }

        .alert-error {
            background: #FEF2F2;
            color: #991B1B;
            border: 1px solid #FECACA;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--card-bg);
            padding: 24px;
            border-radius: 16px;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-icon.blue {
            background: #EFF6FF;
            color: #3B82F6;
        }

        .stat-icon.yellow {
            background: #FFFBEB;
            color: #D97706;
        }

        .stat-icon.green {
            background: #ECFDF5;
            color: #10B981;
        }

        .stat-icon.purple {
            background: #F5F3FF;
            color: #8B5CF6;
        }

        .stat-info h4 {
            font-size: 13px;
            color: var(--text-secondary);
            margin-bottom: 4px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-main);
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            padding: 16px;
            background: #F8FAFC;
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 13px;
            border-bottom: 1px solid var(--border);
        }

        td {
            padding: 16px;
            border-bottom: 1px solid var(--border);
            color: var(--text-main);
            font-size: 14px;
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: #F8FAFC;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            position: relative;
        }

        .navbar-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-light);
        }

        .navbar-user-info {
            font-size: 13px;
        }

        .navbar-user-name {
            font-weight: 600;
            color: var(--text);
        }

        /* ── Bottom Navigation (MySIKA Aesthetic) ────────────────── */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 75px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            display: none;
            justify-content: space-around;
            align-items: center;
            z-index: 1000;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            padding: 0 10px;
            box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.05);
        }

        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            color: #94A3B8;
            transition: all 0.3s ease;
            position: relative;
            flex: 1;
        }

        .bottom-nav-item i {
            font-size: 22px;
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .bottom-nav-item span {
            font-size: 10px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .bottom-nav-item.active {
            color: var(--primary);
        }

        .bottom-nav-item.active i {
            transform: translateY(-4px);
        }

        .bottom-nav-item.active::after {
            content: '';
            position: absolute;
            bottom: -8px;
            width: 4px;
            height: 4px;
            background: var(--primary);
            border-radius: 50%;
        }

        /* ── Mobile Header (MySIKA Aesthetic) ────────────────────── */
        .mobile-header {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 65px;
            background: #FFFFFF;
            padding: 0 20px;
            align-items: center;
            justify-content: space-between;
            z-index: 1000;
            border-bottom: 1px solid #F1F5F9;
        }

        .mobile-header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .mobile-header-logo {
            width: 32px;
            height: 32px;
            object-fit: contain;
        }

        .mobile-header-title {
            font-size: 18px;
            font-weight: 700;
            color: #0F172A;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .mobile-header-badge {
            font-size: 9px;
            font-weight: 600;
            padding: 2px 6px;
            background: #FEF3C7;
            color: #B45309;
            border: 1px solid #FDE68A;
            border-radius: 6px;
        }

        .mobile-header-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .mobile-header-btn {
            color: #64748B;
            font-size: 18px;
            cursor: pointer;
            background: none;
            border: none;
            padding: 4px;
        }

        /* ── Profile & Grid Components (For Dashboard) ───────────── */
        .m-profile-section {
            text-align: center;
            padding: 30px 20px;
            background: #FFFFFF;
        }

        .m-avatar-wrapper {
            position: relative;
            width: 100px;
            height: 100px;
            margin: 0 auto 16px;
        }

        .m-avatar {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: #E2E8F0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: #94A3B8;
            border: 4px solid #F8FAFC;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .m-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .m-privacy-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(4px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            font-size: 24px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .m-privacy-overlay.active {
            opacity: 1;
        }

        .m-greeting {
            font-size: 20px;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 4px;
        }

        .m-subtext {
            font-size: 13px;
            color: #94A3B8;
        }

        .m-section-label {
            font-size: 11px;
            font-weight: 700;
            color: #94A3B8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 20px 0 16px;
            padding: 0 20px;
        }

        .m-grid-menu {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            padding: 0 20px 20px;
        }

        .m-grid-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            position: relative;
        }

        .m-grid-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            transition: transform 0.2s;
        }

        .m-grid-item:active .m-grid-icon {
            transform: scale(0.9);
        }

        .m-grid-label {
            font-size: 11px;
            font-weight: 600;
            color: #64748B;
            text-align: center;
        }

        .m-badge-new {
            position: absolute;
            top: -5px;
            right: 0;
            background: #EF4444;
            color: #FFFFFF;
            font-size: 8px;
            font-weight: 800;
            padding: 1px 4px;
            border-radius: 4px;
            text-transform: uppercase;
            border: 1.5px solid #FFFFFF;
        }

        /* Grid Icon Colors */
        .bg-blue-soft {
            background: #EFF6FF;
            color: #3B82F6;
        }

        .bg-purple-soft {
            background: #F5F3FF;
            color: #8B5CF6;
        }

        .bg-green-soft {
            background: #ECFDF5;
            color: #10B981;
        }

        .bg-orange-soft {
            background: #FFF7ED;
            color: #F97316;
        }

        .bg-pink-soft {
            background: #FDF2F8;
            color: #EC4899;
        }

        .bg-indigo-soft {
            background: #EEF2FF;
            color: #6366F1;
        }

        .m-summary-section {
            padding: 0 20px 30px;
        }

        .m-summary-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .m-summary-title {
            font-size: 18px;
            font-weight: 700;
            color: #0F172A;
        }

        .m-summary-cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .m-summary-card {
            padding: 20px;
            border-radius: 16px;
            color: #FFFFFF;
            position: relative;
            overflow: hidden;
        }

        .m-summary-card.blue {
            background: linear-gradient(135deg, #3B82F6, #2563EB);
        }

        .m-summary-card.green {
            background: linear-gradient(135deg, #10B981, #059669);
        }

        .m-card-label {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            opacity: 0.9;
            margin-bottom: 8px;
        }

        .m-card-value {
            font-size: 18px;
            font-weight: 700;
        }


        .navbar-user-role {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: capitalize;
        }

        .user-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            min-width: 180px;
            border: 1px solid var(--border);
            margin-top: 8px;
            overflow: hidden;
            z-index: 999;
        }

        .user-dropdown.show {
            display: block;
        }

        .user-dropdown a,
        .user-dropdown button {
            display: block;
            width: 100%;
            padding: 10px 16px;
            font-size: 13px;
            color: var(--text);
            text-decoration: none;
            text-align: left;
            border: none;
            background: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .user-dropdown a:hover,
        .user-dropdown button:hover {
            background: var(--primary-50);
            color: var(--primary);
        }

        /* ── Main Content ────────────────────────── */
        .main-content {
            margin-left: var(--sidebar-w);
            margin-top: var(--navbar-h);
            padding: 28px;
            min-height: calc(100vh - var(--navbar-h));
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ── Cards ───────────────────────────────── */
        .card {
            background: var(--bg-card);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            transition: all 0.2s ease;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header h3 {
            font-size: 15px;
            font-weight: 600;
        }

        .card-body {
            padding: 20px;
        }

        /* ── Stat Cards ──────────────────────────── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--bg-card);
            border-radius: var(--radius);
            padding: 20px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            transition: all 0.2s ease;
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .stat-icon.blue {
            background: var(--primary-light);
            color: var(--primary);
        }

        .stat-icon.green {
            background: #D1FAE5;
            color: var(--success);
        }

        .stat-icon.yellow {
            background: #FEF3C7;
            color: var(--warning);
        }

        .stat-icon.red {
            background: #FEE2E2;
            color: var(--danger);
        }

        .stat-icon.purple {
            background: #EDE9FE;
            color: #8B5CF6;
        }

        .stat-icon.cyan {
            background: #CFFAFE;
            color: #06B6D4;
        }

        .stat-info h4 {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 500;
            margin-bottom: 4px;
        }

        .stat-value {
            font-size: 26px;
            font-weight: 700;
            color: var(--text);
            line-height: 1;
        }

        /* ── Tables ──────────────────────────────── */
        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            padding: 12px 16px;
            text-align: center;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--border);
            background: var(--primary-50);
        }

        tbody td {
            padding: 12px 16px;
            font-size: 13.5px;
            border-bottom: 1px solid var(--border);
            color: var(--text);
            vertical-align: middle;
            text-align: center;
        }

        /* Flex Utilities */
        .flex {
            display: flex;
            align-items: center;
        }

        .flex-wrap {
            flex-wrap: wrap;
        }

        .justify-center {
            justify-content: center;
        }

        .gap-2 {
            gap: 8px;
        }

        tbody td.flex {
            justify-content: center;
        }

        tbody tr {
            transition: all 0.2s ease;
        }

        tbody tr:hover {
            background: var(--primary-50);
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        /* ── Buttons ─────────────────────────────── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 6px;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-success {
            background: var(--success);
            color: #fff;
        }

        .btn-success:hover {
            background: #059669;
        }

        .btn-warning {
            background: var(--warning);
            color: #fff;
        }

        .btn-warning:hover {
            background: #D97706;
        }

        .btn-danger {
            background: var(--danger);
            color: #fff;
        }

        .btn-danger:hover {
            background: #DC2626;
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-secondary);
        }

        .btn-outline:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-50);
        }

        /* ── Forms ────────────────────────────────── */
        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 6px;
            color: var(--text);
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.2s ease;
            background: #fff;
            color: var(--text);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748B' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 32px;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .form-error {
            color: var(--danger);
            font-size: 12px;
            margin-top: 4px;
        }

        /* ── Badges ───────────────────────────────── */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-green {
            background: #D1FAE5;
            color: #065F46;
        }

        .badge-yellow {
            background: #FEF3C7;
            color: #92400E;
        }

        .badge-red {
            background: #FEE2E2;
            color: #991B1B;
        }

        .badge-blue {
            background: var(--primary-light);
            color: var(--primary-dark);
        }

        .badge-gray {
            background: #F1F5F9;
            color: #475569;
        }

        /* ── Alerts ──────────────────────────────── */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 13.5px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideDown 0.3s ease;
        }

        .alert-success {
            background: #D1FAE5;
            color: #065F46;
            border: 1px solid #A7F3D0;
        }

        .alert-error {
            background: #FEE2E2;
            color: #991B1B;
            border: 1px solid #FECACA;
        }

        .alert-warning {
            background: #FEF3C7;
            color: #92400E;
            border: 1px solid #FDE68A;
        }

        /* ── Premium Pagination ──────────────────── */
        .pagination-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 24px;
            background: #fff;
            border-top: 1px solid var(--border);
            border-radius: 0 0 16px 16px;
        }

        .pagination-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .pagination-per-page select {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 13px;
            border: 1px solid var(--border);
            color: var(--text-secondary);
            background: #fff;
            cursor: pointer;
            outline: none;
            transition: all 0.2s;
        }

        .pagination-per-page select:hover {
            border-color: var(--primary);
        }

        .pagination-info {
            font-size: 13px;
            color: var(--text-secondary);
        }

        .pagination-info span {
            font-weight: 600;
            color: var(--text-main);
        }

        .pagination-links {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pagination-numbers {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .pagination-item {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 12px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid var(--border);
            background: #fff;
            cursor: pointer;
            user-select: none;
        }

        .pagination-item i {
            font-size: 11px;
        }

        .pagination-item:hover:not(.disabled):not(.active) {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-light);
            transform: translateY(-1px);
        }

        .pagination-item.active {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
            box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
        }

        .pagination-item.disabled {
            opacity: 0.4;
            cursor: not-allowed;
            background: #F8FAFC;
        }

        /* Mobile Adjustments for Pagination */
        @media (max-width: 640px) {
            .pagination-container {
                flex-direction: column;
                gap: 16px;
                padding: 16px;
                text-align: center;
            }

            .pagination-numbers {
                display: none; /* Hide numbers on very small screens, keep Prev/Next */
            }
        }

        .table-footer {
            background: #F8FAFC;
            border-top: 1px solid var(--border);
        }

        /* ── Modal ───────────────────────────────── */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 200;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal {
            background: white;
            border-radius: var(--radius);
            padding: 24px;
            max-width: 480px;
            width: 90%;
            box-shadow: var(--shadow-lg);
            animation: modalIn 0.2s ease;
        }

        .modal h3 {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .modal p {
            color: var(--text-secondary);
            font-size: 14px;
            margin-bottom: 20px;
        }

        .modal-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }

        /* ── Animations ──────────────────────────── */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* ── Hamburger ───────────────────────────── */
        .hamburger {
            display: none;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: var(--text);
            padding: 8px;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.4);
            z-index: 2000;
            opacity: 0;
            backdrop-filter: blur(0px);
            transition: opacity 0.3s ease, backdrop-filter 0.3s ease;
        }

        .sidebar-overlay.show {
            display: block;
            opacity: 1;
            backdrop-filter: blur(8px);
        }

        /* ── Desktop Sidebar Tweaks ──────────────────── */
        @media (min-width: 1025px) {

            .sidebar-mobile-header,
            .m-header-actions {
                display: none !important;
            }

            .sidebar-card {
                overflow: visible;
                display: flex;
                flex-direction: column;
            }
        }

        /* ── Responsive Mobile ──────────────────────────── */
        @media (max-width: 1024px) {
            .sidebar-wrapper {
                display: none;
                left: auto !important;
                right: -320px !important;
                width: 320px;
                height: 100vh;
                background: #FFFFFF;
                z-index: 2001;
                padding: 0;
                transition: right 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
                box-shadow: -10px 0 30px rgba(0, 0, 0, 0.05);
                overflow-y: auto;
                transform: none !important;
            }

            .sidebar-wrapper.open {
                display: block;
                right: 0 !important;
                left: auto !important;
            }

            .sidebar-card {
                border-radius: 0;
                border: none;
                min-height: 100%;
                margin: 0;
                display: block;
            }

            .sidebar-brand {
                display: none;
            }

            .sidebar-mobile-header {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 24px 20px;
                background: #FFFFFF;
                border-bottom: 1px solid #F1F5F9;
            }

            .m-user-avatar {
                width: 48px;
                height: 48px;
                border-radius: 50%;
                background: #F1F5F9;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                flex-shrink: 0;
                border: 2px solid #FFFFFF;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            }

            .m-user-avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .m-user-avatar span {
                font-weight: 700;
                color: #3B82F6;
                font-size: 18px;
            }

            .m-user-details {
                display: flex;
                flex-direction: column;
                flex: 1;
            }

            .m-user-name {
                font-weight: 700;
                color: #0F172A;
                font-size: 15px;
                line-height: 1.2;
            }

            .m-user-id {
                font-size: 12px;
                color: #64748B;
                margin-top: 2px;
            }

            .m-header-actions {
                display: flex;
                gap: 8px;
                align-items: center;
            }

            .m-close-btn,
            .m-logout-icon-btn {
                width: 36px;
                height: 36px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.2s;
                border: none;
            }

            .m-close-btn {
                background: #F8FAFC;
                color: #64748B;
            }

            .m-logout-icon-btn {
                background: #FFF1F2;
                color: #E11D48;
            }

            .m-logout-icon-btn:active {
                transform: scale(0.92);
            }

            .sidebar-menu {
                padding: 0 20px 30px;
                flex: 1;
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
                display: block;
            }

            .sidebar-link {
                margin-bottom: 12px;
                padding: 18px 24px;
                border-radius: 18px;
                font-size: 15.5px;
                font-weight: 500;
                color: #475569;
                display: flex;
                align-items: center;
                gap: 20px;
                text-decoration: none;
                transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .sidebar-link i {
                font-size: 22px;
                width: 26px;
                text-align: center;
            }

            .sidebar-link.active {
                background: var(--primary);
                color: #FFFFFF;
                box-shadow: 0 10px 24px rgba(59, 130, 246, 0.35);
                font-weight: 600;
            }

            .sidebar-label {
                padding: 24px 24px 20px;
                font-size: 12px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 1.3px;
                color: #94A3B8;
                opacity: 0.9;
            }

            /* Add more space for subsequent labels */
            .sidebar-label~.sidebar-label {
                padding-top: 22px;
            }

            .m-btn {
                width: 100%;
                padding: 14px;
                border-radius: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                font-size: 14px;
                font-weight: 600;
                text-decoration: none;
                border: none;
                cursor: pointer;
                transition: all 0.2s;
            }

            .m-btn-settings {
                background: #FFFFFF;
                color: #475569;
                border: 1px solid #E2E8F0;
            }

            .m-btn-logout {
                background: #FFF1F2;
                color: #E11D48;
            }

            .sidebar-user-section {
                display: none;
            }

            .sidebar-footer-note {
                display: none;
            }

            /* Responsive Essentials */
            .navbar {
                display: flex;
            }

            .mobile-header,
            .bottom-nav {
                display: none !important;
            }

            @media (max-width: 1024px) {
                .navbar {
                    display: none !important;
                }

                .mobile-header {
                    display: flex !important;
                }

                .bottom-nav {
                    display: flex !important;
                }

                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                height: 70px;
                background: rgba(255, 255, 255, 0.85);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border-bottom: 1px solid rgba(255, 255, 255, 0.3);
                z-index: 1000;
                padding: 0 20px;
                align-items: center;
                justify-content: space-between;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            }

            .mobile-header-left {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .mobile-header-logo {
                width: 38px;
                height: 38px;
                object-fit: contain;
            }

            .mobile-header-title {
                font-size: 18px;
                font-weight: 800;
                color: #0F172A;
                letter-spacing: -0.5px;
            }

            .mobile-header-right {
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .mobile-header-btn {
                width: 38px;
                height: 38px;
                border-radius: 12px;
                background: transparent;
                border: none;
                color: #64748B;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 16px;
                transition: all 0.2s;
            }

            .mobile-header-btn:active {
                background: #F1F5F9;
                transform: scale(0.9);
            }

            /* ── Premium Glassmorphism Bottom Nav ────── */
            .bottom-nav {
                display: flex;
                position: fixed;
                bottom: 20px;
                left: 20px;
                right: 20px;
                height: 75px;
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(25px);
                -webkit-backdrop-filter: blur(25px);
                border-radius: 24px;
                border: 1px solid rgba(255, 255, 255, 0.4);
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
                z-index: 1000;
                justify-content: space-around;
                align-items: center;
                padding: 0 10px;
            }

            .bottom-nav-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 5px;
                color: #94A3B8;
                text-decoration: none;
                font-size: 10px;
                font-weight: 600;
                padding: 8px 12px;
                border-radius: 16px;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .bottom-nav-item i {
                font-size: 20px;
            }

            .bottom-nav-item.active {
                color: var(--primary);
                background: rgba(59, 130, 246, 0.08);
            }

            .bottom-nav-item.active i {
                transform: translateY(-2px);
            }

            /* ── Quick Access Grid (Mobile) ────── */
            .quick-access-section {
                padding: 24px 0;
            }

            .section-label {
                font-size: 12px;
                font-weight: 800;
                color: #94A3B8;
                text-transform: uppercase;
                letter-spacing: 1px;
                margin-bottom: 20px;
            }

            .quick-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 20px 10px;
            }

            .quick-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 10px;
                text-decoration: none;
                transition: all 0.2s;
            }

            .quick-item:active {
                transform: scale(0.9);
            }

            .quick-icon-box {
                width: 54px;
                height: 54px;
                border-radius: 18px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.04);
            }

            .quick-label {
                font-size: 11px;
                font-weight: 700;
                color: #64748B;
                text-align: center;
            }

            /* Icon Colors */
            .bg-blue-soft {
                background: #EFF6FF;
                color: #3B82F6;
            }

            .bg-purple-soft {
                background: #F5F3FF;
                color: #8B5CF6;
            }

            .bg-green-soft {
                background: #ECFDF5;
                color: #10B981;
            }

            .bg-orange-soft {
                background: #FFF7ED;
                color: #F97316;
            }

            .bg-red-soft {
                background: #FEF2F2;
                color: #EF4444;
            }

            .bg-cyan-soft {
                background: #ECFEFF;
                color: #06B6D4;
            }

            .bg-pink-soft {
                background: #FDF2F7;
                color: #DB2777;
            }

            .bg-indigo-soft {
                background: #EEF2FF;
                color: #6366F1;
            }

            /* ── Mobile UI Helpers ────── */
            .mobile-only-ui {
                display: block;
            }

            .desktop-only-ui {
                display: none;
            }

            .main-content {
                margin-left: 0 !important;
                margin-top: 70px !important;
                padding: 20px 16px 110px !important;
                background: #F8FAFC;
                min-height: 100vh;
            }

            .welcome-card-mobile {
                padding: 40px 0;
                text-align: center;
                background: radial-gradient(circle at top right, #F8FAFC, #FFFFFF);
            }

            .privacy-icon-box {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                background: #E2E8F0;
                margin: 0 auto 24px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 32px;
                color: #94A3B8;
                position: relative;
            }

            .privacy-icon-box::after {
                content: '';
                position: absolute;
                inset: -10px;
                border-radius: 50%;
                border: 1px solid #E2E8F0;
                opacity: 0.5;
            }
        }

        @media (min-width: 1025px) {
            .mobile-only-ui {
                display: none;
            }

            .desktop-only-ui {
                display: block;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ── Utilities ───────────────────────────── */
        .text-center {
            text-align: center;
        }

        .mb-4 {
            margin-bottom: 16px;
        }

        .mt-4 {
            margin-top: 16px;
        }

        .flex {
            display: flex;
        }

        .items-center {
            align-items: center;
        }

        .justify-between {
            justify-content: space-between;
        }

        .gap-2 {
            gap: 8px;
        }

        .gap-4 {
            gap: 16px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        @media (max-width: 768px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }

        /* ── Welcome Header ─────────────────────── */
        .welcome-header {
            display: flex;
            align-items: center;
            gap: 24px;
            margin-bottom: 32px;
            padding: 10px 0;
        }

        .welcome-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #F1F5F9;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 4px solid #FFFFFF;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            flex-shrink: 0;
        }

        .welcome-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .welcome-avatar i {
            font-size: 32px;
            color: var(--text-muted);
        }

        .welcome-text h2 {
            font-size: 26px;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 4px;
            letter-spacing: -0.5px;
        }

        .welcome-text p {
            font-size: 14px;
            color: var(--text-secondary);
        }

        .hide-mobile {
            display: inline-block;
        }

        @media (max-width: 768px) {
            .hide-mobile {
                display: none !important;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    {{-- Custom Premium Sidebar Wrapper --}}
    <aside class="sidebar-wrapper" id="sidebarWrapper">
        <div class="sidebar-card">
            {{-- Collapse Button exactly matching screenshot --}}
            <button class="sidebar-collapse-btn" onclick="toggleSidebarCollapse()" title="Toggle Sidebar">
                <i class="fas fa-chevron-left"></i>
            </button>

            {{-- New Mobile Header --}}
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
                    <span class="m-user-id">{{ auth()->user()->identifier }}</span>
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

            {{-- Footer Note inside menu matching screenshot aesthetic exactly --}}
            <div class="sidebar-footer-note">
                <i class="fas fa-code"></i> Dibuat untuk <span>@sman1tajurhalang</span>
            </div>

            {{-- Bottom User Profile Card --}}
            <div class="sidebar-user-section" id="sidebarUserSection" onclick="toggleBottomUserDropdown(event)">
                <div class="user-avatar-box">
                    <span>{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    <div class="online-dot"></div>
                </div>
                <div class="user-info-box">
                    <div class="user-name-text">{{ auth()->user()->name }}</div>
                    <div class="user-sub-text">Klik untuk menu</div>
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

    {{-- Mobile Header (MySIKA Aesthetic) --}}
    <header class="mobile-header">
        <div class="mobile-header-left">
            <img src="{{ asset('assets/logo-sekolah.png') }}" class="mobile-header-logo" alt="Logo">
            <div class="mobile-header-title">
                LMS SMAN 1
            </div>
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
            {{-- Keep classic top right profile simple/functional as fallback --}}
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

    {{-- Bottom Navigation (Dynamic by Role) --}}
    <nav class="bottom-nav">
        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}"
                class="bottom-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i><span>Dash</span>
            </a>
            <a href="{{ route('admin.guru.index') }}"
                class="bottom-nav-item {{ request()->routeIs('admin.guru.*') ? 'active' : '' }}">
                <i class="fas fa-chalkboard-teacher"></i><span>Guru</span>
            </a>
            <a href="{{ route('admin.siswa.index') }}"
                class="bottom-nav-item {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
                <i class="fas fa-user-graduate"></i><span>Siswa</span>
            </a>
            <a href="{{ route('admin.kelas.index') }}"
                class="bottom-nav-item {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
                <i class="fas fa-school"></i><span>Kelas</span>
            </a>
            <a href="{{ route('admin.mapel.index') }}"
                class="bottom-nav-item {{ request()->routeIs('admin.mapel.*') ? 'active' : '' }}">
                <i class="fas fa-book"></i><span>Mapel</span>
            </a>
        @elseif(auth()->user()->isGuru())
            <a href="{{ route('guru.dashboard') }}"
                class="bottom-nav-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i><span>Dash</span>
            </a>
            <a href="{{ route('guru.materi.index') }}"
                class="bottom-nav-item {{ request()->routeIs('guru.materi.*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i><span>Materi</span>
            </a>
            <a href="{{ route('guru.tugas.index') }}"
                class="bottom-nav-item {{ request()->routeIs('guru.tugas.*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i><span>Tugas</span>
            </a>
            <a href="{{ route('guru.nilai.index') }}"
                class="bottom-nav-item {{ request()->routeIs('guru.nilai.*') ? 'active' : '' }}">
                <i class="fas fa-star"></i><span>Nilai</span>
            </a>
            <a href="{{ route('guru.profil.edit') }}"
                class="bottom-nav-item {{ request()->routeIs('guru.profil.*') ? 'active' : '' }}">
                <i class="fas fa-user-cog"></i><span>Profil</span>
            </a>
        @elseif(auth()->user()->isSiswa())
            <a href="{{ route('siswa.dashboard') }}"
                class="bottom-nav-item {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i><span>Dash</span>
            </a>
            <a href="{{ route('siswa.materi.index') }}"
                class="bottom-nav-item {{ request()->routeIs('siswa.materi.*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i><span>Materi</span>
            </a>
            <a href="{{ route('siswa.tugas.index') }}"
                class="bottom-nav-item {{ request()->routeIs('siswa.tugas.*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i><span>Tugas</span>
            </a>
            <a href="{{ route('siswa.nilai.index') }}"
                class="bottom-nav-item {{ request()->routeIs('siswa.nilai.*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i><span>Nilai</span>
            </a>
            <a href="{{ route('siswa.profil.edit') }}"
                class="bottom-nav-item {{ request()->routeIs('siswa.profil.*') ? 'active' : '' }}">
                <i class="fas fa-user-cog"></i><span>Profil</span>
            </a>
        @endif
    </nav>

    <script>
        // Toggle mobile sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebarWrapper');
            const overlay = document.getElementById('sidebarOverlay');
            if (sidebar.style.display === 'none' || !sidebar.classList.contains('open')) {
                sidebar.style.display = 'block';
                setTimeout(() => {
                    sidebar.classList.add('open');
                    overlay.classList.add('show');
                }, 10);
            } else {
                sidebar.classList.remove('open');
                overlay.classList.remove('show');
                setTimeout(() => sidebar.style.display = 'none', 300);
            }
        }

        // Toggle sidebar collapse state (desktop animation)
        function toggleSidebarCollapse() {
            document.body.classList.toggle('sidebar-collapsed');
            // Save state in localStorage to persist across refreshes
            const isCollapsed = document.body.classList.contains('sidebar-collapsed');
            localStorage.setItem('sidebar_collapsed', isCollapsed ? 'yes' : 'no');
        }

        // Apply persisted collapse state instantly on load
        document.addEventListener('DOMContentLoaded', function () {
            if (localStorage.getItem('sidebar_collapsed') === 'yes' && window.innerWidth > 768) {
                document.body.classList.add('sidebar-collapsed');
            }
        });

        // Toggle bottom user dropdown
        function toggleBottomUserDropdown(e) {
            e.stopPropagation();
            const dd = document.getElementById('bottomUserDropdown');
            const section = document.getElementById('sidebarUserSection');
            if (dd) {
                dd.classList.toggle('show');
                section.classList.toggle('open');
            }
            // Close top dropdown if open
            const topDd = document.getElementById('userDropdown');
            if (topDd) topDd.classList.remove('show');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function (e) {
            const bottomDd = document.getElementById('bottomUserDropdown');
            const bottomSection = document.getElementById('sidebarUserSection');
            if (bottomDd && !e.target.closest('#sidebarUserSection')) {
                bottomDd.classList.remove('show');
                if (bottomSection) bottomSection.classList.remove('open');
            }

            const topDd = document.getElementById('userDropdown');
            if (topDd && !e.target.closest('.navbar-user')) {
                topDd.classList.remove('show');
            }
        });
    </script>
    @stack('scripts')
</body>

</html>