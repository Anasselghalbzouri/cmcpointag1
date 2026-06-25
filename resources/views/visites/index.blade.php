@extends('layouts.app')

@section('title', 'Visites - CMC Pointage')
@section('page-title', 'Visites')
@section('breadcrumb', 'CMC Pointage › Gestion des visites')

@section('content')
<div>
    {{-- Header Row --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 animate-in">
        <div>
            <h2 class="section-title mb-0">Gestion des visites</h2>
            <p class="section-subtitle mb-0">{{ $visites->total() }} visite(s) trouvée(s)</p>
        </div>
        <a href="{{ route('visites.create') }}" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius:.625rem; font-weight:600; font-size:.85rem; padding:.55rem 1.25rem;">
            <i class="bi bi-plus-lg"></i> Nouvelle visite
        </a>
    </div>

    {{-- KPI --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-4">
            <div class="kpi-card animate-in delay-1">
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div class="kpi-icon" style="background:linear-gradient(135deg,#dbeafe,#eff6ff); color:#2563eb;"><i class="bi bi-person-walking"></i></div>
                    <div><div class="kpi-label">Total</div><div class="kpi-value">{{ $stats['total'] }}</div></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-4">
            <div class="kpi-card animate-in delay-2">
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div class="kpi-icon" style="background:linear-gradient(135deg,#fef3c7,#fffbeb); color:#d97706;"><i class="bi bi-hourglass-split"></i></div>
                    <div><div class="kpi-label">En cours</div><div class="kpi-value">{{ $stats['en_cours'] }}</div></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-4">
            <div class="kpi-card animate-in delay-3">
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div class="kpi-icon" style="background:linear-gradient(135deg,#d1fae5,#ecfdf5); color:#059669;"><i class="bi bi-calendar-check"></i></div>
                    <div><div class="kpi-label">Aujourd'hui</div><div class="kpi-value">{{ $stats['today'] }}</div></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-4 animate-in" style="animation-delay:.15s;">
        <div class="card-body py-3 px-4">
            <form method="GET" action="{{ route('visites.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-6">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Rechercher par nom, CIN, matricule...">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <select class="form-select" name="statut" style="border-radius:.75rem; height:42px; font-size:.85rem;">
                        <option value="">Tous statuts</option>
                        <option value="en_cours" {{ request('statut') === 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="sortie" {{ request('statut') === 'sortie' ? 'selected' : '' }}>Sortie</option>
                        <option value="entree" {{ request('statut') === 'entree' ? 'selected' : '' }}>Entrée</option>
                    </select>
                </div>
                <div class="col-12 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-dark flex-grow-1" style="border-radius:.625rem; font-weight:600; font-size:.85rem; height:42px;"><i class="bi bi-funnel"></i> Filtrer</button>
                    @if(request()->hasAny(['search', 'statut']))
                        <a href="{{ route('visites.index') }}" class="btn btn-outline-secondary" style="border-radius:.625rem; font-size:.85rem; height:42px; display:flex; align-items:center;"><i class="bi bi-x-lg"></i></a>
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
                            <th>Visiteur</th>
                            <th>CIN</th>
                            <th>Entrée</th>
                            <th>Sortie</th>
                            <th>Motif</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($visites as $v)
                            <tr>
                                <td>
                                    <div class="fw-medium" style="font-size:.875rem;">{{ $v->prenom_visiteur }} {{ $v->nom_visiteur }}</div>
                                    @if($v->matricul_visiteur)
                                        <div class="text-muted" style="font-size:.7rem;">{{ $v->matricul_visiteur }}</div>
                                    @endif
                                </td>
                                <td><code style="background:#f1f5f9; color:#475569; padding:.15rem .4rem; border-radius:.375rem; font-size:.75rem;">{{ $v->cin_visiteur }}</code></td>
                                <td style="font-size:.82rem;">{{ \Carbon\Carbon::parse($v->date_heure_entree)->format('d/m/Y H:i') }}</td>
                                <td style="font-size:.82rem;">
                                    @if($v->date_heure_sortie)
                                        {{ \Carbon\Carbon::parse($v->date_heure_sortie)->format('d/m/Y H:i') }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td style="font-size:.82rem; max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $v->motif }}</td>
                                <td>
                                    @if($v->statut === 'en_cours')
                                        <span class="status-badge" style="background:#fef3c7; color:#92400e;"><span class="status-dot" style="background:#f59e0b;"></span> En cours</span>
                                    @elseif($v->statut === 'sortie')
                                        <span class="status-badge" style="background:#d1fae5; color:#065f46;"><span class="status-dot" style="background:#10b981;"></span> Sortie</span>
                                    @else
                                        <span class="status-badge" style="background:#e2e8f0; color:#475569;">Entrée</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($v->statut === 'en_cours')
                                        <form method="POST" action="{{ route('visites.checkout', $v->id) }}" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-light" style="border-radius:.5rem; font-size:.78rem; font-weight:600;" title="Enregistrer la sortie">
                                                <i class="bi bi-box-arrow-right"></i> Sortie
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-person-walking" style="font-size:2rem; display:block; margin-bottom:.5rem; opacity:.3;"></i>
                                        <div style="font-size:.9rem; font-weight:500;">Aucune visite trouvée</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($visites->hasPages())
                <div class="px-4 py-3">
                    {{ $visites->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
