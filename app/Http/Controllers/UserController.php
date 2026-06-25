<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('utilisateurs')->whereNull('deleted_at');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        $users = $query->orderBy('nom')->paginate(20)->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs',
            'telephone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,security,etudiant',
        ]);

        DB::table('utilisateurs')->insert([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit($id)
    {
        $user = DB::table('utilisateurs')->where('id', $id)->whereNull('deleted_at')->first();

        if (!$user) {
            abort(404);
        }

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = DB::table('utilisateurs')->where('id', $id)->whereNull('deleted_at')->first();

        if (!$user) {
            abort(404);
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email,' . $id,
            'telephone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,security,etudiant',
        ]);

        if ($user->role === 'admin' && $validated['role'] !== 'admin') {
            $otherActiveAdmins = DB::table('utilisateurs')
                ->where('role', 'admin')
                ->whereNull('deleted_at')
                ->where('id', '!=', $id)
                ->exists();

            if (!$otherActiveAdmins) {
                return back()->withErrors(['role' => 'Impossible de retirer le rôle admin : c\'est le dernier administrateur.'])->withInput();
            }
        }

        $update = [
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'] ?? null,
            'role' => $validated['role'],
            'updated_at' => now(),
        ];

        if (!empty($validated['password'])) {
            $update['password'] = Hash::make($validated['password']);
        }

        DB::table('utilisateurs')->where('id', $id)->update($update);

        return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $user = DB::table('utilisateurs')->where('id', $id)->whereNull('deleted_at')->first();

        if (!$user) {
            abort(404);
        }

        if ($id == Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        if ($user->role === 'admin') {
            $otherActiveAdmins = DB::table('utilisateurs')
                ->where('role', 'admin')
                ->whereNull('deleted_at')
                ->where('id', '!=', $id)
                ->exists();

            if (!$otherActiveAdmins) {
                return back()->with('error', 'Impossible de supprimer le dernier administrateur.');
            }
        }

        DB::table('utilisateurs')->where('id', $id)->update(['deleted_at' => now()]);

        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }
}
