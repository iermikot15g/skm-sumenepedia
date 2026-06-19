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
        :root {
            --primary: #1a3a5c;
            --primary-light: #2d6a9f;
            --sidebar-width: 260px;
        }
        body {
            background: #f0f4f8;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--primary);
            color: white;
            padding: 20px 0;
            overflow-y: auto;
            z-index: 1000;
        }
        .sidebar .brand {
            text-align: center;
            padding: 10px 0 25px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar .brand h5 {
            font-weight: 700;
        }
        .sidebar .brand small {
            opacity: 0.7;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 12px 25px;
            border-radius: 0;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255,255,255,0.1);
        }
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.15);
            border-right: 3px solid #fbbf24;
        }
        .sidebar .nav-link i {
            width: 22px;
            margin-right: 10px;
        }
        .sidebar .nav-section {
            padding: 15px 25px 5px;
            font-size: 0.7rem;
            text-transform: uppercase;
            opacity: 0.5;
            letter-spacing: 1px;
        }
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px 30px;
            min-height: 100vh;
        }
        .topbar {
            background: white;
            border-radius: 16px;
            padding: 15px 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .topbar .page-title {
            font-weight: 600;
            color: var(--primary);
        }
        .topbar .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .topbar .user-info .avatar {
            width: 40px;
            height: 40px;
            background: var(--primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
        }
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 20px 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }
        .stat-card .number {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--primary);
        }
        .stat-card .label {
            color: #6b7280;
            font-size: 0.9rem;
        }
        .stat-card .icon {
            font-size: 2rem;
            opacity: 0.2;
        }
        .card-custom {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: none;
        }
        .card-custom .card-header {
            background: none;
            border-bottom: 1px solid #e5e7eb;
            padding: 0 0 15px;
            font-weight: 600;
        }
        .badge-mutu {
            padding: 5px 12px;
            border-radius: 20px;
        }
        .badge-mutu-a { background: #d1fae5; color: #065f46; }
        .badge-mutu-b { background: #dbeafe; color: #1e40af; }
        .badge-mutu-c { background: #fef3c7; color: #92400e; }
        .badge-mutu-d { background: #fecaca; color: #991b1b; }
        .btn-primary-custom {
            background: var(--primary);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 50px;
            transition: all 0.3s;
        }
        .btn-primary-custom:hover {
            background: var(--primary-light);
            color: white;
        }
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
            <h5>SKM Sumenep</h5>
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
            <a href="{{ route('admin.layanan.index') }}" class="nav-link {{ request()->routeIs('admin.layanan.*') ? 'active' : '' }}">
                <i class="fas fa-list"></i> Layanan
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
        
        <div class="nav-section" style="margin-top: auto;">Akun</div>
        <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar">
            <div class="page-title">@yield('page-title')</div>
            <div class="user-info">
                <span>{{ auth()->user()->name }}</span>
                <span class="badge bg-secondary">{{ auth()->user()->role }}</span>
                <div class="avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
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