@extends('layouts.app')

@section('title', 'Connexion - CMC Pointage')

@section('content')
<style>
    .auth-screen {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        padding: 2rem 1.25rem;
        background: #0c2226 url('{{ asset('images/cmc-tta.png') }}') center center / cover no-repeat;
    }
    .auth-screen::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(160deg, rgba(8,30,33,.82) 0%, rgba(15,60,65,.72) 100%);
    }
    .auth-form-wrap { position: relative; z-index: 1; width: 100%; max-width: 400px; }

    .auth-card {
        background: #fff;
        border: 1px solid rgba(43,179,191,.12);
        border-radius: 1.1rem;
        padding: 2rem 2.25rem;
        box-shadow: 0 16px 40px rgba(0,0,0,.25);
    }

    .auth-label { font-size: .82rem; font-weight: 600; color: #334155; margin-bottom: .4rem; }

    .auth-input-group { position: relative; }
    .auth-input-group .bi-leading {
        position: absolute;
        left: .9rem; top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 1rem;
        pointer-events: none;
    }
    .auth-input {
        width: 100%;
        height: 46px;
        padding: 0 2.6rem;
        border: 1px solid #e2e8f0;
        border-radius: .7rem;
        font-size: .9rem;
        background: #fff;
        transition: all .2s cubic-bezier(.4,0,.2,1);
    }
    .auth-input:focus {
        outline: none;
        border-color: #2bb3bf;
        box-shadow: 0 0 0 3px rgba(43,179,191,.14);
    }
    .auth-input.is-invalid { border-color: #ef4444; }
    .auth-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,.12); }

    .auth-toggle-pwd {
        position: absolute;
        right: .65rem; top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        padding: .4rem;
        display: flex;
        border-radius: .4rem;
        transition: color .15s;
    }
    .auth-toggle-pwd:hover { color: #475569; }

    .auth-feedback { color: #ef4444; font-size: .78rem; margin-top: .4rem; display: flex; align-items: center; gap: .3rem; }

    .auth-options { display: flex; align-items: center; justify-content: space-between; margin: 1.1rem 0 1.5rem; }
    .auth-remember { display: flex; align-items: center; gap: .5rem; font-size: .85rem; color: #475569; cursor: pointer; user-select: none; }
    .auth-remember input { width: 16px; height: 16px; accent-color: #2bb3bf; cursor: pointer; }

    .auth-submit {
        width: 100%;
        height: 46px;
        border: none;
        border-radius: .7rem;
        background: #2bb3bf;
        color: #fff;
        font-weight: 700;
        font-size: .92rem;
        display: flex; align-items: center; justify-content: center;
        gap: .5rem;
        cursor: pointer;
        transition: all .2s cubic-bezier(.4,0,.2,1);
        box-shadow: 0 8px 20px rgba(43,179,191,.3);
    }
    .auth-submit:hover { background: #218e98; box-shadow: 0 10px 24px rgba(43,179,191,.38); }
    .auth-submit:active { transform: translateY(1px); }
    .auth-submit:disabled { opacity: .75; cursor: not-allowed; }

    .auth-error-summary {
        display: flex;
        gap: .6rem;
        align-items: flex-start;
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
        border-radius: .7rem;
        padding: .85rem 1rem;
        font-size: .85rem;
        margin-bottom: 1.25rem;
    }
    .auth-error-summary i { font-size: 1rem; margin-top: 1px; }

    .auth-demo-box {
        margin-top: 1.75rem;
        border: 1px dashed #e2e8f0;
        border-radius: .7rem;
        padding: .9rem 1rem;
        background: #f8fafc;
    }
    .auth-demo-title { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #94a3b8; margin-bottom: .55rem; }
    .auth-demo-row { display: flex; align-items: center; justify-content: space-between; font-size: .8rem; color: #475569; padding: .25rem 0; }
    .auth-demo-row strong { color: #1e293b; font-weight: 600; }
</style>

<div class="auth-screen">
    <div class="auth-form-wrap">
        <div class="auth-card">
            @if (session('success'))
                <div class="auth-error-summary" style="background:#ecfdf5;border-color:#a7f3d0;color:#065f46;">
                    <i class="bi bi-check-circle-fill"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if (session('error'))
                <div class="auth-error-summary">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            @if ($errors->any() && !$errors->has('email') && !$errors->has('password'))
                <div class="auth-error-summary" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                @csrf

                <div class="mb-3">
                    <label for="email" class="auth-label">Adresse e-mail</label>
                    <div class="auth-input-group">
                        <i class="bi bi-envelope bi-leading"></i>
                        <input
                            type="email"
                            class="auth-input @error('email') is-invalid @enderror"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="admin@cmc.ma"
                            aria-describedby="emailError"
                        >
                    </div>
                    @error('email')
                        <div class="auth-feedback" id="emailError"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="password" class="auth-label">Mot de passe</label>
                    <div class="auth-input-group">
                        <i class="bi bi-lock bi-leading"></i>
                        <input
                            type="password"
                            class="auth-input @error('password') is-invalid @enderror"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                            aria-describedby="passwordError"
                        >
                        <button type="button" class="auth-toggle-pwd" id="togglePassword" aria-label="Afficher le mot de passe" tabindex="-1">
                            <i class="bi bi-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="auth-feedback" id="passwordError"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="auth-options">
                    <label class="auth-remember" for="remember">
                        <input type="checkbox" id="remember" name="remember">
                        Se souvenir de moi
                    </label>
                </div>

                <button type="submit" class="auth-submit" id="submitBtn">
                    <span id="submitText">Se connecter</span>
                </button>
            </form>

            <div class="auth-demo-box">
                <div class="auth-demo-title">Comptes de démonstration</div>
                <div class="auth-demo-row"><span>Admin</span> <strong>admin@cmc.ma</strong></div>
                <div class="auth-demo-row"><span>Sécurité</span> <strong>security1@cmc.ma</strong></div>
                <div class="auth-demo-row"><span>Mot de passe</span> <strong>password</strong></div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        var toggleBtn = document.getElementById('togglePassword');
        var pwdInput = document.getElementById('password');
        var icon = document.getElementById('togglePasswordIcon');
        toggleBtn.addEventListener('click', function () {
            var isPassword = pwdInput.type === 'password';
            pwdInput.type = isPassword ? 'text' : 'password';
            icon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
            toggleBtn.setAttribute('aria-label', isPassword ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
        });

        var form = document.getElementById('loginForm');
        var submitBtn = document.getElementById('submitBtn');
        var submitText = document.getElementById('submitText');
        form.addEventListener('submit', function () {
            submitBtn.disabled = true;
            submitText.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Connexion...';
        });
    })();
</script>
@endsection
