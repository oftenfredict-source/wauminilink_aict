<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Member;
use App\Models\SundayService;
use App\Models\ServiceAttendance;
use Carbon\Carbon;

echo "Debugging attendance logic...\n";

// Get all members
$members = Member::where('membership_type', 'permanent')
    ->whereNotNull('phone_number')
    ->where('phone_number', '!=', '')
    ->get();

echo "Found {$members->count()} members\n";

// Debug the logic for each member
foreach ($members as $member) {
    echo "\n=== Debugging member: {$member->full_name} ===\n";
    
    // Get the last 4 weeks of Sunday services
    $fourWeeksAgo = Carbon::now()->subWeeks(4)->startOfWeek();
    $lastSunday = Carbon::now()->previous(Carbon::SUNDAY);
    
    echo "Four weeks ago: {$fourWeeksAgo}\n";
    echo "Last Sunday: {$lastSunday}\n";
    
    // Get all Sunday services in the last 4 weeks
    $recentServices = SundayService::whereBetween('service_date', [$fourWeeksAgo, $lastSunday])
        ->orderBy('service_date', 'desc')
        ->get();
    
    echo "Recent services found: {$recentServices->count()}\n";
    foreach ($recentServices as $service) {
        echo "  - {$service->service_date} - {$service->theme}\n";
    }
    
    if ($recentServices->count() < 4) {
        echo "Not enough services to determine 4 consecutive weeks\n";
        continue;
    }
    
    // Get member's attendance for these services
    $attendedServices = ServiceAttendance::where('member_id', $member->id)
        ->where('service_type', 'sunday_service')
        ->whereIn('service_id', $recentServices->pluck('id'))
        ->pluck('service_id')
        ->toArray();
    
    echo "Member attended services: " . implode(', ', $attendedServices) . "\n";
    
    // Check if they missed the last 4 consecutive services
    $lastFourServices = $recentServices->take(4);
    $missedCount = 0;
    
    echo "Checking last 4 services:\n";
    foreach ($lastFourServices as $service) {
        $attended = in_array($service->id, $attendedServices);
        echo "  - {$service->service_date} - {$service->theme}: " . ($attended ? 'ATTENDED' : 'MISSED') . "\n";
        
        if (!$attended) {
            $missedCount++;
        } else {
            // If they attended any service in the last 4, they haven't missed 4 consecutive
            break;
        }
    }
    
    echo "Missed count: {$missedCount}\n";
    echo "Should get SMS: " . ($missedCount >= 4 ? 'YES' : 'NO') . "\n";
}



