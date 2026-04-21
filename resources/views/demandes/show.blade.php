@extends('layouts.app')

@section('title', 'Demande #' . $demande->id . ' - CMC Pointage')
@section('page-title', 'Détail demande')
@section('breadcrumb', 'CMC Pointage › Demandes › #' . $demande->id)

@section('content')
<div>
    <a href="{{ route('demandes.index') }}" class="btn btn-light d-inline-flex align-items-center gap-2 mb-3" style="border-radius:.625rem; font-size:.85rem; font-weight:500;">
        <i class="bi bi-arrow-left"></i> Retour aux demandes
    </a>

    <div class="row g-3">
        <div class="col-12 col-lg-5 animate-in delay-1">
            <div class="card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h2 class="section-title mb-1">Demande #{{ $demande->id }}</h2>
                            <div class="section-subtitle">Créée le {{ \Carbon\Carbon::parse($demande->created_at)->format('d/m/Y à H:i') }}</div>
                        </div>
                        @if($demande->statut === 'en_attente')
                            <span class="status-badge" style="background:#fef3c7; color:#92400e;"><span class="status-dot" style="background:#f59e0b;"></span> En attente</span>
                        @elseif($demande->statut === 'approuvee')
                            <span class="status-badge" style="background:#d1fae5; color:#065f46;"><span class="status-dot" style="background:#10b981;"></span> Approuvée</span>
                        @elseif($demande->statut === 'rejetee')
                            <span class="status-badge" style="background:#fee2e2; color:#991b1b;"><span class="status-dot" style="background:#ef4444;"></span> Rejetée</span>
                        @else
                            <span class="status-badge" style="background:#e2e8f0; color:#475569;">Annulée</span>
                        @endif
                    </div>

                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between py-2 px-0" style="font-size:.85rem;">
                            <span class="text-muted">Type</span>
                            <span class="status-badge" style="background:#eff6ff; color:#1d4ed8;">{{ str_replace('_', ' ', ucfirst($demande->type)) }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between py-2 px-0" style="font-size:.85rem;">
                            <span class="text-muted">Étudiant</span>
                            <strong>{{ $demande->etudiant_prenom }} {{ $demande->etudiant_nom }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between py-2 px-0" style="font-size:.85rem;">
                            <span class="text-muted">CIN</span>
                            <strong>{{ $demande->cin }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between py-2 px-0" style="font-size:.85rem;">
                            <span class="text-muted">Email</span>
                            <strong>{{ $demande->etudiant_email }}</strong>
                        </div>
                        @if($demande->date_reponse)
                        <div class="list-group-item d-flex justify-content-between py-2 px-0" style="font-size:.85rem;">
                            <span class="text-muted">Traitée le</span>
                            <strong>{{ \Carbon\Carbon::parse($demande->date_reponse)->format('d/m/Y') }}</strong>
                        </div>
                        @endif
                        @if($demande->traitee_par_nom)
                        <div class="list-group-item d-flex justify-content-between py-2 px-0" style="font-size:.85rem;">
                            <span class="text-muted">Par</span>
                            <strong>{{ $demande->traitee_par_prenom }} {{ $demande->traitee_par_nom }}</strong>
                        </div>
                        @endif
                    </div>

                    <div class="mt-3">
                        <div class="text-muted mb-1" style="font-size:.75rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em;">Description</div>
                        <div class="p-3" style="background:#f8fafc; border-radius:.625rem; font-size:.85rem; line-height:1.6;">{{ $demande->description }}</div>
                    </div>

                    @if($demande->remarques)
                    <div class="mt-3">
                        <div class="text-muted mb-1" style="font-size:.75rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em;">Remarques admin</div>
                        <div class="p-3" style="background:#eff6ff; border-radius:.625rem; font-size:.85rem; line-height:1.6;">{{ $demande->remarques }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-7 animate-in delay-2">
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="section-title mb-3"><i class="bi bi-gear me-2" style="font-size:.9rem;"></i>Traiter la demande</h3>

                    <form method="POST" action="{{ route('demandes.updateStatus', $demande->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label" style="font-size:.82rem; font-weight:600;">Nouveau statut</label>
                            <div class="row g-2">
                                <div class="col-6 col-md-3">
                                    <input type="radio" class="btn-check" name="statut" id="st_approuvee" value="approuvee" {{ $demande->statut === 'approuvee' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-success w-100" for="st_approuvee" style="border-radius:.625rem; font-size:.82rem;">
                                        <i class="bi bi-check-lg"></i> Approuver
                                    </label>
                                </div>
                                <div class="col-6 col-md-3">
                                    <input type="radio" class="btn-check" name="statut" id="st_rejetee" value="rejetee" {{ $demande->statut === 'rejetee' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-danger w-100" for="st_rejetee" style="border-radius:.625rem; font-size:.82rem;">
                                        <i class="bi bi-x-lg"></i> Rejeter
                                    </label>
                                </div>
                                <div class="col-6 col-md-3">
                                    <input type="radio" class="btn-check" name="statut" id="st_attente" value="en_attente" {{ $demande->statut === 'en_attente' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-warning w-100" for="st_attente" style="border-radius:.625rem; font-size:.82rem;">
                                        <i class="bi bi-hourglass"></i> Attente
                                    </label>
                                </div>
                                <div class="col-6 col-md-3">
                                    <input type="radio" class="btn-check" name="statut" id="st_annulee" value="annulee" {{ $demande->statut === 'annulee' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-secondary w-100" for="st_annulee" style="border-radius:.625rem; font-size:.82rem;">
                                        <i class="bi bi-slash-circle"></i> Annuler
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="remarques_admin" class="form-label" style="font-size:.82rem; font-weight:600;">Remarques (optionnel)</label>
                            <textarea class="form-control" id="remarques_admin" name="remarques_admin" rows="4" placeholder="Ajoutez une remarque ou justification..." style="border-radius:.625rem;">{{ old('remarques_admin', $demande->remarques) }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius:.625rem; font-weight:600; font-size:.85rem; padding:.55rem 1.5rem;">
                            <i class="bi bi-check-lg"></i> Enregistrer la décision
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
