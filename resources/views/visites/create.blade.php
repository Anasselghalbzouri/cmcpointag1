@extends('layouts.app')

@section('title', 'Nouvelle visite - CMC Pointage')
@section('page-title', 'Nouvelle visite')
@section('breadcrumb', 'CMC Pointage › Visites › Ajouter')

@section('content')
<div>
    <a href="{{ route('visites.index') }}" class="btn btn-light d-inline-flex align-items-center gap-2 mb-3" style="border-radius:.625rem; font-size:.85rem; font-weight:500;">
        <i class="bi bi-arrow-left"></i> Retour aux visites
    </a>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card animate-in">
                <div class="card-body p-4">
                    <h2 class="section-title mb-1">Enregistrer une visite</h2>
                    <p class="section-subtitle mb-4">Remplissez les informations du visiteur</p>

                    <form method="POST" action="{{ route('visites.store') }}">
                        @csrf

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="prenom_visiteur" class="form-label" style="font-size:.82rem; font-weight:600;">Prénom *</label>
                                <input type="text" class="form-control @error('prenom_visiteur') is-invalid @enderror" id="prenom_visiteur" name="prenom_visiteur" value="{{ old('prenom_visiteur') }}" required style="border-radius:.625rem;">
                                @error('prenom_visiteur') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="nom_visiteur" class="form-label" style="font-size:.82rem; font-weight:600;">Nom *</label>
                                <input type="text" class="form-control @error('nom_visiteur') is-invalid @enderror" id="nom_visiteur" name="nom_visiteur" value="{{ old('nom_visiteur') }}" required style="border-radius:.625rem;">
                                @error('nom_visiteur') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="cin_visiteur" class="form-label" style="font-size:.82rem; font-weight:600;">CIN *</label>
                                <input type="text" class="form-control @error('cin_visiteur') is-invalid @enderror" id="cin_visiteur" name="cin_visiteur" value="{{ old('cin_visiteur') }}" required style="border-radius:.625rem;">
                                @error('cin_visiteur') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="matricul_visiteur" class="form-label" style="font-size:.82rem; font-weight:600;">Matricule véhicule (optionnel)</label>
                                <input type="text" class="form-control @error('matricul_visiteur') is-invalid @enderror" id="matricul_visiteur" name="matricul_visiteur" value="{{ old('matricul_visiteur') }}" style="border-radius:.625rem;">
                                @error('matricul_visiteur') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="motif" class="form-label" style="font-size:.82rem; font-weight:600;">Motif de la visite *</label>
                            <textarea class="form-control @error('motif') is-invalid @enderror" id="motif" name="motif" rows="3" required style="border-radius:.625rem;" placeholder="Visite familiale, rendez-vous, livraison...">{{ old('motif') }}</textarea>
                            @error('motif') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('visites.index') }}" class="btn btn-light" style="border-radius:.625rem; font-weight:500; font-size:.85rem;">Annuler</a>
                            <button type="submit" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius:.625rem; font-weight:600; font-size:.85rem; padding:.55rem 1.5rem;">
                                <i class="bi bi-box-arrow-in-right"></i> Enregistrer l'entrée
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
