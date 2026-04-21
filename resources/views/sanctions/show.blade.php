@extends('layouts.app')

@section('title', 'Sanction #' . $sanction->id . ' - CMC Pointage')
@section('page-title', 'Détail sanction')
@section('breadcrumb', 'CMC Pointage › Sanctions › #' . $sanction->id)

@section('content')
<div>
    <a href="{{ route('sanctions.index') }}" class="btn btn-light d-inline-flex align-items-center gap-2 mb-3" style="border-radius:.625rem; font-size:.85rem; font-weight:500;">
        <i class="bi bi-arrow-left"></i> Retour aux sanctions
    </a>

    <div class="row g-3">
        <div class="col-12 col-lg-5 animate-in delay-1">
            <div class="card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h2 class="section-title mb-1">Sanction #{{ $sanction->id }}</h2>
                            <div class="section-subtitle">Enregistrée le {{ \Carbon\Carbon::parse($sanction->date_sanction)->format('d/m/Y') }}</div>
                        </div>
                        @if($sanction->statut === 'active')
                            <span class="status-badge" style="background:#fee2e2; color:#991b1b;"><span class="status-dot" style="background:#ef4444;"></span> Active</span>
                        @elseif($sanction->statut === 'levee')
                            <span class="status-badge" style="background:#d1fae5; color:#065f46;"><span class="status-dot" style="background:#10b981;"></span> Levée</span>
                        @else
                            <span class="status-badge" style="background:#fef3c7; color:#92400e;"><span class="status-dot" style="background:#f59e0b;"></span> {{ ucfirst($sanction->statut) }}</span>
                        @endif
                    </div>

                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between py-2 px-0" style="font-size:.85rem;">
                            <span class="text-muted">Type</span>
                            <span class="status-badge" style="background:#f1f5f9; color:#475569;">{{ ucfirst($sanction->type) }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between py-2 px-0" style="font-size:.85rem;">
                            <span class="text-muted">Étudiant</span>
                            <strong>{{ $sanction->etudiant_prenom }} {{ $sanction->etudiant_nom }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between py-2 px-0" style="font-size:.85rem;">
                            <span class="text-muted">CIN</span>
                            <strong>{{ $sanction->cin }}</strong>
                        </div>
                        @if($sanction->montant_amende)
                        <div class="list-group-item d-flex justify-content-between py-2 px-0" style="font-size:.85rem;">
                            <span class="text-muted">Montant</span>
                            <strong class="text-danger">{{ number_format($sanction->montant_amende, 0) }} DH</strong>
                        </div>
                        @endif
                        @if($sanction->date_fin)
                        <div class="list-group-item d-flex justify-content-between py-2 px-0" style="font-size:.85rem;">
                            <span class="text-muted">Jusqu'au</span>
                            <strong>{{ \Carbon\Carbon::parse($sanction->date_fin)->format('d/m/Y') }}</strong>
                        </div>
                        @endif
                        <div class="list-group-item d-flex justify-content-between py-2 px-0" style="font-size:.85rem;">
                            <span class="text-muted">Enregistrée par</span>
                            <strong>{{ $sanction->enregistre_prenom }} {{ $sanction->enregistre_nom }}</strong>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="text-muted mb-1" style="font-size:.75rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em;">Motif</div>
                        <div class="p-3" style="background:#f8fafc; border-radius:.625rem; font-size:.85rem; line-height:1.6;">{{ $sanction->motif }}</div>
                    </div>

                    @if($sanction->observations)
                    <div class="mt-3">
                        <div class="text-muted mb-1" style="font-size:.75rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em;">Observations admin</div>
                        <div class="p-3" style="background:#eff6ff; border-radius:.625rem; font-size:.85rem; line-height:1.6;">{{ $sanction->observations }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-7 animate-in delay-2">
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="section-title mb-3"><i class="bi bi-gear me-2" style="font-size:.9rem;"></i>Modifier le statut</h3>

                    <form method="POST" action="{{ route('sanctions.updateStatus', $sanction->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label" style="font-size:.82rem; font-weight:600;">Nouveau statut</label>
                            <div class="row g-2">
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="statut" id="st_active" value="active" {{ $sanction->statut === 'active' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-danger w-100" for="st_active" style="border-radius:.625rem; font-size:.82rem;">
                                        <i class="bi bi-exclamation-triangle"></i> Active
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="statut" id="st_levee" value="levee" {{ $sanction->statut === 'levee' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-success w-100" for="st_levee" style="border-radius:.625rem; font-size:.82rem;">
                                        <i class="bi bi-check-circle"></i> Levée
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="statut" id="st_suspendue" value="suspendue" {{ $sanction->statut === 'suspendue' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-warning w-100" for="st_suspendue" style="border-radius:.625rem; font-size:.82rem;">
                                        <i class="bi bi-pause-circle"></i> Suspendue
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="observations" class="form-label" style="font-size:.82rem; font-weight:600;">Observations / Justification</label>
                            <textarea class="form-control" id="observations" name="observations" rows="4" placeholder="Ajoutez une observation sur le changement de statut..." style="border-radius:.625rem;">{{ old('observations', $sanction->observations) }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius:.625rem; font-weight:600; font-size:.85rem; padding:.55rem 1.5rem;">
                            <i class="bi bi-check-lg"></i> Enregistrer les modifications
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
