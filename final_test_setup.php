<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Member;
use App\Models\SundayService;
use App\Models\ServiceAttendance;
use Carbon\Carbon;

echo "Creating final test setup...\n";

// Create 4 consecutive weeks of services
$services = [];
for ($i = 4; $i >= 1; $i--) {
    $serviceDate = Carbon::now()->subWeeks($i)->previous(Carbon::SUNDAY);
    
    $service = SundayService::create([
        'service_date' => $serviceDate,
        'service_type' => 'sunday_service',
        'theme' => "Test Service - Week {$i} ago",
        'preacher' => 'Pastor Test',
        'start_time' => '09:00',
        'end_time' => '11:00',
        'venue' => 'Main Sanctuary',
        'attendance_count' => 0,
        'offerings_amount' => 0,
        'scripture_readings' => 'Test Scripture',
        'choir' => 'Test Choir',
        'announcements' => 'Test announcements',
        'notes' => 'Test service for SMS notification testing',
        'status' => 'completed'
    ]);
    
    $services[] = $service;
    echo "Created service: {$service->service_date} - {$service->theme}\n";
}

// Get members
$members = Member::where('membership_type', 'permanent')
    ->whereNotNull('phone_number')
    ->where('phone_number', '!=', '')
    ->get();

echo "Found {$members->count()} members\n";

// Create attendance patterns
foreach ($members as $index => $member) {
    echo "Processing member: {$member->full_name}\n";
    
    if ($index % 2 == 0) {
        // Member misses ALL 4 consecutive weeks (should get SMS)
        echo "  - Missed all 4 consecutive weeks (SHOULD GET SMS)\n";
        
    } else {
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

echo "\nFinal test setup completed!\n";
echo "Now test the SMS notification system:\n";
echo "1. Run: php artisan attendance:check-notifications --dry-run\n";
echo "2. Go to: http://127.0.0.1:8000/attendance/statistics\n";



