<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Member;
use App\Models\SundayService;
use App\Models\ServiceAttendance;
use Carbon\Carbon;

echo "Creating missed attendance test scenario...\n";

// Clear existing test data
ServiceAttendance::where('recorded_by', 'Test System')->delete();
echo "Cleared existing test data\n";

// Get members
$members = Member::where('membership_type', 'permanent')
    ->whereNotNull('phone_number')
    ->where('phone_number', '!=', '')
    ->get();

if ($members->count() == 0) {
    echo "No members found with phone numbers.\n";
    exit;
}

// Get the test services
$services = SundayService::where('theme', 'like', 'Test Service%')
    ->orderBy('service_date', 'asc')
    ->get();

if ($services->count() < 4) {
    echo "Need at least 4 services for testing. Found: {$services->count()}\n";
    exit;
}

echo "Found {$members->count()} members and {$services->count()} test services\n";

// Create 4 more weeks of services to simulate 4+ consecutive weeks
$additionalServices = [];
for ($i = 1; $i <= 4; $i++) {
    $serviceDate = Carbon::now()->subWeeks($i)->previous(Carbon::SUNDAY);
    
    $service = SundayService::create([
        'service_date' => $serviceDate,
        'service_type' => 'sunday_service',
        'theme' => "Test Service - {$i} weeks ago",
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
    
    $additionalServices[] = $service;
    echo "Created additional service: {$service->service_date} - {$service->theme}\n";
}

// Combine all services
$allServices = $services->concat(collect($additionalServices))->sortBy('service_date');

// Create attendance patterns
foreach ($members as $index => $member) {
    echo "Processing member: {$member->full_name}\n";
    
    if ($index % 2 == 0) {
        // Member attends only the first service (misses last 4+ consecutive weeks)
        $firstService = $allServices->first();
        ServiceAttendance::create([
            'service_type' => 'sunday_service',
            'service_id' => $firstService->id,
            'member_id' => $member->id,
            'attended_at' => $firstService->service_date->setTime(9, 30),
            'recorded_by' => 'Test System',
            'notes' => 'Test attendance record - attended first service only'
        ]);
        echo "  - Attended only first service (missed last 4+ weeks)\n";
        
    } else {
        // Member attends all services (regular attendee)
        foreach ($allServices as $service) {
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
foreach ($allServices as $service) {
    $attendanceCount = ServiceAttendance::where('service_type', 'sunday_service')
        ->where('service_id', $service->id)
        ->count();
    
    $service->update(['attendance_count' => $attendanceCount]);
    echo "Updated service {$service->theme}: {$attendanceCount} attendees\n";
}

echo "\nMissed attendance test scenario created successfully!\n";
echo "Now test the SMS notification system:\n";
echo "1. Run: php artisan attendance:check-notifications --dry-run\n";
echo "2. Go to: http://127.0.0.1:8000/attendance/statistics\n";
echo "3. Click 'Preview' to see which members would get SMS\n";



