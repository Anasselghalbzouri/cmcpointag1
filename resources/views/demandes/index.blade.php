@extends('layouts.app')

@section('title', 'Demandes - CMC Pointage')
@section('page-title', 'Demandes')
@section('breadcrumb', 'CMC Pointage › Gestion des demandes')

@section('content')
<div>
    {{-- Header Row --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 animate-in">
        <div>
            <h2 class="section-title mb-0">Gestion des demandes</h2>
            <p class="section-subtitle mb-0">{{ $demandes->total() }} demande(s) trouvée(s)</p>
        </div>
        <a href="{{ route('demandes.create') }}" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius:.625rem; font-weight:600; font-size:.85rem; padding:.55rem 1.25rem;">
            <i class="bi bi-plus-lg"></i> Nouvelle demande
        </a>
    </div>

    {{-- KPI --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="kpi-card animate-in delay-1">
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div class="kpi-icon" style="background:linear-gradient(135deg,#dbeafe,#eff6ff); color:#2563eb;"><i class="bi bi-file-earmark-text-fill"></i></div>
                    <div><div class="kpi-label">Total</div><div class="kpi-value">{{ $stats['total'] }}</div></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="kpi-card animate-in delay-2">
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div class="kpi-icon" style="background:linear-gradient(135deg,#fef3c7,#fffbeb); color:#d97706;"><i class="bi bi-hourglass-split"></i></div>
                    <div><div class="kpi-label">En attente</div><div class="kpi-value">{{ $stats['en_attente'] }}</div></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="kpi-card animate-in delay-3">
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div class="kpi-icon" style="background:linear-gradient(135deg,#d1fae5,#ecfdf5); color:#059669;"><i class="bi bi-check-circle-fill"></i></div>
                    <div><div class="kpi-label">Approuvées</div><div class="kpi-value">{{ $stats['approuvee'] }}</div></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="kpi-card animate-in delay-4">
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div class="kpi-icon" style="background:linear-gradient(135deg,#fee2e2,#fef2f2); color:#dc2626;"><i class="bi bi-x-circle-fill"></i></div>
                    <div><div class="kpi-label">Rejetées</div><div class="kpi-value">{{ $stats['rejetee'] }}</div></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-4 animate-in" style="animation-delay:.15s;">
        <div class="card-body py-3 px-4">
            <form method="GET" action="{{ route('demandes.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Rechercher par nom, CIN, description...">
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <select class="form-select" name="statut" style="border-radius:.75rem; height:42px; font-size:.85rem;">
                        <option value="">Tous statuts</option>
                        <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="approuvee" {{ request('statut') === 'approuvee' ? 'selected' : '' }}>Approuvée</option>
                        <option value="rejetee" {{ request('statut') === 'rejetee' ? 'selected' : '' }}>Rejetée</option>
                        <option value="annulee" {{ request('statut') === 'annulee' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select class="form-select" name="type" style="border-radius:.75rem; height:42px; font-size:.85rem;">
                        <option value="">Tous types</option>
                        @foreach($types as $t)
                            <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ str_replace('_', ' ', ucfirst($t)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-dark flex-grow-1" style="border-radius:.625rem; font-weight:600; font-size:.85rem; height:42px;"><i class="bi bi-funnel"></i> Filtrer</button>
                    @if(request()->hasAny(['search', 'statut', 'type']))
                        <a href="{{ route('demandes.index') }}" class="btn btn-outline-secondary" style="border-radius:.625rem; font-size:.85rem; height:42px; display:flex; align-items:center;"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card animate-in" style="animation-delay:.2s;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Étudiant</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($demandes as $d)
                            <tr>
                                <td>
                                    <span class="fw-semibold">{{ \Carbon\Carbon::parse($d->created_at)->format('d/m/Y') }}</span>
                                    <div class="text-muted" style="font-size:.7rem;">{{ \Carbon\Carbon::parse($d->created_at)->format('H:i') }}</div>
                                </td>
                                <td>
                                    <div class="fw-medium" style="font-size:.875rem;">{{ $d->etudiant_prenom }} {{ $d->etudiant_nom }}</div>
                                    <div class="text-muted" style="font-size:.72rem;">{{ $d->cin }}</div>
                                </td>
                                <td><span class="status-badge" style="background:#eff6ff; color:#1d4ed8;">{{ str_replace('_', ' ', ucfirst($d->type)) }}</span></td>
                                <td style="font-size:.82rem; max-width:250px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ Str::limit($d->description, 60) }}</td>
                                <td>
                                    @if($d->statut === 'en_attente')
                                        <span class="status-badge" style="background:#fef3c7; color:#92400e;"><span class="status-dot" style="background:#f59e0b;"></span> En attente</span>
                                    @elseif($d->statut === 'approuvee')
                                        <span class="status-badge" style="background:#d1fae5; color:#065f46;"><span class="status-dot" style="background:#10b981;"></span> Approuvée</span>
                                    @elseif($d->statut === 'rejetee')
                                        <span class="status-badge" style="background:#fee2e2; color:#991b1b;"><span class="status-dot" style="background:#ef4444;"></span> Rejetée</span>
                                    @else
                                        <span class="status-badge" style="background:#e2e8f0; color:#475569;">Annulée</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('demandes.show', $d->id) }}" class="btn btn-sm btn-light" style="border-radius:.5rem; width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center;" title="Voir">
                                        <i class="bi bi-eye" style="font-size:.85rem;"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-file-earmark-text" style="font-size:2rem; display:block; margin-bottom:.5rem; opacity:.3;"></i>
                                        <div style="font-size:.9rem; font-weight:500;">Aucune demande trouvée</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($demandes->hasPages())
                <div class="px-4 py-3">
                    {{ $demandes->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
