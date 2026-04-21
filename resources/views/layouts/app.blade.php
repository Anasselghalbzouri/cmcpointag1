<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CMC Pointage')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 270px;
            --sidebar-collapsed: 0px;
            --header-height: 64px;
            --bg-sidebar: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            --bg-body: #f1f5f9;
            --accent: #3b82f6;
            --accent-hover: #2563eb;
            --accent-light: rgba(59, 130, 246, 0.1);
            --text-sidebar: #94a3b8;
            --text-sidebar-active: #ffffff;
            --border-color: #e2e8f0;
            --card-shadow: 0 1px 3px rgba(0,0,0,.04), 0 4px 16px rgba(0,0,0,.04);
            --card-shadow-hover: 0 4px 24px rgba(0,0,0,.08);
            --glass-bg: rgba(255,255,255,.82);
            --glass-border: rgba(255,255,255,.3);
            --transition: all .2s cubic-bezier(.4,0,.2,1);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-body);
            margin: 0;
            min-height: 100vh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Sidebar ─────────────────────────────── */
        .sidebar {
            position: fixed;
            left: 0; top: 0; bottom: 0;
            width: var(--sidebar-width);
            background: var(--bg-sidebar);
            z-index: 1040;
            display: flex;
            flex-direction: column;
            transition: transform .3s cubic-bezier(.4,0,.2,1);
            border-right: 1px solid rgba(255,255,255,.06);
        }

        .sidebar-brand {
            padding: 1.5rem 1.5rem 1rem;
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .sidebar-brand-icon {
            width: 40px; height: 40px;
            background: var(--accent);
            border-radius: .75rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            color: #fff;
            font-weight: 800;
            box-shadow: 0 4px 12px rgba(59,130,246,.35);
        }
        .sidebar-brand-text {
            color: #fff;
            font-weight: 800;
            font-size: 1.15rem;
            letter-spacing: -.01em;
        }
        .sidebar-brand-sub {
            color: #64748b;
            font-size: .68rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            font-weight: 600;
        }

        .sidebar-nav {
            flex: 1;
            padding: .5rem .75rem;
            overflow-y: auto;
        }
        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 3px; }

        .nav-section-label {
            color: #475569;
            font-size: .65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            padding: 1.25rem .75rem .5rem;
        }

        .nav-link-sidebar {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .7rem .75rem;
            border-radius: .625rem;
            color: var(--text-sidebar);
            text-decoration: none;
            font-size: .875rem;
            font-weight: 500;
            transition: var(--transition);
            margin-bottom: 2px;
            position: relative;
        }
        .nav-link-sidebar:hover {
            color: #e2e8f0;
            background: rgba(255,255,255,.06);
        }
        .nav-link-sidebar.active {
            color: var(--text-sidebar-active);
            background: var(--accent-light);
            font-weight: 600;
        }
        .nav-link-sidebar.active::before {
            content: '';
            position: absolute;
            left: -.75rem;
            top: 50%; transform: translateY(-50%);
            width: 3px; height: 60%;
            background: var(--accent);
            border-radius: 0 3px 3px 0;
        }
        .nav-link-sidebar .bi {
            font-size: 1.15rem;
            width: 1.5rem;
            text-align: center;
            opacity: .7;
        }
        .nav-link-sidebar.active .bi { opacity: 1; color: var(--accent); }

        .nav-badge {
            margin-left: auto;
            font-size: .65rem;
            font-weight: 700;
            padding: .2rem .5rem;
            border-radius: 999px;
            min-width: 1.5rem;
            text-align: center;
        }

        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,.06);
        }
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .sidebar-avatar {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: .625rem;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: .85rem;
        }
        .sidebar-user-name {
            color: #e2e8f0;
            font-size: .85rem;
            font-weight: 600;
        }
        .sidebar-user-role {
            color: #64748b;
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            font-weight: 600;
        }

        /* ── Header ──────────────────────────────── */
        .main-header {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--header-height);
            background: var(--glass-bg);
            backdrop-filter: blur(12px) saturate(180%);
            -webkit-backdrop-filter: blur(12px) saturate(180%);
            border-bottom: 1px solid var(--border-color);
            z-index: 1030;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            transition: left .3s cubic-bezier(.4,0,.2,1);
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex: 1;
        }
        .header-title {
            font-weight: 700;
            font-size: 1.05rem;
            color: #1e293b;
        }
        .header-breadcrumb {
            color: #94a3b8;
            font-size: .8rem;
            font-weight: 500;
        }
        .header-right {
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .header-icon-btn {
            width: 38px; height: 38px;
            border-radius: .625rem;
            border: 1px solid var(--border-color);
            background: #fff;
            display: flex; align-items: center; justify-content: center;
            color: #64748b;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            position: relative;
        }
        .header-icon-btn:hover {
            background: #f8fafc;
            color: #1e293b;
            border-color: #cbd5e1;
        }
        .header-notification-dot {
            position: absolute;
            top: 6px; right: 6px;
            width: 7px; height: 7px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid #fff;
        }
        .btn-sidebar-toggle {
            display: none;
            width: 38px; height: 38px;
            border-radius: .625rem;
            border: 1px solid var(--border-color);
            background: #fff;
            align-items: center; justify-content: center;
            color: #64748b;
            cursor: pointer;
        }

        /* ── Main Content ────────────────────────── */
        .main-content {
            margin-left: var(--sidebar-width);
            padding-top: var(--header-height);
            min-height: 100vh;
            transition: margin-left .3s cubic-bezier(.4,0,.2,1);
        }
        .content-wrapper {
            padding: 1.5rem;
            max-width: 1440px;
        }

        /* ── Sidebar Overlay for Mobile ──────────── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.4);
            z-index: 1035;
            backdrop-filter: blur(2px);
        }

        /* ── Cards ────────────────────────────────── */
        .card {
            border: 1px solid var(--border-color);
            border-radius: .875rem;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
        }
        .card:hover {
            box-shadow: var(--card-shadow-hover);
        }

        .kpi-card {
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            box-shadow: var(--card-shadow);
            background: #fff;
            transition: var(--transition);
        }
        .kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--card-shadow-hover);
        }
        .kpi-icon {
            width: 48px; height: 48px;
            border-radius: .75rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem;
        }
        .kpi-label {
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #64748b;
            font-weight: 600;
        }
        .kpi-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1e293b;
            line-height: 1;
        }

        /* ── Tables ───────────────────────────────── */
        .table thead th {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #64748b;
            font-weight: 700;
            border-bottom-width: 1px;
            padding: .75rem 1rem;
            background: #f8fafc;
        }
        .table tbody td {
            padding: .75rem 1rem;
            vertical-align: middle;
            font-size: .875rem;
            color: #334155;
        }
        .table-hover tbody tr:hover {
            background: rgba(59,130,246,.03);
        }

        /* ── Status Badges ────────────────────────── */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .3rem .7rem;
            border-radius: 999px;
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .02em;
        }
        .status-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            display: inline-block;
        }

        /* ── Search ───────────────────────────────── */
        .search-box {
            position: relative;
        }
        .search-box .bi {
            position: absolute;
            left: .875rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: .95rem;
        }
        .search-box input {
            padding-left: 2.5rem;
            border: 1px solid var(--border-color);
            border-radius: .75rem;
            height: 42px;
            font-size: .875rem;
            background: #fff;
            transition: var(--transition);
        }
        .search-box input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59,130,246,.12);
        }

        /* ── Animations ──────────────────────────── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in {
            animation: fadeInUp .4s ease-out both;
        }
        .delay-1 { animation-delay: .05s; }
        .delay-2 { animation-delay: .1s; }
        .delay-3 { animation-delay: .15s; }
        .delay-4 { animation-delay: .2s; }

        /* ── Responsive ──────────────────────────── */
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .sidebar-overlay.show { display: block; }
            .main-header { left: 0; }
            .main-content { margin-left: 0; }
            .btn-sidebar-toggle { display: flex; }
        }

        /* ── Misc ────────────────────────────────── */
        .section-title {
            font-weight: 700;
            font-size: 1.05rem;
            color: #1e293b;
        }
        .section-subtitle {
            font-size: .8rem;
            color: #94a3b8;
        }

        /* Logout button in sidebar */
        .btn-logout-sidebar {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .5rem .75rem;
            border-radius: .5rem;
            background: rgba(239,68,68,.1);
            color: #f87171;
            border: none;
            font-size: .8rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            width: 100%;
            margin-top: .75rem;
        }
        .btn-logout-sidebar:hover { background: rgba(239,68,68,.18); color: #ef4444; }
    </style>
</head>
<body>

@auth
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon">CP</div>
            <div>
                <div class="sidebar-brand-text">CMC Pointage</div>
                <div class="sidebar-brand-sub">Gestion résidence</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-label">Principal</div>
            <a href="{{ route('dashboard') }}" class="nav-link-sidebar {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>

            @if(auth()->user()->isSecurity())
                <div class="nav-section-label">Pointage</div>
                <a href="{{ route('pointage.index') }}" class="nav-link-sidebar {{ request()->routeIs('pointage.*') ? 'active' : '' }}">
                    <i class="bi bi-upc-scan"></i> Pointage
                </a>
                <a href="{{ route('mouvements.index') }}" class="nav-link-sidebar {{ request()->routeIs('mouvements.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-left-right"></i> Mouvements
                </a>
            @endif

            @if(auth()->user()->isAdmin())
                <div class="nav-section-label">Gestion</div>
                <a href="{{ route('students.index') }}" class="nav-link-sidebar {{ request()->routeIs('students.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i> Étudiants
                    <span class="nav-badge bg-primary text-white">{{ \DB::table('etudiants')->where('statut','actif')->whereNull('deleted_at')->count() }}</span>
                </a>
                <a href="{{ route('mouvements.index') }}" class="nav-link-sidebar {{ request()->routeIs('mouvements.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-left-right"></i> Mouvements
                </a>

                <div class="nav-section-label">Administration</div>
                <a href="{{ route('demandes.index') }}" class="nav-link-sidebar {{ request()->routeIs('demandes.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text-fill"></i> Demandes
                    @php $pendingDemandes = \DB::table('demandes')->where('statut','en_attente')->whereNull('deleted_at')->count(); @endphp
                    @if($pendingDemandes > 0)
                        <span class="nav-badge bg-warning text-dark">{{ $pendingDemandes }}</span>
                    @endif
                </a>
                <a href="{{ route('sanctions.index') }}" class="nav-link-sidebar {{ request()->routeIs('sanctions.*') ? 'active' : '' }}">
                    <i class="bi bi-shield-exclamation"></i> Sanctions
                    @php $activeSanctions = \DB::table('sanctions')->where('statut','active')->whereNull('deleted_at')->count(); @endphp
                    @if($activeSanctions > 0)
                        <span class="nav-badge bg-danger text-white">{{ $activeSanctions }}</span>
                    @endif
                </a>
            @endif
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-avatar">
                    {{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}{{ strtoupper(substr(auth()->user()->nom, 0, 1)) }}
                </div>
                <div>
                    <div class="sidebar-user-name">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</div>
                    <div class="sidebar-user-role">{{ auth()->user()->role }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout-sidebar">
                    <i class="bi bi-box-arrow-left"></i> Déconnexion
                </button>
            </form>
        </div>
    </aside>

    <!-- Header -->
    <header class="main-header">
        <div class="header-left">
            <button class="btn-sidebar-toggle" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <div>
                <div class="header-title">@yield('page-title', 'Dashboard')</div>
                <div class="header-breadcrumb">@yield('breadcrumb', 'CMC Pointage')</div>
            </div>
        </div>
        <div class="header-right">
            <div class="header-icon-btn">
                <i class="bi bi-bell"></i>
                <span class="header-notification-dot"></span>
            </div>
            <div class="header-icon-btn">
                <i class="bi bi-gear"></i>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="main-content">
        <div class="content-wrapper">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show animate-in" role="alert" style="border-radius:.75rem; border:none; background:linear-gradient(135deg,#d1fae5,#ecfdf5); color:#065f46;">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show animate-in" role="alert" style="border-radius:.75rem; border:none; background:linear-gradient(135deg,#fee2e2,#fef2f2); color:#991b1b;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
@else
    {{-- Guest layout (login page) --}}
    <main class="container py-4">
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
    </main>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('show');
        document.getElementById('sidebarOverlay').classList.toggle('show');
    }
</script>
@stack('scripts')
</body>
</html>
