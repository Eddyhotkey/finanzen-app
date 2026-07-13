<?php

namespace App\Console\Commands;

use App\Models\PlannerAppointment;
use App\Models\PlannerTask;
use App\Notifications\PlannerAppointmentReminder;
use App\Notifications\PlannerTaskReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendPlannerReminders extends Command
{
    protected $signature = 'planner:send-reminders';

    protected $description = 'Send Web Push reminders for due Planner appointments and tasks';

    public function handle(): int
    {
        $now = Carbon::now('Europe/Berlin');
        $today = $now->toDateString();

        $windowEnd = $now->copy()->addMinutes(30)->format('H:i:s');

        $appointments = PlannerAppointment::with('user')
            ->whereDate('date', $today)
            ->whereNull('notified_at')
            ->where('start_time', '>=', $now->format('H:i:s'))
            ->where('start_time', '<=', $windowEnd)
            ->get();

        foreach ($appointments as $appointment) {
            $appointment->user->notify(new PlannerAppointmentReminder($appointment));
            $appointment->update(['notified_at' => $now]);
        }

        if ($now->format('H:i') >= '08:00') {
            $tasks = PlannerTask::with('user')
                ->whereDate('date', $today)
                ->where('is_done', false)
                ->whereNull('notified_at')
                ->get();

            foreach ($tasks as $task) {
                $task->user->notify(new PlannerTaskReminder($task));
                $task->update(['notified_at' => $now]);
            }
        }

        return self::SUCCESS;
    }
}
