<?php

namespace App\Http\Controllers;

use App\Services\Finance\ReportService;
use Inertia\Inertia;

class YearlyReportController extends Controller
{
    public function __invoke(ReportService $reports, ?int $year = null)
    {
        return Inertia::render(
            'Reports/Year',
            $reports->year(auth()->user(), $year)
        );
    }
}
