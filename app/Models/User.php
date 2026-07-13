<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\WebPush\HasPushSubscriptions;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasPushSubscriptions;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function categories()
    {
        return $this->hasMany(\App\Models\Category::class);
    }

    public function transactions()
    {
        return $this->hasMany(\App\Models\Transaction::class);
    }

    public function plannedTransactions()
    {
        return $this->hasMany(\App\Models\PlannedTransaction::class);
    }

    public function budgets()
    {
        return $this->hasMany(\App\Models\Budget::class);
    }

    public function accounts()
    {
        return $this->hasMany(\App\Models\Account::class);
    }

    public function savingsGoals()
    {
        return $this->hasMany(\App\Models\SavingsGoal::class);
    }

    public function plannerCategories()
    {
        return $this->hasMany(\App\Models\PlannerCategory::class);
    }

    public function plannerTasks()
    {
        return $this->hasMany(\App\Models\PlannerTask::class);
    }

    public function plannerAppointments()
    {
        return $this->hasMany(\App\Models\PlannerAppointment::class);
    }

    public function plannerDailyNotes()
    {
        return $this->hasMany(\App\Models\PlannerDailyNote::class);
    }

    public function plannerWeekNotes()
    {
        return $this->hasMany(\App\Models\PlannerWeekNote::class);
    }

    public function plannerMonthNotes()
    {
        return $this->hasMany(\App\Models\PlannerMonthNote::class);
    }

    public function plannerSpecialPeriods()
    {
        return $this->hasMany(\App\Models\PlannerSpecialPeriod::class);
    }

    public function plannerYearGoals()
    {
        return $this->hasMany(\App\Models\PlannerYearGoal::class);
    }
}
