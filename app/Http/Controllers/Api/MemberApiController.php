<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Tithe;
use App\Models\Offering;
use App\Models\Donation;
use App\Models\Pledge;
use App\Models\PledgePayment;
use App\Models\SpecialEvent;
use App\Models\Celebration;
use App\Models\SundayService;
use App\Models\Announcement;
use App\Models\AnnouncementView;
use App\Models\Leader;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MemberApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Get member dashboard data
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function dashboard(Request $request): JsonResponse
    {
        $user = Auth::user();

        // Ensure user is a member
        if (!$user->isMember() || !$user->member_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access. Only members can access this endpoint.'
            ], 403);
        }

        $member = $user->member;

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member record not found.'
            ], 404);
        }

        // Get member information
        $memberInfo = [
            'member_id' => $member->member_id,
            'full_name' => $member->full_name,
            'email' => $member->email,
            'phone_number' => $member->phone_number,
            'date_of_birth' => $member->date_of_birth ? $member->date_of_birth->format('Y-m-d') : null,
            'gender' => $member->gender,
            'membership_type' => $member->membership_type,
            'member_type' => $member->member_type,
            'profession' => $member->profession,
            'address' => $member->address,
            'region' => $member->region,
            'district' => $member->district,
        ];

        // Get financial summary
        $financialSummary = $this->getFinancialSummary($member);

        // Get announcements
        $announcements = $this->getAnnouncements($member);

        // Get unread announcements count
        $unreadCount = $this->getUnreadAnnouncementsCount($member);

        // Get leadership data
        $leadershipData = $this->getLeadershipData($member);

        return response()->json([
            'success' => true,
            'data' => [
                'member_info' => $memberInfo,
                'financial_summary' => $financialSummary,
                'announcements' => $announcements,
                'unread_announcements_count' => $unreadCount,
                'leadership' => $leadershipData,
            ]
        ], 200);
    }

    /**
     * Get financial summary for the member
     */
    private function getFinancialSummary($member)
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        // Get tithes - use approved scope method
        $totalTithes = Tithe::where('member_id', $member->id)
            ->approved()
            ->sum('amount');
        
        $monthlyTithes = Tithe::where('member_id', $member->id)
            ->approved()
            ->whereYear('tithe_date', $currentYear)
            ->whereMonth('tithe_date', $currentMonth)
            ->sum('amount');

        // Get offerings - use approved scope method
        $totalOfferings = Offering::where('member_id', $member->id)
            ->approved()
            ->sum('amount');
        
        $monthlyOfferings = Offering::where('member_id', $member->id)
            ->approved()
            ->whereYear('offering_date', $currentYear)
            ->whereMonth('offering_date', $currentMonth)
            ->sum('amount');

        // Get donations - use approved scope method
        $totalDonations = Donation::where('member_id', $member->id)
            ->approved()
            ->sum('amount');
        
        $monthlyDonations = Donation::where('member_id', $member->id)
            ->approved()
            ->whereYear('donation_date', $currentYear)
            ->whereMonth('donation_date', $currentMonth)
            ->sum('amount');

        // Get pledges
        $totalPledges = Pledge::where('member_id', $member->id)->sum('pledge_amount');
        $totalPledgePayments = PledgePayment::whereHas('pledge', function($query) use ($member) {
            $query->where('member_id', $member->id);
        })->approved()->sum('amount');
        $remainingPledges = max(0, $totalPledges - $totalPledgePayments);

        // Recent transactions
        $recentTithes = Tithe::where('member_id', $member->id)
            ->approved()
            ->orderBy('tithe_date', 'desc')
            ->take(5)
            ->get()
            ->map(function ($tithe) {
                return [
                    'id' => $tithe->id,
                    'amount' => $tithe->amount,
                    'date' => $tithe->tithe_date->format('Y-m-d'),
                    'type' => 'tithe',
                ];
            });

        $recentOfferings = Offering::where('member_id', $member->id)
            ->approved()
            ->orderBy('offering_date', 'desc')
            ->take(5)
            ->get()
            ->map(function ($offering) {
                return [
                    'id' => $offering->id,
                    'amount' => $offering->amount,
                    'date' => $offering->offering_date->format('Y-m-d'),
                    'type' => 'offering',
                ];
            });

        $recentDonations = Donation::where('member_id', $member->id)
            ->approved()
            ->orderBy('donation_date', 'desc')
            ->take(5)
            ->get()
            ->map(function ($donation) {
                return [
                    'id' => $donation->id,
                    'amount' => $donation->amount,
                    'date' => $donation->donation_date->format('Y-m-d'),
                    'type' => 'donation',
                ];
            });

        return [
            'total_tithes' => (float) $totalTithes,
            'monthly_tithes' => (float) $monthlyTithes,
            'total_offerings' => (float) $totalOfferings,
            'monthly_offerings' => (float) $monthlyOfferings,
            'total_donations' => (float) $totalDonations,
            'monthly_donations' => (float) $monthlyDonations,
            'total_pledges' => (float) $totalPledges,
            'total_pledge_payments' => (float) $totalPledgePayments,
            'remaining_pledges' => (float) $remainingPledges,
            'recent_transactions' => $recentTithes->concat($recentOfferings)->concat($recentDonations)
                ->sortByDesc('date')
                ->take(10)
                ->values(),
        ];
    }

    /**
     * Get announcements (upcoming events, celebrations, and church announcements)
     */
    private function getAnnouncements($member = null)
    {
        $now = Carbon::now();
        $next30Days = $now->copy()->addDays(30);

        // Get active church announcements (pinned first, then by date)
        $announcements = Announcement::active()
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($announcement) use ($member) {
                $isUnread = false;
                if ($member) {
                    $isUnread = !AnnouncementView::where('announcement_id', $announcement->id)
                        ->where('member_id', $member->id)
                        ->exists();
                }

                return [
                    'id' => $announcement->id,
                    'title' => $announcement->title,
                    'content' => $announcement->content,
                    'is_pinned' => $announcement->is_pinned,
                    'is_unread' => $isUnread,
                    'created_at' => $announcement->created_at->toISOString(),
                    'updated_at' => $announcement->updated_at->toISOString(),
                ];
            });

        // Get upcoming special events
        $events = SpecialEvent::whereDate('event_date', '>=', $now->toDateString())
            ->whereDate('event_date', '<=', $next30Days->toDateString())
            ->orderBy('event_date')
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'event_date' => $event->event_date->format('Y-m-d'),
                    'event_time' => $event->event_time,
                    'location' => $event->location,
                ];
            });

        // Get upcoming celebrations
        $celebrations = Celebration::whereDate('celebration_date', '>=', $now->toDateString())
            ->whereDate('celebration_date', '<=', $next30Days->toDateString())
            ->orderBy('celebration_date')
            ->get()
            ->map(function ($celebration) {
                return [
                    'id' => $celebration->id,
                    'member_name' => $celebration->member->full_name ?? 'N/A',
                    'celebration_type' => $celebration->celebration_type,
                    'celebration_date' => $celebration->celebration_date->format('Y-m-d'),
                    'description' => $celebration->description,
                ];
            });

        // Get upcoming Sunday services
        $sundayServices = SundayService::whereDate('service_date', '>=', $now->toDateString())
            ->whereDate('service_date', '<=', $next30Days->toDateString())
            ->orderBy('service_date')
            ->get()
            ->map(function ($service) {
                return [
                    'id' => $service->id,
                    'service_date' => $service->service_date->format('Y-m-d'),
                    'service_time' => $service->service_time,
                    'theme' => $service->theme,
                    'preacher' => $service->preacher,
                ];
            });

        return [
            'announcements' => $announcements,
            'events' => $events,
            'celebrations' => $celebrations,
            'sunday_services' => $sundayServices,
        ];
    }

    /**
     * Get leadership data for the member
     */
    private function getLeadershipData($member)
    {
        // Get all active leaders with their member information
        $allLeaders = Leader::with('member')
            ->where('is_active', true)
            ->where(function($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', now()->toDateString());
            })
            ->orderBy('position')
            ->orderBy('appointment_date', 'desc')
            ->get()
            ->map(function ($leader) {
                return [
                    'id' => $leader->id,
                    'position' => $leader->position,
                    'member_name' => $leader->member->full_name ?? 'N/A',
                    'member_phone' => $leader->member->phone_number ?? null,
                    'appointment_date' => $leader->appointment_date->format('Y-m-d'),
                    'end_date' => $leader->end_date ? $leader->end_date->format('Y-m-d') : null,
                ];
            });

        // Get current member's leadership positions
        $memberLeadershipPositions = $member->activeLeadershipPositions()
            ->where(function($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', now()->toDateString());
            })
            ->get()
            ->map(function ($position) {
                return [
                    'id' => $position->id,
                    'position' => $position->position,
                    'appointment_date' => $position->appointment_date->format('Y-m-d'),
                    'end_date' => $position->end_date ? $position->end_date->format('Y-m-d') : null,
                ];
            });

        return [
            'all_leaders' => $allLeaders,
            'member_positions' => $memberLeadershipPositions,
            'has_leadership_position' => $memberLeadershipPositions->count() > 0
        ];
    }

    /**
     * Get count of unread announcements for a member
     */
    private function getUnreadAnnouncementsCount($member)
    {
        $activeAnnouncements = Announcement::active()->pluck('id');
        
        $viewedAnnouncementIds = AnnouncementView::where('member_id', $member->id)
            ->whereIn('announcement_id', $activeAnnouncements)
            ->pluck('announcement_id');
        
        return $activeAnnouncements->diff($viewedAnnouncementIds)->count();
    }

    /**
     * Mark announcement as read
     */
    public function markAnnouncementAsRead(Request $request, $announcementId): JsonResponse
    {
        $user = Auth::user();

        if (!$user->isMember() || !$user->member_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $member = $user->member;

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member record not found.'
            ], 404);
        }

        $announcement = Announcement::find($announcementId);

        if (!$announcement) {
            return response()->json([
                'success' => false,
                'message' => 'Announcement not found.'
            ], 404);
        }

        AnnouncementView::firstOrCreate([
            'announcement_id' => $announcement->id,
            'member_id' => $member->id,
        ], [
            'viewed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Announcement marked as read.'
        ], 200);
    }
}



