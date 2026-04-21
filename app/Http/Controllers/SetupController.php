<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class SetupController extends Controller
{
    public function initialize()
    {
        // Data already exists from CMCpointage.sql import
        $count = DB::table('utilisateurs')->count();
        
        if ($count > 0) {
            return redirect()->route('login')->with('success', 
                "Base de données prête! {$count} utilisateurs trouvés. Login: admin@cmc.ma / password"
            );
        }

        return redirect()->route('login')->with('error', 'Aucun utilisateur trouvé. Importez CMCpointage.sql.');
    }
}
