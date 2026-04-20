@extends('layouts.app')

@section('title', 'Nouvel Étudiant - CMC Pointage')

@section('content')
<div>
    <h1 class="h3 mb-4">Ajouter un Nouvel Étudiant</h1>

    <div class="card border-0 shadow-sm mx-auto" style="max-width: 760px;">
        <div class="card-body">
        <form method="POST" action="{{ route('students.store') }}">
            @csrf
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="cne" class="form-label">CNE *</label>
                    <input type="text" class="form-control @error('cne') is-invalid @enderror" id="cne" name="cne" required value="{{ old('cne') }}" placeholder="CNE12345">
                    @error('cne')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="username" class="form-label">Nom d'utilisateur *</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" required value="{{ old('username') }}" placeholder="student123">
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <label for="nom" class="form-label">Nom *</label>
                    <input type="text" class="form-control" id="nom" name="nom" required value="{{ old('nom') }}" placeholder="Doe">
                </div>

                <div class="col-md-6">
                    <label for="prenom" class="form-label">Prénom *</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" required value="{{ old('prenom') }}" placeholder="John">
                </div>
            </div>

            <div class="mt-3">
                <label for="password" class="form-label">Mot de passe *</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="••••••••">
                <div class="form-text">Minimum 6 caractères</div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Annuler</a>
                <button type="submit" class="btn btn-success">Créer l'étudiant</button>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection
