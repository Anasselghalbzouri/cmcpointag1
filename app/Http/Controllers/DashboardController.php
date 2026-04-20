<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Movement;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isStaff()) {
            return $this->adminDashboard();
        }
        
        return $this->studentDashboard($user);
    }

    private function adminDashboard()
    {
        $students = User::where('role', 'student')->get();
        $totalStudents = $students->count();
        $studentsInside = $students->filter(fn($s) => $s->currentStatus() === 'a_linterieur')->count();
        $studentsOutside = $students->filter(fn($s) => $s->currentStatus() === 'a_lexterieur')->count();

        $pendingExitCount = Movement::where('type', 'sortie')
            ->whereDate('scanned_at', today())
            ->count();

        $studentsWithoutMovementToday = $students->filter(function ($student) {
            return !$student->movements()
                ->whereDate('scanned_at', today())
                ->exists();
        })->count();

        $occupancyRate = $totalStudents > 0
            ? round(($studentsInside / $totalStudents) * 100, 1)
            : 0;

        $stats = [
            'total_students' => $totalStudents,
            'total_movements_today' => Movement::whereDate('scanned_at', today())->count(),
            'students_inside' => $studentsInside,
            'students_outside' => $studentsOutside,
            'present_22h' => $studentsInside,
            'absent_22h' => $studentsOutside,
            'pending_requests' => $pendingExitCount,
            'occupancy_rate' => $occupancyRate,
        ];

        $recent_movements = Movement::with('user')
            ->latest()
            ->take(5)
            ->get();

        $pavilions = [
            ['name' => 'Pavilion A', 'capacity' => 50, 'status' => 'Active', 'occupied' => 0],
            ['name' => 'Pavilion B', 'capacity' => 40, 'status' => 'Active', 'occupied' => 0],
            ['name' => 'Pavilion C', 'capacity' => 30, 'status' => 'Maintenance', 'occupied' => 0],
            ['name' => 'Pavilion D', 'capacity' => 60, 'status' => 'Active', 'occupied' => 0],
        ];

        foreach ($students as $student) {
            $index = ($student->id - 1) % count($pavilions);
            $pavilions[$index]['occupied']++;
        }

        foreach ($pavilions as &$pavilion) {
            $pavilion['free'] = max($pavilion['capacity'] - $pavilion['occupied'], 0);
            $pavilion['occupancy_rate'] = $pavilion['capacity'] > 0
                ? round(($pavilion['occupied'] / $pavilion['capacity']) * 100, 1)
                : 0;
        }

        $alerts = [
            ['label' => 'Sorties en attente', 'value' => $pendingExitCount, 'variant' => $pendingExitCount > 0 ? 'warning' : 'success'],
            ['label' => 'Absences non justifiées (jour)', 'value' => $studentsWithoutMovementToday, 'variant' => $studentsWithoutMovementToday > 0 ? 'danger' : 'success'],
            ['label' => 'Mouvements aujourd\'hui', 'value' => $stats['total_movements_today'], 'variant' => 'info'],
        ];

        return view('dashboard.admin', compact('stats', 'recent_movements', 'pavilions', 'alerts'));
    }

    private function studentDashboard(User $student)
    {
        $my_movements = $student->movements()
            ->latest()
            ->take(20)
            ->get();

        $status = $student->currentStatus();

        return view('dashboard.student', compact('my_movements', 'status'));
    }
}
