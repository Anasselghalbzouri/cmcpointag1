@extends('layouts.app')

@section('title', 'Ajouter un étudiant - CMC Pointage')
@section('page-title', 'Nouvel étudiant')
@section('breadcrumb', 'CMC Pointage › Étudiants › Ajouter')

@section('content')
<div>
    <a href="{{ route('students.index') }}" class="btn btn-light d-inline-flex align-items-center gap-2 mb-3" style="border-radius:.625rem; font-size:.85rem; font-weight:500;">
        <i class="bi bi-arrow-left"></i> Retour à la liste
    </a>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card animate-in">
                <div class="card-body p-4">
                    <h2 class="section-title mb-1">Ajouter un étudiant</h2>
                    <p class="section-subtitle mb-4">Remplissez les informations ci-dessous</p>

                    <form method="POST" action="{{ route('students.store') }}">
                        @csrf

                        <h6 class="text-muted text-uppercase mb-3" style="font-size:.7rem; font-weight:700; letter-spacing:.08em;">Informations personnelles</h6>

                        <div class="row g-3 mb-4">
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
                                <label for="cin" class="form-label" style="font-size:.82rem; font-weight:600;">CIN *</label>
                                <input type="text" class="form-control @error('cin') is-invalid @enderror" id="cin" name="cin" value="{{ old('cin') }}" required style="border-radius:.625rem;" placeholder="Ex: AB123456">
                                @error('cin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="date_naissance" class="form-label" style="font-size:.82rem; font-weight:600;">Date de naissance *</label>
                                <input type="date" class="form-control @error('date_naissance') is-invalid @enderror" id="date_naissance" name="date_naissance" value="{{ old('date_naissance') }}" required style="border-radius:.625rem;">
                                @error('date_naissance') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="sexe" class="form-label" style="font-size:.82rem; font-weight:600;">Sexe *</label>
                                <select class="form-select @error('sexe') is-invalid @enderror" id="sexe" name="sexe" required style="border-radius:.625rem;">
                                    <option value="">— Sélectionner —</option>
                                    <option value="M" {{ old('sexe') === 'M' ? 'selected' : '' }}>Masculin</option>
                                    <option value="F" {{ old('sexe') === 'F' ? 'selected' : '' }}>Féminin</option>
                                </select>
                                @error('sexe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="nationalite" class="form-label" style="font-size:.82rem; font-weight:600;">Nationalité</label>
                                <input type="text" class="form-control" id="nationalite" name="nationalite" value="{{ old('nationalite', 'Maroc') }}" style="border-radius:.625rem;">
                            </div>
                        </div>

                        <h6 class="text-muted text-uppercase mb-3" style="font-size:.7rem; font-weight:700; letter-spacing:.08em;">Contact</h6>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="email" class="form-label" style="font-size:.82rem; font-weight:600;">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required style="border-radius:.625rem;">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="telephone" class="form-label" style="font-size:.82rem; font-weight:600;">Téléphone *</label>
                                <input type="text" class="form-control @error('telephone') is-invalid @enderror" id="telephone" name="telephone" value="{{ old('telephone') }}" required style="border-radius:.625rem;" placeholder="06XXXXXXXX">
                                @error('telephone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <h6 class="text-muted text-uppercase mb-3" style="font-size:.7rem; font-weight:700; letter-spacing:.08em;">Formation</h6>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="duree_formation" class="form-label" style="font-size:.82rem; font-weight:600;">Durée de la formation *</label>
                                <select class="form-select @error('duree_formation') is-invalid @enderror" id="duree_formation" name="duree_formation" required style="border-radius:.625rem;">
                                    <option value="2_ans" {{ old('duree_formation', '2_ans') === '2_ans' ? 'selected' : '' }}>2 ans</option>
                                    <option value="2_ans_demi" {{ old('duree_formation') === '2_ans_demi' ? 'selected' : '' }}>2 ans et demi</option>
                                </select>
                                @error('duree_formation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <h6 class="text-muted text-uppercase mb-3" style="font-size:.7rem; font-weight:700; letter-spacing:.08em;">Hébergement</h6>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="chambre_id" class="form-label" style="font-size:.82rem; font-weight:600;">Chambre</label>
                                <select class="form-select @error('chambre_id') is-invalid @enderror" id="chambre_id" name="chambre_id" style="border-radius:.625rem;">
                                    <option value="">— Sans chambre —</option>
                                    @foreach($chambres as $c)
                                        <option value="{{ $c->id }}" data-sexe="{{ $c->pavillon_nom === 'homme' ? 'M' : 'F' }}" {{ old('chambre_id') == $c->id ? 'selected' : '' }}>
                                            {{ $c->numero }} — Pav. {{ ucfirst($c->pavillon_nom) }}, Étg {{ $c->etage }} ({{ $c->occupants_actuels }}/{{ $c->capacite }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('chambre_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <div class="form-text" id="chambreHint" style="font-size:.75rem;">Sélectionnez le sexe pour filtrer les chambres disponibles.</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('students.index') }}" class="btn btn-light" style="border-radius:.625rem; font-weight:500; font-size:.85rem;">Annuler</a>
                            <button type="submit" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius:.625rem; font-weight:600; font-size:.85rem; padding:.55rem 1.5rem;">
                                <i class="bi bi-plus-lg"></i> Créer l'étudiant
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        var sexeSelect = document.getElementById('sexe');
        var chambreSelect = document.getElementById('chambre_id');
        var options = Array.from(chambreSelect.options);

        function filterChambres() {
            var sexe = sexeSelect.value;
            options.forEach(function (opt) {
                if (!opt.value) return;
                var matches = !sexe || opt.dataset.sexe === sexe;
                opt.hidden = !matches;
                if (!matches && opt.selected) {
                    chambreSelect.value = '';
                }
            });
        }

        sexeSelect.addEventListener('change', filterChambres);
        filterChambres();
    })();
</script>
@endsection
