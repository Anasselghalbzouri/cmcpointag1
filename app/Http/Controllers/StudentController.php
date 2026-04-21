<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('etudiants')
            ->leftJoin('chambres', 'etudiants.chambre_id', '=', 'chambres.id')
            ->leftJoin('pavillons', 'chambres.pavillon_id', '=', 'pavillons.id')
            ->select(
                'etudiants.*',
                'chambres.numero as chambre_numero',
                'pavillons.type as pavillon_nom'
            )
            ->whereNull('etudiants.deleted_at');

        // Search functionality
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('etudiants.nom', 'like', "%{$search}%")
                  ->orWhere('etudiants.prenom', 'like', "%{$search}%")
                  ->orWhere('etudiants.cin', 'like', "%{$search}%")
                  ->orWhere('etudiants.email', 'like', "%{$search}%")
                  ->orWhere('etudiants.telephone', 'like', "%{$search}%")
                  ->orWhere('chambres.numero', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($statut = $request->input('statut')) {
            $query->where('etudiants.statut', $statut);
        }

        // Pavilion filter
        if ($pavillon = $request->input('pavillon')) {
            $query->where('pavillons.id', $pavillon);
        }

        $students = $query->orderBy('etudiants.nom')->get();
        $pavillons = DB::table('pavillons')->get();

        return view('students.index', compact('students', 'pavillons'));
    }

    public function show($id)
    {
        $student = DB::table('etudiants')
            ->leftJoin('chambres', 'etudiants.chambre_id', '=', 'chambres.id')
            ->leftJoin('pavillons', 'chambres.pavillon_id', '=', 'pavillons.id')
            ->select(
                'etudiants.*',
                'chambres.numero as chambre_numero',
                'chambres.etage',
                'pavillons.type as pavillon_nom'
            )
            ->where('etudiants.id', $id)
            ->first();

        if (!$student) {
            abort(404);
        }

        $movements = DB::table('mouvements')
            ->join('pavillons', 'mouvements.pavillon_id', '=', 'pavillons.id')
            ->where('mouvements.etudiant_id', $id)
            ->select('mouvements.*', 'pavillons.type as pavillon_nom')
            ->orderByDesc('mouvements.date_heure')
            ->limit(20)
            ->get();

        $sanctions = DB::table('sanctions')
            ->where('etudiant_id', $id)
            ->whereNull('deleted_at')
            ->orderByDesc('date_sanction')
            ->get();

        $demandes = DB::table('demandes')
            ->where('etudiant_id', $id)
            ->whereNull('deleted_at')
            ->orderByDesc('created_at')
            ->get();

        return view('students.show', compact('student', 'movements', 'sanctions', 'demandes'));
    }

    public function create()
    {
        $chambres = DB::table('chambres')
            ->join('pavillons', 'chambres.pavillon_id', '=', 'pavillons.id')
            ->select('chambres.*', 'pavillons.type as pavillon_nom')
            ->whereNull('chambres.deleted_at')
            ->where('chambres.statut', '!=', 'fermee')
            ->whereRaw('chambres.occupants_actuels < chambres.capacite')
            ->orderBy('pavillons.type')
            ->orderBy('chambres.numero')
            ->get();

        return view('students.create', compact('chambres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:etudiants',
            'telephone' => 'required|string|max:20',
            'cin' => 'required|string|unique:etudiants',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:M,F',
            'chambre_id' => 'nullable|exists:chambres,id',
            'nationalite' => 'nullable|string|max:100',
        ]);

        $validated['date_entree'] = now()->toDateString();
        $validated['statut'] = 'actif';
        $validated['nationalite'] = $validated['nationalite'] ?? 'Maroc';
        $validated['created_at'] = now();
        $validated['updated_at'] = now();

        DB::table('etudiants')->insert($validated);

        if (!empty($validated['chambre_id'])) {
            DB::table('chambres')
                ->where('id', $validated['chambre_id'])
                ->increment('occupants_actuels');
        }

        return redirect()->route('students.index')->with('success', 'Étudiant créé avec succès.');
    }

    public function edit($id)
    {
        $student = DB::table('etudiants')->where('id', $id)->first();

        if (!$student) {
            abort(404);
        }

        $chambres = DB::table('chambres')
            ->join('pavillons', 'chambres.pavillon_id', '=', 'pavillons.id')
            ->select('chambres.*', 'pavillons.type as pavillon_nom')
            ->whereNull('chambres.deleted_at')
            ->where('chambres.statut', '!=', 'fermee')
            ->where(function ($q) use ($student) {
                $q->whereRaw('chambres.occupants_actuels < chambres.capacite')
                  ->orWhere('chambres.id', $student->chambre_id);
            })
            ->orderBy('pavillons.type')
            ->orderBy('chambres.numero')
            ->get();

        return view('students.edit', compact('student', 'chambres'));
    }

    public function update(Request $request, $id)
    {
        $student = DB::table('etudiants')->where('id', $id)->first();

        if (!$student) {
            abort(404);
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:etudiants,email,' . $id,
            'telephone' => 'required|string|max:20',
            'cin' => 'required|string|unique:etudiants,cin,' . $id,
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:M,F',
            'chambre_id' => 'nullable|exists:chambres,id',
            'nationalite' => 'nullable|string|max:100',
            'statut' => 'required|in:actif,suspendu,sorti,archive',
        ]);

        // Handle room change
        $oldChambreId = $student->chambre_id;
        $newChambreId = $validated['chambre_id'] ?? null;

        if ($oldChambreId != $newChambreId) {
            if ($oldChambreId) {
                DB::table('chambres')->where('id', $oldChambreId)->decrement('occupants_actuels');
            }
            if ($newChambreId) {
                DB::table('chambres')->where('id', $newChambreId)->increment('occupants_actuels');
            }
        }

        $validated['updated_at'] = now();

        DB::table('etudiants')->where('id', $id)->update($validated);

        return redirect()->route('students.show', $id)->with('success', 'Étudiant mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $student = DB::table('etudiants')->where('id', $id)->first();

        if (!$student) {
            abort(404);
        }

        // Soft delete
        DB::table('etudiants')->where('id', $id)->update([
            'deleted_at' => now(),
            'statut' => 'archive',
        ]);

        // Free room
        if ($student->chambre_id) {
            DB::table('chambres')->where('id', $student->chambre_id)->decrement('occupants_actuels');
        }

        return redirect()->route('students.index')->with('success', 'Étudiant supprimé avec succès.');
    }
}
