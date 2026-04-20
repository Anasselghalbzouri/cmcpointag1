@extends('layouts.app')

@section('title', 'Détails Étudiant - CMC Pointage')

@section('content')
<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('students.index') }}" class="text-decoration-none text-muted">← Retour à la liste</a>
            <h1 class="h3 mt-2 mb-1">{{ $student->prenom }} {{ $student->nom }}</h1>
            <p class="text-muted mb-0">CNE: <code>{{ $student->cne }}</code></p>
        </div>
        <span class="badge text-bg-primary">Étudiant</span>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h2 class="h5 mb-3">Informations</h2>
                    <div class="mb-3">
                        <span class="text-muted d-block small">Statut actuel</span>
                    @if($student->currentStatus() === 'a_linterieur')
                            <span class="badge text-bg-success mt-1">À l'intérieur</span>
                    @elseif($student->currentStatus() === 'a_lexterieur')
                            <span class="badge text-bg-secondary mt-1">À l'extérieur</span>
                    @else
                            <span class="badge text-bg-danger mt-1">Jamais scanné</span>
                    @endif
                    </div>
                
                    <div class="mb-3">
                        <span class="text-muted d-block small">Total mouvements</span>
                        <strong class="fs-4">{{ $movements->total() }}</strong>
                    </div>

                    <div>
                        <span class="text-muted d-block small">Inscrit le</span>
                        <strong>{{ $student->created_at->format('d/m/Y') }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h2 class="h5 mb-3">Scan rapide</h2>
                    <form method="POST" action="{{ route('pointage.scan') }}">
                        @csrf
                        <input type="hidden" name="cne" value="{{ $student->cne }}">
                        <button type="submit" class="btn btn-primary w-100 py-3 fs-5">
                            Scanner {{ $student->prenom }} {{ $student->nom }}
                        </button>
                        <p class="text-muted text-center mb-0 mt-3 small">
                            CNE: {{ $student->cne }}
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-3">
        <div class="card-header bg-white">
            <h2 class="h5 mb-0">Historique des mouvements</h2>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Date</th>
                            <th>Heure</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $movement)
                            <tr>
                                <td class="ps-3">{{ $movement->id }}</td>
                                <td>{{ $movement->scanned_at->format('d/m/Y') }}</td>
                                <td>{{ $movement->scanned_at->format('H:i:s') }}</td>
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
                                <td colspan="4" class="text-center text-muted py-4">Aucun mouvement enregistré</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-white">
            {{ $movements->links() }}
        </div>
    </div>
</div>
@endsection
