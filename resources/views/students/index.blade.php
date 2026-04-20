@extends('layouts.app')

@section('title', 'Liste des Étudiants - CMC Pointage')

@section('content')
<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Liste des Étudiants</h1>
        <a href="{{ route('students.create') }}" class="btn btn-success">Ajouter un étudiant</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">CNE</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Statut</th>
                            <th>Total Mouvements</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td class="ps-3"><code>{{ $student->cne }}</code></td>
                                <td>{{ $student->nom }}</td>
                                <td>{{ $student->prenom }}</td>
                                <td>
                                    @if($student->status === 'a_linterieur')
                                        <span class="badge text-bg-success">À l'intérieur</span>
                                    @elseif($student->status === 'a_lexterieur')
                                        <span class="badge text-bg-secondary">À l'extérieur</span>
                                    @else
                                        <span class="badge text-bg-danger">Jamais scanné</span>
                                    @endif
                                </td>
                                <td>{{ $student->movements_count }}</td>
                                <td>
                                    <a href="{{ route('students.show', $student) }}" class="btn btn-outline-secondary btn-sm">
                                        Voir détails
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Aucun étudiant trouvé</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
