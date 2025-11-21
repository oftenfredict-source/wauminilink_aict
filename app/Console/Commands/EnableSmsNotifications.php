<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SettingsService;

class EnableSmsNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:enable {--url=} {--username=} {--password=} {--sender=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable SMS notifications with configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Enabling SMS notifications...');

        // Enable SMS notifications
        SettingsService::set('enable_sms_notifications', true, 'boolean');
        $this->info('✅ SMS notifications enabled');

        // Set SMS configuration
        $url = $this->option('url') ?: 'https://messaging-service.co.tz/link/sms/v1/text/single';
        $username = $this->option('username') ?: 'emcatechn';
        $password = $this->option('password') ?: 'Emca@#12';
        $sender = $this->option('sender') ?: 'WauminiLnk';

        SettingsService::set('sms_api_url', $url, 'string');
        SettingsService::set('sms_username', $username, 'string');
        SettingsService::set('sms_password', $password, 'string');
        SettingsService::set('sms_sender_id', $sender, 'string');

        $this->info('✅ SMS configuration set:');
        $this->line("   URL: {$url}");
        $this->line("   Username: {$username}");
        $this->line("   Sender ID: {$sender}");

        // Test SMS functionality
        $this->info('Testing SMS functionality...');
        $testPhone = $this->ask('Enter a test phone number (e.g., +255712345678)');
        
        if ($testPhone) {
            try {
                $smsService = app(\App\Services\SmsService::class);
                $result = $smsService->sendDebug($testPhone, 'Test message from Waumini Link');
                
                if ($result['ok']) {
                    $this->info('✅ SMS test successful!');
                } else {
                    $this->error('❌ SMS test failed: ' . ($result['reason'] ?? 'Unknown error'));
                    $this->line('Response: ' . json_encode($result, JSON_PRETTY_PRINT));
                }
            } catch (\Exception $e) {
                $this->error('❌ SMS test failed: ' . $e->getMessage());
            }
        }

        $this->info('SMS setup complete!');
    }
}




