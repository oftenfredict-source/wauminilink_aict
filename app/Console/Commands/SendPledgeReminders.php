<?php

namespace App\Console\Commands;

use App\Models\Pledge;
use App\Models\Member;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendPledgeReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pledges:send-reminders {--dry-run : Run without sending actual SMS}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS reminders to members with active pledges (every 2 days)';

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
        $this->info('Starting pledge reminder check...');
        
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->warn('DRY RUN MODE: No SMS will be sent');
        }

        // Get active pledges that need reminders
        $pledges = $this->getPledgesNeedingReminders();

        if ($pledges->isEmpty()) {
            $this->info('No pledges need reminders at this time.');
            return 0;
        }

        $this->info("Found {$pledges->count()} pledge(s) needing reminders.");

        $sentCount = 0;
        $failedCount = 0;

        foreach ($pledges as $pledge) {
            $member = $pledge->member;
            
            // Check if member has valid phone number
            if (empty($member->phone_number)) {
                $this->warn("Skipping pledge #{$pledge->id} - Member {$member->full_name} has no phone number");
                continue;
            }

            $remainingAmount = $pledge->remaining_amount;
            $pledgeType = $pledge->pledge_type ?? 'general';
            $dueDate = $pledge->due_date ? $pledge->due_date->format('Y-m-d') : 'Hakuna tarehe maalum';

            $this->line("Processing pledge #{$pledge->id} for {$member->full_name}...");
            $this->line("  Type: {$pledgeType}");
            $this->line("  Remaining: TZS " . number_format($remainingAmount, 0));
            $this->line("  Due Date: " . ($pledge->due_date ? $pledge->due_date->format('d/m/Y') : 'Not set'));

            if ($dryRun) {
                $this->info("  [DRY RUN] Would send SMS to {$member->phone_number}");
                $sentCount++;
            } else {
                // Send SMS reminder
                $success = $this->smsService->sendPledgeReminderNotification(
                    $member->phone_number,
                    $member->full_name,
                    $pledgeType,
                    $remainingAmount,
                    $dueDate
                );

                if ($success) {
                    // Update last reminder sent timestamp
                    $pledge->last_reminder_sent_at = now();
                    $pledge->save();
                    
                    $this->info("  ✓ SMS sent successfully to {$member->phone_number}");
                    $sentCount++;
                    
                    Log::info("Pledge reminder sent", [
                        'pledge_id' => $pledge->id,
                        'member_id' => $member->id,
                        'member_name' => $member->full_name,
                        'phone' => $member->phone_number,
                        'remaining_amount' => $remainingAmount,
                        'due_date' => $dueDate
                    ]);
                } else {
                    $this->error("  ✗ Failed to send SMS to {$member->phone_number}");
                    $failedCount++;
                    
                    Log::error("Pledge reminder failed", [
                        'pledge_id' => $pledge->id,
                        'member_id' => $member->id,
                        'member_name' => $member->full_name,
                        'phone' => $member->phone_number
                    ]);
                }
            }
        }

        $this->newLine();
        $this->info("Summary:");
        $this->info("  Total processed: {$pledges->count()}");
        $this->info("  Successfully sent: {$sentCount}");
        
        if ($failedCount > 0) {
            $this->warn("  Failed: {$failedCount}");
        }

        return 0;
    }

    /**
     * Get pledges that need reminders
     * Criteria:
     * - Status is 'active'
     * - Approval status is 'approved'
     * - Has remaining amount (not fully paid)
     * - Member has phone number
     * - Last reminder was sent more than 2 days ago (or never sent)
     * - If has due_date, it should not be in the past (or we can still remind for overdue)
     */
    private function getPledgesNeedingReminders()
    {
        $twoDaysAgo = Carbon::now()->subDays(2);

        return Pledge::with('member')
            ->where('status', 'active')
            ->where('approval_status', 'approved')
            ->whereHas('member', function ($query) {
                $query->whereNotNull('phone_number')
                      ->where('phone_number', '!=', '');
            })
            ->where(function ($query) use ($twoDaysAgo) {
                $query->whereNull('last_reminder_sent_at')
                      ->orWhere('last_reminder_sent_at', '<=', $twoDaysAgo);
            })
            ->whereRaw('pledge_amount > amount_paid') // Has remaining amount
            ->get()
            ->filter(function ($pledge) {
                // Double-check remaining amount
                return $pledge->remaining_amount > 0;
            });
    }
}
