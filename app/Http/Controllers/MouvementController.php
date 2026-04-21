<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class MouvementController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('mouvements')
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
            );

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('etudiants.nom', 'like', "%{$search}%")
                  ->orWhere('etudiants.prenom', 'like', "%{$search}%")
                  ->orWhere('etudiants.cin', 'like', "%{$search}%")
                  ->orWhere('chambres.numero', 'like', "%{$search}%");
            });
        }

        // Type filter
        if ($type = $request->input('type')) {
            $query->where('mouvements.type', $type);
        }

        // Date filter
        if ($date = $request->input('date')) {
            $query->whereDate('mouvements.date_heure', $date);
        }

        $mouvements = $query->orderByDesc('mouvements.date_heure')->limit(200)->get();

        // Stats
        $todayCount = DB::table('mouvements')->whereDate('date_heure', today())->count();
        $todayEntrees = DB::table('mouvements')->whereDate('date_heure', today())->where('type', 'entree')->count();
        $todaySorties = DB::table('mouvements')->whereDate('date_heure', today())->where('type', 'sortie')->count();

        $stats = [
            'today_total' => $todayCount,
            'today_entrees' => $todayEntrees,
            'today_sorties' => $todaySorties,
            'total' => DB::table('mouvements')->count(),
        ];

        return view('mouvements.index', compact('mouvements', 'stats'));
    }

    public function export(Request $request)
    {
        $query = DB::table('mouvements')
            ->join('etudiants', 'mouvements.etudiant_id', '=', 'etudiants.id')
            ->join('pavillons', 'mouvements.pavillon_id', '=', 'pavillons.id')
            ->leftJoin('chambres', 'etudiants.chambre_id', '=', 'chambres.id')
            ->select(
                'mouvements.id',
                'mouvements.date_heure',
                'mouvements.type',
                'mouvements.motif',
                'mouvements.observations',
                'etudiants.nom as etudiant_nom',
                'etudiants.prenom as etudiant_prenom',
                'etudiants.cin',
                'chambres.numero as chambre_numero',
                'pavillons.type as pavillon'
            );

        // Apply same filters
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('etudiants.nom', 'like', "%{$search}%")
                  ->orWhere('etudiants.prenom', 'like', "%{$search}%")
                  ->orWhere('etudiants.cin', 'like', "%{$search}%")
                  ->orWhere('chambres.numero', 'like', "%{$search}%");
            });
        }
        if ($type = $request->input('type')) {
            $query->where('mouvements.type', $type);
        }
        if ($date = $request->input('date')) {
            $query->whereDate('mouvements.date_heure', $date);
        }

        $mouvements = $query->orderByDesc('mouvements.date_heure')->get();

        // Build CSV
        $csv = "\xEF\xBB\xBF"; // BOM for Excel UTF-8
        $csv .= "ID;Date/Heure;Étudiant;CIN;Chambre;Pavillon;Type;Motif;Observations\n";

        foreach ($mouvements as $m) {
            $csv .= implode(';', [
                $m->id,
                $m->date_heure,
                '"' . $m->etudiant_prenom . ' ' . $m->etudiant_nom . '"',
                $m->cin,
                $m->chambre_numero ?? '',
                ucfirst($m->pavillon),
                $m->type === 'entree' ? 'Entrée' : 'Sortie',
                '"' . str_replace('"', '""', $m->motif ?? '') . '"',
                '"' . str_replace('"', '""', $m->observations ?? '') . '"',
            ]) . "\n";
        }

        $filename = 'mouvements_' . now()->format('Y-m-d_His') . '.csv';

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
