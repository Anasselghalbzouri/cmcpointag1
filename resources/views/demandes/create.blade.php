@extends('layouts.app')

@section('title', 'Nouvelle demande - CMC Pointage')
@section('page-title', 'Nouvelle demande')
@section('breadcrumb', 'CMC Pointage › Demandes › Ajouter')

@section('content')
<div>
    <a href="{{ route('demandes.index') }}" class="btn btn-light d-inline-flex align-items-center gap-2 mb-3" style="border-radius:.625rem; font-size:.85rem; font-weight:500;">
        <i class="bi bi-arrow-left"></i> Retour aux demandes
    </a>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card animate-in">
                <div class="card-body p-4">
                    <h2 class="section-title mb-1">Créer une demande</h2>
                    <p class="section-subtitle mb-4">Remplissez les informations ci-dessous</p>

                    <form method="POST" action="{{ route('demandes.store') }}">
                        @csrf

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="etudiant_id" class="form-label" style="font-size:.82rem; font-weight:600;">Étudiant *</label>
                                <select class="form-select @error('etudiant_id') is-invalid @enderror" id="etudiant_id" name="etudiant_id" required style="border-radius:.625rem;">
                                    <option value="">— Sélectionner —</option>
                                    @foreach($students as $s)
                                        <option value="{{ $s->id }}" {{ old('etudiant_id') == $s->id ? 'selected' : '' }}>
                                            {{ $s->prenom }} {{ $s->nom }} — {{ $s->cin }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('etudiant_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="type" class="form-label" style="font-size:.82rem; font-weight:600;">Type *</label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required style="border-radius:.625rem;">
                                    <option value="">— Sélectionner —</option>
                                    <option value="changement_chambre" {{ old('type') === 'changement_chambre' ? 'selected' : '' }}>Changement de chambre</option>
                                    <option value="extension" {{ old('type') === 'extension' ? 'selected' : '' }}>Extension</option>
                                    <option value="permission" {{ old('type') === 'permission' ? 'selected' : '' }}>Permission</option>
                                    <option value="autre" {{ old('type') === 'autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                                @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="date_limite" class="form-label" style="font-size:.82rem; font-weight:600;">Date limite (optionnel)</label>
                                <input type="date" class="form-control @error('date_limite') is-invalid @enderror" id="date_limite" name="date_limite" value="{{ old('date_limite') }}" style="border-radius:.625rem;">
                                @error('date_limite') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label" style="font-size:.82rem; font-weight:600;">Description *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required style="border-radius:.625rem;" placeholder="Détails de la demande...">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('demandes.index') }}" class="btn btn-light" style="border-radius:.625rem; font-weight:500; font-size:.85rem;">Annuler</a>
                            <button type="submit" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius:.625rem; font-weight:600; font-size:.85rem; padding:.55rem 1.5rem;">
                                <i class="bi bi-plus-lg"></i> Créer la demande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
