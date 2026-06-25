@extends('layouts.app')

@section('title', 'Modifier chambre - CMC Pointage')
@section('page-title', 'Modifier chambre')
@section('breadcrumb', 'CMC Pointage › Chambres › Modifier')

@section('content')
<div>
    <a href="{{ route('chambres.index') }}" class="btn btn-light d-inline-flex align-items-center gap-2 mb-3" style="border-radius:.625rem; font-size:.85rem; font-weight:500;">
        <i class="bi bi-arrow-left"></i> Retour aux chambres
    </a>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-6">
            <div class="card animate-in">
                <div class="card-body p-4">
                    <h2 class="section-title mb-1">Modifier la chambre {{ $chambre->numero }}</h2>
                    <p class="section-subtitle mb-4">{{ $chambre->occupants_actuels }} occupant(s) actuellement</p>

                    <form method="POST" action="{{ route('chambres.update', $chambre->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="pavillon_id" class="form-label" style="font-size:.82rem; font-weight:600;">Pavillon *</label>
                            <select class="form-select @error('pavillon_id') is-invalid @enderror" id="pavillon_id" name="pavillon_id" required style="border-radius:.625rem;" {{ $chambre->occupants_actuels > 0 ? 'disabled' : '' }}>
                                @foreach($pavillons as $p)
                                    <option value="{{ $p->id }}" {{ old('pavillon_id', $chambre->pavillon_id) == $p->id ? 'selected' : '' }}>{{ ucfirst($p->type) }}</option>
                                @endforeach
                            </select>
                            @if($chambre->occupants_actuels > 0)
                                <input type="hidden" name="pavillon_id" value="{{ $chambre->pavillon_id }}">
                                <div class="text-muted" style="font-size:.72rem;">Verrouillé : la chambre a des occupants.</div>
                            @endif
                            @error('pavillon_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="numero" class="form-label" style="font-size:.82rem; font-weight:600;">Numéro *</label>
                                <input type="text" class="form-control @error('numero') is-invalid @enderror" id="numero" name="numero" value="{{ old('numero', $chambre->numero) }}" required style="border-radius:.625rem;">
                                @error('numero') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="etage" class="form-label" style="font-size:.82rem; font-weight:600;">Étage *</label>
                                <input type="number" class="form-control @error('etage') is-invalid @enderror" id="etage" name="etage" value="{{ old('etage', $chambre->etage) }}" min="0" max="50" required style="border-radius:.625rem;">
                                @error('etage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="capacite" class="form-label" style="font-size:.82rem; font-weight:600;">Capacité *</label>
                                <input type="number" class="form-control @error('capacite') is-invalid @enderror" id="capacite" name="capacite" value="{{ old('capacite', $chambre->capacite) }}" min="{{ $chambre->occupants_actuels > 0 ? $chambre->occupants_actuels : 1 }}" max="20" required style="border-radius:.625rem;">
                                @error('capacite') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="statut" class="form-label" style="font-size:.82rem; font-weight:600;">Statut *</label>
                                <select class="form-select @error('statut') is-invalid @enderror" id="statut" name="statut" required style="border-radius:.625rem;">
                                    <option value="disponible" {{ old('statut', $chambre->statut) === 'disponible' ? 'selected' : '' }}>Disponible</option>
                                    <option value="occupee" {{ old('statut', $chambre->statut) === 'occupee' ? 'selected' : '' }}>Occupée</option>
                                    <option value="maintenance" {{ old('statut', $chambre->statut) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    <option value="fermee" {{ old('statut', $chambre->statut) === 'fermee' ? 'selected' : '' }}>Fermée</option>
                                </select>
                                @error('statut') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('chambres.index') }}" class="btn btn-light" style="border-radius:.625rem; font-weight:500; font-size:.85rem;">Annuler</a>
                            <button type="submit" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius:.625rem; font-weight:600; font-size:.85rem; padding:.55rem 1.5rem;">
                                <i class="bi bi-check-lg"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
