<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\ServiceAttendance;
use App\Models\SundayService;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckAttendanceNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:check-notifications {--dry-run : Run without sending actual SMS}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for members who haven\'t attended church for 4 consecutive weeks and send SMS notifications';

    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        parent::__construct();
        $this->smsService = $smsService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting attendance notification check...');
        
        $dryRun = $this->option('dry-run');
        if ($dryRun) {
            $this->warn('DRY RUN MODE - No SMS will be sent');
        }

        // Get all active members
        $members = Member::where('membership_type', 'permanent')
            ->whereNotNull('phone_number')
            ->where('phone_number', '!=', '')
            ->get();

        $this->info("Checking {$members->count()} members for missed attendance...");

        $notificationsSent = 0;
        $membersNotified = [];

        foreach ($members as $member) {
            if ($this->hasMissedFourConsecutiveWeeks($member)) {
                $this->line("Member {$member->full_name} ({$member->member_id}) has missed 4+ consecutive weeks");
                
                if (!$dryRun) {
                    try {
                        $this->sendMissedAttendanceSms($member);
                        $notificationsSent++;
                        $membersNotified[] = $member->full_name;
                        
                        Log::info("Attendance notification sent to {$member->full_name} ({$member->member_id})");
                    } catch (\Exception $e) {
                        $this->error("Failed to send SMS to {$member->full_name}: " . $e->getMessage());
                        Log::error("Failed to send attendance notification to {$member->full_name}: " . $e->getMessage());
                    }
                } else {
                    $this->line("Would send SMS to: {$member->full_name} ({$member->phone_number})");
                    $notificationsSent++;
                    $membersNotified[] = $member->full_name;
                }
            }
        }

        if ($notificationsSent > 0) {
            $this->info("✅ Sent {$notificationsSent} attendance notifications");
            $this->table(
                ['Member Name', 'Phone Number'],
                collect($membersNotified)->map(function($name) use ($members) {
                    $member = $members->firstWhere('full_name', $name);
                    return [$name, $member ? $member->phone_number : 'N/A'];
                })
            );
        } else {
            $this->info("✅ No members need attendance notifications at this time");
        }

        $this->info('Attendance notification check completed.');
    }

    /**
     * Check if a member has missed 4 consecutive weeks
     */
    private function hasMissedFourConsecutiveWeeks(Member $member): bool
    {
        // Get the last 4 weeks of Sunday services (including the 4th week)
        $fiveWeeksAgo = Carbon::now()->subWeeks(5)->startOfWeek();
        $lastSunday = Carbon::now()->previous(Carbon::SUNDAY);
        
        // Get all Sunday services in the last 5 weeks to ensure we have 4 consecutive
        $recentServices = SundayService::whereBetween('service_date', [$fiveWeeksAgo, $lastSunday])
            ->orderBy('service_date', 'desc')
            ->get();

        if ($recentServices->count() < 4) {
            // Not enough services to determine 4 consecutive weeks
            return false;
        }

        // Get member's attendance for these services
        $attendedServices = ServiceAttendance::where('member_id', $member->id)
            ->where('service_type', 'sunday_service')
            ->whereIn('service_id', $recentServices->pluck('id'))
            ->pluck('service_id')
            ->toArray();

        // Check if they missed the last 4 consecutive services
        $lastFourServices = $recentServices->take(4);
        $missedCount = 0;

        foreach ($lastFourServices as $service) {
            if (!in_array($service->id, $attendedServices)) {
                $missedCount++;
            } else {
                // If they attended any service in the last 4, they haven't missed 4 consecutive
                break;
            }
        }

        return $missedCount >= 4;
    }

    /**
     * Send missed attendance SMS to member
     */
    private function sendMissedAttendanceSms(Member $member): void
    {
        $message = $this->getMissedAttendanceMessage($member);
        
        $this->smsService->send(
            $member->phone_number,
            $message
        );
    }

    /**
     * Get the SMS message for missed attendance
     */
    private function getMissedAttendanceMessage(Member $member): string
    {
        $memberName = $member->full_name;
        
        return "Shalom {$memberName}, ni muda sasa hatujakuona kanisani. Tunaendelea kukuombea, tukitumaini utaungana nasi tena karibuni. Kumbuka, wewe ni sehemu muhimu ya familia ya Mungu. WAEBRANIA 10:25";
    }
}
