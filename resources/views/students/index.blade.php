@extends('layouts.app')

@section('title', 'Étudiants - CMC Pointage')
@section('page-title', 'Étudiants')
@section('breadcrumb', 'CMC Pointage › Gestion des étudiants')

@section('content')
<div>
    {{-- Header Row --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 animate-in">
        <div>
            <h2 class="section-title mb-0">Gestion des étudiants</h2>
            <p class="section-subtitle mb-0">{{ count($students) }} étudiant(s) trouvé(s)</p>
        </div>
        <a href="{{ route('students.create') }}" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius:.625rem; font-weight:600; font-size:.85rem; padding:.55rem 1.25rem;">
            <i class="bi bi-plus-lg"></i> Ajouter un étudiant
        </a>
    </div>

    {{-- Search & Filters --}}
    <div class="card mb-4 animate-in delay-1">
        <div class="card-body py-3 px-4">
            <form method="GET" action="{{ route('students.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-5">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input
                            type="text"
                            class="form-control"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Rechercher par nom, CIN, email, n° chambre..."
                        >
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <select class="form-select" name="statut" style="border-radius:.75rem; height:42px; font-size:.85rem;">
                        <option value="">Tous statuts</option>
                        <option value="actif" {{ request('statut') === 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="suspendu" {{ request('statut') === 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                        <option value="sorti" {{ request('statut') === 'sorti' ? 'selected' : '' }}>Sorti</option>
                        <option value="archive" {{ request('statut') === 'archive' ? 'selected' : '' }}>Archivé</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select class="form-select" name="pavillon" style="border-radius:.75rem; height:42px; font-size:.85rem;">
                        <option value="">Tous pavillons</option>
                        @foreach($pavillons as $p)
                            <option value="{{ $p->id }}" {{ request('pavillon') == $p->id ? 'selected' : '' }}>{{ ucfirst($p->type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-dark flex-grow-1" style="border-radius:.625rem; font-weight:600; font-size:.85rem; height:42px;">
                        <i class="bi bi-funnel"></i> Filtrer
                    </button>
                    @if(request()->hasAny(['search', 'statut', 'pavillon']))
                        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary" style="border-radius:.625rem; font-size:.85rem; height:42px; display:flex; align-items:center;">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Students Table --}}
    <div class="card animate-in delay-2">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Étudiant</th>
                            <th>CIN</th>
                            <th>Contact</th>
                            <th>Chambre</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width:36px; height:36px; border-radius:.5rem; background:{{ $student->sexe === 'F' ? 'linear-gradient(135deg,#fce7f3,#fbcfe8)' : 'linear-gradient(135deg,#dbeafe,#bfdbfe)' }}; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:.75rem; color:{{ $student->sexe === 'F' ? '#be185d' : '#1d4ed8' }}; flex-shrink:0;">
                                            {{ strtoupper(substr($student->prenom, 0, 1)) }}{{ strtoupper(substr($student->nom, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold" style="font-size:.875rem;">{{ $student->prenom }} {{ $student->nom }}</div>
                                            <div class="text-muted" style="font-size:.72rem;">{{ $student->sexe === 'M' ? 'Masculin' : 'Féminin' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><code style="background:#f1f5f9; color:#475569; padding:.2rem .5rem; border-radius:.375rem; font-size:.78rem;">{{ $student->cin }}</code></td>
                                <td>
                                    <div style="font-size:.82rem;">{{ $student->email }}</div>
                                    <div class="text-muted" style="font-size:.72rem;">{{ $student->telephone }}</div>
                                </td>
                                <td>
                                    @if($student->chambre_numero)
                                        <span class="status-badge" style="background:#f1f5f9; color:#475569;">
                                            <i class="bi bi-door-open" style="font-size:.7rem;"></i>
                                            {{ $student->chambre_numero }}
                                        </span>
                                        <div class="text-muted" style="font-size:.68rem;">{{ ucfirst($student->pavillon_nom ?? '') }}</div>
                                    @else
                                        <span class="text-muted" style="font-size:.78rem;">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->statut === 'actif')
                                        <span class="status-badge" style="background:#d1fae5; color:#065f46;">
                                            <span class="status-dot" style="background:#10b981;"></span> Actif
                                        </span>
                                    @elseif($student->statut === 'suspendu')
                                        <span class="status-badge" style="background:#fef3c7; color:#92400e;">
                                            <span class="status-dot" style="background:#f59e0b;"></span> Suspendu
                                        </span>
                                    @elseif($student->statut === 'sorti')
                                        <span class="status-badge" style="background:#e2e8f0; color:#475569;">
                                            <span class="status-dot" style="background:#94a3b8;"></span> Sorti
                                        </span>
                                    @else
                                        <span class="status-badge" style="background:#f1f5f9; color:#64748b;">
                                            <span class="status-dot" style="background:#cbd5e1;"></span> Archivé
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-light" style="border-radius:.5rem; width:32px; height:32px; display:flex; align-items:center; justify-content:center;" title="Voir">
                                            <i class="bi bi-eye" style="font-size:.85rem;"></i>
                                        </a>
                                        <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-light" style="border-radius:.5rem; width:32px; height:32px; display:flex; align-items:center; justify-content:center;" title="Modifier">
                                            <i class="bi bi-pencil" style="font-size:.8rem;"></i>
                                        </a>
                                        <form method="POST" action="{{ route('students.destroy', $student->id) }}" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?');">
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
                                    <div class="text-muted">
                                        <i class="bi bi-search" style="font-size:2rem; display:block; margin-bottom:.5rem; opacity:.3;"></i>
                                        <div style="font-size:.9rem; font-weight:500;">Aucun étudiant trouvé</div>
                                        <div style="font-size:.78rem;">Essayez de modifier vos critères de recherche</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
