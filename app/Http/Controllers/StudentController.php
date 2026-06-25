<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\StudentAcademicYearService;

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

        $students = $query->orderBy('etudiants.nom')->paginate(20)->withQueryString();
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
            'duree_formation' => 'required|in:2_ans,2_ans_demi',
        ]);

        if (!empty($validated['chambre_id'])) {
            $error = $this->checkChambreAssignment($validated['chambre_id'], $validated['sexe']);
            if ($error) {
                return back()->withErrors(['chambre_id' => $error])->withInput();
            }
        }

        $dateEntree = now();
        $validated['date_entree'] = $dateEntree->toDateString();
        $validated['date_sortie_prevue'] = $this->computeDateSortiePrevue($dateEntree, $validated['duree_formation']);
        $validated['annee_etude'] = '1';
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
            'duree_formation' => 'required|in:2_ans,2_ans_demi',
        ]);

        if ($validated['duree_formation'] !== $student->duree_formation) {
            $validated['date_sortie_prevue'] = $this->computeDateSortiePrevue(
                \Carbon\Carbon::parse($student->date_entree),
                $validated['duree_formation']
            );
        }

        // Handle room change
        $oldChambreId = $student->chambre_id;
        $newChambreId = $validated['chambre_id'] ?? null;

        if ($newChambreId && $oldChambreId != $newChambreId) {
            $error = $this->checkChambreAssignment($newChambreId, $validated['sexe']);
            if ($error) {
                return back()->withErrors(['chambre_id' => $error])->withInput();
            }
        }

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

    public function processAcademicYear(StudentAcademicYearService $service)
    {
        $result = $service->process();

        return redirect()->route('students.index')->with(
            'success',
            "Traitement terminé : {$result['archived']} étudiant(s) archivé(s), {$result['promoted']} promu(s) en 2e année."
        );
    }

    private function computeDateSortiePrevue(\Carbon\Carbon $dateEntree, string $dureeFormation): string
    {
        $months = $dureeFormation === '2_ans_demi' ? 30 : 24;

        return $dateEntree->copy()->addMonths($months)->toDateString();
    }

    private function checkChambreAssignment(int $chambreId, string $sexe): ?string
    {
        $chambre = DB::table('chambres')
            ->join('pavillons', 'chambres.pavillon_id', '=', 'pavillons.id')
            ->where('chambres.id', $chambreId)
            ->select('chambres.occupants_actuels', 'chambres.capacite', 'pavillons.type as pavillon_type')
            ->first();

        if (!$chambre) {
            return 'Chambre introuvable.';
        }

        if ($chambre->occupants_actuels >= $chambre->capacite) {
            return 'Cette chambre est complète.';
        }

        $expectedType = $sexe === 'F' ? 'femme' : 'homme';
        if ($chambre->pavillon_type !== $expectedType) {
            return 'Le pavillon de cette chambre ne correspond pas au sexe de l\'étudiant.';
        }

        return null;
    }
}
