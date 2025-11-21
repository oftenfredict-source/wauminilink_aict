<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Services\SmsService;

class PaymentApprovalNotification extends Notification
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
     * Send SMS notification directly
     */
    public function sendSmsNotification($notifiable)
    {
        if (!empty($notifiable->phone_number)) {
            try {
                $smsService = new \App\Services\SmsService();
                $smsService->sendPaymentApprovalNotification(
                    $notifiable->phone_number,
                    $notifiable->full_name,
                    $this->data['payment_type'],
                    $this->data['amount'],
                    $this->data['payment_date']->format('Y-m-d')
                );
            } catch (\Exception $e) {
                \Log::error('Failed to send payment approval SMS: ' . $e->getMessage());
            }
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];
        
        // Add email if member has email
        if (!empty($notifiable->email)) {
            $channels[] = 'mail';
        }
        
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $type = ucfirst($this->data['payment_type']);
        $amount = number_format($this->data['amount'], 0);
        $date = $this->data['payment_date']->format('M d, Y');

        return (new MailMessage)
            ->subject("Payment Approved - Waumini Link")
            ->greeting('Hello ' . $notifiable->full_name . ',')
            ->line("Your {$type} has been approved and received successfully.")
            ->line("**Details:**")
            ->line("- Amount: TZS {$amount}")
            ->line("- Date: {$date}")
            ->line("- Status: Approved")
            ->action('View Details', url('/member/dashboard'))
            ->line('Thank you for your contribution to the church!')
            ->line('God bless you!');
    }

    /**
     * Get the SMS representation of the notification.
     */
    public function toSms(object $notifiable): string
    {
        $smsService = new SmsService();
        
        // Send SMS using the service
        $smsService->sendPaymentApprovalNotification(
            $notifiable->phone_number,
            $notifiable->full_name,
            $this->data['payment_type'],
            $this->data['amount'],
            $this->data['payment_date']->format('Y-m-d')
        );
        
        // Return a simple message for the notification record
        return "Your {$this->data['payment_type']} of TZS " . number_format($this->data['amount'], 0) . " has been approved.";
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'payment_approval',
            'payment_type' => $this->data['payment_type'],
            'amount' => $this->data['amount'],
            'payment_date' => $this->data['payment_date']->format('Y-m-d'),
            'message' => "Your {$this->data['payment_type']} of TZS " . number_format($this->data['amount'], 0) . " has been approved and received successfully.",
            'action_url' => '/member/dashboard'
        ];
    }
}
