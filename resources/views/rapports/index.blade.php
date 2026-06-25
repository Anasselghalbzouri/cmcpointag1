@extends('layouts.app')

@section('title', 'Rapports - CMC Pointage')
@section('page-title', 'Rapports')
@section('breadcrumb', 'CMC Pointage › Rapports & exports')

@section('content')
<div>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 animate-in">
        <div>
            <h2 class="section-title mb-0">Rapports & statistiques</h2>
            <p class="section-subtitle mb-0">Vue d'ensemble de l'établissement</p>
        </div>
    </div>

    {{-- KPI --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-2-4" style="flex:0 0 20%; max-width:20%;">
            <div class="kpi-card animate-in delay-1">
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div class="kpi-icon" style="background:linear-gradient(135deg,#d1fae5,#ecfdf5); color:#059669;"><i class="bi bi-people-fill"></i></div>
                    <div><div class="kpi-label">Actifs</div><div class="kpi-value">{{ $kpis['etudiants_actifs'] }}</div></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-2-4" style="flex:0 0 20%; max-width:20%;">
            <div class="kpi-card animate-in delay-2">
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div class="kpi-icon" style="background:linear-gradient(135deg,#f1f5f9,#f8fafc); color:#64748b;"><i class="bi bi-archive-fill"></i></div>
                    <div><div class="kpi-label">Archivés</div><div class="kpi-value">{{ $kpis['etudiants_archives'] }}</div></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-2-4" style="flex:0 0 20%; max-width:20%;">
            <div class="kpi-card animate-in delay-3">
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div class="kpi-icon" style="background:linear-gradient(135deg,#fee2e2,#fef2f2); color:#dc2626;"><i class="bi bi-shield-exclamation"></i></div>
                    <div><div class="kpi-label">Sanctions actives</div><div class="kpi-value">{{ $kpis['sanctions_actives'] }}</div></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-2-4" style="flex:0 0 20%; max-width:20%;">
            <div class="kpi-card animate-in delay-4">
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div class="kpi-icon" style="background:linear-gradient(135deg,#fef3c7,#fffbeb); color:#d97706;"><i class="bi bi-hourglass-split"></i></div>
                    <div><div class="kpi-label">Demandes en attente</div><div class="kpi-value">{{ $kpis['demandes_en_attente'] }}</div></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-2-4" style="flex:0 0 20%; max-width:20%;">
            <div class="kpi-card animate-in delay-4">
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div class="kpi-icon" style="background:linear-gradient(135deg,#dbeafe,#eff6ff); color:#2563eb;"><i class="bi bi-person-walking"></i></div>
                    <div><div class="kpi-label">Visites en cours</div><div class="kpi-value">{{ $kpis['visites_en_cours'] }}</div></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        {{-- Occupation par pavillon --}}
        <div class="col-12 col-lg-6 animate-in delay-1">
            <div class="card h-100">
                <div class="card-header border-0 bg-white px-4 pt-3 pb-0">
                    <div class="section-title">Occupation par pavillon</div>
                    @php $occGlobalPct = $occupationGlobale->capacite > 0 ? round($occupationGlobale->occupants / $occupationGlobale->capacite * 100) : 0; @endphp
                    <div class="section-subtitle">Global : {{ $occupationGlobale->occupants }} / {{ $occupationGlobale->capacite }} ({{ $occGlobalPct }}%)</div>
                </div>
                <div class="card-body">
                    @forelse($occupationParPavillon as $p)
                        @php $pct = $p->capacite > 0 ? round($p->occupants / $p->capacite * 100) : 0; @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1" style="font-size:.82rem;">
                                <span class="fw-medium">{{ ucfirst($p->type) }} ({{ $p->nb_chambres }} chambres)</span>
                                <span class="text-muted">{{ $p->occupants }} / {{ $p->capacite }} ({{ $pct }}%)</span>
                            </div>
                            <div class="progress" style="height:8px; border-radius:999px;">
                                <div class="progress-bar {{ $pct >= 90 ? 'bg-danger' : ($pct >= 70 ? 'bg-warning' : 'bg-success') }}" style="width:{{ $pct }}%; border-radius:999px;"></div>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted text-center py-4">Aucune donnée</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Étudiants par année --}}
        <div class="col-12 col-lg-3 animate-in delay-2">
            <div class="card h-100">
                <div class="card-header border-0 bg-white px-4 pt-3 pb-0">
                    <div class="section-title">Par année d'étude</div>
                </div>
                <div class="card-body">
                    @forelse($etudiantsParAnnee as $a)
                        <div class="d-flex justify-content-between align-items-center mb-2 py-1">
                            <span style="font-size:.85rem;">{{ $a->annee_etude }}{{ $a->annee_etude === '1' ? 'ère' : 'ème' }} année</span>
                            <span class="status-badge" style="background:#f1f5f9; color:#475569;">{{ $a->total }}</span>
                        </div>
                    @empty
                        <div class="text-muted text-center py-4">Aucune donnée</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Étudiants par statut --}}
        <div class="col-12 col-lg-3 animate-in delay-3">
            <div class="card h-100">
                <div class="card-header border-0 bg-white px-4 pt-3 pb-0">
                    <div class="section-title">Par statut</div>
                </div>
                <div class="card-body">
                    @forelse($etudiantsParStatut as $s)
                        <div class="d-flex justify-content-between align-items-center mb-2 py-1">
                            <span style="font-size:.85rem;">{{ ucfirst($s->statut) }}</span>
                            <span class="status-badge" style="background:#f1f5f9; color:#475569;">{{ $s->total }}</span>
                        </div>
                    @empty
                        <div class="text-muted text-center py-4">Aucune donnée</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        {{-- Sanctions par type --}}
        <div class="col-12 col-lg-4 animate-in delay-1">
            <div class="card h-100">
                <div class="card-header border-0 bg-white px-4 pt-3 pb-0">
                    <div class="section-title">Sanctions par type</div>
                </div>
                <div class="card-body">
                    @forelse($sanctionsParType as $s)
                        <div class="d-flex justify-content-between align-items-center mb-2 py-1">
                            <span style="font-size:.85rem;">{{ ucfirst($s->type) }}</span>
                            <span class="status-badge" style="background:#fee2e2; color:#991b1b;">{{ $s->total }}</span>
                        </div>
                    @empty
                        <div class="text-muted text-center py-4">Aucune donnée</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Demandes par statut --}}
        <div class="col-12 col-lg-4 animate-in delay-2">
            <div class="card h-100">
                <div class="card-header border-0 bg-white px-4 pt-3 pb-0">
                    <div class="section-title">Demandes par statut</div>
                </div>
                <div class="card-body">
                    @forelse($demandesParStatut as $d)
                        <div class="d-flex justify-content-between align-items-center mb-2 py-1">
                            <span style="font-size:.85rem;">{{ str_replace('_', ' ', ucfirst($d->statut)) }}</span>
                            <span class="status-badge" style="background:#eff6ff; color:#1d4ed8;">{{ $d->total }}</span>
                        </div>
                    @empty
                        <div class="text-muted text-center py-4">Aucune donnée</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Mouvements 30 derniers jours --}}
        <div class="col-12 col-lg-4 animate-in delay-3">
            <div class="card h-100">
                <div class="card-header border-0 bg-white px-4 pt-3 pb-0">
                    <div class="section-title">Mouvements (30 derniers jours)</div>
                </div>
                <div class="card-body p-0" style="max-height:260px; overflow-y:auto;">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr><th>Jour</th><th>Entrées</th><th>Sorties</th></tr>
                        </thead>
                        <tbody>
                            @forelse($mouvementsParJour as $m)
                                <tr>
                                    <td style="font-size:.78rem;">{{ \Carbon\Carbon::parse($m->jour)->format('d/m/Y') }}</td>
                                    <td style="font-size:.78rem;">{{ $m->entrees }}</td>
                                    <td style="font-size:.78rem;">{{ $m->sorties }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-4">Aucun mouvement</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Exports --}}
    <div class="card animate-in" style="animation-delay:.2s;">
        <div class="card-header border-0 bg-white px-4 pt-3 pb-0">
            <div class="section-title">Exporter en CSV</div>
            <div class="section-subtitle">Fichiers compatibles Excel</div>
        </div>
        <div class="card-body d-flex flex-wrap gap-2">
            <a href="{{ route('rapports.export.etudiants') }}" class="btn btn-success d-flex align-items-center gap-2" style="border-radius:.625rem; font-weight:600; font-size:.85rem;">
                <i class="bi bi-file-earmark-spreadsheet"></i> Étudiants
            </a>
            <a href="{{ route('rapports.export.sanctions') }}" class="btn btn-success d-flex align-items-center gap-2" style="border-radius:.625rem; font-weight:600; font-size:.85rem;">
                <i class="bi bi-file-earmark-spreadsheet"></i> Sanctions
            </a>
            <a href="{{ route('rapports.export.demandes') }}" class="btn btn-success d-flex align-items-center gap-2" style="border-radius:.625rem; font-weight:600; font-size:.85rem;">
                <i class="bi bi-file-earmark-spreadsheet"></i> Demandes
            </a>
            <a href="{{ route('rapports.export.visites') }}" class="btn btn-success d-flex align-items-center gap-2" style="border-radius:.625rem; font-weight:600; font-size:.85rem;">
                <i class="bi bi-file-earmark-spreadsheet"></i> Visites
            </a>
            <a href="{{ route('rapports.export.occupation') }}" class="btn btn-success d-flex align-items-center gap-2" style="border-radius:.625rem; font-weight:600; font-size:.85rem;">
                <i class="bi bi-file-earmark-spreadsheet"></i> Occupation des chambres
            </a>
        </div>
    </div>
</div>
@endsection
