@extends('layouts.app')

@section('title', $student->prenom . ' ' . $student->nom . ' - CMC Pointage')
@section('page-title', $student->prenom . ' ' . $student->nom)
@section('breadcrumb', 'CMC Pointage › Étudiants › Profil')

@section('content')
<div>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3 animate-in">
        <a href="{{ route('students.index') }}" class="btn btn-light d-inline-flex align-items-center gap-2" style="border-radius:.625rem; font-size:.85rem; font-weight:500;">
            <i class="bi bi-arrow-left"></i> Liste des étudiants
        </a>
        <div class="d-flex gap-2">
            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius:.625rem; font-weight:600; font-size:.85rem;">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <form method="POST" action="{{ route('students.destroy', $student->id) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger d-flex align-items-center gap-2" style="border-radius:.625rem; font-weight:600; font-size:.85rem;">
                    <i class="bi bi-trash3"></i> Supprimer
                </button>
            </form>
        </div>
    </div>

    <div class="row g-3">
        {{-- Profile Card --}}
        <div class="col-12 col-lg-4 animate-in delay-1">
            <div class="card">
                <div class="card-body text-center p-4">
                    <div class="mx-auto mb-3" style="width:72px; height:72px; border-radius:1rem; background:{{ $student->sexe === 'F' ? 'linear-gradient(135deg,#fce7f3,#f9a8d4)' : 'linear-gradient(135deg,#dbeafe,#93c5fd)' }}; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:1.5rem; color:{{ $student->sexe === 'F' ? '#be185d' : '#1d4ed8' }};">
                        {{ strtoupper(substr($student->prenom, 0, 1)) }}{{ strtoupper(substr($student->nom, 0, 1)) }}
                    </div>
                    <h2 style="font-size:1.1rem; font-weight:700; margin-bottom:.25rem;">{{ $student->prenom }} {{ $student->nom }}</h2>
                    <p class="text-muted mb-3" style="font-size:.82rem;">{{ $student->email }}</p>
                    
                    @if($student->statut === 'actif')
                        <span class="status-badge" style="background:#d1fae5; color:#065f46;">
                            <span class="status-dot" style="background:#10b981;"></span> Actif
                        </span>
                    @elseif($student->statut === 'suspendu')
                        <span class="status-badge" style="background:#fef3c7; color:#92400e;">
                            <span class="status-dot" style="background:#f59e0b;"></span> Suspendu
                        </span>
                    @else
                        <span class="status-badge" style="background:#e2e8f0; color:#475569;">
                            <span class="status-dot" style="background:#94a3b8;"></span> {{ ucfirst($student->statut) }}
                        </span>
                    @endif
                </div>
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between py-2 px-4" style="font-size:.85rem;">
                        <span class="text-muted">CIN</span>
                        <strong>{{ $student->cin }}</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between py-2 px-4" style="font-size:.85rem;">
                        <span class="text-muted">Téléphone</span>
                        <strong>{{ $student->telephone }}</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between py-2 px-4" style="font-size:.85rem;">
                        <span class="text-muted">Sexe</span>
                        <strong>{{ $student->sexe === 'M' ? 'Masculin' : 'Féminin' }}</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between py-2 px-4" style="font-size:.85rem;">
                        <span class="text-muted">Nationalité</span>
                        <strong>{{ $student->nationalite }}</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between py-2 px-4" style="font-size:.85rem;">
                        <span class="text-muted">Naissance</span>
                        <strong>{{ \Carbon\Carbon::parse($student->date_naissance)->format('d/m/Y') }}</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between py-2 px-4" style="font-size:.85rem;">
                        <span class="text-muted">Chambre</span>
                        <strong>{{ $student->chambre_numero ?? '—' }}</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between py-2 px-4" style="font-size:.85rem;">
                        <span class="text-muted">Pavillon</span>
                        <strong>{{ $student->pavillon_nom ? ucfirst($student->pavillon_nom) : '—' }}</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between py-2 px-4" style="font-size:.85rem;">
                        <span class="text-muted">Date entrée</span>
                        <strong>{{ \Carbon\Carbon::parse($student->date_entree)->format('d/m/Y') }}</strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- Details --}}
        <div class="col-12 col-lg-8">
            {{-- Movements --}}
            <div class="card mb-3 animate-in delay-2">
                <div class="card-header border-0 bg-white px-4 pt-3 pb-0">
                    <div class="section-title"><i class="bi bi-arrow-left-right me-2" style="font-size:.9rem;"></i>Historique des mouvements</div>
                    <div class="section-subtitle">{{ count($movements) }} dernier(s) mouvement(s)</div>
                </div>
                <div class="card-body p-0 pt-2">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Date / Heure</th>
                                    <th>Pavillon</th>
                                    <th>Type</th>
                                    <th>Motif</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($movements as $m)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold">{{ \Carbon\Carbon::parse($m->date_heure)->format('d/m/Y') }}</span>
                                            <span class="text-muted ms-1">{{ \Carbon\Carbon::parse($m->date_heure)->format('H:i') }}</span>
                                        </td>
                                        <td><span class="status-badge" style="background:#f1f5f9; color:#475569;">{{ ucfirst($m->pavillon_nom) }}</span></td>
                                        <td>
                                            @if($m->type === 'entree')
                                                <span class="status-badge" style="background:#d1fae5; color:#065f46;">
                                                    <span class="status-dot" style="background:#10b981;"></span> Entrée
                                                </span>
                                            @else
                                                <span class="status-badge" style="background:#e2e8f0; color:#475569;">
                                                    <span class="status-dot" style="background:#94a3b8;"></span> Sortie
                                                </span>
                                            @endif
                                        </td>
                                        <td style="font-size:.82rem;">{{ $m->motif ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-4">Aucun mouvement</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Sanctions --}}
            @if(count($sanctions) > 0)
            <div class="card mb-3 animate-in delay-3">
                <div class="card-header border-0 bg-white px-4 pt-3 pb-0">
                    <div class="section-title"><i class="bi bi-shield-exclamation me-2 text-danger" style="font-size:.9rem;"></i>Sanctions ({{ count($sanctions) }})</div>
                </div>
                <div class="card-body p-0 pt-2">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Motif</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sanctions as $s)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($s->date_sanction)->format('d/m/Y') }}</td>
                                        <td><span class="status-badge" style="background:#f1f5f9; color:#475569;">{{ ucfirst($s->type) }}</span></td>
                                        <td style="font-size:.82rem;">{{ Str::limit($s->motif, 40) }}</td>
                                        <td>
                                            @if($s->statut === 'active')
                                                <span class="status-badge" style="background:#fee2e2; color:#991b1b;">
                                                    <span class="status-dot" style="background:#ef4444;"></span> Active
                                                </span>
                                            @elseif($s->statut === 'levee')
                                                <span class="status-badge" style="background:#d1fae5; color:#065f46;">
                                                    <span class="status-dot" style="background:#10b981;"></span> Levée
                                                </span>
                                            @else
                                                <span class="status-badge" style="background:#fef3c7; color:#92400e;">
                                                    <span class="status-dot" style="background:#f59e0b;"></span> {{ ucfirst($s->statut) }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            {{-- Demandes --}}
            @if(count($demandes) > 0)
            <div class="card animate-in delay-4">
                <div class="card-header border-0 bg-white px-4 pt-3 pb-0">
                    <div class="section-title"><i class="bi bi-file-earmark-text me-2 text-primary" style="font-size:.9rem;"></i>Demandes ({{ count($demandes) }})</div>
                </div>
                <div class="card-body p-0 pt-2">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($demandes as $d)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($d->created_at)->format('d/m/Y') }}</td>
                                        <td><span class="status-badge" style="background:#eff6ff; color:#1d4ed8;">{{ str_replace('_', ' ', $d->type) }}</span></td>
                                        <td style="font-size:.82rem;">{{ Str::limit($d->description, 50) }}</td>
                                        <td>
                                            @if($d->statut === 'en_attente')
                                                <span class="status-badge" style="background:#fef3c7; color:#92400e;">
                                                    <span class="status-dot" style="background:#f59e0b;"></span> En attente
                                                </span>
                                            @elseif($d->statut === 'approuvee')
                                                <span class="status-badge" style="background:#d1fae5; color:#065f46;">
                                                    <span class="status-dot" style="background:#10b981;"></span> Approuvée
                                                </span>
                                            @elseif($d->statut === 'rejetee')
                                                <span class="status-badge" style="background:#fee2e2; color:#991b1b;">
                                                    <span class="status-dot" style="background:#ef4444;"></span> Rejetée
                                                </span>
                                            @else
                                                <span class="status-badge" style="background:#e2e8f0; color:#475569;">Annulée</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
