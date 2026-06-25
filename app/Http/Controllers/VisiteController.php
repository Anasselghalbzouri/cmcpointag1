<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VisiteController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('visites')
            ->leftJoin('utilisateurs', 'visites.enregistre_par', '=', 'utilisateurs.id')
            ->select(
                'visites.*',
                'utilisateurs.nom as enregistre_nom',
                'utilisateurs.prenom as enregistre_prenom'
            )
            ->whereNull('visites.deleted_at');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('visites.nom_visiteur', 'like', "%{$search}%")
                  ->orWhere('visites.prenom_visiteur', 'like', "%{$search}%")
                  ->orWhere('visites.cin_visiteur', 'like', "%{$search}%")
                  ->orWhere('visites.matricul_visiteur', 'like', "%{$search}%");
            });
        }

        if ($statut = $request->input('statut')) {
            $query->where('visites.statut', $statut);
        }

        $visites = $query->orderByDesc('visites.date_heure_entree')->paginate(20)->withQueryString();

        $stats = [
            'total' => DB::table('visites')->whereNull('deleted_at')->count(),
            'en_cours' => DB::table('visites')->where('statut', 'en_cours')->whereNull('deleted_at')->count(),
            'today' => DB::table('visites')->whereDate('date_heure_entree', now()->toDateString())->whereNull('deleted_at')->count(),
        ];

        return view('visites.index', compact('visites', 'stats'));
    }

    public function create()
    {
        return view('visites.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_visiteur' => 'required|string|max:255',
            'prenom_visiteur' => 'required|string|max:255',
            'cin_visiteur' => 'required|string|max:255',
            'matricul_visiteur' => 'nullable|string|max:255',
            'motif' => 'required|string|max:1000',
        ]);

        $id = DB::table('visites')->insertGetId([
            'nom_visiteur' => $validated['nom_visiteur'],
            'prenom_visiteur' => $validated['prenom_visiteur'],
            'cin_visiteur' => $validated['cin_visiteur'],
            'matricul_visiteur' => $validated['matricul_visiteur'] ?? null,
            'date_heure_entree' => now(),
            'motif' => $validated['motif'],
            'enregistre_par' => Auth::id(),
            'statut' => 'en_cours',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('visites.index')->with('success', 'Visite enregistrée avec succès.');
    }

    public function checkout($id)
    {
        $visite = DB::table('visites')->where('id', $id)->whereNull('deleted_at')->first();

        if (!$visite) {
            abort(404);
        }

        if ($visite->statut !== 'en_cours') {
            return back()->with('error', 'Cette visite est déjà clôturée.');
        }

        DB::table('visites')->where('id', $id)->update([
            'date_heure_sortie' => now(),
            'statut' => 'sortie',
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Sortie du visiteur enregistrée.');
    }
}
