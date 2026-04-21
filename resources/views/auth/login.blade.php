@extends('layouts.app')

@section('title', 'Login - CMC Pointage')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: calc(100vh - 120px);">
    <div class="col-12 col-md-8 col-lg-5">
        <div class="text-center mb-4">
            <h1 class="fw-bold">CMC Pointage</h1>
            <p class="text-muted mb-0">Système de gestion des présences</p>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <h2 class="h5 mb-4">Connexion</h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="mb-3">
                    <label for="email" class="form-label">Adresse e-mail</label>
                    <input 
                        type="email" 
                        class="form-control @error('email') is-invalid @enderror"
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus
                        placeholder="admin@cmc.ma"
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input 
                        type="password" 
                        class="form-control"
                        id="password" 
                        name="password" 
                        required
                        placeholder="••••••••"
                    >
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Se souvenir de moi</label>
                </div>

                <button type="submit" class="btn btn-primary w-100">Se connecter</button>
            </form>
            </div>
        </div>

        <div class="text-center mt-4 text-muted small">
            <p class="mb-1"><strong>Comptes par défaut :</strong></p>
            <p class="mb-1">Admin: <strong>admin@cmc.ma</strong> / password</p>
            <p class="mb-1">Sécurité: <strong>security1@cmc.ma</strong> / password</p>
        </div>
    </div>
</div>
@endsection
