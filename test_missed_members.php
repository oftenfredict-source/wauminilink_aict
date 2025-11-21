<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Member;
use App\Models\ServiceAttendance;
use App\Models\SundayService;

echo "Testing getMembersWithMissedAttendance method...\n";

try {
    $members = Member::where('membership_type', 'permanent')
        ->whereNotNull('phone_number')
        ->where('phone_number', '!=', '')
        ->get();
        
    echo "Found {$members->count()} members\n";
    
    $membersWithMissedAttendance = [];
    
    foreach ($members as $member) {
        echo "Checking member: {$member->full_name}\n";
        
        // Test the hasMissedFourConsecutiveWeeks method
        $fiveWeeksAgo = now()->subWeeks(5)->startOfWeek();
        $lastSunday = now()->previous(\Carbon\Carbon::SUNDAY);
        
        echo "  Date range: {$fiveWeeksAgo} to {$lastSunday}\n";
        
        $recentServices = SundayService::whereBetween('service_date', [$fiveWeeksAgo, $lastSunday])
            ->orderBy('service_date', 'desc')
            ->get();
            
        echo "  Recent services: {$recentServices->count()}\n";
        
        if ($recentServices->count() < 4) {
            echo "  Not enough services\n";
            continue;
        }
        
        $attendedServices = ServiceAttendance::where('member_id', $member->id)
            ->where('service_type', 'sunday_service')
            ->whereIn('service_id', $recentServices->pluck('id'))
            ->pluck('service_id')
            ->toArray();
            
        echo "  Attended services: " . implode(', ', $attendedServices) . "\n";
        
        $lastFourServices = $recentServices->take(4);
        $missedCount = 0;
        
        foreach ($lastFourServices as $service) {
            if (!in_array($service->id, $attendedServices)) {
                $missedCount++;
            } else {
                break;
            }
        }
        
        echo "  Missed count: {$missedCount}\n";
        
        if ($missedCount >= 4) {
            echo "  SHOULD GET SMS\n";
            
            $lastAttendance = ServiceAttendance::where('member_id', $member->id)
                ->where('service_type', 'sunday_service')
                ->orderBy('attended_at', 'desc')
                ->first();
                
            $membersWithMissedAttendance[] = [
                'id' => $member->id,
                'name' => $member->full_name,
                'member_id' => $member->member_id,
                'phone' => $member->phone_number,
                'last_attendance' => $lastAttendance ? $lastAttendance->attended_at->format('M d, Y') : 'Never',
                'weeks_missed' => $missedCount
            ];
        } else {
            echo "  No SMS needed\n";
        }
    }
    
    echo "\nResult:\n";
    echo json_encode([
        'success' => true,
        'members' => $membersWithMissedAttendance,
        'count' => count($membersWithMissedAttendance)
    ], JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}



