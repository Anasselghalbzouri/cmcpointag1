@extends('layouts.app')

@section('title', 'Chambres - CMC Pointage')
@section('page-title', 'Chambres')
@section('breadcrumb', 'CMC Pointage › Gestion des chambres')

@section('content')
<div>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 animate-in">
        <div>
            <h2 class="section-title mb-0">Gestion des chambres</h2>
            <p class="section-subtitle mb-0">{{ $chambres->total() }} chambre(s) trouvée(s)</p>
        </div>
    </div>

    <div class="card mb-4 animate-in" style="animation-delay:.15s;">
        <div class="card-body py-3 px-4">
            <form method="GET" action="{{ route('chambres.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Rechercher par numéro...">
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <select class="form-select" name="pavillon" style="border-radius:.75rem; height:42px; font-size:.85rem;">
                        <option value="">Tous pavillons</option>
                        @foreach($pavillons as $p)
                            <option value="{{ $p->id }}" {{ request('pavillon') == $p->id ? 'selected' : '' }}>{{ ucfirst($p->type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select class="form-select" name="statut" style="border-radius:.75rem; height:42px; font-size:.85rem;">
                        <option value="">Tous statuts</option>
                        <option value="disponible" {{ request('statut') === 'disponible' ? 'selected' : '' }}>Disponible</option>
                        <option value="occupee" {{ request('statut') === 'occupee' ? 'selected' : '' }}>Occupée</option>
                        <option value="maintenance" {{ request('statut') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="fermee" {{ request('statut') === 'fermee' ? 'selected' : '' }}>Fermée</option>
                    </select>
                </div>
                <div class="col-12 col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-dark flex-grow-1" style="border-radius:.625rem; font-weight:600; font-size:.85rem; height:42px;"><i class="bi bi-funnel"></i> Filtrer</button>
                    @if(request()->hasAny(['search', 'pavillon', 'statut']))
                        <a href="{{ route('chambres.index') }}" class="btn btn-outline-secondary" style="border-radius:.625rem; font-size:.85rem; height:42px; display:flex; align-items:center;"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card animate-in" style="animation-delay:.2s;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Numéro</th>
                            <th>Pavillon</th>
                            <th>Étage</th>
                            <th>Occupants / Capacité</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($chambres as $c)
                            <tr>
                                <td class="fw-medium">{{ $c->numero }}</td>
                                <td><span class="status-badge" style="background:#f1f5f9; color:#475569;">{{ ucfirst($c->pavillon_type) }}</span></td>
                                <td>{{ $c->etage }}</td>
                                <td>
                                    <span class="fw-semibold {{ $c->occupants_actuels >= $c->capacite ? 'text-danger' : '' }}">{{ $c->occupants_actuels }}</span>
                                    <span class="text-muted">/ {{ $c->capacite }}</span>
                                </td>
                                <td>
                                    @if($c->statut === 'disponible')
                                        <span class="status-badge" style="background:#d1fae5; color:#065f46;"><span class="status-dot" style="background:#10b981;"></span> Disponible</span>
                                    @elseif($c->statut === 'occupee')
                                        <span class="status-badge" style="background:#fef3c7; color:#92400e;"><span class="status-dot" style="background:#f59e0b;"></span> Occupée</span>
                                    @elseif($c->statut === 'maintenance')
                                        <span class="status-badge" style="background:#ffedd5; color:#9a3412;"><span class="status-dot" style="background:#fb923c;"></span> Maintenance</span>
                                    @else
                                        <span class="status-badge" style="background:#e2e8f0; color:#475569;"><span class="status-dot" style="background:#94a3b8;"></span> Fermée</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="{{ route('chambres.edit', $c->id) }}" class="btn btn-sm btn-light" style="border-radius:.5rem; width:32px; height:32px; display:flex; align-items:center; justify-content:center;" title="Modifier">
                                            <i class="bi bi-pencil" style="font-size:.8rem;"></i>
                                        </a>
                                        <form method="POST" action="{{ route('chambres.destroy', $c->id) }}" class="d-inline" onsubmit="return confirm('Supprimer cette chambre ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light" style="border-radius:.5rem; width:32px; height:32px; display:flex; align-items:center; justify-content:center; color:#ef4444;" title="Supprimer">
                                                <i class="bi bi-trash3" style="font-size:.8rem;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">Aucune chambre trouvée</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($chambres->hasPages())
                <div class="px-4 py-3">
                    {{ $chambres->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
