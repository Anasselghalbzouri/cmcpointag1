@extends('layouts.app')

@section('title', 'Utilisateurs - CMC Pointage')
@section('page-title', 'Utilisateurs')
@section('breadcrumb', 'CMC Pointage › Gestion des utilisateurs')

@section('content')
<div>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 animate-in">
        <div>
            <h2 class="section-title mb-0">Gestion des utilisateurs</h2>
            <p class="section-subtitle mb-0">{{ $users->total() }} utilisateur(s) trouvé(s)</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius:.625rem; font-weight:600; font-size:.85rem; padding:.55rem 1.25rem;">
            <i class="bi bi-plus-lg"></i> Ajouter un utilisateur
        </a>
    </div>

    <div class="card mb-4 animate-in" style="animation-delay:.15s;">
        <div class="card-body py-3 px-4">
            <form method="GET" action="{{ route('users.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-6">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Rechercher par nom, email...">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <select class="form-select" name="role" style="border-radius:.75rem; height:42px; font-size:.85rem;">
                        <option value="">Tous rôles</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="security" {{ request('role') === 'security' ? 'selected' : '' }}>Sécurité</option>
                        <option value="etudiant" {{ request('role') === 'etudiant' ? 'selected' : '' }}>Étudiant</option>
                    </select>
                </div>
                <div class="col-12 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-dark flex-grow-1" style="border-radius:.625rem; font-weight:600; font-size:.85rem; height:42px;"><i class="bi bi-funnel"></i> Filtrer</button>
                    @if(request()->hasAny(['search', 'role']))
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary" style="border-radius:.625rem; font-size:.85rem; height:42px; display:flex; align-items:center;"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card animate-in" style="animation-delay:.2s;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Rôle</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $u)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width:32px; height:32px; border-radius:.5rem; background:linear-gradient(135deg,#dbeafe,#bfdbfe); display:flex; align-items:center; justify-content:center; font-weight:700; font-size:.7rem; color:#1d4ed8;">
                                            {{ strtoupper(substr($u->prenom, 0, 1)) }}{{ strtoupper(substr($u->nom, 0, 1)) }}
                                        </div>
                                        <span class="fw-medium" style="font-size:.875rem;">{{ $u->prenom }} {{ $u->nom }}</span>
                                        @if($u->id == auth()->id())
                                            <span class="text-muted" style="font-size:.7rem;">(vous)</span>
                                        @endif
                                    </div>
                                </td>
                                <td style="font-size:.82rem;">{{ $u->email }}</td>
                                <td style="font-size:.82rem;">{{ $u->telephone ?? '—' }}</td>
                                <td>
                                    @if($u->role === 'admin')
                                        <span class="status-badge" style="background:#ede9fe; color:#5b21b6;">Admin</span>
                                    @elseif($u->role === 'security')
                                        <span class="status-badge" style="background:#dbeafe; color:#1d4ed8;">Sécurité</span>
                                    @else
                                        <span class="status-badge" style="background:#f1f5f9; color:#475569;">Étudiant</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="{{ route('users.edit', $u->id) }}" class="btn btn-sm btn-light" style="border-radius:.5rem; width:32px; height:32px; display:flex; align-items:center; justify-content:center;" title="Modifier">
                                            <i class="bi bi-pencil" style="font-size:.8rem;"></i>
                                        </a>
                                        @if($u->id != auth()->id())
                                            <form method="POST" action="{{ route('users.destroy', $u->id) }}" class="d-inline" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light" style="border-radius:.5rem; width:32px; height:32px; display:flex; align-items:center; justify-content:center; color:#ef4444;" title="Supprimer">
                                                    <i class="bi bi-trash3" style="font-size:.8rem;"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">Aucun utilisateur trouvé</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="px-4 py-3">
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
