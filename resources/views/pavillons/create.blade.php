@extends('layouts.app')

@section('title', 'Nouveau pavillon - CMC Pointage')
@section('page-title', 'Nouveau pavillon')
@section('breadcrumb', 'CMC Pointage › Pavillons › Ajouter')

@section('content')
<div>
    <a href="{{ route('pavillons.index') }}" class="btn btn-light d-inline-flex align-items-center gap-2 mb-3" style="border-radius:.625rem; font-size:.85rem; font-weight:500;">
        <i class="bi bi-arrow-left"></i> Retour aux pavillons
    </a>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-6">
            <div class="card animate-in">
                <div class="card-body p-4">
                    <h2 class="section-title mb-1">Créer un pavillon</h2>
                    <p class="section-subtitle mb-4">Remplissez les informations ci-dessous</p>

                    <form method="POST" action="{{ route('pavillons.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="type" class="form-label" style="font-size:.82rem; font-weight:600;">Type *</label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required style="border-radius:.625rem;">
                                <option value="">— Sélectionner —</option>
                                <option value="homme" {{ old('type') === 'homme' ? 'selected' : '' }}>Homme</option>
                                <option value="femme" {{ old('type') === 'femme' ? 'selected' : '' }}>Femme</option>
                            </select>
                            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="nombre_etages" class="form-label" style="font-size:.82rem; font-weight:600;">Nombre d'étages *</label>
                            <input type="number" class="form-control @error('nombre_etages') is-invalid @enderror" id="nombre_etages" name="nombre_etages" value="{{ old('nombre_etages', 3) }}" min="1" max="50" required style="border-radius:.625rem;">
                            @error('nombre_etages') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('pavillons.index') }}" class="btn btn-light" style="border-radius:.625rem; font-weight:500; font-size:.85rem;">Annuler</a>
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
