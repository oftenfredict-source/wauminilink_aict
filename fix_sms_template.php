<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Fixing SMS Payment Approval Template\n";
    echo "====================================\n\n";
    
    // Set the SMS payment approval template
    $template = "Hongera {{name}}! {{payment_type}} yako ya TZS {{amount}} tarehe {{date}} imethibitishwa na imepokelewa kikamilifu.\n\nAsante kwa mchango wako wa kiroho. Mungu akubariki!\n\nWaumini Link";
    
    \App\Services\SettingsService::set('sms_payment_approval_template', $template, 'text');
    
    echo "✅ Template set successfully\n";
    echo "Template: $template\n\n";
    
    // Verify it was set
    $retrievedTemplate = \App\Services\SettingsService::get('sms_payment_approval_template');
    echo "Retrieved template: $retrievedTemplate\n\n";
    
    // Test the full SMS
    echo "Testing SMS with template...\n";
    $smsService = app(\App\Services\SmsService::class);
    $result = $smsService->sendPaymentApprovalNotificationDebug(
        '+255743001243', 
        'John Doe', 
        'Tithe', 
        50000, 
        date('Y-m-d')
    );
    
    echo "SMS Result:\n";
    print_r($result);
    
    if ($result['ok']) {
        echo "\n✅ SMS sent successfully! Check your phone.\n";
    } else {
        echo "\n❌ SMS failed: " . ($result['reason'] ?? 'Unknown error') . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}




