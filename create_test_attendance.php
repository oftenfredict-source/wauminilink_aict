<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Member;
use App\Models\SundayService;
use App\Models\ServiceAttendance;
use Carbon\Carbon;

echo "Creating test attendance data...\n";

// Get all members
$members = Member::where('membership_type', 'permanent')
    ->whereNotNull('phone_number')
    ->where('phone_number', '!=', '')
    ->get();

if ($members->count() == 0) {
    echo "No members found with phone numbers. Please add some members first.\n";
    exit;
}

// Get the test services
$services = SundayService::where('theme', 'like', 'Test Service%')
    ->orderBy('service_date', 'asc')
    ->get();

if ($services->count() == 0) {
    echo "No test services found. Please run create_test_services.php first.\n";
    exit;
}

echo "Found {$members->count()} members and {$services->count()} test services\n";

// Simulate different attendance patterns
foreach ($members as $index => $member) {
    echo "Processing member: {$member->full_name}\n";
    
    // Different attendance patterns for testing
    if ($index % 3 == 0) {
        // Member attends all services (regular attendee)
        foreach ($services as $service) {
            ServiceAttendance::create([
                'service_type' => 'sunday_service',
                'service_id' => $service->id,
                'member_id' => $member->id,
                'attended_at' => $service->service_date->setTime(9, 30),
                'recorded_by' => 'Test System',
                'notes' => 'Test attendance record'
            ]);
        }
        echo "  - Regular attendee (attended all services)\n";
        
    } elseif ($index % 3 == 1) {
        // Member missed last 2 services (should get SMS)
        foreach ($services->take(1) as $service) {
            ServiceAttendance::create([
                'service_type' => 'sunday_service',
                'service_id' => $service->id,
                'member_id' => $member->id,
                'attended_at' => $service->service_date->setTime(9, 30),
                'recorded_by' => 'Test System',
                'notes' => 'Test attendance record'
            ]);
        }
        echo "  - Missed last 2 services (should get SMS)\n";
        
    } else {
        // Member missed all services (should definitely get SMS)
        echo "  - Missed all services (should get SMS)\n";
    }
}

// Update service attendance counts
foreach ($services as $service) {
    $attendanceCount = ServiceAttendance::where('service_type', 'sunday_service')
        ->where('service_id', $service->id)
        ->count();
    
    $service->update(['attendance_count' => $attendanceCount]);
    echo "Updated service {$service->theme}: {$attendanceCount} attendees\n";
}

echo "\nTest attendance data created successfully!\n";
echo "Now you can test the SMS notification system:\n";
echo "1. Go to: http://127.0.0.1:8000/attendance/statistics\n";
echo "2. Click 'Preview' to see which members would get SMS\n";
echo "3. Click 'Send SMS' to actually send notifications\n";
echo "\nOr run: php artisan attendance:check-notifications --dry-run\n";



