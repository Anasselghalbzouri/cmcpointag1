<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = User::where('role', 'student')
            ->withCount('movements')
            ->get()
            ->map(function ($student) {
                $student->status = $student->currentStatus();
                return $student;
            });

        return view('students.index', compact('students'));
    }

    public function show(User $student)
    {
        $movements = $student->movements()
            ->latest()
            ->paginate(20);

        return view('students.show', compact('student', 'movements'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cne' => 'required|string|unique:users',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'username' => 'required|string|unique:users',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'cne' => $validated['cne'],
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'username' => $validated['username'],
            'password' => bcrypt($validated['password']),
            'role' => 'student',
        ]);

        return redirect()->route('students.index')->with('success', 'Étudiant créé avec succès.');
    }
}
