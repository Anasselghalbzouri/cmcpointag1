@extends('layouts.app')

@section('title', 'Nouvel utilisateur - CMC Pointage')
@section('page-title', 'Nouvel utilisateur')
@section('breadcrumb', 'CMC Pointage › Utilisateurs › Ajouter')

@section('content')
<div>
    <a href="{{ route('users.index') }}" class="btn btn-light d-inline-flex align-items-center gap-2 mb-3" style="border-radius:.625rem; font-size:.85rem; font-weight:500;">
        <i class="bi bi-arrow-left"></i> Retour aux utilisateurs
    </a>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card animate-in">
                <div class="card-body p-4">
                    <h2 class="section-title mb-1">Créer un utilisateur</h2>
                    <p class="section-subtitle mb-4">Remplissez les informations ci-dessous</p>

                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="prenom" class="form-label" style="font-size:.82rem; font-weight:600;">Prénom *</label>
                                <input type="text" class="form-control @error('prenom') is-invalid @enderror" id="prenom" name="prenom" value="{{ old('prenom') }}" required style="border-radius:.625rem;">
                                @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="nom" class="form-label" style="font-size:.82rem; font-weight:600;">Nom *</label>
                                <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom') }}" required style="border-radius:.625rem;">
                                @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label" style="font-size:.82rem; font-weight:600;">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required style="border-radius:.625rem;">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="telephone" class="form-label" style="font-size:.82rem; font-weight:600;">Téléphone</label>
                                <input type="text" class="form-control @error('telephone') is-invalid @enderror" id="telephone" name="telephone" value="{{ old('telephone') }}" style="border-radius:.625rem;">
                                @error('telephone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label" style="font-size:.82rem; font-weight:600;">Mot de passe *</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required minlength="8" style="border-radius:.625rem;">
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="role" class="form-label" style="font-size:.82rem; font-weight:600;">Rôle *</label>
                                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required style="border-radius:.625rem;">
                                    <option value="">— Sélectionner —</option>
                                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="security" {{ old('role') === 'security' ? 'selected' : '' }}>Sécurité</option>
                                    <option value="etudiant" {{ old('role') === 'etudiant' ? 'selected' : '' }}>Étudiant</option>
                                </select>
                                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('users.index') }}" class="btn btn-light" style="border-radius:.625rem; font-weight:500; font-size:.85rem;">Annuler</a>
                            <button type="submit" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius:.625rem; font-weight:600; font-size:.85rem; padding:.55rem 1.5rem;">
                                <i class="bi bi-plus-lg"></i> Créer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
