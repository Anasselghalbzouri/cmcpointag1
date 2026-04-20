@extends('layouts.app')

@section('title', 'Dashboard Admin - CMC Pointage')

@section('content')
<style>
    .admin-shell {
        background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        padding: 1rem;
    }
    .kpi-card {
        border: 1px solid #e5e7eb;
        border-radius: 1rem;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
    }
    .kpi-label {
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #64748b;
        font-weight: 700;
    }
    .section-card {
        border: 1px solid #e5e7eb;
        border-radius: 1rem;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
    }
</style>

<div class="admin-shell">
<div>
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold">Dashboard Administrateur</h1>
            <p class="text-muted mb-0">Vue globale résidence - Laravel / MySQL</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('pointage.index') }}" class="btn btn-primary">Check-in / out</a>
            <a href="{{ route('students.create') }}" class="btn btn-success">Ajouter étudiant</a>
            <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Gestion étudiants</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card kpi-card h-100">
                <div class="card-body">
                    <div class="kpi-label mb-1">Total étudiants</div>
                    <div class="display-6 fw-bold text-dark">{{ $stats['total_students'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card kpi-card h-100">
                <div class="card-body">
                    <div class="kpi-label mb-1">Présents à 22h</div>
                    <div class="display-6 fw-bold text-success">{{ $stats['present_22h'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card kpi-card h-100">
                <div class="card-body">
                    <div class="kpi-label mb-1">Absents à 22h</div>
                    <div class="display-6 fw-bold text-danger">{{ $stats['absent_22h'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card kpi-card h-100">
                <div class="card-body">
                    <div class="kpi-label mb-1">Demandes en attente</div>
                    <div class="display-6 fw-bold text-warning">{{ $stats['pending_requests'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-7">
            <div class="card section-card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h2 class="h5 mb-0">Mouvements récents</h2>
                    <a href="{{ route('pointage.index') }}" class="btn btn-outline-secondary btn-sm">Voir tout</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3">Date / Heure</th>
                                    <th>Étudiant</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_movements as $movement)
                                    <tr>
                                        <td class="ps-3">{{ $movement->scanned_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $movement->user->prenom }} {{ $movement->user->nom }}</td>
                                        <td>
                                            @if($movement->type === 'entree')
                                                <span class="badge text-bg-success">Entrée</span>
                                            @else
                                                <span class="badge text-bg-secondary">Sortie</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Aucun mouvement enregistré</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-5">
            <div class="card section-card mb-3">
                <div class="card-body">
                    <h2 class="h5 mb-3">Panel d'alertes</h2>
                    <div class="vstack gap-2">
                        @foreach($alerts as $alert)
                            <div class="alert alert-{{ $alert['variant'] }} py-2 px-3 mb-0 d-flex justify-content-between align-items-center">
                                <span>{{ $alert['label'] }}</span>
                                <span class="fw-bold">{{ $alert['value'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card section-card">
                <div class="card-body">
                    <h2 class="h5 mb-2">Taux d'occupation global</h2>
                    <p class="text-muted small mb-2">Basé sur la présence actuelle des étudiants</p>
                    <div class="progress" role="progressbar" aria-label="Occupancy rate" aria-valuenow="{{ $stats['occupancy_rate'] }}" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-success" style="width: {{ $stats['occupancy_rate'] }}%">{{ $stats['occupancy_rate'] }}%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card section-card mt-3">
        <div class="card-header bg-white">
            <h2 class="h5 mb-0">Aperçu des pavillons</h2>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($pavilions as $pavilion)
                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="border rounded-3 p-3 h-100 bg-light-subtle">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>{{ $pavilion['name'] }}</strong>
                                @if($pavilion['status'] === 'Active')
                                    <span class="badge text-bg-success">Active</span>
                                @elseif($pavilion['status'] === 'Maintenance')
                                    <span class="badge text-bg-warning">Maintenance</span>
                                @else
                                    <span class="badge text-bg-secondary">Closed</span>
                                @endif
                            </div>
                            <div class="small text-muted">Capacité: {{ $pavilion['occupied'] }} / {{ $pavilion['capacity'] }}</div>
                            <div class="small text-muted mb-2">Places libres: {{ $pavilion['free'] }}</div>
                            <div class="progress" role="progressbar" aria-valuenow="{{ $pavilion['occupancy_rate'] }}" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar {{ $pavilion['status'] === 'Maintenance' ? 'bg-warning' : 'bg-primary' }}" style="width: {{ $pavilion['occupancy_rate'] }}%">
                                    {{ $pavilion['occupancy_rate'] }}%
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
</div>
@endsection
