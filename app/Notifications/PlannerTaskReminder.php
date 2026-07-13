<?php

namespace App\Notifications;

use App\Models\PlannerTask;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class PlannerTaskReminder extends Notification
{
    public function __construct(private PlannerTask $task)
    {
        //
    }

    public function via($notifiable): array
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Aufgabe heute fällig')
            ->icon('/icon-192.png')
            ->badge('/icon-192.png')
            ->body($this->task->title)
            ->data(['url' => route('planner.day', $this->task->date->toDateString())])
            ->options(['TTL' => 300]);
    }
}
