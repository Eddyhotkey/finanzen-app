<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlannedTransactionController;
use App\Http\Controllers\MonthlyReportController;
use App\Http\Controllers\YearlyReportController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Category;
Route::middleware(['auth', 'verified'])->group(function () {

    Route::resource('categories', CategoryController::class);

});

// Transaction;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('transactions', TransactionController::class);
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::resource('planned-transactions', PlannedTransactionController::class);

Route::post(
    'planned-transactions/{plannedTransaction}/pay',
    [PlannedTransactionController::class, 'pay']
)->name('planned-transactions.pay');



Route::get('/reports/month/{year?}/{month?}', MonthlyReportController::class)
    ->middleware(['auth', 'verified'])
    ->name('reports.month');


Route::get('/reports/year/{year?}', YearlyReportController::class)
    ->middleware(['auth', 'verified'])
    ->name('reports.year');

require __DIR__.'/auth.php';
