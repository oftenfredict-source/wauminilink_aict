<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Debugging SMS Payment Approval\n";
    echo "==============================\n\n";
    
    // Enable SMS notifications
    \App\Services\SettingsService::set('enable_sms_notifications', true, 'boolean');
    \App\Services\SettingsService::set('sms_api_url', 'https://messaging-service.co.tz/link/sms/v1/text/single', 'string');
    \App\Services\SettingsService::set('sms_username', 'emcatechn', 'string');
    \App\Services\SettingsService::set('sms_password', 'Emca@#12', 'string');
    \App\Services\SettingsService::set('sms_sender_id', 'WauminiLnk', 'string');
    
    echo "1. SMS Settings:\n";
    echo "   Enabled: " . (\App\Services\SettingsService::get('enable_sms_notifications') ? 'Yes' : 'No') . "\n";
    echo "   API URL: " . \App\Services\SettingsService::get('sms_api_url') . "\n";
    echo "   Username: " . \App\Services\SettingsService::get('sms_username') . "\n";
    echo "   Sender ID: " . \App\Services\SettingsService::get('sms_sender_id') . "\n\n";
    
    // Test template retrieval
    echo "2. Template Test:\n";
    $template = \App\Services\SettingsService::get('sms_payment_approval_template', 'Default template');
    echo "   Template: " . $template . "\n\n";
    
    // Test message building
    echo "3. Message Building Test:\n";
    $memberName = 'John Doe';
    $paymentType = 'Tithe';
    $amount = 50000.0;
    $paymentDate = date('Y-m-d');
    
    echo "   Member Name: $memberName\n";
    echo "   Payment Type: $paymentType\n";
    echo "   Amount: $amount\n";
    echo "   Date: $paymentDate\n";
    
    // Build message manually
    $formattedAmount = number_format($amount, 0);
    $formattedDate = date('d/m/Y', strtotime($paymentDate));
    
    $message = str_replace('{{name}}', $memberName, $template);
    $message = str_replace('{{payment_type}}', $paymentType, $message);
    $message = str_replace('{{amount}}', $formattedAmount, $message);
    $message = str_replace('{{date}}', $formattedDate, $message);
    
    echo "   Formatted Amount: $formattedAmount\n";
    echo "   Formatted Date: $formattedDate\n";
    echo "   Final Message: $message\n\n";
    
    // Test SMS service
    echo "4. SMS Service Test:\n";
    $smsService = app(\App\Services\SmsService::class);
    $result = $smsService->sendDebug('+255743001243', $message);
    
    echo "   Result: " . json_encode($result, JSON_PRETTY_PRINT) . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}




