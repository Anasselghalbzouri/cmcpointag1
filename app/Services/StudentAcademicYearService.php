<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StudentAcademicYearService
{
    /**
     * Archive students past their date_sortie_prevue, and promote
     * first-year students who have completed 12 months to second year.
     *
     * Archival is checked before promotion so a student crossing both
     * thresholds in the same run is archived, not promoted.
     *
     * @return array{archived: int, promoted: int}
     */
    public function process(): array
    {
        $students = DB::table('etudiants')
            ->where('statut', 'actif')
            ->whereNull('deleted_at')
            ->get();

        $archived = 0;
        $promoted = 0;

        foreach ($students as $student) {
            if ($student->date_sortie_prevue && now()->toDateString() >= $student->date_sortie_prevue) {
                $this->archive($student);
                $archived++;
                continue;
            }

            if ($student->annee_etude === '1' && Carbon::parse($student->date_entree)->diffInMonths(now()) >= 12) {
                DB::table('etudiants')->where('id', $student->id)->update([
                    'annee_etude' => '2',
                    'updated_at' => now(),
                ]);
                $promoted++;
            }
        }

        return ['archived' => $archived, 'promoted' => $promoted];
    }

    private function archive(object $student): void
    {
        DB::table('etudiants')->where('id', $student->id)->update([
            'statut' => 'archive',
            'deleted_at' => now(),
            'updated_at' => now(),
        ]);

        if ($student->chambre_id) {
            DB::table('chambres')->where('id', $student->chambre_id)->decrement('occupants_actuels');
        }
    }
}
