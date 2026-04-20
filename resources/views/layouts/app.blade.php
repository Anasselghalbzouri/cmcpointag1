<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CMC Pointage')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
        .app-navbar-brand {
            font-weight: 800;
            letter-spacing: .2px;
        }
        .status-pill {
            border-radius: 999px;
            font-weight: 600;
            font-size: 0.75rem;
            padding: 0.35rem 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
        }
        .status-dot {
            width: .45rem;
            height: .45rem;
            border-radius: 50%;
            display: inline-block;
        }
        .table thead th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #6b7280;
            font-weight: 700;
        }
    </style>
</head>
<body>
    @auth
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand app-navbar-brand" href="{{ route('dashboard') }}">CMC Pointage</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        @if(auth()->user()->isStaff())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('pointage.*') ? 'active' : '' }}" href="{{ route('pointage.index') }}">Pointage</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}">Étudiants</a>
                            </li>
                        @endif
                    </ul>

                    <div class="d-flex align-items-center gap-3">
                        <div class="text-light small d-none d-md-block">
                            {{ auth()->user()->prenom }} {{ auth()->user()->nom }}
                        </div>
                        <span class="badge text-bg-secondary text-uppercase">{{ auth()->user()->role }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm">Déconnexion</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    @endauth

    <main class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    @stack('scripts')
</body>
</html>
