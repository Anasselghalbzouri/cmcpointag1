<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SanctionController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('sanctions')
            ->join('etudiants', 'sanctions.etudiant_id', '=', 'etudiants.id')
            ->leftJoin('utilisateurs', 'sanctions.enregistree_par', '=', 'utilisateurs.id')
            ->select(
                'sanctions.*',
                'etudiants.nom as etudiant_nom',
                'etudiants.prenom as etudiant_prenom',
                'etudiants.cin',
                'utilisateurs.nom as enregistre_nom',
                'utilisateurs.prenom as enregistre_prenom'
            )
            ->whereNull('sanctions.deleted_at');

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('etudiants.nom', 'like', "%{$search}%")
                  ->orWhere('etudiants.prenom', 'like', "%{$search}%")
                  ->orWhere('etudiants.cin', 'like', "%{$search}%")
                  ->orWhere('sanctions.motif', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($statut = $request->input('statut')) {
            $query->where('sanctions.statut', $statut);
        }

        // Type filter
        if ($type = $request->input('type')) {
            $query->where('sanctions.type', $type);
        }

        $sanctions = $query->orderByDesc('sanctions.date_sanction')->get();

        $stats = [
            'total' => DB::table('sanctions')->whereNull('deleted_at')->count(),
            'active' => DB::table('sanctions')->where('statut', 'active')->whereNull('deleted_at')->count(),
            'levee' => DB::table('sanctions')->where('statut', 'levee')->whereNull('deleted_at')->count(),
            'suspendue' => DB::table('sanctions')->where('statut', 'suspendue')->whereNull('deleted_at')->count(),
        ];

        return view('sanctions.index', compact('sanctions', 'stats'));
    }

    public function show($id)
    {
        $sanction = DB::table('sanctions')
            ->join('etudiants', 'sanctions.etudiant_id', '=', 'etudiants.id')
            ->leftJoin('utilisateurs', 'sanctions.enregistree_par', '=', 'utilisateurs.id')
            ->select(
                'sanctions.*',
                'etudiants.nom as etudiant_nom',
                'etudiants.prenom as etudiant_prenom',
                'etudiants.cin',
                'etudiants.email as etudiant_email',
                'utilisateurs.nom as enregistre_nom',
                'utilisateurs.prenom as enregistre_prenom'
            )
            ->where('sanctions.id', $id)
            ->first();

        if (!$sanction) {
            abort(404);
        }

        return view('sanctions.show', compact('sanction'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'statut' => 'required|in:active,levee,suspendue',
            'observations' => 'nullable|string|max:1000',
        ]);

        DB::table('sanctions')->where('id', $id)->update([
            'statut' => $request->statut,
            'observations' => $request->observations,
            'updated_at' => now(),
        ]);

        return redirect()->route('sanctions.show', $id)
            ->with('success', 'Statut de la sanction mis à jour avec succès.');
    }
}
