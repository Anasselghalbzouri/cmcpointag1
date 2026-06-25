<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChambreController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('chambres')
            ->join('pavillons', 'chambres.pavillon_id', '=', 'pavillons.id')
            ->select('chambres.*', 'pavillons.type as pavillon_type')
            ->whereNull('chambres.deleted_at');

        if ($search = $request->input('search')) {
            $query->where('chambres.numero', 'like', "%{$search}%");
        }

        if ($pavillon = $request->input('pavillon')) {
            $query->where('chambres.pavillon_id', $pavillon);
        }

        if ($statut = $request->input('statut')) {
            $query->where('chambres.statut', $statut);
        }

        $chambres = $query->orderBy('pavillons.type')->orderBy('chambres.numero')->paginate(20)->withQueryString();
        $pavillons = DB::table('pavillons')->whereNull('deleted_at')->orderBy('type')->get();

        return view('chambres.index', compact('chambres', 'pavillons'));
    }

    public function create()
    {
        $pavillons = DB::table('pavillons')->whereNull('deleted_at')->orderBy('type')->get();

        return view('chambres.create', compact('pavillons'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pavillon_id' => 'required|exists:pavillons,id',
            'numero' => 'required|string|max:255',
            'etage' => 'required|integer|min:0|max:50',
            'capacite' => 'required|integer|min:1|max:20',
            'statut' => 'required|in:disponible,occupee,maintenance,fermee',
        ]);

        $validated['occupants_actuels'] = 0;
        $validated['created_at'] = now();
        $validated['updated_at'] = now();

        DB::table('chambres')->insert($validated);

        return redirect()->route('chambres.index')->with('success', 'Chambre créée avec succès.');
    }

    public function edit($id)
    {
        $chambre = DB::table('chambres')->where('id', $id)->whereNull('deleted_at')->first();

        if (!$chambre) {
            abort(404);
        }

        $pavillons = DB::table('pavillons')->whereNull('deleted_at')->orderBy('type')->get();

        return view('chambres.edit', compact('chambre', 'pavillons'));
    }

    public function update(Request $request, $id)
    {
        $chambre = DB::table('chambres')->where('id', $id)->whereNull('deleted_at')->first();

        if (!$chambre) {
            abort(404);
        }

        $validated = $request->validate([
            'pavillon_id' => 'required|exists:pavillons,id',
            'numero' => 'required|string|max:255',
            'etage' => 'required|integer|min:0|max:50',
            'capacite' => 'required|integer|min:1|max:20',
            'statut' => 'required|in:disponible,occupee,maintenance,fermee',
        ]);

        if ($validated['capacite'] < $chambre->occupants_actuels) {
            return back()->withErrors(['capacite' => "La capacité ne peut pas être inférieure aux occupants actuels ({$chambre->occupants_actuels})."])->withInput();
        }

        if ($validated['pavillon_id'] != $chambre->pavillon_id && $chambre->occupants_actuels > 0) {
            return back()->withErrors(['pavillon_id' => 'Impossible de changer le pavillon : cette chambre a des occupants.'])->withInput();
        }

        $validated['updated_at'] = now();

        DB::table('chambres')->where('id', $id)->update($validated);

        return redirect()->route('chambres.index')->with('success', 'Chambre mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $chambre = DB::table('chambres')->where('id', $id)->whereNull('deleted_at')->first();

        if (!$chambre) {
            abort(404);
        }

        if ($chambre->occupants_actuels > 0) {
            return back()->with('error', 'Impossible de supprimer : cette chambre a des occupants.');
        }

        DB::table('chambres')->where('id', $id)->update(['deleted_at' => now()]);

        return redirect()->route('chambres.index')->with('success', 'Chambre supprimée avec succès.');
    }
}
