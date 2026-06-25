<?php

namespace App\Console\Commands;

use App\Services\StudentAcademicYearService;
use Illuminate\Console\Command;

class ProcessStudentAcademicYears extends Command
{
    protected $signature = 'students:process-academic-year';

    protected $description = "Archive students past their date_sortie_prevue and promote first-year students to second year";

    public function handle(StudentAcademicYearService $service): int
    {
        $result = $service->process();

        $this->info("Archivés : {$result['archived']}, Promus en 2e année : {$result['promoted']}.");

        return self::SUCCESS;
    }
}
