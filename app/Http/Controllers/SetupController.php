<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SetupController extends Controller
{
    public function initialize()
    {
        if (!User::where('username', 'admin')->exists()) {
            User::create([
                'username' => 'admin',
                'password' => Hash::make('password'),
                'nom' => 'Admin',
                'prenom' => 'System',
                'role' => 'admin',
            ]);
        }

        if (!User::where('username', 'agent')->exists()) {
            User::create([
                'username' => 'agent',
                'password' => Hash::make('password'),
                'nom' => 'Agent',
                'prenom' => 'Campus',
                'role' => 'agent',
            ]);
        }

        if (!User::where('cne', 'CNE12345')->exists()) {
            User::create([
                'username' => 'student1',
                'password' => Hash::make('password'),
                'nom' => 'Doe',
                'prenom' => 'John',
                'role' => 'student',
                'cne' => 'CNE12345',
            ]);
        }

        $testStudents = [
            ['CNE67890', 'Smith', 'Alice'],
            ['CNE11111', 'Brown', 'Bob'],
            ['CNE22222', 'Wilson', 'Carol'],
        ];

        foreach ($testStudents as $index => [$cne, $nom, $prenom]) {
            if (!User::where('cne', $cne)->exists()) {
                User::create([
                    'username' => 'student' . ($index + 2),
                    'password' => Hash::make('password'),
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'role' => 'student',
                    'cne' => $cne,
                ]);
            }
        }

        return redirect()->route('login')->with('success', 'Données de test créées! Login: admin/password ou CNE12345/password');
    }
}
