@extends('layouts.app')

@section('title', 'Mouvements - CMC Pointage')
@section('page-title', 'Mouvements')
@section('breadcrumb', 'CMC Pointage › Historique des mouvements')

@section('content')
<div>
    {{-- KPI Row --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="kpi-card animate-in delay-1">
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div class="kpi-icon" style="background:linear-gradient(135deg,#dbeafe,#eff6ff); color:#2563eb;">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Total mouvements</div>
                        <div class="kpi-value">{{ number_format($stats['total']) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="kpi-card animate-in delay-2">
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div class="kpi-icon" style="background:linear-gradient(135deg,#fef3c7,#fffbeb); color:#d97706;">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Aujourd'hui</div>
                        <div class="kpi-value">{{ $stats['today_total'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="kpi-card animate-in delay-3">
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div class="kpi-icon" style="background:linear-gradient(135deg,#d1fae5,#ecfdf5); color:#059669;">
                        <i class="bi bi-box-arrow-in-right"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Entrées aujourd'hui</div>
                        <div class="kpi-value">{{ $stats['today_entrees'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="kpi-card animate-in delay-4">
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div class="kpi-icon" style="background:linear-gradient(135deg,#fee2e2,#fef2f2); color:#dc2626;">
                        <i class="bi bi-box-arrow-right"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Sorties aujourd'hui</div>
                        <div class="kpi-value">{{ $stats['today_sorties'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Search & Filters + Export --}}
    <div class="card mb-4 animate-in" style="animation-delay:.15s;">
        <div class="card-body py-3 px-4">
            <form method="GET" action="{{ route('mouvements.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Rechercher par nom, CIN, n° chambre...">
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <select class="form-select" name="type" style="border-radius:.75rem; height:42px; font-size:.85rem;">
                        <option value="">Tous types</option>
                        <option value="entree" {{ request('type') === 'entree' ? 'selected' : '' }}>Entrée</option>
                        <option value="sortie" {{ request('type') === 'sortie' ? 'selected' : '' }}>Sortie</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <input type="date" class="form-control" name="date" value="{{ request('date') }}" style="border-radius:.75rem; height:42px; font-size:.85rem;">
                </div>
                <div class="col-12 col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-dark flex-grow-1" style="border-radius:.625rem; font-weight:600; font-size:.85rem; height:42px;">
                        <i class="bi bi-funnel"></i> Filtrer
                    </button>
                    @if(request()->hasAny(['search', 'type', 'date']))
                        <a href="{{ route('mouvements.index') }}" class="btn btn-outline-secondary" style="border-radius:.625rem; font-size:.85rem; height:42px; display:flex; align-items:center;">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                    <a href="{{ route('mouvements.export', request()->all()) }}" class="btn btn-success d-flex align-items-center gap-1" style="border-radius:.625rem; font-weight:600; font-size:.85rem; height:42px; white-space:nowrap;">
                        <i class="bi bi-file-earmark-spreadsheet"></i> Exporter Excel
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Movements Table --}}
    <div class="card animate-in" style="animation-delay:.2s;">
        <div class="card-header border-0 bg-white d-flex justify-content-between align-items-center px-4 pt-3 pb-0">
            <div>
                <div class="section-title">Historique des mouvements</div>
                <div class="section-subtitle">{{ count($mouvements) }} résultat(s)</div>
            </div>
        </div>
        <div class="card-body p-0 pt-2">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Date / Heure</th>
                            <th>Étudiant</th>
                            <th>CIN</th>
                            <th>Chambre</th>
                            <th>Pavillon</th>
                            <th>Type</th>
                            <th>Motif</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mouvements as $m)
                            <tr>
                                <td>
                                    <span class="fw-semibold">{{ \Carbon\Carbon::parse($m->date_heure)->format('d/m/Y') }}</span>
                                    <span class="text-muted ms-1">{{ \Carbon\Carbon::parse($m->date_heure)->format('H:i') }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width:30px; height:30px; border-radius:.5rem; background:linear-gradient(135deg,#dbeafe,#bfdbfe); display:flex; align-items:center; justify-content:center; font-weight:700; font-size:.65rem; color:#1d4ed8; flex-shrink:0;">
                                            {{ strtoupper(substr($m->etudiant_prenom, 0, 1)) }}{{ strtoupper(substr($m->etudiant_nom, 0, 1)) }}
                                        </div>
                                        <span class="fw-medium">{{ $m->etudiant_prenom }} {{ $m->etudiant_nom }}</span>
                                    </div>
                                </td>
                                <td><code style="background:#f1f5f9; color:#475569; padding:.15rem .4rem; border-radius:.375rem; font-size:.75rem;">{{ $m->cin }}</code></td>
                                <td>
                                    @if($m->chambre_numero)
                                        <span style="font-size:.82rem;">{{ $m->chambre_numero }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="status-badge" style="background:#f1f5f9; color:#475569;">{{ ucfirst($m->pavillon_nom) }}</span>
                                </td>
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
                                <td style="font-size:.82rem; max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $m->motif ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-arrow-left-right" style="font-size:2rem; display:block; margin-bottom:.5rem; opacity:.3;"></i>
                                        <div style="font-size:.9rem; font-weight:500;">Aucun mouvement trouvé</div>
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
