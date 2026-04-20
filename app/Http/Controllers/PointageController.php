<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Movement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PointageController extends Controller
{
    public function kiosk()
    {
        $recent_movements = Movement::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('kiosk.index', compact('recent_movements'));
    }

    public function index()
    {
        $recent_movements = Movement::with('user')
            ->latest()
            ->take(50)
            ->get();

        return view('pointage.index', compact('recent_movements'));
    }

    public function scan(Request $request)
    {
        $request->validate([
            'cne' => 'required|string',
        ]);

        $student = User::where('cne', $request->cne)
            ->where('role', 'student')
            ->first();

        if (!$student) {
            return back()->with('error', 'Étudiant non trouvé avec ce CNE.');
        }

        $lastMovement = $student->lastMovement();
        $newType = 'entree';

        if ($lastMovement && $lastMovement->type === 'entree') {
            $newType = 'sortie';
        }

        try {
            DB::transaction(function () use ($student, $newType) {
                Movement::create([
                    'user_id' => $student->id,
                    'type' => $newType,
                    'scanned_at' => now(),
                ]);
            });

            $message = $newType === 'entree' 
                ? "✅ {$student->prenom} {$student->nom} est entré(e)." 
                : "👋 {$student->prenom} {$student->nom} est sorti(e).";

            return back()->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'enregistrement.');
        }
    }

    public function kioskScan(Request $request)
    {
        $request->validate([
            'cne' => 'required|string',
            'movement_type' => 'required|in:entree,sortie',
        ]);

        $student = User::where('cne', $request->cne)
            ->where('role', 'student')
            ->first();

        if (!$student) {
            return back()->with('error', 'Étudiant non trouvé avec ce CNE.');
        }

        $requestedType = $request->movement_type;
        $lastMovement = $student->lastMovement();

        if ($requestedType === 'entree' && $lastMovement && $lastMovement->type === 'entree') {
            return back()->with('error', 'Cet étudiant est déjà marqué à l\'intérieur.');
        }

        if ($requestedType === 'sortie' && (!$lastMovement || $lastMovement->type === 'sortie')) {
            return back()->with('error', 'Cet étudiant n\'est pas actuellement à l\'intérieur.');
        }

        try {
            Movement::create([
                'user_id' => $student->id,
                'type' => $requestedType,
                'scanned_at' => now(),
            ]);

            return back()
                ->with('success', $requestedType === 'entree'
                    ? "{$student->prenom} {$student->nom} marqué ENTRÉE."
                    : "{$student->prenom} {$student->nom} marqué SORTIE.")
                ->with('movement_type', $requestedType)
                ->with('movement_student', "{$student->prenom} {$student->nom}")
                ->with('movement_time', now()->timezone('Africa/Casablanca')->format('d/m/Y H:i:s'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'enregistrement.');
        }
    }

    public function manualEntry(Request $request)
    {
        $request->validate([
            'cne' => 'required|string|exists:users,cne',
            'type' => 'required|in:entree,sortie',
        ]);

        $student = User::where('cne', $request->cne)->first();
        $lastMovement = $student->lastMovement();

        if ($request->type === 'entree' && $lastMovement && $lastMovement->type === 'entree') {
            return back()->with('error', 'L\'étudiant est déjà à l\'intérieur.');
        }

        if ($request->type === 'sortie' && (!$lastMovement || $lastMovement->type === 'sortie')) {
            return back()->with('error', 'L\'étudiant n\'est pas à l\'intérieur.');
        }

        Movement::create([
            'user_id' => $student->id,
            'type' => $request->type,
            'scanned_at' => now(),
        ]);

        return back()->with('success', 'Mouvement enregistré manuellement.');
    }
}
