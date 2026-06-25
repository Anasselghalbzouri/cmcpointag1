@extends('layouts.app')

@section('title', 'Nouvelle sanction - CMC Pointage')
@section('page-title', 'Nouvelle sanction')
@section('breadcrumb', 'CMC Pointage › Sanctions › Ajouter')

@section('content')
<div>
    <a href="{{ route('sanctions.index') }}" class="btn btn-light d-inline-flex align-items-center gap-2 mb-3" style="border-radius:.625rem; font-size:.85rem; font-weight:500;">
        <i class="bi bi-arrow-left"></i> Retour aux sanctions
    </a>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card animate-in">
                <div class="card-body p-4">
                    <h2 class="section-title mb-1">Émettre une sanction</h2>
                    <p class="section-subtitle mb-4">Remplissez les informations ci-dessous</p>

                    <form method="POST" action="{{ route('sanctions.store') }}">
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
                                    <option value="avertissement" {{ old('type') === 'avertissement' ? 'selected' : '' }}>Avertissement</option>
                                    <option value="suspension" {{ old('type') === 'suspension' ? 'selected' : '' }}>Suspension</option>
                                    <option value="amende" {{ old('type') === 'amende' ? 'selected' : '' }}>Amende</option>
                                    <option value="exclusion" {{ old('type') === 'exclusion' ? 'selected' : '' }}>Exclusion</option>
                                </select>
                                @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="date_sanction" class="form-label" style="font-size:.82rem; font-weight:600;">Date de la sanction *</label>
                                <input type="date" class="form-control @error('date_sanction') is-invalid @enderror" id="date_sanction" name="date_sanction" value="{{ old('date_sanction', now()->format('Y-m-d')) }}" required style="border-radius:.625rem;">
                                @error('date_sanction') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="duree" class="form-label" style="font-size:.82rem; font-weight:600;">Durée de la sanction (jours)</label>
                                <input type="number" step="1" min="1" class="form-control @error('duree') is-invalid @enderror" id="duree" name="duree" value="{{ old('duree') }}" style="border-radius:.625rem;" placeholder="Ex: 14">
                                @error('duree') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="motif" class="form-label" style="font-size:.82rem; font-weight:600;">Motif *</label>
                            <input type="text" class="form-control @error('motif') is-invalid @enderror" id="motif" name="motif" value="{{ old('motif') }}" required style="border-radius:.625rem;" placeholder="Ex: Tapage nocturne répété">
                            @error('motif') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label" style="font-size:.82rem; font-weight:600;">Description *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required style="border-radius:.625rem;" placeholder="Détails de l'incident...">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('sanctions.index') }}" class="btn btn-light" style="border-radius:.625rem; font-weight:500; font-size:.85rem;">Annuler</a>
                            <button type="submit" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius:.625rem; font-weight:600; font-size:.85rem; padding:.55rem 1.5rem;">
                                <i class="bi bi-plus-lg"></i> Émettre la sanction
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
