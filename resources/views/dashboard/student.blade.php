@extends('layouts.app')

@section('title', 'Mon Espace - CMC Pointage')

@section('content')
<div>
    <h1 class="h3 mb-4">Mon Espace Étudiant</h1>

    <div class="row g-3">
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <h2 class="h5 mb-4">Statut actuel</h2>
            
            @if($status === 'a_linterieur')
                        <div class="display-3 mb-3">🏫</div>
                        <span class="badge text-bg-success px-3 py-2">Vous êtes à l'intérieur</span>
            @elseif($status === 'a_lexterieur')
                        <div class="display-3 mb-3">🚪</div>
                        <span class="badge text-bg-secondary px-3 py-2">Vous êtes à l'extérieur</span>
            @else
                        <div class="display-3 mb-3">❓</div>
                        <span class="badge text-bg-danger px-3 py-2">Jamais scanné</span>
            @endif
            
                    <p class="text-muted mt-4 mb-0">
                        Présentez votre CNE à un agent pour scanner votre entrée/sortie.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white">
                    <h2 class="h5 mb-0">Mon historique</h2>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3">Date</th>
                                    <th>Heure</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($my_movements as $movement)
                                    <tr>
                                        <td class="ps-3">{{ $movement->scanned_at->format('d/m/Y') }}</td>
                                        <td>{{ $movement->scanned_at->format('H:i') }}</td>
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
                                        <td colspan="3" class="text-center text-muted py-4">Aucun mouvement</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
