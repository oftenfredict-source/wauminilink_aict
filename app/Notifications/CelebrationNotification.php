<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Celebration;

class CelebrationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $celebration;
    protected $type; // 'created', 'updated', 'reminder'

    public function __construct(Celebration $celebration, $type = 'created')
    {
        $this->celebration = $celebration;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $subject = $this->getSubject();
        $greeting = $this->getGreeting();
        $message = $this->getMessage();

        return (new MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->line($message)
            ->line('Celebration Details:')
            ->line('🎉 Type: ' . ($this->celebration->celebration_type ?? 'TBD'))
            ->line('📅 Date: ' . $this->celebration->celebration_date?->format('F j, Y') ?? 'TBD')
            ->line('🕐 Time: ' . ($this->celebration->start_time ? $this->celebration->start_time . ' - ' . $this->celebration->end_time : 'TBD'))
            ->line('📍 Venue: ' . ($this->celebration->venue ?? 'TBD'))
            ->line('👤 Celebrant: ' . ($this->celebration->celebrant_name ?? 'TBD'))
            ->line('📝 Description: ' . ($this->celebration->description ?? 'No description provided'))
            ->action('View Celebration Details', url('/celebrations'))
            ->line('Thank you for being part of our church community!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'celebration_' . $this->type,
            'celebration_id' => $this->celebration->id,
            'title' => $this->celebration->celebration_type,
            'date' => $this->celebration->celebration_date?->format('Y-m-d'),
            'time' => $this->celebration->start_time,
            'venue' => $this->celebration->venue,
            'message' => $this->getMessage(),
        ];
    }

    private function getSubject()
    {
        switch ($this->type) {
            case 'created':
                return '🎉 New Celebration: ' . $this->celebration->celebration_type;
            case 'updated':
                return '📝 Celebration Updated: ' . $this->celebration->celebration_type;
            case 'reminder':
                return '⏰ Reminder: ' . $this->celebration->celebration_type . ' is coming up!';
            default:
                return 'Celebration Notification';
        }
    }

    private function getGreeting()
    {
        return 'Hello ' . ($this->celebration->member->full_name ?? 'Church Member') . '!';
    }

    private function getMessage()
    {
        switch ($this->type) {
            case 'created':
                return 'A new celebration has been added to our church calendar. We hope you can join us!';
            case 'updated':
                return 'A celebration you might be interested in has been updated with new details.';
            case 'reminder':
                return 'This is a friendly reminder about an upcoming celebration.';
            default:
                return 'You have a notification about a celebration.';
        }
    }
}