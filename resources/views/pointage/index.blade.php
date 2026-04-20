@extends('layouts.app')

@section('title', 'Pointage - CMC Pointage')

@section('content')
<div>
    <h1 class="h3 mb-4">Pointage - Scanner</h1>

    <div class="row g-3">
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h2 class="h5 mb-2">Scan rapide (Auto-détection)</h2>
                    <p class="text-muted mb-4">
                Entrez le CNE de l'étudiant. Le système détectera automatiquement s'il s'agit d'une entrée ou d'une sortie.
                    </p>
            
                    <form method="POST" action="{{ route('pointage.scan') }}">
                        @csrf
                        <div class="p-4 border rounded-3 bg-light">
                            <div class="text-center display-5 mb-3">📱</div>
                            <label class="form-label" for="scan_cne">CNE étudiant</label>
                        <input 
                            type="text" 
                                class="form-control form-control-lg text-center"
                                id="scan_cne"
                            name="cne" 
                            placeholder="Entrez le CNE..." 
                            required 
                            autofocus
                            autocomplete="off"
                                style="letter-spacing: 2px;"
                        >
                            <button type="submit" class="btn btn-primary w-100 mt-3">Enregistrer le mouvement</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h2 class="h5 mb-2">Saisie manuelle</h2>
                    <p class="text-muted mb-4">
                Pour les corrections ou entrées forcées.
                    </p>
            
                    <form method="POST" action="{{ route('pointage.manual') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="manual_cne" class="form-label">CNE de l'étudiant</label>
                            <input type="text" class="form-control" id="manual_cne" name="cne" required placeholder="CNE...">
                        </div>
                
                        <div class="mb-4">
                            <label for="manual_type" class="form-label">Type de mouvement</label>
                            <select class="form-select" id="manual_type" name="type" required>
                                <option value="">-- Sélectionner --</option>
                                <option value="entree">Entrée</option>
                                <option value="sortie">Sortie</option>
                            </select>
                        </div>
                
                        <button type="submit" class="btn btn-success w-100">Enregistrer manuellement</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-3">
        <div class="card-header bg-white">
            <h2 class="h5 mb-0">Derniers mouvements</h2>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">ID</th>
                            <th>Heure</th>
                            <th>CNE</th>
                            <th>Nom</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_movements as $movement)
                            <tr>
                                <td class="ps-3">#{{ $movement->id }}</td>
                                <td>{{ $movement->scanned_at->format('d/m/Y H:i:s') }}</td>
                                <td><code>{{ $movement->user->cne }}</code></td>
                                <td>{{ $movement->user->prenom }} {{ $movement->user->nom }}</td>
                                <td>
                                    @if($movement->type === 'entree')
                                        <span class="badge text-bg-success">Entrée</span>
                                    @else
                                        <span class="badge text-bg-secondary">Sortie</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Aucun mouvement enregistré</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
