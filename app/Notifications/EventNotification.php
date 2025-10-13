<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\SpecialEvent;

class EventNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $event;
    protected $type; // 'created', 'updated', 'reminder'

    public function __construct(SpecialEvent $event, $type = 'created')
    {
        $this->event = $event;
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
            ->line('Event Details:')
            ->line('📅 Date: ' . $this->event->event_date?->format('F j, Y') ?? 'TBD')
            ->line('🕐 Time: ' . ($this->event->start_time ? $this->event->start_time . ' - ' . $this->event->end_time : 'TBD'))
            ->line('📍 Venue: ' . ($this->event->venue ?? 'TBD'))
            ->line('👨‍💼 Speaker: ' . ($this->event->speaker ?? 'TBD'))
            ->line('📝 Description: ' . ($this->event->description ?? 'No description provided'))
            ->action('View Event Details', url('/special-events'))
            ->line('Thank you for being part of our church community!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'event_' . $this->type,
            'event_id' => $this->event->id,
            'title' => $this->event->title,
            'date' => $this->event->event_date?->format('Y-m-d'),
            'time' => $this->event->start_time,
            'venue' => $this->event->venue,
            'message' => $this->getMessage(),
        ];
    }

    private function getSubject()
    {
        switch ($this->type) {
            case 'created':
                return '🎉 New Special Event: ' . $this->event->title;
            case 'updated':
                return '📝 Event Updated: ' . $this->event->title;
            case 'reminder':
                return '⏰ Reminder: ' . $this->event->title . ' is coming up!';
            default:
                return 'Special Event Notification';
        }
    }

    private function getGreeting()
    {
        return 'Hello ' . ($this->event->member->full_name ?? 'Church Member') . '!';
    }

    private function getMessage()
    {
        switch ($this->type) {
            case 'created':
                return 'A new special event has been added to our church calendar. We hope you can join us!';
            case 'updated':
                return 'An event you might be interested in has been updated with new details.';
            case 'reminder':
                return 'This is a friendly reminder about an upcoming special event.';
            default:
                return 'You have a notification about a special event.';
        }
    }
}