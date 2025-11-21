<?php

namespace App\Notifications;

use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MissedAttendanceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $member;
    protected $weeksMissed;

    /**
     * Create a new notification instance.
     */
    public function __construct(Member $member, int $weeksMissed = 4)
    {
        $this->member = $member;
        $this->weeksMissed = $weeksMissed;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Shalom - Tunakukumbuka")
            ->greeting("Shalom {$this->member->full_name},")
            ->line("ni muda sasa hatujakuona kanisani. Tunaendelea kukuombea, tukitumaini utaungana nasi tena karibuni. Kumbuka, wewe ni sehemu muhimu ya familia ya Mungu.")
            ->salutation("Baraka,\nTimu ya Uchungaji");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'member_id' => $this->member->id,
            'member_name' => $this->member->full_name,
            'weeks_missed' => $this->weeksMissed,
            'message' => "Missed attendance notification sent to {$this->member->full_name}",
            'type' => 'missed_attendance'
        ];
    }
}
