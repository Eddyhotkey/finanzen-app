<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlannedTransactionController;
use App\Http\Controllers\MonthlyReportController;
use App\Http\Controllers\YearlyReportController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\SavingsGoalController;
use App\Http\Controllers\PlannerDayController;
use App\Http\Controllers\PlannerTaskController;
use App\Http\Controllers\PlannerAppointmentController;
use App\Http\Controllers\PlannerCategoryController;
use App\Http\Controllers\PlannerDailyNoteController;
use App\Http\Controllers\PlannerWeekController;
use App\Http\Controllers\PlannerWeekNoteController;
use App\Http\Controllers\PlannerMonthController;
use App\Http\Controllers\PlannerMonthNoteController;
use App\Http\Controllers\PlannerIdeasController;
use App\Http\Controllers\PlannerYearController;
use App\Http\Controllers\PlannerSpecialPeriodController;
use App\Http\Controllers\PlannerYearGoalController;
use App\Http\Controllers\PushSubscriptionController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/push-subscriptions', [PushSubscriptionController::class, 'store'])->name('push-subscriptions.store');
    Route::delete('/push-subscriptions', [PushSubscriptionController::class, 'destroy'])->name('push-subscriptions.destroy');
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


Route::resource('budgets', BudgetController::class)
    ->middleware(['auth', 'verified']);



Route::resource('accounts', AccountController::class)
    ->middleware(['auth', 'verified']);



Route::resource('savings-goals', SavingsGoalController::class)
    ->middleware(['auth', 'verified']);



Route::get('/planner/day/{date?}', PlannerDayController::class)
    ->middleware(['auth', 'verified'])
    ->name('planner.day');

Route::resource('planner-tasks', PlannerTaskController::class)
    ->only(['store', 'update', 'destroy'])
    ->middleware(['auth', 'verified']);

Route::post('planner-tasks/{plannerTask}/defer', [PlannerTaskController::class, 'defer'])
    ->middleware(['auth', 'verified'])
    ->name('planner-tasks.defer');

Route::resource('planner-appointments', PlannerAppointmentController::class)
    ->only(['store', 'update', 'destroy'])
    ->middleware(['auth', 'verified']);

Route::resource('planner-categories', PlannerCategoryController::class)
    ->only(['store', 'update', 'destroy'])
    ->middleware(['auth', 'verified']);

Route::post('planner-daily-notes', [PlannerDailyNoteController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('planner-daily-notes.store');

Route::get('/planner/week/{date?}', PlannerWeekController::class)
    ->middleware(['auth', 'verified'])
    ->name('planner.week');

Route::post('planner-week-notes', [PlannerWeekNoteController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('planner-week-notes.store');

Route::get('/planner/month/{year?}/{month?}', PlannerMonthController::class)
    ->middleware(['auth', 'verified'])
    ->name('planner.month');

Route::post('planner-month-notes', [PlannerMonthNoteController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('planner-month-notes.store');

Route::get('/planner/ideas', PlannerIdeasController::class)
    ->middleware(['auth', 'verified'])
    ->name('planner.ideas');

Route::get('/planner/year/{year?}', PlannerYearController::class)
    ->middleware(['auth', 'verified'])
    ->name('planner.year');

Route::resource('planner-special-periods', PlannerSpecialPeriodController::class)
    ->only(['store', 'update', 'destroy'])
    ->middleware(['auth', 'verified']);

Route::resource('planner-year-goals', PlannerYearGoalController::class)
    ->only(['store', 'update', 'destroy'])
    ->middleware(['auth', 'verified']);

Route::post('planner-year-goals/{plannerYearGoal}/progress', [PlannerYearGoalController::class, 'progress'])
    ->middleware(['auth', 'verified'])
    ->name('planner-year-goals.progress');

require __DIR__.'/auth.php';
