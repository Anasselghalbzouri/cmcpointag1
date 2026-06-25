<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class RapportController extends Controller
{
    public function index()
    {
        $kpis = [
            'etudiants_actifs' => DB::table('etudiants')->where('statut', 'actif')->whereNull('deleted_at')->count(),
            'etudiants_archives' => DB::table('etudiants')->where('statut', 'archive')->count(),
            'sanctions_actives' => DB::table('sanctions')->where('statut', 'active')->whereNull('deleted_at')->count(),
            'demandes_en_attente' => DB::table('demandes')->where('statut', 'en_attente')->whereNull('deleted_at')->count(),
            'visites_en_cours' => DB::table('visites')->where('statut', 'en_cours')->whereNull('deleted_at')->count(),
        ];

        $occupationGlobale = DB::table('chambres')
            ->whereNull('deleted_at')
            ->selectRaw('SUM(capacite) as capacite, SUM(occupants_actuels) as occupants')
            ->first();

        $occupationParPavillon = DB::table('chambres')
            ->join('pavillons', 'chambres.pavillon_id', '=', 'pavillons.id')
            ->whereNull('chambres.deleted_at')
            ->groupBy('pavillons.id', 'pavillons.type')
            ->selectRaw('pavillons.type, SUM(chambres.capacite) as capacite, SUM(chambres.occupants_actuels) as occupants, COUNT(chambres.id) as nb_chambres')
            ->get();

        $etudiantsParAnnee = DB::table('etudiants')
            ->where('statut', 'actif')
            ->whereNull('deleted_at')
            ->groupBy('annee_etude')
            ->selectRaw('annee_etude, COUNT(*) as total')
            ->get();

        $etudiantsParStatut = DB::table('etudiants')
            ->groupBy('statut')
            ->selectRaw('statut, COUNT(*) as total')
            ->get();

        $sanctionsParType = DB::table('sanctions')
            ->whereNull('deleted_at')
            ->groupBy('type')
            ->selectRaw('type, COUNT(*) as total')
            ->get();

        $demandesParStatut = DB::table('demandes')
            ->whereNull('deleted_at')
            ->groupBy('statut')
            ->selectRaw('statut, COUNT(*) as total')
            ->get();

        $mouvementsParJour = DB::table('mouvements')
            ->where('date_heure', '>=', now()->subDays(30))
            ->selectRaw("DATE(date_heure) as jour, SUM(type = 'entree') as entrees, SUM(type = 'sortie') as sorties")
            ->groupBy('jour')
            ->orderByDesc('jour')
            ->get();

        return view('rapports.index', compact(
            'kpis',
            'occupationGlobale',
            'occupationParPavillon',
            'etudiantsParAnnee',
            'etudiantsParStatut',
            'sanctionsParType',
            'demandesParStatut',
            'mouvementsParJour'
        ));
    }

    public function exportEtudiants()
    {
        $etudiants = DB::table('etudiants')
            ->leftJoin('chambres', 'etudiants.chambre_id', '=', 'chambres.id')
            ->leftJoin('pavillons', 'chambres.pavillon_id', '=', 'pavillons.id')
            ->select(
                'etudiants.id',
                'etudiants.nom',
                'etudiants.prenom',
                'etudiants.cin',
                'etudiants.sexe',
                'etudiants.date_entree',
                'etudiants.duree_formation',
                'etudiants.annee_etude',
                'etudiants.date_sortie_prevue',
                'etudiants.statut',
                'chambres.numero as chambre_numero',
                'pavillons.type as pavillon'
            )
            ->orderBy('etudiants.nom')
            ->get();

        $headers = ['ID', 'Nom', 'Prénom', 'CIN', 'Sexe', 'Date entrée', 'Durée formation', 'Année', 'Date sortie prévue', 'Statut', 'Chambre', 'Pavillon'];
        $rows = $etudiants->map(fn ($e) => [
            $e->id, $e->nom, $e->prenom, $e->cin, $e->sexe, $e->date_entree,
            $e->duree_formation === '2_ans_demi' ? '2 ans et demi' : '2 ans',
            $e->annee_etude, $e->date_sortie_prevue, $e->statut,
            $e->chambre_numero ?? '', $e->pavillon ? ucfirst($e->pavillon) : '',
        ]);

        return $this->csvResponse('etudiants', $headers, $rows);
    }

    public function exportSanctions()
    {
        $sanctions = DB::table('sanctions')
            ->join('etudiants', 'sanctions.etudiant_id', '=', 'etudiants.id')
            ->whereNull('sanctions.deleted_at')
            ->select(
                'sanctions.id', 'sanctions.date_sanction', 'sanctions.type', 'sanctions.motif',
                'sanctions.montant_amende', 'sanctions.statut',
                'etudiants.nom as etudiant_nom', 'etudiants.prenom as etudiant_prenom', 'etudiants.cin'
            )
            ->orderByDesc('sanctions.date_sanction')
            ->get();

        $headers = ['ID', 'Date', 'Type', 'Motif', 'Montant amende', 'Statut', 'Étudiant', 'CIN'];
        $rows = $sanctions->map(fn ($s) => [
            $s->id, $s->date_sanction, $s->type, $s->motif, $s->montant_amende ?? '', $s->statut,
            $s->etudiant_prenom . ' ' . $s->etudiant_nom, $s->cin,
        ]);

        return $this->csvResponse('sanctions', $headers, $rows);
    }

    public function exportDemandes()
    {
        $demandes = DB::table('demandes')
            ->join('etudiants', 'demandes.etudiant_id', '=', 'etudiants.id')
            ->whereNull('demandes.deleted_at')
            ->select(
                'demandes.id', 'demandes.created_at', 'demandes.type', 'demandes.description',
                'demandes.statut', 'demandes.date_limite',
                'etudiants.nom as etudiant_nom', 'etudiants.prenom as etudiant_prenom', 'etudiants.cin'
            )
            ->orderByDesc('demandes.created_at')
            ->get();

        $headers = ['ID', 'Date création', 'Type', 'Description', 'Statut', 'Date limite', 'Étudiant', 'CIN'];
        $rows = $demandes->map(fn ($d) => [
            $d->id, $d->created_at, $d->type, $d->description, $d->statut, $d->date_limite ?? '',
            $d->etudiant_prenom . ' ' . $d->etudiant_nom, $d->cin,
        ]);

        return $this->csvResponse('demandes', $headers, $rows);
    }

    public function exportVisites()
    {
        $visites = DB::table('visites')
            ->whereNull('deleted_at')
            ->orderByDesc('date_heure_entree')
            ->get();

        $headers = ['ID', 'Visiteur', 'CIN', 'Matricule', 'Entrée', 'Sortie', 'Motif', 'Statut'];
        $rows = $visites->map(fn ($v) => [
            $v->id, $v->prenom_visiteur . ' ' . $v->nom_visiteur, $v->cin_visiteur, $v->matricul_visiteur ?? '',
            $v->date_heure_entree, $v->date_heure_sortie ?? '', $v->motif, $v->statut,
        ]);

        return $this->csvResponse('visites', $headers, $rows);
    }

    public function exportOccupation()
    {
        $chambres = DB::table('chambres')
            ->join('pavillons', 'chambres.pavillon_id', '=', 'pavillons.id')
            ->whereNull('chambres.deleted_at')
            ->select('chambres.numero', 'pavillons.type as pavillon', 'chambres.etage', 'chambres.capacite', 'chambres.occupants_actuels', 'chambres.statut')
            ->orderBy('pavillons.type')
            ->orderBy('chambres.numero')
            ->get();

        $headers = ['Numéro', 'Pavillon', 'Étage', 'Capacité', 'Occupants', 'Taux occupation', 'Statut'];
        $rows = $chambres->map(fn ($c) => [
            $c->numero, ucfirst($c->pavillon), $c->etage, $c->capacite, $c->occupants_actuels,
            $c->capacite > 0 ? round($c->occupants_actuels / $c->capacite * 100) . '%' : '0%', $c->statut,
        ]);

        return $this->csvResponse('occupation_chambres', $headers, $rows);
    }

    private function csvResponse(string $name, array $headers, $rows)
    {
        $csv = "\xEF\xBB\xBF";
        $csv .= implode(';', $headers) . "\n";

        foreach ($rows as $row) {
            $csv .= implode(';', array_map(function ($value) {
                return '"' . str_replace('"', '""', (string) $value) . '"';
            }, $row)) . "\n";
        }

        $filename = $name . '_' . now()->format('Y-m-d_His') . '.csv';

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
