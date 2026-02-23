<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProcessAgedOutChildren extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-aged-out-children';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transition children aged 21 and above to independent members';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ageLimit = config('membership.child_max_age', 21);
        $this->info("Checking for children aged {$ageLimit} and above...");

        $agedOutChildren = \App\Models\Child::all()->filter(function ($child) use ($ageLimit) {
            return $child->getAge() >= $ageLimit;
        });

        if ($agedOutChildren->isEmpty()) {
            $this->info("No children found for transition.");
            return;
        }

        $this->info("Found " . $agedOutChildren->count() . " children to transition.");

        foreach ($agedOutChildren as $child) {
            $this->processTransition($child);
        }

        $this->info("Transition process completed.");
    }

    protected function processTransition($child)
    {
        \DB::beginTransaction();
        try {
            $this->info("Transitioning child: {$child->full_name}");

            // Create Member record
            $memberData = [
                'member_id' => \App\Models\Member::generateMemberId(),
                'full_name' => $child->full_name,
                'gender' => $child->gender,
                'date_of_birth' => $child->date_of_birth,
                'biometric_enroll_id' => $child->biometric_enroll_id,
                'member_type' => 'independent',
                'membership_type' => 'permanent',
                // Copy residence/tribe from parent if available
                'region' => $child->member->region ?? null,
                'district' => $child->member->district ?? null,
                'ward' => $child->member->ward ?? null,
                'street' => $child->member->street ?? null,
                'address' => $child->member->address ?? null,
                'tribe' => $child->member->tribe ?? null,
                'phone_number' => $child->parent_phone ?? ($child->member->phone_number ?? '0000000000'),
                'marital_status' => 'single',
            ];

            $member = \App\Models\Member::create($memberData);

            // Reassign attendance records
            \App\Models\ServiceAttendance::where('child_id', $child->id)
                ->update([
                    'member_id' => $member->id,
                    'child_id' => null
                ]);

            // Notify Secretary
            $secretaries = \App\Models\User::where('role', 'secretary')->get();
            if ($secretaries->isNotEmpty()) {
                \Notification::send($secretaries, new \App\Notifications\ChildTransitionNotification($member, $child));
            }

            // Delete child record
            $child->delete();

            \DB::commit();
            $this->info("Successfully transitioned {$child->full_name} to Member ID: {$member->member_id}");
        } catch (\Exception $e) {
            \DB::rollBack();
            $this->error("Failed to transition child {$child->full_name}: " . $e->getMessage());
            \Log::error("Child transition failure", [
                'child_id' => $child->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
