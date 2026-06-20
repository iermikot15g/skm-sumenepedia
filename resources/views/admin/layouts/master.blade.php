<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard SKM') - Kabupaten Sumenep</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.css" rel="stylesheet">
    <style>
        /* ========== GREEN LUXURY THEME ========== */
        :root {
            --green-dark: #0d5c3b;
            --green-primary: #1a7a4e;
            --green-mid: #28a06b;
            --green-light: #d4edda;
            --green-bg: #f0faf4;
            --gold: #c8a96e;
            --gold-light: #e8d5b5;
            --text-dark: #1a2e28;
            --text-muted: #5a7a6a;
            --sidebar-width: 260px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--green-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-dark);
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--green-dark) 0%, var(--green-primary) 100%);
            color: white;
            padding: 20px 0;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 4px 0 20px rgba(13, 92, 59, 0.15);
        }

        .sidebar .brand {
            text-align: center;
            padding: 10px 0 25px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar .brand h5 {
            font-weight: 700;
            letter-spacing: 1px;
            color: var(--gold);
        }

        .sidebar .brand small {
            opacity: 0.7;
            font-weight: 300;
        }

        .sidebar .nav-section {
            padding: 15px 25px 5px;
            font-size: 0.65rem;
            text-transform: uppercase;
            opacity: 0.5;
            letter-spacing: 2px;
            font-weight: 600;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 12px 25px;
            border-radius: 0;
            transition: all 0.3s;
            font-weight: 500;
            border-left: 3px solid transparent;
        }

        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255,255,255,0.08);
            border-left-color: var(--gold);
        }

        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.12);
            border-left-color: var(--gold);
        }

        .sidebar .nav-link i {
            width: 22px;
            margin-right: 12px;
            color: var(--gold-light);
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px 30px;
            min-height: 100vh;
        }

        /* ===== TOPBAR ===== */
        .topbar {
            background: white;
            border-radius: 16px;
            padding: 15px 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.04);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid var(--gold);
        }

        .topbar .page-title {
            font-weight: 600;
            color: var(--green-dark);
            font-size: 1.2rem;
        }

        .topbar .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .topbar .user-info .avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--green-primary), var(--green-mid));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            border: 2px solid var(--gold-light);
        }

        /* ===== STAT CARDS ===== */
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 20px 25px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.04);
            transition: all 0.3s;
            border-bottom: 4px solid var(--gold);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        }

        .stat-card .number {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--green-primary);
        }

        .stat-card .label {
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .stat-card .icon {
            font-size: 2rem;
            color: var(--gold);
            opacity: 0.25;
        }

        /* ===== CUSTOM CARDS ===== */
        .card-custom {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.04);
            border: none;
        }

        .card-custom .card-header {
            background: none;
            border-bottom: 2px solid var(--green-light);
            padding: 0 0 15px;
            font-weight: 600;
            color: var(--green-dark);
        }

        /* ===== BADGES ===== */
        .badge-mutu {
            padding: 5px 14px;
            border-radius: 20px;
            font-weight: 600;
        }
        .badge-mutu-a { background: #d4edda; color: #0d5c3b; }
        .badge-mutu-b { background: #cce5ff; color: #004085; }
        .badge-mutu-c { background: #fff3cd; color: #856404; }
        .badge-mutu-d { background: #f8d7da; color: #721c24; }

        /* ===== BUTTONS ===== */
        .btn-primary-custom {
            background: var(--green-primary);
            color: white;
            border: none;
            padding: 8px 24px;
            border-radius: 50px;
            transition: all 0.3s;
            font-weight: 600;
        }

        .btn-primary-custom:hover {
            background: var(--green-dark);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(26, 122, 78, 0.3);
        }

        .btn-gold {
            background: var(--gold);
            color: var(--green-dark);
            border: none;
            padding: 8px 24px;
            border-radius: 50px;
            transition: all 0.3s;
            font-weight: 600;
        }

        .btn-gold:hover {
            background: #d4b87a;
            color: var(--green-dark);
            transform: translateY(-2px);
        }

        /* ===== SCROLLBAR SIDEBAR ===== */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: var(--gold);
            border-radius: 10px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- ===== SIDEBAR ===== -->
    <div class="sidebar">
        <div class="brand">
            <h5><i class="fas fa-leaf" style="color: var(--gold);"></i> SKM Sumenep</h5>
            <small>Survei Kepuasan Masyarakat</small>
        </div>

        <div class="nav-section">Menu Utama</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i> Dashboard
        </a>

        @if(auth()->user()->role == 'super_admin')
            <div class="nav-section">Manajemen</div>
            <a href="{{ route('admin.unit-pelayanan.index') }}" class="nav-link {{ request()->routeIs('admin.unit-pelayanan.*') ? 'active' : '' }}">
                <i class="fas fa-building"></i> Unit Pelayanan
            </a>
            <a href="{{ route('admin.periode.index') }}" class="nav-link {{ request()->routeIs('admin.periode.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i> Periode Survei
            </a>
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> User
            </a>
            <div class="nav-section">Log & Laporan</div>
            <a href="{{ route('admin.audit-logs') }}" class="nav-link {{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}">
                <i class="fas fa-history"></i> Audit Log
            </a>
        @endif

        @if(in_array(auth()->user()->role, ['super_admin', 'admin_unit']))
            <div class="nav-section">Data & Laporan</div>
            <a href="{{ route('admin.layanan.index') }}" class="nav-link {{ request()->routeIs('admin.layanan.*') ? 'active' : '' }}">
                <i class="fas fa-list"></i> Layanan
            </a>
            <a href="{{ route('admin.data-survei') }}" class="nav-link {{ request()->routeIs('admin.data-survei.*') ? 'active' : '' }}">
                <i class="fas fa-database"></i> Data Survei
            </a>
            <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i> Laporan
            </a>
        @endif

        @if(in_array(auth()->user()->role, ['pimpinan_unit', 'pimpinan_utama']))
            <div class="nav-section">Laporan</div>
            <a href="{{ route('admin.laporan.index') }}" class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i> Laporan
            </a>
        @endif

        <!-- ===== LOGOUT ===== -->
        <div class="nav-section" style="margin-top: auto;">Akun</div>
        <form action="{{ route('logout') }}" method="POST" id="logout-form">
            @csrf
            <button type="submit" class="nav-link" style="background:none; border:none; color:rgba(255,255,255,0.7); width:100%; text-align:left; padding:12px 25px; cursor:pointer; font-weight:500;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="main-content">
        <div class="topbar">
            <div class="page-title">@yield('page-title')</div>
            <div class="user-info">
                <span>{{ auth()->user()->name }}</span>
                <span class="badge" style="background: var(--gold); color: var(--green-dark); font-weight:600;">
                    {{ auth()->user()->role }}
                </span>
                <div class="avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-left: 4px solid var(--green-primary);">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-left: 4px solid #dc3545;">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @yield('scripts')
</body>
</html>