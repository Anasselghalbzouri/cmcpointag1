<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PavillonController extends Controller
{
    public function index()
    {
        $pavillons = DB::table('pavillons')
            ->whereNull('deleted_at')
            ->orderBy('type')
            ->get()
            ->map(function ($pavillon) {
                $pavillon->chambres_count = DB::table('chambres')
                    ->where('pavillon_id', $pavillon->id)
                    ->whereNull('deleted_at')
                    ->count();
                $pavillon->occupants_total = DB::table('chambres')
                    ->where('pavillon_id', $pavillon->id)
                    ->whereNull('deleted_at')
                    ->sum('occupants_actuels');
                $pavillon->capacite_total = DB::table('chambres')
                    ->where('pavillon_id', $pavillon->id)
                    ->whereNull('deleted_at')
                    ->sum('capacite');

                return $pavillon;
            });

        return view('pavillons.index', compact('pavillons'));
    }

    public function create()
    {
        return view('pavillons.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:femme,homme',
            'nombre_etages' => 'required|integer|min:1|max:50',
        ]);

        $validated['created_at'] = now();
        $validated['updated_at'] = now();

        DB::table('pavillons')->insert($validated);

        return redirect()->route('pavillons.index')->with('success', 'Pavillon créé avec succès.');
    }

    public function edit($id)
    {
        $pavillon = DB::table('pavillons')->where('id', $id)->whereNull('deleted_at')->first();

        if (!$pavillon) {
            abort(404);
        }

        return view('pavillons.edit', compact('pavillon'));
    }

    public function update(Request $request, $id)
    {
        $pavillon = DB::table('pavillons')->where('id', $id)->whereNull('deleted_at')->first();

        if (!$pavillon) {
            abort(404);
        }

        $validated = $request->validate([
            'type' => 'required|in:femme,homme',
            'nombre_etages' => 'required|integer|min:1|max:50',
        ]);

        if ($validated['type'] !== $pavillon->type) {
            $hasChambres = DB::table('chambres')->where('pavillon_id', $id)->whereNull('deleted_at')->exists();
            if ($hasChambres) {
                return back()->withErrors(['type' => 'Impossible de changer le type : ce pavillon contient des chambres.'])->withInput();
            }
        }

        $validated['updated_at'] = now();

        DB::table('pavillons')->where('id', $id)->update($validated);

        return redirect()->route('pavillons.index')->with('success', 'Pavillon mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $pavillon = DB::table('pavillons')->where('id', $id)->whereNull('deleted_at')->first();

        if (!$pavillon) {
            abort(404);
        }

        $hasChambres = DB::table('chambres')->where('pavillon_id', $id)->whereNull('deleted_at')->exists();
        if ($hasChambres) {
            return back()->with('error', 'Impossible de supprimer : ce pavillon contient des chambres.');
        }

        DB::table('pavillons')->where('id', $id)->update(['deleted_at' => now()]);

        return redirect()->route('pavillons.index')->with('success', 'Pavillon supprimé avec succès.');
    }
}
