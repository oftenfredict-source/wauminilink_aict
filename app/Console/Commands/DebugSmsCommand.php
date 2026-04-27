<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SmsService;
use App\Services\SettingsService;

class DebugSmsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:debug-sms {phone}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test SMS delivery with detailed debug output';

    /**
     * Execute the console command.
     */
    public function handle(SmsService $smsService)
    {
        $phone = $this->argument('phone');
        $this->info("Starting SMS debug for phone: {$phone}");

        $this->info("System Settings Check:");
        $this->line("- Enabled: " . (SettingsService::get('enable_sms_notifications') ? 'Yes' : 'No'));
        $this->line("- API URL: " . SettingsService::get('sms_api_url'));
        $this->line("- Sender ID: " . SettingsService::get('sms_sender_id'));
        $this->line("- Username: " . SettingsService::get('sms_username'));

        $this->info("\nSending test message...");
        $result = $smsService->sendDebug($phone, "WauminiLink Debug SMS: Success!");

        if ($result['ok']) {
            $this->info("SMS sent successfully!");
        } else {
            $this->error("SMS sending failed!");
            if (isset($result['reason'])) {
                $this->error("Reason: " . $result['reason']);
            }
            if (isset($result['error'])) {
                $this->error("Exception Error: " . $result['error']);
            }
        }

        $this->info("\nServer Response details:");
        if (isset($result['status'])) {
            $this->line("- HTTP Status: " . $result['status']);
        }
        if (isset($result['body'])) {
            $this->line("- Response Body: " . $result['body']);
        }
        if (isset($result['request'])) {
            $this->line("- Request Method: " . $result['request']['method']);
            $this->line("- Request URL: " . $result['request']['url']);
        }

        return $result['ok'] ? 0 : 1;
    }
}
