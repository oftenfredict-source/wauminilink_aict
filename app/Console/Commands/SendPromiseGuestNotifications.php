<?php

namespace App\Console\Commands;

use App\Models\PromiseGuest;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendPromiseGuestNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promise-guests:send-notifications {--dry-run : Run without sending actual SMS}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS notifications to promise guests 1 day before their promised service date';

    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        parent::__construct();
        $this->smsService = $smsService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting promise guest notification check...');
        
        $dryRun = $this->option('dry-run');
        if ($dryRun) {
            $this->warn('DRY RUN MODE - No SMS will be sent');
        }

        // Get promise guests that need notification (1 day before service)
        // Service is tomorrow, and they haven't been notified yet
        $tomorrow = Carbon::tomorrow()->startOfDay();
        
        $promiseGuests = PromiseGuest::needsNotification()
            ->whereDate('promised_service_date', $tomorrow)
            ->with('service')
            ->get();

        $this->info("Found {$promiseGuests->count()} promise guest(s) to notify for tomorrow's service...");

        if ($promiseGuests->isEmpty()) {
            $this->info("✅ No promise guests need notifications at this time");
            return 0;
        }

        $notificationsSent = 0;
        $notificationsFailed = 0;
        $guestsNotified = [];

        foreach ($promiseGuests as $promiseGuest) {
            $this->line("Processing: {$promiseGuest->name} ({$promiseGuest->phone_number})");
            
            if (!$dryRun) {
                try {
                    $service = $promiseGuest->service;
                    
                    if (!$service) {
                        // Try to find or create service for this date
                        $service = \App\Models\SundayService::firstOrCreate(
                            ['service_date' => $promiseGuest->promised_service_date],
                            [
                                'service_type' => 'sunday_service',
                                'status' => 'scheduled',
                            ]
                        );
                        $promiseGuest->update(['service_id' => $service->id]);
                    }

                    $sent = $this->smsService->sendPromiseGuestNotification(
                        $promiseGuest->phone_number,
                        $promiseGuest->name,
                        $service
                    );

                    if ($sent) {
                        $promiseGuest->update([
                            'status' => 'notified',
                            'notified_at' => now(),
                        ]);
                        
                        $notificationsSent++;
                        $guestsNotified[] = [
                            'name' => $promiseGuest->name,
                            'phone' => $promiseGuest->phone_number,
                            'service_date' => $promiseGuest->promised_service_date->format('d/m/Y'),
                        ];
                        
                        Log::info("Promise guest notification sent", [
                            'promise_guest_id' => $promiseGuest->id,
                            'name' => $promiseGuest->name,
                            'phone' => $promiseGuest->phone_number,
                            'service_date' => $promiseGuest->promised_service_date,
                        ]);
                        
                        $this->info("  ✅ Notification sent to {$promiseGuest->name}");
                    } else {
                        $notificationsFailed++;
                        $this->error("  ❌ Failed to send notification to {$promiseGuest->name}");
                        Log::error("Failed to send promise guest notification", [
                            'promise_guest_id' => $promiseGuest->id,
                            'name' => $promiseGuest->name,
                        ]);
                    }
                } catch (\Exception $e) {
                    $notificationsFailed++;
                    $this->error("  ❌ Error sending to {$promiseGuest->name}: " . $e->getMessage());
                    Log::error("Error sending promise guest notification", [
                        'promise_guest_id' => $promiseGuest->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            } else {
                $this->line("  Would send SMS to: {$promiseGuest->name} ({$promiseGuest->phone_number})");
                $notificationsSent++;
                $guestsNotified[] = [
                    'name' => $promiseGuest->name,
                    'phone' => $promiseGuest->phone_number,
                    'service_date' => $promiseGuest->promised_service_date->format('d/m/Y'),
                ];
            }
        }

        if ($notificationsSent > 0) {
            $this->info("\n✅ Sent {$notificationsSent} promise guest notification(s)");
            if (!$dryRun) {
                $this->table(
                    ['Guest Name', 'Phone Number', 'Service Date'],
                    collect($guestsNotified)->map(function($guest) {
                        return [$guest['name'], $guest['phone'], $guest['service_date']];
                    })
                );
            }
        }

        if ($notificationsFailed > 0) {
            $this->warn("\n⚠️  {$notificationsFailed} notification(s) failed");
        }

        $this->info('Promise guest notification check completed.');
        return 0;
    }
}




