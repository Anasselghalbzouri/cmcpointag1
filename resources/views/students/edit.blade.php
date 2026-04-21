@extends('layouts.app')

@section('title', 'Modifier - ' . $student->prenom . ' ' . $student->nom)
@section('page-title', 'Modifier l\'étudiant')
@section('breadcrumb', 'CMC Pointage › Étudiants › Modifier')

@section('content')
<div>
    <a href="{{ route('students.show', $student->id) }}" class="btn btn-light d-inline-flex align-items-center gap-2 mb-3" style="border-radius:.625rem; font-size:.85rem; font-weight:500;">
        <i class="bi bi-arrow-left"></i> Retour au profil
    </a>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card animate-in">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div style="width:48px; height:48px; border-radius:.75rem; background:{{ $student->sexe === 'F' ? 'linear-gradient(135deg,#fce7f3,#fbcfe8)' : 'linear-gradient(135deg,#dbeafe,#bfdbfe)' }}; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:.9rem; color:{{ $student->sexe === 'F' ? '#be185d' : '#1d4ed8' }};">
                            {{ strtoupper(substr($student->prenom, 0, 1)) }}{{ strtoupper(substr($student->nom, 0, 1)) }}
                        </div>
                        <div>
                            <h2 class="section-title mb-0">Modifier : {{ $student->prenom }} {{ $student->nom }}</h2>
                            <p class="section-subtitle mb-0">CIN: {{ $student->cin }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('students.update', $student->id) }}">
                        @csrf
                        @method('PUT')

                        <h6 class="text-muted text-uppercase mb-3" style="font-size:.7rem; font-weight:700; letter-spacing:.08em;">Informations personnelles</h6>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="prenom" class="form-label" style="font-size:.82rem; font-weight:600;">Prénom</label>
                                <input type="text" class="form-control @error('prenom') is-invalid @enderror" id="prenom" name="prenom" value="{{ old('prenom', $student->prenom) }}" required style="border-radius:.625rem;">
                                @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="nom" class="form-label" style="font-size:.82rem; font-weight:600;">Nom</label>
                                <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom', $student->nom) }}" required style="border-radius:.625rem;">
                                @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="cin" class="form-label" style="font-size:.82rem; font-weight:600;">CIN</label>
                                <input type="text" class="form-control @error('cin') is-invalid @enderror" id="cin" name="cin" value="{{ old('cin', $student->cin) }}" required style="border-radius:.625rem;">
                                @error('cin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="date_naissance" class="form-label" style="font-size:.82rem; font-weight:600;">Date de naissance</label>
                                <input type="date" class="form-control @error('date_naissance') is-invalid @enderror" id="date_naissance" name="date_naissance" value="{{ old('date_naissance', $student->date_naissance) }}" required style="border-radius:.625rem;">
                                @error('date_naissance') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="sexe" class="form-label" style="font-size:.82rem; font-weight:600;">Sexe</label>
                                <select class="form-select @error('sexe') is-invalid @enderror" id="sexe" name="sexe" required style="border-radius:.625rem;">
                                    <option value="M" {{ old('sexe', $student->sexe) === 'M' ? 'selected' : '' }}>Masculin</option>
                                    <option value="F" {{ old('sexe', $student->sexe) === 'F' ? 'selected' : '' }}>Féminin</option>
                                </select>
                                @error('sexe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="nationalite" class="form-label" style="font-size:.82rem; font-weight:600;">Nationalité</label>
                                <input type="text" class="form-control @error('nationalite') is-invalid @enderror" id="nationalite" name="nationalite" value="{{ old('nationalite', $student->nationalite) }}" style="border-radius:.625rem;">
                                @error('nationalite') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <h6 class="text-muted text-uppercase mb-3" style="font-size:.7rem; font-weight:700; letter-spacing:.08em;">Contact</h6>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="email" class="form-label" style="font-size:.82rem; font-weight:600;">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $student->email) }}" required style="border-radius:.625rem;">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="telephone" class="form-label" style="font-size:.82rem; font-weight:600;">Téléphone</label>
                                <input type="text" class="form-control @error('telephone') is-invalid @enderror" id="telephone" name="telephone" value="{{ old('telephone', $student->telephone) }}" required style="border-radius:.625rem;">
                                @error('telephone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <h6 class="text-muted text-uppercase mb-3" style="font-size:.7rem; font-weight:700; letter-spacing:.08em;">Hébergement & Statut</h6>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="chambre_id" class="form-label" style="font-size:.82rem; font-weight:600;">Chambre</label>
                                <select class="form-select @error('chambre_id') is-invalid @enderror" id="chambre_id" name="chambre_id" style="border-radius:.625rem;">
                                    <option value="">— Sans chambre —</option>
                                    @foreach($chambres as $c)
                                        <option value="{{ $c->id }}" {{ old('chambre_id', $student->chambre_id) == $c->id ? 'selected' : '' }}>
                                            {{ $c->numero }} — Pav. {{ ucfirst($c->pavillon_nom) }}, Étg {{ $c->etage }} ({{ $c->occupants_actuels }}/{{ $c->capacite }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('chambre_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="statut" class="form-label" style="font-size:.82rem; font-weight:600;">Statut</label>
                                <select class="form-select @error('statut') is-invalid @enderror" id="statut" name="statut" required style="border-radius:.625rem;">
                                    <option value="actif" {{ old('statut', $student->statut) === 'actif' ? 'selected' : '' }}>Actif</option>
                                    <option value="suspendu" {{ old('statut', $student->statut) === 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                                    <option value="sorti" {{ old('statut', $student->statut) === 'sorti' ? 'selected' : '' }}>Sorti</option>
                                    <option value="archive" {{ old('statut', $student->statut) === 'archive' ? 'selected' : '' }}>Archivé</option>
                                </select>
                                @error('statut') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('students.show', $student->id) }}" class="btn btn-light" style="border-radius:.625rem; font-weight:500; font-size:.85rem;">Annuler</a>
                            <button type="submit" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius:.625rem; font-weight:600; font-size:.85rem; padding:.55rem 1.5rem;">
                                <i class="bi bi-check-lg"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
