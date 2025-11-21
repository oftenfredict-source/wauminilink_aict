<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Testing Payment Approval SMS for +255743001243\n";
    echo "==============================================\n\n";
    
    // Enable SMS notifications
    \App\Services\SettingsService::set('enable_sms_notifications', true, 'boolean');
    \App\Services\SettingsService::set('sms_api_url', 'https://messaging-service.co.tz/link/sms/v1/text/single', 'string');
    \App\Services\SettingsService::set('sms_username', 'emcatechn', 'string');
    \App\Services\SettingsService::set('sms_password', 'Emca@#12', 'string');
    \App\Services\SettingsService::set('sms_sender_id', 'WauminiLnk', 'string');
    
    echo "✅ SMS settings configured\n";
    
    // Test payment approval SMS
    $smsService = app(\App\Services\SmsService::class);
    $result = $smsService->sendPaymentApprovalNotificationDebug(
        '+255743001243', 
        'John Doe', 
        'Tithe', 
        50000, 
        date('Y-m-d')
    );
    
    echo "Payment Approval SMS Test Result:\n";
    print_r($result);
    
    if ($result['ok']) {
        echo "\n✅ SMS sent successfully!\n";
        echo "Check your phone (+255743001243) for the message.\n";
    } else {
        echo "\n❌ SMS failed: " . ($result['reason'] ?? 'Unknown error') . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}




