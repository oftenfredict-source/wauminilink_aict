<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Updating SMS Sender ID to 'WauminiLnk'...\n\n";
    
    // Update Sender ID to the registered one
    \App\Services\SettingsService::set('sms_sender_id', 'WauminiLnk', 'string');
    
    echo "✅ Sender ID updated successfully!\n\n";
    
    // Verify the change
    $currentSenderId = \App\Services\SettingsService::get('sms_sender_id');
    echo "Current Sender ID: " . $currentSenderId . "\n";
    
    // Test SMS with the new Sender ID
    echo "\nTesting SMS with new Sender ID...\n";
    $smsService = app(\App\Services\SmsService::class);
    $result = $smsService->sendDebug('255614863345', 'Test SMS from WauminiLink - Sender ID updated');
    
    echo "\nTest Result:\n";
    echo "  Success: " . ($result['ok'] ? "YES ✓" : "NO ✗") . "\n";
    if (isset($result['status'])) {
        echo "  HTTP Status: " . $result['status'] . "\n";
    }
    if (isset($result['reason'])) {
        echo "  Reason: " . $result['reason'] . "\n";
    }
    if (isset($result['error'])) {
        echo "  Error: " . $result['error'] . "\n";
    }
    if (isset($result['body'])) {
        $body = json_decode($result['body'], true);
        if (isset($body['messages'][0]['status'])) {
            $status = $body['messages'][0]['status'];
            echo "  Provider Status: " . ($status['groupName'] ?? 'Unknown') . "\n";
            echo "  Description: " . ($status['description'] ?? 'N/A') . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}





