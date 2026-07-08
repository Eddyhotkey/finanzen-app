<?php

namespace App\Http\Controllers;

use App\Services\Finance\DashboardService;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke(DashboardService $dashboard)
    {
        return Inertia::render('Dashboard', $dashboard->build(auth()->user()));
    }
}
