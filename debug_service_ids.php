<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Member;
use App\Models\ServiceAttendance;
use App\Models\SundayService;

echo "Debugging service IDs...\n";

// Get all Sunday services
$services = SundayService::all();
echo "Sunday Services:\n";
foreach ($services as $service) {
    echo "  ID: {$service->id} - {$service->service_date} - {$service->theme}\n";
}

echo "\nAttendance Records:\n";
$attendances = ServiceAttendance::where('member_id', 64)->get();
foreach ($attendances as $attendance) {
    echo "  Member: {$attendance->member_id} - Service ID: {$attendance->service_id} - Type: {$attendance->service_type}\n";
}

echo "\nTesting relationship...\n";
$attendance = ServiceAttendance::where('member_id', 64)->first();
if ($attendance) {
    echo "Testing attendance ID: {$attendance->id}\n";
    echo "Service ID: {$attendance->service_id}\n";
    echo "Service Type: {$attendance->service_type}\n";
    
    $sundayService = $attendance->sundayService;
    if ($sundayService) {
        echo "Sunday Service found: {$sundayService->theme}\n";
    } else {
        echo "Sunday Service not found\n";
    }
    
    $service = $attendance->getService();
    if ($service) {
        echo "Service found via getService(): {$service->theme}\n";
    } else {
        echo "Service not found via getService()\n";
    }
}



