@extends('layouts.app')

@section('title', 'Pavillons - CMC Pointage')
@section('page-title', 'Pavillons')
@section('breadcrumb', 'CMC Pointage › Gestion des pavillons')

@section('content')
<div>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 animate-in">
        <div>
            <h2 class="section-title mb-0">Gestion des pavillons</h2>
            <p class="section-subtitle mb-0">{{ $pavillons->count() }} pavillon(s)</p>
        </div>
    </div>

    <div class="row g-3">
        @forelse($pavillons as $p)
            <div class="col-12 col-md-6">
                <div class="card animate-in">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <div class="kpi-icon" style="width:40px;height:40px;font-size:1rem; background:{{ $p->type === 'femme' ? 'linear-gradient(135deg,#fce7f3,#fbcfe8)' : 'linear-gradient(135deg,#dbeafe,#bfdbfe)' }}; color:{{ $p->type === 'femme' ? '#be185d' : '#1d4ed8' }};">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold" style="font-size:1rem;">Pavillon {{ ucfirst($p->type) }}</div>
                                        <div class="text-muted" style="font-size:.75rem;">{{ $p->nombre_etages }} étage(s)</div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-1">
                                <a href="{{ route('pavillons.edit', $p->id) }}" class="btn btn-sm btn-light" style="border-radius:.5rem; width:32px; height:32px; display:flex; align-items:center; justify-content:center;" title="Modifier">
                                    <i class="bi bi-pencil" style="font-size:.8rem;"></i>
                                </a>
                                <form method="POST" action="{{ route('pavillons.destroy', $p->id) }}" class="d-inline" onsubmit="return confirm('Supprimer ce pavillon ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light" style="border-radius:.5rem; width:32px; height:32px; display:flex; align-items:center; justify-content:center; color:#ef4444;" title="Supprimer">
                                        <i class="bi bi-trash3" style="font-size:.8rem;"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="row g-2 mt-3">
                            <div class="col-4">
                                <div class="text-muted" style="font-size:.7rem; text-transform:uppercase; font-weight:600;">Chambres</div>
                                <div class="fw-bold">{{ $p->chambres_count }}</div>
                            </div>
                            <div class="col-4">
                                <div class="text-muted" style="font-size:.7rem; text-transform:uppercase; font-weight:600;">Occupants</div>
                                <div class="fw-bold">{{ $p->occupants_total }}</div>
                            </div>
                            <div class="col-4">
                                <div class="text-muted" style="font-size:.7rem; text-transform:uppercase; font-weight:600;">Capacité</div>
                                <div class="fw-bold">{{ $p->capacite_total }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card"><div class="card-body text-center py-5 text-muted">Aucun pavillon trouvé</div></div>
            </div>
        @endforelse
    </div>
</div>
@endsection
