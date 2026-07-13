<?php

namespace App\Notifications;

use App\Models\PlannerAppointment;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class PlannerAppointmentReminder extends Notification
{
    public function __construct(private PlannerAppointment $appointment)
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
            ->title('Termin in 30 Min')
            ->icon('/icon-192.png')
            ->badge('/icon-192.png')
            ->body($this->appointment->title.' um '.substr($this->appointment->start_time, 0, 5))
            ->data(['url' => route('planner.day', $this->appointment->date->toDateString())])
            ->options(['TTL' => 300]);
    }
}
