@extends('layouts.app')

@section('title', 'Sanctions - CMC Pointage')
@section('page-title', 'Sanctions')
@section('breadcrumb', 'CMC Pointage › Gestion des sanctions')

@section('content')
<div>
    {{-- KPI --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="kpi-card animate-in delay-1">
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div class="kpi-icon" style="background:linear-gradient(135deg,#dbeafe,#eff6ff); color:#2563eb;"><i class="bi bi-shield-exclamation"></i></div>
                    <div><div class="kpi-label">Total</div><div class="kpi-value">{{ $stats['total'] }}</div></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="kpi-card animate-in delay-2">
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div class="kpi-icon" style="background:linear-gradient(135deg,#fee2e2,#fef2f2); color:#dc2626;"><i class="bi bi-exclamation-triangle-fill"></i></div>
                    <div><div class="kpi-label">Actives</div><div class="kpi-value">{{ $stats['active'] }}</div></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="kpi-card animate-in delay-3">
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div class="kpi-icon" style="background:linear-gradient(135deg,#d1fae5,#ecfdf5); color:#059669;"><i class="bi bi-check-circle-fill"></i></div>
                    <div><div class="kpi-label">Levées</div><div class="kpi-value">{{ $stats['levee'] }}</div></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="kpi-card animate-in delay-4">
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div class="kpi-icon" style="background:linear-gradient(135deg,#fef3c7,#fffbeb); color:#d97706;"><i class="bi bi-pause-circle-fill"></i></div>
                    <div><div class="kpi-label">Suspendues</div><div class="kpi-value">{{ $stats['suspendue'] }}</div></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-4 animate-in" style="animation-delay:.15s;">
        <div class="card-body py-3 px-4">
            <form method="GET" action="{{ route('sanctions.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Rechercher par nom, CIN, motif...">
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <select class="form-select" name="statut" style="border-radius:.75rem; height:42px; font-size:.85rem;">
                        <option value="">Tous statuts</option>
                        <option value="active" {{ request('statut') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="levee" {{ request('statut') === 'levee' ? 'selected' : '' }}>Levée</option>
                        <option value="suspendue" {{ request('statut') === 'suspendue' ? 'selected' : '' }}>Suspendue</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select class="form-select" name="type" style="border-radius:.75rem; height:42px; font-size:.85rem;">
                        <option value="">Tous types</option>
                        <option value="avertissement" {{ request('type') === 'avertissement' ? 'selected' : '' }}>Avertissement</option>
                        <option value="suspension" {{ request('type') === 'suspension' ? 'selected' : '' }}>Suspension</option>
                        <option value="exclusion" {{ request('type') === 'exclusion' ? 'selected' : '' }}>Exclusion</option>
                        <option value="amende" {{ request('type') === 'amende' ? 'selected' : '' }}>Amende</option>
                    </select>
                </div>
                <div class="col-12 col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-dark flex-grow-1" style="border-radius:.625rem; font-weight:600; font-size:.85rem; height:42px;"><i class="bi bi-funnel"></i> Filtrer</button>
                    @if(request()->hasAny(['search', 'statut', 'type']))
                        <a href="{{ route('sanctions.index') }}" class="btn btn-outline-secondary" style="border-radius:.625rem; font-size:.85rem; height:42px; display:flex; align-items:center;"><i class="bi bi-x-lg"></i></a>
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
                            <th>Motif</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sanctions as $s)
                            <tr>
                                <td>
                                    <span class="fw-semibold">{{ \Carbon\Carbon::parse($s->date_sanction)->format('d/m/Y') }}</span>
                                    @if($s->date_fin)
                                        <div class="text-muted" style="font-size:.68rem;">→ {{ \Carbon\Carbon::parse($s->date_fin)->format('d/m/Y') }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-medium" style="font-size:.875rem;">{{ $s->etudiant_prenom }} {{ $s->etudiant_nom }}</div>
                                    <div class="text-muted" style="font-size:.72rem;">{{ $s->cin }}</div>
                                </td>
                                <td>
                                    @php
                                        $typeColors = [
                                            'avertissement' => ['bg' => '#fef3c7', 'color' => '#92400e'],
                                            'suspension' => ['bg' => '#ffedd5', 'color' => '#9a3412'],
                                            'exclusion' => ['bg' => '#fee2e2', 'color' => '#991b1b'],
                                            'amende' => ['bg' => '#ede9fe', 'color' => '#5b21b6'],
                                        ];
                                        $tc = $typeColors[$s->type] ?? ['bg' => '#f1f5f9', 'color' => '#475569'];
                                    @endphp
                                    <span class="status-badge" style="background:{{ $tc['bg'] }}; color:{{ $tc['color'] }};">{{ ucfirst($s->type) }}</span>
                                </td>
                                <td style="font-size:.82rem; max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ Str::limit($s->motif, 40) }}</td>
                                <td>
                                    @if($s->montant_amende)
                                        <span class="fw-bold" style="color:#7c3aed;">{{ number_format($s->montant_amende, 0) }} DH</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($s->statut === 'active')
                                        <span class="status-badge" style="background:#fee2e2; color:#991b1b;"><span class="status-dot" style="background:#ef4444;"></span> Active</span>
                                    @elseif($s->statut === 'levee')
                                        <span class="status-badge" style="background:#d1fae5; color:#065f46;"><span class="status-dot" style="background:#10b981;"></span> Levée</span>
                                    @else
                                        <span class="status-badge" style="background:#fef3c7; color:#92400e;"><span class="status-dot" style="background:#f59e0b;"></span> Suspendue</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('sanctions.show', $s->id) }}" class="btn btn-sm btn-light" style="border-radius:.5rem; width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center;" title="Voir">
                                        <i class="bi bi-eye" style="font-size:.85rem;"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-shield-check" style="font-size:2rem; display:block; margin-bottom:.5rem; opacity:.3;"></i>
                                        <div style="font-size:.9rem; font-weight:500;">Aucune sanction trouvée</div>
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
