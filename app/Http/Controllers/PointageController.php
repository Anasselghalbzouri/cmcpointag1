<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PointageController extends Controller
{
    public function kiosk()
    {
        $recent_movements = DB::table('mouvements')
            ->join('etudiants', 'mouvements.etudiant_id', '=', 'etudiants.id')
            ->select('mouvements.*', 'etudiants.nom as etudiant_nom', 'etudiants.prenom as etudiant_prenom')
            ->orderByDesc('mouvements.date_heure')
            ->limit(5)
            ->get();

        return view('kiosk.index', compact('recent_movements'));
    }

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

        // Get first pavillon as default
        $pavillon = DB::table('pavillons')->first();

        DB::table('mouvements')->insert([
            'etudiant_id' => $student->id,
            'pavillon_id' => $pavillon->id ?? 1,
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

    public function kioskScan(Request $request)
    {
        $request->validate([
            'cne' => 'required|string',
            'movement_type' => 'required|in:entree,sortie',
        ]);

        $student = DB::table('etudiants')
            ->where('cin', $request->cne)
            ->whereNull('deleted_at')
            ->first();

        if (!$student) {
            return back()->with('error', 'Étudiant non trouvé avec ce CIN.');
        }

        $pavillon = DB::table('pavillons')->first();

        DB::table('mouvements')->insert([
            'etudiant_id' => $student->id,
            'pavillon_id' => $pavillon->id ?? 1,
            'type' => $request->movement_type,
            'date_heure' => now(),
            'enregistre_par' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()
            ->with('success', $request->movement_type === 'entree'
                ? "{$student->prenom} {$student->nom} marqué ENTRÉE."
                : "{$student->prenom} {$student->nom} marqué SORTIE.")
            ->with('movement_type', $request->movement_type)
            ->with('movement_student', "{$student->prenom} {$student->nom}")
            ->with('movement_time', now()->timezone('Africa/Casablanca')->format('d/m/Y H:i:s'));
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

        $pavillon = DB::table('pavillons')->first();

        DB::table('mouvements')->insert([
            'etudiant_id' => $student->id,
            'pavillon_id' => $pavillon->id ?? 1,
            'type' => $request->type,
            'date_heure' => now(),
            'enregistre_par' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Mouvement enregistré manuellement.');
    }
}
