<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Testing SMS for number: +255743001243\n";
    echo "=====================================\n\n";
    
    // Test basic SMS
    $smsService = app(\App\Services\SmsService::class);
    $result = $smsService->sendDebug('+255743001243', 'Test message from Waumini Link');
    
    echo "Basic SMS Test Result:\n";
    print_r($result);
    echo "\n";
    
    // Test payment approval SMS
    echo "Testing Payment Approval SMS:\n";
    $paymentResult = $smsService->sendPaymentApprovalNotificationDebug(
        '+255743001243', 
        'John Doe', 
        'Tithe', 
        50000, 
        date('Y-m-d')
    );
    
    echo "Payment Approval SMS Test Result:\n";
    print_r($paymentResult);
    echo "\n";
    
    // Check current SMS settings
    echo "Current SMS Settings:\n";
    echo "Enabled: " . (\App\Services\SettingsService::get('enable_sms_notifications') ? 'Yes' : 'No') . "\n";
    echo "API URL: " . \App\Services\SettingsService::get('sms_api_url') . "\n";
    echo "Username: " . \App\Services\SettingsService::get('sms_username') . "\n";
    echo "Sender ID: " . \App\Services\SettingsService::get('sms_sender_id') . "\n";
    echo "Has API Key: " . (!empty(\App\Services\SettingsService::get('sms_api_key')) ? 'Yes' : 'No') . "\n";
    echo "Has Password: " . (!empty(\App\Services\SettingsService::get('sms_password')) ? 'Yes' : 'No') . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}




