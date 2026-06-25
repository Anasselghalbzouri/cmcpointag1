<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PointageController extends Controller
{
    public function index()
    {
        $recent_movements = DB::table('mouvements')
            ->join('etudiants', 'mouvements.etudiant_id', '=', 'etudiants.id')
            ->join('pavillons', 'mouvements.pavillon_id', '=', 'pavillons.id')
            ->leftJoin('chambres', 'etudiants.chambre_id', '=', 'chambres.id')
            ->select(
                'mouvements.*',
                'etudiants.nom as etudiant_nom',
                'etudiants.prenom as etudiant_prenom',
                'etudiants.cin',
                'chambres.numero as chambre_numero',
                'pavillons.type as pavillon_nom'
            )
            ->orderByDesc('mouvements.date_heure')
            ->limit(50)
            ->get();

        return view('pointage.index', compact('recent_movements'));
    }

    public function scan(Request $request)
    {
        $request->validate([
            'cne' => 'required|string',
        ]);

        $student = DB::table('etudiants')
            ->where('cin', $request->cne)
            ->whereNull('deleted_at')
            ->first();

        if (!$student) {
            return back()->with('error', 'Étudiant non trouvé avec ce CIN.');
        }

        $lastMovement = DB::table('mouvements')
            ->where('etudiant_id', $student->id)
            ->orderByDesc('date_heure')
            ->first();

        $newType = 'entree';
        if ($lastMovement && $lastMovement->type === 'entree') {
            $newType = 'sortie';
        }

        $pavillonId = $this->resolvePavillonId($student);

        DB::table('mouvements')->insert([
            'etudiant_id' => $student->id,
            'pavillon_id' => $pavillonId,
            'type' => $newType,
            'date_heure' => now(),
            'enregistre_par' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $message = $newType === 'entree'
            ? "✅ {$student->prenom} {$student->nom} est entré(e)."
            : "👋 {$student->prenom} {$student->nom} est sorti(e).";

        return back()->with('success', $message);
    }

    public function manualEntry(Request $request)
    {
        $request->validate([
            'cne' => 'required|string',
            'type' => 'required|in:entree,sortie',
        ]);

        $student = DB::table('etudiants')
            ->where('cin', $request->cne)
            ->whereNull('deleted_at')
            ->first();

        if (!$student) {
            return back()->with('error', 'Étudiant non trouvé.');
        }

        $pavillonId = $this->resolvePavillonId($student);

        DB::table('mouvements')->insert([
            'etudiant_id' => $student->id,
            'pavillon_id' => $pavillonId,
            'type' => $request->type,
            'date_heure' => now(),
            'enregistre_par' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Mouvement enregistré manuellement.');
    }

    private function resolvePavillonId(object $student): int
    {
        if ($student->chambre_id) {
            $pavillonId = DB::table('chambres')
                ->where('id', $student->chambre_id)
                ->value('pavillon_id');

            if ($pavillonId) {
                return $pavillonId;
            }
        }

        return DB::table('pavillons')->orderBy('id')->value('id') ?? 1;
    }
}
