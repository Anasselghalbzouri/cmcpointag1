@extends('layouts.app')

@section('title', 'Dashboard - CMC Pointage')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'CMC Pointage › Vue d\'ensemble')

@section('content')
<div>
    {{-- KPI Row --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card animate-in delay-1">
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div class="kpi-icon" style="background:linear-gradient(135deg,#dbeafe,#eff6ff); color:#2563eb;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Total étudiants</div>
                        <div class="kpi-value">{{ $stats['total_students'] }}</div>
                        <div class="text-muted" style="font-size:.72rem;">{{ $stats['active_students'] }} actifs</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card animate-in delay-2">
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div class="kpi-icon" style="background:linear-gradient(135deg,#fef3c7,#fffbeb); color:#d97706;">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Demandes en attente</div>
                        <div class="kpi-value">{{ $stats['pending_demandes'] }}</div>
                        <div class="text-muted" style="font-size:.72rem;">à traiter</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card animate-in delay-3">
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div class="kpi-icon" style="background:linear-gradient(135deg,#fee2e2,#fef2f2); color:#dc2626;">
                        <i class="bi bi-shield-exclamation"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Sanctions actives</div>
                        <div class="kpi-value">{{ $stats['active_sanctions'] }}</div>
                        <div class="text-muted" style="font-size:.72rem;">en cours</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card animate-in delay-4">
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div class="kpi-icon" style="background:linear-gradient(135deg,#d1fae5,#ecfdf5); color:#059669;">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Visiteurs en cours</div>
                        <div class="kpi-value">{{ $stats['active_visites'] }}</div>
                        <div class="text-muted" style="font-size:.72rem;">sur place</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card animate-in delay-4">
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div class="kpi-icon" style="background:linear-gradient(135deg,#ede9fe,#f5f3ff); color:#7c3aed;">
                        <i class="bi bi-calendar-event-fill"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Fins de formation imminentes</div>
                        <div class="kpi-value">{{ $stats['upcoming_departures'] }}</div>
                        <div class="text-muted" style="font-size:.72rem;">dans les 30 jours</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Recent Movements --}}
        <div class="col-12 col-xl-8 animate-in" style="animation-delay:.2s;">
            <div class="card h-100">
                <div class="card-header border-0 bg-white d-flex justify-content-between align-items-center px-4 pt-3 pb-0">
                    <div>
                        <div class="section-title">Mouvements récents</div>
                        <div class="section-subtitle">{{ $stats['total_movements_today'] }} mouvements aujourd'hui</div>
                    </div>
                    <a href="{{ route('mouvements.index') }}" class="btn btn-sm btn-light" style="border-radius:.5rem; font-size:.78rem; font-weight:600;">
                        <i class="bi bi-arrow-right"></i> Voir tout
                    </a>
                </div>
                <div class="card-body p-0 pt-2">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Date / Heure</th>
                                    <th>Étudiant</th>
                                    <th>Pavillon</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_movements as $movement)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold">{{ \Carbon\Carbon::parse($movement->date_heure)->format('d/m') }}</span>
                                            <span class="text-muted ms-1">{{ \Carbon\Carbon::parse($movement->date_heure)->format('H:i') }}</span>
                                        </td>
                                        <td class="fw-medium">{{ $movement->etudiant_prenom }} {{ $movement->etudiant_nom }}</td>
                                        <td>
                                            <span class="status-badge" style="background:#f1f5f9; color:#475569;">
                                                {{ ucfirst($movement->pavillon_nom) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($movement->type === 'entree')
                                                <span class="status-badge" style="background:#d1fae5; color:#065f46;">
                                                    <span class="status-dot" style="background:#10b981;"></span> Entrée
                                                </span>
                                            @else
                                                <span class="status-badge" style="background:#e2e8f0; color:#475569;">
                                                    <span class="status-dot" style="background:#94a3b8;"></span> Sortie
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Aucun mouvement</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Panel --}}
        <div class="col-12 col-xl-4 animate-in" style="animation-delay:.3s;">
            {{-- Alerts --}}
            <div class="card mb-3">
                <div class="card-body px-4 py-3">
                    <div class="section-title mb-3"><i class="bi bi-bell-fill text-warning me-2" style="font-size:.9rem;"></i>Alertes</div>
                    <div class="vstack gap-2">
                        @foreach($alerts as $alert)
                            <div class="d-flex justify-content-between align-items-center py-2 px-3" style="border-radius:.625rem; background:{{ $alert['variant']==='warning' ? '#fffbeb' : ($alert['variant']==='danger' ? '#fef2f2' : ($alert['variant']==='info' ? '#eff6ff' : '#f0fdf4')) }};">
                                <span style="font-size:.82rem; font-weight:500; color:#334155;">{{ $alert['label'] }}</span>
                                <span class="fw-bold" style="font-size:.9rem; color:{{ $alert['variant']==='warning' ? '#d97706' : ($alert['variant']==='danger' ? '#dc2626' : ($alert['variant']==='info' ? '#2563eb' : '#16a34a')) }};">{{ $alert['value'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Occupancy --}}
            <div class="card">
                <div class="card-body px-4 py-3">
                    <div class="section-title mb-1"><i class="bi bi-building me-2" style="font-size:.9rem;"></i>Occupation</div>
                    <div class="section-subtitle mb-3">Taux global: {{ $stats['occupancy_rate'] }}%</div>
                    
                    <div class="progress mb-3" style="height:8px; border-radius:999px; background:#e2e8f0;">
                        <div class="progress-bar" role="progressbar" style="width:{{ $stats['occupancy_rate'] }}%; background:linear-gradient(90deg,#3b82f6,#6366f1); border-radius:999px;"></div>
                    </div>

                    @foreach($pavilions as $p)
                        <div class="d-flex justify-content-between align-items-center py-2 border-top" style="border-color:#f1f5f9 !important;">
                            <div>
                                <div class="fw-semibold" style="font-size:.85rem;">Pavillon {{ $p->nom }}</div>
                                <div class="text-muted" style="font-size:.72rem;">{{ $p->chambres_count }} chambres · {{ $p->occupied }}/{{ $p->capacity }} places</div>
                            </div>
                            <span class="fw-bold" style="font-size:.85rem; color:{{ $p->occupancy_rate > 80 ? '#dc2626' : ($p->occupancy_rate > 50 ? '#d97706' : '#16a34a') }};">
                                {{ $p->occupancy_rate }}%
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
