<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DemandeController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('demandes')
            ->join('etudiants', 'demandes.etudiant_id', '=', 'etudiants.id')
            ->leftJoin('utilisateurs', 'demandes.etudie_par', '=', 'utilisateurs.id')
            ->select(
                'demandes.*',
                'etudiants.nom as etudiant_nom',
                'etudiants.prenom as etudiant_prenom',
                'etudiants.cin',
                'utilisateurs.nom as traitee_par_nom',
                'utilisateurs.prenom as traitee_par_prenom'
            )
            ->whereNull('demandes.deleted_at');

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('etudiants.nom', 'like', "%{$search}%")
                  ->orWhere('etudiants.prenom', 'like', "%{$search}%")
                  ->orWhere('etudiants.cin', 'like', "%{$search}%")
                  ->orWhere('demandes.description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($statut = $request->input('statut')) {
            $query->where('demandes.statut', $statut);
        }

        // Type filter
        if ($type = $request->input('type')) {
            $query->where('demandes.type', $type);
        }

        $demandes = $query->orderByDesc('demandes.created_at')->paginate(20)->withQueryString();

        $stats = [
            'total' => DB::table('demandes')->whereNull('deleted_at')->count(),
            'en_attente' => DB::table('demandes')->where('statut', 'en_attente')->whereNull('deleted_at')->count(),
            'approuvee' => DB::table('demandes')->where('statut', 'approuvee')->whereNull('deleted_at')->count(),
            'rejetee' => DB::table('demandes')->where('statut', 'rejetee')->whereNull('deleted_at')->count(),
        ];

        // Get distinct types for filter
        $types = DB::table('demandes')->whereNull('deleted_at')->distinct()->pluck('type');

        return view('demandes.index', compact('demandes', 'stats', 'types'));
    }

    public function create()
    {
        $students = DB::table('etudiants')
            ->whereNull('deleted_at')
            ->orderBy('nom')
            ->select('id', 'nom', 'prenom', 'cin')
            ->get();

        return view('demandes.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'type' => 'required|in:changement_chambre,extension,permission,autre',
            'description' => 'required|string|max:2000',
            'date_limite' => 'nullable|date|after_or_equal:today',
        ]);

        $id = DB::table('demandes')->insertGetId([
            'etudiant_id' => $request->etudiant_id,
            'type' => $request->type,
            'description' => $request->description,
            'statut' => 'en_attente',
            'date_limite' => $request->date_limite,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('demandes.show', $id)
            ->with('success', 'Demande créée avec succès.');
    }

    public function show($id)
    {
        $demande = DB::table('demandes')
            ->join('etudiants', 'demandes.etudiant_id', '=', 'etudiants.id')
            ->leftJoin('utilisateurs', 'demandes.etudie_par', '=', 'utilisateurs.id')
            ->select(
                'demandes.*',
                'etudiants.nom as etudiant_nom',
                'etudiants.prenom as etudiant_prenom',
                'etudiants.cin',
                'etudiants.email as etudiant_email',
                'utilisateurs.nom as traitee_par_nom',
                'utilisateurs.prenom as traitee_par_prenom'
            )
            ->where('demandes.id', $id)
            ->first();

        if (!$demande) {
            abort(404);
        }

        return view('demandes.show', compact('demande'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'statut' => 'required|in:en_attente,approuvee,rejetee,annulee',
            'remarques_admin' => 'nullable|string|max:2000',
        ]);

        DB::table('demandes')->where('id', $id)->update([
            'statut' => $request->statut,
            'remarques' => $request->remarques_admin,
            'etudie_par' => Auth::id(),
            'date_reponse' => now()->toDateString(),
            'updated_at' => now(),
        ]);

        $statusLabels = [
            'approuvee' => 'approuvée',
            'rejetee' => 'rejetée',
            'annulee' => 'annulée',
            'en_attente' => 'remise en attente',
        ];

        return redirect()->route('demandes.show', $id)
            ->with('success', 'Demande ' . ($statusLabels[$request->statut] ?? 'mise à jour') . ' avec succès.');
    }
}
