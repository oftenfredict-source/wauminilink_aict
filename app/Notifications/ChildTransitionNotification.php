<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChildTransitionNotification extends Notification
{
    use Queueable;

    public $member;
    public $child;

    /**
     * Create a new notification instance.
     */
    public function __construct($member, $child)
    {
        $this->member = $member;
        $this->child = $child;
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
    public function toMail(object $notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Child Transition to Independent Member')
            ->line("{$this->child->full_name} has reached the age of 21 and has been transitioned to an independent member.")
            ->line("New Member ID: {$this->member->member_id}")
            ->action('View Member Profile', url("/members/{$this->member->id}"))
            ->line('Please complete the registration by assigning an envelope number and updating any other necessary details.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'child_transition',
            'title' => 'Child Transitioned to Independent Member',
            'message' => "{$this->child->full_name} has been moved to independent members. New ID: {$this->member->member_id}.",
            'member_id' => $this->member->id,
            'child_id' => $this->child->id,
            'child_name' => $this->child->full_name,
        ];
    }
}
