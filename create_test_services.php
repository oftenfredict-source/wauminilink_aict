<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\SundayService;
use Carbon\Carbon;

echo "Creating test Sunday services...\n";

// Create Sunday service for 2 weeks ago
$service1 = SundayService::create([
    'service_date' => Carbon::now()->subWeeks(2)->previous(Carbon::SUNDAY),
    'service_type' => 'sunday_service',
    'theme' => 'Test Service 1 - 2 weeks ago',
    'preacher' => 'Pastor Test',
    'start_time' => '09:00',
    'end_time' => '11:00',
    'venue' => 'Main Sanctuary',
    'attendance_count' => 0,
    'offerings_amount' => 0,
    'scripture_readings' => 'John 3:16',
    'choir' => 'Test Choir',
    'announcements' => 'Test announcements',
    'notes' => 'Test service for SMS notification testing',
    'status' => 'completed'
]);

echo "Created service 1: {$service1->service_date} - {$service1->theme}\n";

// Create Sunday service for 1 week ago
$service2 = SundayService::create([
    'service_date' => Carbon::now()->subWeek()->previous(Carbon::SUNDAY),
    'service_type' => 'sunday_service',
    'theme' => 'Test Service 2 - 1 week ago',
    'preacher' => 'Pastor Test',
    'start_time' => '09:00',
    'end_time' => '11:00',
    'venue' => 'Main Sanctuary',
    'attendance_count' => 0,
    'offerings_amount' => 0,
    'scripture_readings' => 'Matthew 28:19',
    'choir' => 'Test Choir',
    'announcements' => 'Test announcements',
    'notes' => 'Test service for SMS notification testing',
    'status' => 'completed'
]);

echo "Created service 2: {$service2->service_date} - {$service2->theme}\n";

// Create Sunday service for this week (if it's past Sunday)
$lastSunday = Carbon::now()->previous(Carbon::SUNDAY);
if ($lastSunday->isPast()) {
    $service3 = SundayService::create([
        'service_date' => $lastSunday,
        'service_type' => 'sunday_service',
        'theme' => 'Test Service 3 - This week',
        'preacher' => 'Pastor Test',
        'start_time' => '09:00',
        'end_time' => '11:00',
        'venue' => 'Main Sanctuary',
        'attendance_count' => 0,
        'offerings_amount' => 0,
        'scripture_readings' => 'Psalm 23',
        'choir' => 'Test Choir',
        'announcements' => 'Test announcements',
        'notes' => 'Test service for SMS notification testing',
        'status' => 'completed'
    ]);
    
    echo "Created service 3: {$service3->service_date} - {$service3->theme}\n";
}

echo "\nTest services created successfully!\n";
echo "You can now test the attendance tracking and SMS notifications.\n";
echo "Go to: http://127.0.0.1:8000/attendance to record attendance\n";
echo "Go to: http://127.0.0.1:8000/attendance/statistics to test SMS notifications\n";



