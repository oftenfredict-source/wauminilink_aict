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
        if (!$user->isPastor() && !$user->isAdmin()) {
            abort(403, 'Unauthorized access. Only Pastors can access this dashboard.');
        }
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
        
        // Get pending records for today
        $pendingTithes = Tithe::with(['member', 'approver'])
            ->where('approval_status', 'pending')
            ->whereDate('tithe_date', $today)
            ->count();
            
        $pendingOfferings = Offering::with(['member', 'approver'])
            ->where('approval_status', 'pending')
            ->whereDate('offering_date', $today)
            ->count();
            
        $pendingDonations = Donation::with(['member', 'approver'])
            ->where('approval_status', 'pending')
            ->whereDate('donation_date', $today)
            ->count();
            
        $pendingExpenses = Expense::with(['budget', 'approver'])
            ->where('approval_status', 'pending')
            ->whereDate('expense_date', $today)
            ->count();
            
        $pendingBudgets = Budget::with(['approver'])
            ->where('approval_status', 'pending')
            ->whereDate('created_at', $today)
            ->count();
            
        $pendingPledges = Pledge::with(['member', 'approver'])
            ->where('approval_status', 'pending')
            ->whereDate('pledge_date', $today)
            ->count();
            
        // Get pending pledge payments (actual payments that need approval)
        $pendingPledgePayments = PledgePayment::with(['pledge.member', 'approver'])
            ->where('approval_status', 'pending')
            ->whereDate('payment_date', $today)
            ->count();

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
        
        // Get recent approvals (last 7 days)
        $recentApprovals = collect();
        
        $recentApprovals = $recentApprovals->merge(
            Tithe::with(['member', 'approver'])
                ->where('approval_status', 'approved')
                ->where('approved_at', '>=', Carbon::now()->subDays(7))
                ->get()
                ->map(function($item) {
                    $item->type = 'Tithe';
                    $item->date = $item->tithe_date;
                    return $item;
                })
        );
        
        $recentApprovals = $recentApprovals->merge(
            Offering::with(['member', 'approver'])
                ->where('approval_status', 'approved')
                ->where('approved_at', '>=', Carbon::now()->subDays(7))
                ->get()
                ->map(function($item) {
                    $item->type = 'Offering';
                    $item->date = $item->offering_date;
                    return $item;
                })
        );
        
        $recentApprovals = $recentApprovals->merge(
            Donation::with(['member', 'approver'])
                ->where('approval_status', 'approved')
                ->where('approved_at', '>=', Carbon::now()->subDays(7))
                ->get()
                ->map(function($item) {
                    $item->type = 'Donation';
                    $item->date = $item->donation_date;
                    return $item;
                })
        );
        
        $recentApprovals = $recentApprovals->merge(
            Pledge::with(['member', 'approver'])
                ->where('approval_status', 'approved')
                ->where('approved_at', '>=', Carbon::now()->subDays(7))
                ->get()
                ->map(function($item) {
                    $item->type = 'Pledge';
                    $item->date = $item->pledge_date;
                    return $item;
                })
        );
        
        // Add pledge payments to recent approvals
        $recentApprovals = $recentApprovals->merge(
            PledgePayment::with(['pledge.member', 'approver'])
                ->where('approval_status', 'approved')
                ->where('approved_at', '>=', Carbon::now()->subDays(7))
                ->get()
                ->map(function($item) {
                    $item->type = 'Pledge Payment';
                    $item->date = $item->payment_date;
                    $item->amount = $item->amount;
                    return $item;
                })
        );

        $recentApprovals = $recentApprovals->sortByDesc('approved_at')->take(10);
        
        // Process recent approvals to get approver display names from Leader/Member relationship
        $recentApprovals = $recentApprovals->map(function($record) {
            if ($record->approver) {
                $approverUser = $record->approver;
                $approverDisplayName = $approverUser->name; // Default to user's name
                
                // Try to find the member by email match
                $member = Member::where('email', $approverUser->email)->first();
                
                if ($member) {
                    // Check if this member is assigned as a pastor
                    $pastorLeader = Leader::with('member')
                        ->where('member_id', $member->id)
                        ->where('position', 'pastor')
                        ->where('is_active', true)
                        ->first();
                    
                    if ($pastorLeader && $pastorLeader->member) {
                        $approverDisplayName = $pastorLeader->member->full_name;
                    } else {
                        // If not assigned as pastor but member exists, use member's name
                        $approverDisplayName = $member->full_name;
                    }
                } else {
                    // If no member found by email, check if user is a pastor and get active pastor
                    if ($approverUser->isPastor()) {
                        $activePastor = Leader::with('member')
                            ->where('position', 'pastor')
                            ->where('is_active', true)
                            ->first();
                        
                        if ($activePastor && $activePastor->member) {
                            $approverDisplayName = $activePastor->member->full_name;
                        }
                    }
                }
                
                // Add the display name as an attribute
                $record->approver_display_name = $approverDisplayName;
            } else {
                $record->approver_display_name = 'System';
            }
            
            return $record;
        });
        
        // Get total members count
        $totalMembers = Member::count();
        
        // Get pastor information from leaders table
        // Filter out leaders without valid member relationships
        $pastor = Leader::with('member')
            ->where('position', 'pastor')
            ->where('is_active', true)
            ->get()
            ->filter(function($leader) {
                return $leader->member !== null;
            })
            ->first();
        
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

        return view('pastor.dashboard', compact(
            'pendingTithes',
            'pendingOfferings', 
            'pendingDonations',
            'pendingExpenses',
            'pendingBudgets',
            'pendingPledges',
            'pendingPledgePayments',
            'pendingAmount',
            'monthlyTithes',
            'monthlyOfferings', 
            'monthlyDonations',
            'monthlyPledges',
            'monthlyExpenses',
            'totalIncome',
            'netIncome',
            'recentApprovals',
            'totalMembers',
            'pastor',
            'today'
        ));
    }
}