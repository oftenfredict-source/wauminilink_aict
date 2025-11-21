<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Member;
use App\Models\ServiceAttendance;

echo "Testing member history functionality...\n";

// Get a member
$member = Member::find(64);
if (!$member) {
    echo "Member not found\n";
    exit;
}

echo "Member: {$member->full_name}\n";

// Get attendances for this member
$attendances = ServiceAttendance::where('member_id', 64)
    ->orderBy('attended_at', 'desc')
    ->limit(5)
    ->get();

echo "Found {$attendances->count()} attendances\n";

foreach ($attendances as $attendance) {
    echo "Attendance: {$attendance->attended_at} - {$attendance->service_type}\n";
    
    // Test the getService method
    $service = $attendance->getService();
    if ($service) {
        if ($attendance->service_type === 'sunday_service') {
            echo "  Service: {$service->theme}\n";
        } else {
            echo "  Event: {$service->title}\n";
        }
    } else {
        echo "  Service: Not found\n";
    }
}

echo "Test completed successfully!\n";



