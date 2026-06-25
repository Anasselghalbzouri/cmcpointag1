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
            --topbar-height: 64px;
            --bg-sidebar: linear-gradient(100deg, #0f172a 0%, #1e293b 100%);
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

        /* ── Topbar ──────────────────────────────── */
        .topbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: var(--topbar-height);
            background: var(--bg-sidebar);
            z-index: 1040;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            padding: 0 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,.06);
        }

        .topbar-brand {
            display: flex;
            align-items: center;
            gap: .65rem;
            flex-shrink: 0;
            text-decoration: none;
        }
        .topbar-brand-icon {
            width: 40px; height: 40px;
            background: #fff;
            border-radius: .65rem;
            display: flex; align-items: center; justify-content: center;
            padding: .3rem;
            box-shadow: 0 4px 12px rgba(0,0,0,.25);
            flex-shrink: 0;
        }
        .topbar-brand-icon img {
            width: 100%; height: 100%;
            object-fit: contain;
        }
        .topbar-brand-text {
            color: #fff;
            font-weight: 800;
            font-size: 1rem;
            letter-spacing: -.01em;
            line-height: 1.2;
        }
        .topbar-brand-sub {
            color: #64748b;
            font-size: .65rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            font-weight: 600;
        }

        .topbar-nav {
            flex: 1;
            display: flex;
            align-items: center;
            gap: .25rem;
            overflow-x: auto;
            scrollbar-width: none;
            min-width: 0;
        }
        .topbar-nav::-webkit-scrollbar { display: none; }

        .nav-link-top {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .55rem .85rem;
            border-radius: .6rem;
            color: var(--text-sidebar);
            text-decoration: none;
            font-size: .82rem;
            font-weight: 500;
            white-space: nowrap;
            transition: var(--transition);
            flex-shrink: 0;
        }
        .nav-link-top:hover {
            color: #e2e8f0;
            background: rgba(255,255,255,.06);
        }
        .nav-link-top.active {
            color: var(--text-sidebar-active);
            background: var(--accent-light);
            font-weight: 600;
        }
        .nav-link-top .bi {
            font-size: 1rem;
            opacity: .75;
        }
        .nav-link-top.active .bi { opacity: 1; color: var(--accent); }

        .nav-badge {
            font-size: .62rem;
            font-weight: 700;
            padding: .15rem .42rem;
            border-radius: 999px;
            min-width: 1.3rem;
            text-align: center;
        }

        .topbar-divider {
            width: 1px;
            height: 24px;
            background: rgba(255,255,255,.1);
            flex-shrink: 0;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: .5rem;
            flex-shrink: 0;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .35rem .6rem .35rem .4rem;
            border-radius: .65rem;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            background: transparent;
        }
        .topbar-user:hover { background: rgba(255,255,255,.06); }
        .sidebar-avatar {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: .55rem;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: .78rem;
            flex-shrink: 0;
        }
        .sidebar-user-name {
            color: #e2e8f0;
            font-size: .8rem;
            font-weight: 600;
            line-height: 1.2;
        }
        .sidebar-user-role {
            color: #64748b;
            font-size: .65rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            font-weight: 600;
        }

        .btn-nav-toggle {
            display: none;
            width: 38px; height: 38px;
            border-radius: .625rem;
            border: 1px solid rgba(255,255,255,.12);
            background: rgba(255,255,255,.06);
            align-items: center; justify-content: center;
            color: #e2e8f0;
            cursor: pointer;
            flex-shrink: 0;
        }

        /* ── Main Content ────────────────────────── */
        .main-content {
            padding-top: var(--topbar-height);
            min-height: 100vh;
        }
        .content-wrapper {
            padding: 1.5rem;
            max-width: 1440px;
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
        @media (max-width: 767.98px) {
            .topbar-brand-sub { display: none; }
            .sidebar-user-name, .sidebar-user-role { display: none; }
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
    <!-- Topbar -->
    <header class="topbar">
        <a href="{{ route('dashboard') }}" class="topbar-brand">
            <div class="topbar-brand-icon">
                <img src="{{ asset('images/cmc-logo.png') }}" alt="CMC">
            </div>
            <div>
                <div class="topbar-brand-text">CMC Pointage</div>
                <div class="topbar-brand-sub">Gestion résidence</div>
            </div>
        </a>

        <nav class="topbar-nav">
            <a href="{{ route('dashboard') }}" class="nav-link-top {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>

            @if(auth()->user()->isSecurity())
                <a href="{{ route('pointage.index') }}" class="nav-link-top {{ request()->routeIs('pointage.*') ? 'active' : '' }}">
                    <i class="bi bi-upc-scan"></i> Pointage
                </a>
                <a href="{{ route('mouvements.index') }}" class="nav-link-top {{ request()->routeIs('mouvements.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-left-right"></i> Mouvements
                </a>
                <a href="{{ route('visites.index') }}" class="nav-link-top {{ request()->routeIs('visites.*') ? 'active' : '' }}">
                    <i class="bi bi-person-walking"></i> Visites
                </a>
            @endif

            @if(auth()->user()->isAdmin())
                <a href="{{ route('students.index') }}" class="nav-link-top {{ request()->routeIs('students.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i> Étudiants
                    <span class="nav-badge bg-primary text-white">{{ \DB::table('etudiants')->where('statut','actif')->whereNull('deleted_at')->count() }}</span>
                </a>
                <a href="{{ route('mouvements.index') }}" class="nav-link-top {{ request()->routeIs('mouvements.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-left-right"></i> Mouvements
                </a>
                <a href="{{ route('visites.index') }}" class="nav-link-top {{ request()->routeIs('visites.*') ? 'active' : '' }}">
                    <i class="bi bi-person-walking"></i> Visites
                </a>

                <div class="topbar-divider"></div>

                <a href="{{ route('demandes.index') }}" class="nav-link-top {{ request()->routeIs('demandes.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text-fill"></i> Demandes
                    @php $pendingDemandes = \DB::table('demandes')->where('statut','en_attente')->whereNull('deleted_at')->count(); @endphp
                    @if($pendingDemandes > 0)
                        <span class="nav-badge bg-warning text-dark">{{ $pendingDemandes }}</span>
                    @endif
                </a>
                <a href="{{ route('sanctions.index') }}" class="nav-link-top {{ request()->routeIs('sanctions.*') ? 'active' : '' }}">
                    <i class="bi bi-shield-exclamation"></i> Sanctions
                    @php $activeSanctions = \DB::table('sanctions')->where('statut','active')->whereNull('deleted_at')->count(); @endphp
                    @if($activeSanctions > 0)
                        <span class="nav-badge bg-danger text-white">{{ $activeSanctions }}</span>
                    @endif
                </a>

                <div class="topbar-divider"></div>

                <a href="{{ route('pavillons.index') }}" class="nav-link-top {{ request()->routeIs('pavillons.*') ? 'active' : '' }}">
                    <i class="bi bi-building"></i> Pavillons
                </a>
                <a href="{{ route('chambres.index') }}" class="nav-link-top {{ request()->routeIs('chambres.*') ? 'active' : '' }}">
                    <i class="bi bi-door-open"></i> Chambres
                </a>
                <a href="{{ route('users.index') }}" class="nav-link-top {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="bi bi-person-gear"></i> Utilisateurs
                </a>
                <a href="{{ route('rapports.index') }}" class="nav-link-top {{ request()->routeIs('rapports.*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-fill"></i> Rapports
                </a>
            @endif
        </nav>

        <div class="topbar-right">
            <div class="dropdown">
                <button class="topbar-user" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="sidebar-avatar">
                        {{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}{{ strtoupper(substr(auth()->user()->nom, 0, 1)) }}
                    </div>
                    <div class="text-start">
                        <div class="sidebar-user-name">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</div>
                        <div class="sidebar-user-role">{{ auth()->user()->role }}</div>
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-end p-2" style="min-width: 200px;">
                    <li class="px-2 py-1">
                        <div class="sidebar-user-name" style="color:#1e293b;">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</div>
                        <div class="sidebar-user-role" style="color:#94a3b8;">{{ auth()->user()->role }}</div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn-logout-sidebar">
                                <i class="bi bi-box-arrow-left"></i> Déconnexion
                            </button>
                        </form>
                    </li>
                </ul>
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
    <main>
        @yield('content')
    </main>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
