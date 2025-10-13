<?php

namespace App\Services;

use App\Models\Member;
use App\Models\SpecialEvent;
use App\Models\Celebration;
use App\Notifications\EventNotification;
use App\Notifications\CelebrationNotification;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send event notifications to selected members
     */
    public function sendEventNotifications(SpecialEvent $event, $memberIds = null, $type = 'created')
    {
        try {
            // If no specific members selected, send to all members with email
            if (!$memberIds || empty($memberIds)) {
                $members = Member::whereNotNull('email')
                    ->where('email', '!=', '')
                    ->get();
            } else {
                $members = Member::whereIn('id', $memberIds)
                    ->whereNotNull('email')
                    ->where('email', '!=', '')
                    ->get();
            }

            Log::info('Notification service called', [
                'event_id' => $event->id,
                'member_ids' => $memberIds,
                'members_found' => $members->count(),
                'members_data' => $members->pluck('id', 'email')->toArray()
            ]);

            $notification = new EventNotification($event, $type);
            $sentCount = 0;

            foreach ($members as $member) {
                try {
                    $member->notify($notification);
                    $sentCount++;
                    Log::info('Notification sent to member', ['member_id' => $member->id, 'email' => $member->email]);
                } catch (\Exception $e) {
                    Log::error('Failed to send event notification to member ' . $member->id . ': ' . $e->getMessage());
                }
            }

            Log::info("Event notifications sent", [
                'event_id' => $event->id,
                'event_title' => $event->title,
                'type' => $type,
                'sent_count' => $sentCount,
                'total_members' => $members->count()
            ]);

            return [
                'success' => true,
                'sent_count' => $sentCount,
                'total_members' => $members->count(),
                'message' => "Notifications sent to {$sentCount} members"
            ];

        } catch (\Exception $e) {
            Log::error('Failed to send event notifications: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to send notifications: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send celebration notifications to selected members
     */
    public function sendCelebrationNotifications(Celebration $celebration, $memberIds = null, $type = 'created')
    {
        try {
            // If no specific members selected, send to all members with email
            if (!$memberIds) {
                $members = Member::whereNotNull('email')
                    ->where('email', '!=', '')
                    ->get();
            } else {
                $members = Member::whereIn('id', $memberIds)
                    ->whereNotNull('email')
                    ->where('email', '!=', '')
                    ->get();
            }

            $notification = new CelebrationNotification($celebration, $type);
            $sentCount = 0;

            foreach ($members as $member) {
                try {
                    $member->notify($notification);
                    $sentCount++;
                } catch (\Exception $e) {
                    Log::error('Failed to send celebration notification to member ' . $member->id . ': ' . $e->getMessage());
                }
            }

            Log::info("Celebration notifications sent", [
                'celebration_id' => $celebration->id,
                'celebration_type' => $celebration->celebration_type,
                'type' => $type,
                'sent_count' => $sentCount,
                'total_members' => $members->count()
            ]);

            return [
                'success' => true,
                'sent_count' => $sentCount,
                'total_members' => $members->count(),
                'message' => "Notifications sent to {$sentCount} members"
            ];

        } catch (\Exception $e) {
            Log::error('Failed to send celebration notifications: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to send notifications: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get members for notification selection
     */
    public function getMembersForNotification()
    {
        return Member::select('id', 'full_name', 'email', 'phone_number')
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->orderBy('full_name')
            ->get();
    }

    /**
     * Send reminder notifications for upcoming events
     */
    public function sendEventReminders($daysAhead = 1)
    {
        $upcomingEvents = SpecialEvent::whereDate('event_date', now()->addDays($daysAhead))
            ->get();

        $totalSent = 0;
        foreach ($upcomingEvents as $event) {
            $result = $this->sendEventNotifications($event, null, 'reminder');
            $totalSent += $result['sent_count'];
        }

        return $totalSent;
    }

    /**
     * Send reminder notifications for upcoming celebrations
     */
    public function sendCelebrationReminders($daysAhead = 1)
    {
        $upcomingCelebrations = Celebration::whereDate('celebration_date', now()->addDays($daysAhead))
            ->get();

        $totalSent = 0;
        foreach ($upcomingCelebrations as $celebration) {
            $result = $this->sendCelebrationNotifications($celebration, null, 'reminder');
            $totalSent += $result['sent_count'];
        }

        return $totalSent;
    }
}
