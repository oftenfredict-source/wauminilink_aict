<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Enabling SMS notifications...\n";
    
    // Enable SMS notifications
    \App\Services\SettingsService::set('enable_sms_notifications', true, 'boolean');
    echo "✅ SMS notifications enabled\n";
    
    // Set SMS configuration
    \App\Services\SettingsService::set('sms_api_url', 'https://messaging-service.co.tz/link/sms/v1/text/single', 'string');
    \App\Services\SettingsService::set('sms_username', 'emcatechn', 'string');
    \App\Services\SettingsService::set('sms_password', 'Emca@#12', 'string');
    \App\Services\SettingsService::set('sms_sender_id', 'WauminiLnk', 'string');
    
    echo "✅ SMS configuration set\n";
    
    // Test SMS
    echo "Testing SMS for +255743001243...\n";
    $smsService = app(\App\Services\SmsService::class);
    $result = $smsService->sendDebug('+255743001243', 'Test message from Waumini Link');
    
    echo "SMS Test Result:\n";
    print_r($result);
    
    // Test payment approval SMS
    echo "\nTesting Payment Approval SMS...\n";
    $paymentResult = $smsService->sendPaymentApprovalNotificationDebug(
        '+255743001243', 
        'John Doe', 
        'Tithe', 
        50000, 
        date('Y-m-d')
    );
    
    echo "Payment Approval SMS Result:\n";
    print_r($paymentResult);
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}




