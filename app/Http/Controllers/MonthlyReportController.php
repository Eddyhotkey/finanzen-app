<?php

namespace App\Http\Controllers;

use App\Services\Finance\ReportService;
use Inertia\Inertia;

class MonthlyReportController extends Controller
{
    public function __invoke(ReportService $reports, ?int $year = null, ?int $month = null)
    {
        return Inertia::render(
            'Reports/Month',
            $reports->month(auth()->user(), $year, $month)
        );
    }
}
