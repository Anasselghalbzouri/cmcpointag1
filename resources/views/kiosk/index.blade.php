@extends('layouts.app')

@section('title', 'Kiosk Public - CMC Pointage')

@section('content')
<style>
    .kiosk-shell {
        background: radial-gradient(circle at top right, rgba(25, 135, 84, 0.14), transparent 35%),
                    radial-gradient(circle at top left, rgba(13, 110, 253, 0.10), transparent 30%),
                    #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 1.25rem;
    }
    .kiosk-title {
        letter-spacing: .2px;
        font-weight: 800;
    }
    .kiosk-card {
        border: 1px solid #e9ecef;
        border-radius: 1rem;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
    }
    .kiosk-input {
        height: 4rem;
        font-size: 1.4rem;
        font-weight: 700;
        border-radius: .9rem;
        letter-spacing: 2px;
        border: 2px solid #dee2e6;
    }
    .kiosk-input:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.2);
    }
    .kiosk-btn {
        min-height: 4rem;
        border-radius: .9rem;
        font-weight: 700;
        letter-spacing: .2px;
    }
    .shift-box {
        border-radius: 1rem;
        border: 1px solid #dbeafe;
        background: linear-gradient(135deg, #eff6ff, #f8fafc);
    }
</style>

<div class="kiosk-shell p-3 p-md-4">
<div class="row g-4 justify-content-center">
    <div class="col-12 d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h1 class="h2 kiosk-title mb-1">Kiosk Public / Pointage</h1>
            <p class="text-muted mb-0">Saisie tactile matricule (CNE) pour entrée et sortie.</p>
        </div>
        <div class="shift-box px-3 py-2">
            <div class="card-body py-2 px-3">
                <strong class="fs-5 text-primary-emphasis">
                    {{ auth()->check() ? auth()->user()->prenom . ' ' . auth()->user()->nom : 'Guard Session' }}
                </strong>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-8 col-xl-7">
        <div class="card kiosk-card">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h2 class="h4 mb-0 fw-bold">Checkpoint sécurité</h2>
                    <span class="badge text-bg-dark px-3 py-2">KIOSK MODE</span>
                </div>
                <p class="text-muted mb-4">Tapez le CNE puis choisissez l'action.</p>
                <form method="POST" action="{{ route('kiosk.scan') }}">
                    @csrf
                    <label for="kiosk_cne" class="form-label fw-semibold">Matricule / CNE</label>
                    <input
                        id="kiosk_cne"
                        name="cne"
                        type="text"
                        class="form-control kiosk-input text-center @error('cne') is-invalid @enderror"
                        placeholder="Ex: CNE12345"
                        autofocus
                        required
                        autocomplete="off"
                    >
                    @error('cne')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @error('movement_type')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror

                    <div class="row g-2 mt-3">
                        <div class="col-12 col-md-6 d-grid">
                            <button type="submit" name="movement_type" value="entree" class="btn btn-success kiosk-btn">
                                Entrée
                            </button>
                        </div>
                        <div class="col-12 col-md-6 d-grid">
                            <button type="submit" name="movement_type" value="sortie" class="btn btn-primary kiosk-btn">
                                Sortie
                            </button>
                        </div>
                    </div>
                </form>

                @if(session('movement_type'))
                    @php
                        $isEntry = session('movement_type') === 'entree';
                    @endphp
                    <div class="alert mt-4 mb-0 {{ $isEntry ? 'alert-success' : 'alert-primary' }} border-0 shadow-sm" role="alert">
                        <h3 class="h6 mb-1 fw-bold">{{ $isEntry ? 'Entrée enregistrée' : 'Sortie enregistrée' }}</h3>
                        <div><strong>{{ session('movement_student') }}</strong></div>
                        <small>Horodatage: {{ session('movement_time') }}</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</div>
@endsection
