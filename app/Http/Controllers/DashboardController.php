<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isSecurity()) {
            return redirect()->route('pointage.index');
        }

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }
        
        return $this->studentDashboard($user);
    }

    private function adminDashboard()
    {
        // Count from etudiants table
        $totalStudents = DB::table('etudiants')->whereNull('deleted_at')->count();
        $activeStudents = DB::table('etudiants')->where('statut', 'actif')->whereNull('deleted_at')->count();

        // Today's movements from mouvements table
        $totalMovementsToday = DB::table('mouvements')->whereDate('date_heure', today())->count();

        // Latest movements
        $recent_movements = DB::table('mouvements')
            ->join('etudiants', 'mouvements.etudiant_id', '=', 'etudiants.id')
            ->join('pavillons', 'mouvements.pavillon_id', '=', 'pavillons.id')
            ->select(
                'mouvements.*',
                'etudiants.nom as etudiant_nom',
                'etudiants.prenom as etudiant_prenom',
                'pavillons.type as pavillon_nom'
            )
            ->orderByDesc('mouvements.date_heure')
            ->limit(8)
            ->get();

        // Demandes en attente
        $pendingDemandes = DB::table('demandes')->where('statut', 'en_attente')->count();

        // Active sanctions
        $activeSanctions = DB::table('sanctions')->where('statut', 'active')->whereNull('deleted_at')->count();

        // Visites en cours
        $activeVisites = DB::table('visites')->where('statut', 'en_cours')->whereNull('deleted_at')->count();

        // Étudiants dont la fin de formation est imminente (30 prochains jours)
        $upcomingDepartures = DB::table('etudiants')
            ->where('statut', 'actif')
            ->whereNull('deleted_at')
            ->whereBetween('date_sortie_prevue', [today()->toDateString(), today()->addDays(30)->toDateString()])
            ->count();

        // Pavillon data
        $pavilions = DB::table('pavillons')->get()->map(function ($pav) {
            $chambresCount = DB::table('chambres')->where('pavillon_id', $pav->id)->whereNull('deleted_at')->count();
            $totalCapacity = DB::table('chambres')->where('pavillon_id', $pav->id)->whereNull('deleted_at')->sum('capacite');
            $totalOccupants = DB::table('chambres')->where('pavillon_id', $pav->id)->whereNull('deleted_at')->sum('occupants_actuels');
            
            return (object) [
                'id' => $pav->id,
                'nom' => ucfirst($pav->type),
                'type' => $pav->type,
                'chambres_count' => $chambresCount,
                'capacity' => $totalCapacity,
                'occupied' => $totalOccupants,
                'free' => max($totalCapacity - $totalOccupants, 0),
                'occupancy_rate' => $totalCapacity > 0 ? round(($totalOccupants / $totalCapacity) * 100, 1) : 0,
            ];
        });

        // Overall occupancy
        $totalCapacity = $pavilions->sum('capacity');
        $totalOccupied = $pavilions->sum('occupied');
        $occupancyRate = $totalCapacity > 0 ? round(($totalOccupied / $totalCapacity) * 100, 1) : 0;

        $stats = [
            'total_students' => $totalStudents,
            'active_students' => $activeStudents,
            'total_movements_today' => $totalMovementsToday,
            'pending_demandes' => $pendingDemandes,
            'active_sanctions' => $activeSanctions,
            'active_visites' => $activeVisites,
            'upcoming_departures' => $upcomingDepartures,
            'occupancy_rate' => $occupancyRate,
        ];

        $alerts = [
            ['label' => 'Demandes en attente', 'value' => $pendingDemandes, 'variant' => $pendingDemandes > 0 ? 'warning' : 'success'],
            ['label' => 'Sanctions actives', 'value' => $activeSanctions, 'variant' => $activeSanctions > 0 ? 'danger' : 'success'],
            ['label' => 'Visiteurs en cours', 'value' => $activeVisites, 'variant' => $activeVisites > 0 ? 'info' : 'success'],
            ['label' => 'Mouvements aujourd\'hui', 'value' => $totalMovementsToday, 'variant' => 'info'],
        ];

        return view('dashboard.admin', compact('stats', 'recent_movements', 'pavilions', 'alerts'));
    }

    private function studentDashboard(User $student)
    {
        $my_movements = collect();
        $status = 'jamais_scanne';

        return view('dashboard.student', compact('my_movements', 'status'));
    }
}
