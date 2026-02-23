<?php

namespace App\Http\Controllers;

use App\Models\Tithe;
use App\Models\Offering;
use App\Models\Donation;
use App\Models\Expense;
use App\Models\Budget;
use App\Models\Pledge;
use App\Models\PledgePayment;
use App\Models\Member;
use App\Models\Leader;
use App\Models\Announcement;
use App\Models\AnnouncementView;
use App\Models\SpecialEvent;
use App\Models\Celebration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PastorDashboardController extends Controller
{
    public function __construct()
    {
        // Middleware is applied at route level
    }

    private function checkPastorPermission()
    {
        if (!auth()->check()) {
            abort(401, 'Please log in to access this page.');
        }

        $user = auth()->user();

        // Allow admin
        if ($user->isAdmin()) {
            return;
        }

        // Check for active pastor position in database
        if ($user->member_id) {
            $member = $user->member;
            if ($member) {
                $activePastorPosition = Leader::where('member_id', $member->id)
                    ->whereIn('position', ['pastor', 'assistant_pastor'])
                    ->where('is_active', true)
                    ->where(function ($query) {
                        $query->whereNull('end_date')
                            ->orWhere('end_date', '>=', now()->toDateString());
                    })
                    ->first();

                if ($activePastorPosition) {
                    return; // Has active pastor position
                }
            }
        }

        // No active position and not admin - deny access
        abort(403, 'Unauthorized access. Only active Pastors can access this dashboard.');
    }

    /**
     * Display the Pastor dashboard
     */
    public function index()
    {
        $this->checkPastorPermission();

        $today = Carbon::today();
        $currentMonth = Carbon::now()->startOfMonth();
        $currentYear = Carbon::now()->year;

        // Get financial summary for current month (only approved records)
        $monthlyTithes = Tithe::whereMonth('tithe_date', $currentMonth->month)
            ->whereYear('tithe_date', $currentYear)
            ->where('approval_status', 'approved')
            ->sum('amount');

        $monthlyOfferings = Offering::whereMonth('offering_date', $currentMonth->month)
            ->whereYear('offering_date', $currentYear)
            ->where('approval_status', 'approved')
            ->sum('amount');

        $monthlyDonations = Donation::whereMonth('donation_date', $currentMonth->month)
            ->whereYear('donation_date', $currentYear)
            ->where('approval_status', 'approved')
            ->sum('amount');

        $monthlyExpenses = Expense::whereMonth('expense_date', $currentMonth->month)
            ->whereYear('expense_date', $currentYear)
            ->where('status', 'paid')
            ->where('approval_status', 'approved')
            ->sum('amount');

        $monthlyPledges = Pledge::whereMonth('pledge_date', $currentMonth->month)
            ->whereYear('pledge_date', $currentYear)
            ->where('approval_status', 'approved')
            ->sum('pledge_amount');

        $totalIncome = $monthlyTithes + $monthlyOfferings + $monthlyDonations + $monthlyPledges;
        $netIncome = $totalIncome - $monthlyExpenses;

        // Get total members count
        $totalMembers = Member::count();

        // Get logged-in pastor's information
        $user = auth()->user();
        $pastor = null;
        $pastorMember = null;

        if ($user && $user->member_id) {
            // Get the logged-in pastor's leader record
            $pastor = Leader::with('member')
                ->where('member_id', $user->member_id)
                ->where('position', 'pastor')
                ->where('is_active', true)
                ->first();

            // Get member information
            $pastorMember = $user->member;
        }

        // Fallback: If no logged-in pastor found, get first active pastor
        if (!$pastor) {
            $pastor = Leader::with('member')
                ->where('position', 'pastor')
                ->where('is_active', true)
                ->get()
                ->filter(function ($leader) {
                    return $leader->member !== null;
                })
                ->first();
        }

        // Get pastor's duties/responsibilities
        $pastorDuties = [];
        if ($pastor) {
            $pastorDuties = [
                'position' => $pastor->position_display,
                'description' => $pastor->description,
                'appointment_date' => $pastor->appointment_date,
                'end_date' => $pastor->end_date,
                'notes' => $pastor->notes,
            ];
        }

        // Get pending amount (including pledge payments)
        $pendingAmount = Tithe::where('approval_status', 'pending')
            ->whereDate('tithe_date', $today)
            ->sum('amount') +
            Offering::where('approval_status', 'pending')
                ->whereDate('offering_date', $today)
                ->sum('amount') +
            Donation::where('approval_status', 'pending')
                ->whereDate('donation_date', $today)
                ->sum('amount') +
            Pledge::where('approval_status', 'pending')
                ->whereDate('pledge_date', $today)
                ->sum('pledge_amount') +
            PledgePayment::where('approval_status', 'pending')
                ->whereDate('payment_date', $today)
                ->sum('amount');

        // Get member portal data if pastor has member record
        $memberInfo = null;
        $financialSummary = null;
        $announcements = null;
        $unreadCount = 0;
        $leadershipData = null;

        if ($pastorMember) {
            // Get member information
            $memberInfo = [
                'member_id' => $pastorMember->member_id,
                'full_name' => $pastorMember->full_name,
                'email' => $pastorMember->email,
                'phone_number' => $pastorMember->phone_number,
                'date_of_birth' => $pastorMember->date_of_birth,
                'gender' => $pastorMember->gender,
                'membership_type' => $pastorMember->membership_type,
                'member_type' => $pastorMember->member_type,
                'profession' => $pastorMember->profession,
                'address' => $pastorMember->address,
                'region' => $pastorMember->region,
                'district' => $pastorMember->district,
            ];

            // Get financial summary
            $currentYear = Carbon::now()->year;
            $currentMonth = Carbon::now()->month;

            $financialSummary = [
                'total_tithes' => Tithe::where('member_id', $pastorMember->id)->approved()->sum('amount'),
                'monthly_tithes' => Tithe::where('member_id', $pastorMember->id)->approved()
                    ->whereYear('tithe_date', $currentYear)->whereMonth('tithe_date', $currentMonth)->sum('amount'),
                'total_offerings' => Offering::where('member_id', $pastorMember->id)->approved()->sum('amount'),
                'monthly_offerings' => Offering::where('member_id', $pastorMember->id)->approved()
                    ->whereYear('offering_date', $currentYear)->whereMonth('offering_date', $currentMonth)->sum('amount'),
                'total_donations' => Donation::where('member_id', $pastorMember->id)->approved()->sum('amount'),
                'monthly_donations' => Donation::where('member_id', $pastorMember->id)->approved()
                    ->whereYear('donation_date', $currentYear)->whereMonth('donation_date', $currentMonth)->sum('amount'),
                'total_pledges' => Pledge::where('member_id', $pastorMember->id)->sum('pledge_amount'),
                'total_pledge_payments' => PledgePayment::whereHas('pledge', function ($q) use ($pastorMember) {
                    $q->where('member_id', $pastorMember->id);
                })->approved()->sum('amount'),
                'remaining_pledges' => 0,
            ];
            $financialSummary['remaining_pledges'] = $financialSummary['total_pledges'] - $financialSummary['total_pledge_payments'];

            // Get announcements
            $now = Carbon::now();
            $next30Days = $now->copy()->addDays(30);
            $announcementsList = Announcement::active()
                ->orderBy('is_pinned', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
            $viewedAnnouncementIds = AnnouncementView::where('member_id', $pastorMember->id)
                ->whereIn('announcement_id', $announcementsList->pluck('id'))
                ->pluck('announcement_id')
                ->toArray();
            foreach ($announcementsList as $announcement) {
                $announcement->is_unread = !in_array($announcement->id, $viewedAnnouncementIds);
            }
            $activeAnnouncements = Announcement::active()->pluck('id');
            $unreadCount = $activeAnnouncements->diff($viewedAnnouncementIds)->count();

            $announcements = [
                'announcements' => $announcementsList,
                'events' => SpecialEvent::whereDate('event_date', '>=', $now->toDateString())
                    ->whereDate('event_date', '<=', $next30Days->toDateString())
                    ->orderBy('event_date')->get(),
                'celebrations' => Celebration::whereDate('celebration_date', '>=', $now->toDateString())
                    ->whereDate('celebration_date', '<=', $next30Days->toDateString())
                    ->orderBy('celebration_date')->get(),
            ];

            // Get leadership data
            $memberPositions = $pastorMember->activeLeadershipPositions()
                ->where(function ($query) {
                    $query->whereNull('end_date')->orWhere('end_date', '>=', now()->toDateString());
                })->get();
            $leadershipData = [
                'member_positions' => $memberPositions,
                'has_leadership_position' => $memberPositions->count() > 0
            ];
        }

        return view('pastor.dashboard', compact(
            'totalIncome',
            'netIncome',
            'totalMembers',
            'pastor',
            'pastorMember',
            'pastorDuties',
            'memberInfo',
            'financialSummary',
            'announcements',
            'unreadCount',
            'leadershipData',
            'today'
        ));
    }
}