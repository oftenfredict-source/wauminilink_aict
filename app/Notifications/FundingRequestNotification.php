<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FundingRequestNotification extends Notification
{
    use Queueable;

    protected $data;

    /**
     * Create a new notification instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Funding Request Required - ' . $this->data['expense_name'])
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('A new funding request has been submitted for an expense that requires additional funding.')
                    ->line('**Expense Details:**')
                    ->line('• Expense: ' . $this->data['expense_name'])
                    ->line('• Budget: ' . $this->data['budget_name'])
                    ->line('• Requested Amount: TZS ' . number_format($this->data['requested_amount']))
                    ->line('• Available Amount: TZS ' . number_format($this->data['available_amount']))
                    ->line('• Shortfall: TZS ' . number_format($this->data['shortfall_amount']))
                    ->line('**Reason:** ' . $this->data['reason'])
                    ->line('Please review and approve the suggested funding allocations or provide alternative funding sources.')
                    ->action('Review Funding Request', url('/finance/approval/funding-requests'))
                    ->line('Thank you for your attention to this matter.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'funding_request',
            'title' => 'Funding Request Required',
            'message' => "Expense '{$this->data['expense_name']}' requires additional funding of TZS " . number_format($this->data['shortfall_amount']),
            'expense_name' => $this->data['expense_name'],
            'budget_name' => $this->data['budget_name'],
            'requested_amount' => $this->data['requested_amount'],
            'available_amount' => $this->data['available_amount'],
            'shortfall_amount' => $this->data['shortfall_amount'],
            'reason' => $this->data['reason'],
            'suggested_allocations' => $this->data['suggested_allocations'],
            'created_at' => $this->data['created_at']
        ];
    }
}