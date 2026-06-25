<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pointage - CMC</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            position: relative;
            background: #0c2226 url('{{ asset('images/cmc-tta.png') }}') center center / cover no-repeat;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            -webkit-font-smoothing: antialiased;
        }
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, rgba(8,30,33,.88) 0%, rgba(15,60,65,.82) 100%);
            z-index: 0;
        }
        .top-bar, .pointage-wrapper {
            position: relative;
            z-index: 1;
        }

        .pointage-wrapper {
            width: 100%;
            max-width: 860px;
        }

        .page-brand {
            text-align: center;
            margin-bottom: 2rem;
        }
        .page-brand-icon {
            width: 64px; height: 64px;
            background: #fff;
            border-radius: 1rem;
            display: inline-flex; align-items: center; justify-content: center;
            padding: .5rem;
            box-shadow: 0 8px 24px rgba(0,0,0,.3);
            margin-bottom: .75rem;
        }
        .page-brand-icon img {
            width: 100%; height: 100%;
            object-fit: contain;
        }
        .page-brand-title {
            color: #fff;
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: -.02em;
        }
        .page-brand-sub {
            color: #64748b;
            font-size: .8rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .card {
            background: rgba(255,255,255,.05);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 1.25rem;
            backdrop-filter: blur(16px);
        }

        .card-inner {
            padding: 2rem;
        }

        .section-label {
            color: #94a3b8;
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            margin-bottom: .75rem;
        }
        .section-title {
            color: #fff;
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: .25rem;
        }
        .section-sub {
            color: #64748b;
            font-size: .8rem;
        }

        .scan-icon-box {
            width: 42px; height: 42px;
            border-radius: .875rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem;
        }

        /* Inputs */
        .form-control, .form-select {
            background: rgba(255,255,255,.07);
            border: 1.5px solid rgba(255,255,255,.12);
            border-radius: .75rem;
            color: #fff;
            font-size: .9rem;
            height: 50px;
        }
        .form-control::placeholder { color: #475569; }
        .form-control:focus, .form-select:focus {
            background: rgba(255,255,255,.1);
            border-color: #2bb3bf;
            box-shadow: 0 0 0 3px rgba(43,179,191,.25);
            color: #fff;
        }
        .form-select option { background: #1e293b; color: #fff; }

        /* Alert */
        .alert-result {
            border-radius: .875rem;
            border: none;
            padding: .875rem 1.25rem;
            font-weight: 600;
            font-size: .9rem;
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: 1.25rem;
        }
        .alert-entree { background: rgba(16,185,129,.15); color: #6ee7b7; border: 1px solid rgba(16,185,129,.25); }
        .alert-sortie { background: rgba(148,163,184,.12); color: #cbd5e1; border: 1px solid rgba(148,163,184,.2); }
        .alert-error  { background: rgba(239,68,68,.15); color: #fca5a5; border: 1px solid rgba(239,68,68,.25); }

        /* Buttons */
        .btn-scan {
            background: linear-gradient(135deg, #2bb3bf, #1d8a96);
            border: none;
            border-radius: .875rem;
            color: #fff;
            font-weight: 700;
            font-size: .95rem;
            height: 52px;
            width: 100%;
            display: flex; align-items: center; justify-content: center; gap: .6rem;
            box-shadow: 0 4px 16px rgba(43,179,191,.4);
            transition: all .2s;
        }
        .btn-scan:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(43,179,191,.5); color: #fff; }

        .btn-manual {
            background: rgba(255,255,255,.08);
            border: 1.5px solid rgba(255,255,255,.15);
            border-radius: .875rem;
            color: #e2e8f0;
            font-weight: 700;
            font-size: .875rem;
            height: 48px;
            width: 100%;
            display: flex; align-items: center; justify-content: center; gap: .6rem;
            transition: all .2s;
        }
        .btn-manual:hover { background: rgba(255,255,255,.13); color: #fff; }

        /* Type radio buttons */
        .type-btn-check { display: none; }
        .type-btn-label {
            display: flex; align-items: center; justify-content: center; gap: .5rem;
            border: 1.5px solid rgba(255,255,255,.12);
            border-radius: .75rem;
            padding: .65rem 1rem;
            color: #94a3b8;
            font-weight: 600;
            font-size: .85rem;
            cursor: pointer;
            transition: all .2s;
            background: rgba(255,255,255,.04);
        }
        .type-btn-check:checked + .type-btn-label.entree-label {
            background: rgba(16,185,129,.15);
            border-color: #10b981;
            color: #6ee7b7;
        }
        .type-btn-check:checked + .type-btn-label.sortie-label {
            background: rgba(148,163,184,.15);
            border-color: #94a3b8;
            color: #e2e8f0;
        }

        .btn-logout {
            display: flex; align-items: center; justify-content: center; gap: .5rem;
            margin-top: 1rem;
            width: 100%;
            padding: .65rem 1rem;
            border-radius: .75rem;
            background: rgba(239,68,68,.1);
            border: 1.5px solid rgba(239,68,68,.2);
            color: #fca5a5;
            font-weight: 600;
            font-size: .85rem;
            text-decoration: none;
            transition: all .2s;
        }
        .btn-logout:hover { background: rgba(239,68,68,.18); color: #f87171; }

        .top-bar {
            position: fixed;
            top: 1rem; right: 1.25rem;
            display: flex; align-items: center; gap: .75rem;
            z-index: 100;
        }
        .user-chip {
            display: flex; align-items: center; gap: .5rem;
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 999px;
            padding: .35rem .75rem .35rem .45rem;
            color: #e2e8f0;
            font-size: .82rem;
            font-weight: 600;
        }
        .user-avatar {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex; align-items: center; justify-content: center;
            font-size: .65rem; font-weight: 700; color: #fff;
        }
        .btn-logout-top {
            display: flex; align-items: center; gap: .4rem;
            background: rgba(239,68,68,.12);
            border: 1px solid rgba(239,68,68,.2);
            border-radius: 999px;
            padding: .35rem .85rem;
            color: #fca5a5;
            font-size: .82rem; font-weight: 600;
            text-decoration: none;
            transition: all .2s;
        }
        .btn-logout-top:hover { background: rgba(239,68,68,.22); color: #f87171; }
    </style>
</head>
<body>

{{-- Top-right user + logout --}}
<div class="top-bar">
    <div class="user-chip">
        <div class="user-avatar">
            {{ strtoupper(substr(auth()->user()->prenom,0,1)) }}{{ strtoupper(substr(auth()->user()->nom,0,1)) }}
        </div>
        {{ auth()->user()->prenom }} {{ auth()->user()->nom }}
    </div>
    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-top').submit();" class="btn-logout-top">
        <i class="bi bi-box-arrow-left"></i> Déconnexion
    </a>
    <form id="logout-top" method="POST" action="{{ route('logout') }}" style="display:none;">@csrf</form>
</div>

<div class="pointage-wrapper">

    {{-- Brand --}}
    <div class="page-brand">
        <div class="page-brand-icon"><img src="{{ asset('images/cmc-logo.png') }}" alt="CMC"></div>
        <div class="page-brand-title">CMC Pointage</div>
        <div class="page-brand-sub">Enregistrement des mouvements</div>
    </div>

    <div class="row g-3 justify-content-center">

        {{-- Saisie manuelle --}}
        <div class="col-12 col-md-7">
            <div class="card">
                <div class="card-inner">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="scan-icon-box" style="background:rgba(251,191,36,.12);color:#fbbf24;">
                            <i class="bi bi-pencil-square"></i>
                        </div>
                        <div>
                            <div class="section-title">Saisie manuelle</div>
                            <div class="section-sub">Enregistrer un mouvement étudiant</div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert-result alert-entree mb-4">
                            <i class="bi bi-check-circle-fill" style="font-size:1.1rem;flex-shrink:0;"></i>
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert-result alert-error mb-4">
                            <i class="bi bi-exclamation-triangle-fill" style="font-size:1.1rem;flex-shrink:0;"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('pointage.manual') }}">
                        @csrf
                        <div class="mb-3">
                            <div class="section-label">CIN étudiant</div>
                            <input type="text" name="cne" class="form-control" required placeholder="Entrez le CIN..." autofocus autocomplete="off" style="font-size:1rem;font-weight:600;letter-spacing:1px;">
                        </div>
                        <div class="mb-4">
                            <div class="section-label">Type de mouvement — cliquez pour enregistrer</div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="radio" class="type-btn-check" name="type" id="t_entree" value="entree" required>
                                    <label class="type-btn-label entree-label w-100" for="t_entree" onclick="submitForm()">
                                        <i class="bi bi-box-arrow-in-right"></i> Entrée
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="type-btn-check" name="type" id="t_sortie" value="sortie">
                                    <label class="type-btn-label sortie-label w-100" for="t_sortie" onclick="submitForm()">
                                        <i class="bi bi-box-arrow-right"></i> Sortie
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function submitForm() {
        setTimeout(function () {
            document.querySelector('form[action="{{ route('pointage.manual') }}"]').submit();
        }, 120);
    }
</script>
</body>
</html>
