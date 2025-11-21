<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Member;
use App\Models\SundayService;
use App\Models\ServiceAttendance;
use Carbon\Carbon;

echo "Creating simple missed attendance test...\n";

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

echo "Found {$members->count()} members and {$services->count()} test services\n";

// Create a simple test: some members attend only the first service, others attend all
foreach ($members as $index => $member) {
    echo "Processing member: {$member->full_name}\n";
    
    if ($index % 2 == 0) {
        // Member attends only the first service (oldest)
        $firstService = $services->first();
        ServiceAttendance::create([
            'service_type' => 'sunday_service',
            'service_id' => $firstService->id,
            'member_id' => $member->id,
            'attended_at' => $firstService->service_date->setTime(9, 30),
            'recorded_by' => 'Test System',
            'notes' => 'Test attendance record - attended first service only'
        ]);
        echo "  - Attended only first service (should miss recent services)\n";
        
    } else {
        // Member attends all services
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

echo "\nSimple test created successfully!\n";
echo "Now test the SMS notification system:\n";
echo "1. Run: php artisan attendance:check-notifications --dry-run\n";
echo "2. Go to: http://127.0.0.1:8000/attendance/statistics\n";



