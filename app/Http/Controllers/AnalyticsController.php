<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Child;
use App\Models\Tithe;
use App\Models\Offering;
use App\Models\Donation;
use App\Models\Expense;
use App\Models\ServiceAttendance;
use App\Models\SundayService;
use App\Models\SpecialEvent;
use App\Models\Celebration;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Financial Analytics
        $financialData = $this->getFinancialAnalytics();
        
        // Member Analytics
        $memberData = $this->getMemberAnalytics();
        
        // Attendance Analytics
        $attendanceData = $this->getAttendanceAnalytics();
        
        // Event Analytics
        $eventData = $this->getEventAnalytics();
        
        return view('analytics', compact(
            'financialData',
            'memberData',
            'attendanceData',
            'eventData'
        ));
    }
    
    private function getFinancialAnalytics()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        
        // Total financial summaries
        $totalTithes = Tithe::where('approval_status', 'approved')->sum('amount');
        $totalOfferings = Offering::where('approval_status', 'approved')->sum('amount');
        $totalDonations = Donation::where('approval_status', 'approved')->sum('amount');
        $totalExpenses = Expense::where('approval_status', 'approved')->sum('amount');
        $netIncome = ($totalTithes + $totalOfferings + $totalDonations) - $totalExpenses;
        
        // Current month totals
        $monthlyTithes = Tithe::whereYear('tithe_date', $currentYear)
            ->whereMonth('tithe_date', $currentMonth)
            ->where('approval_status', 'approved')
            ->sum('amount');
            
        $monthlyOfferings = Offering::whereYear('offering_date', $currentYear)
            ->whereMonth('offering_date', $currentMonth)
            ->where('approval_status', 'approved')
            ->sum('amount');
            
        $monthlyDonations = Donation::whereYear('donation_date', $currentYear)
            ->whereMonth('donation_date', $currentMonth)
            ->where('approval_status', 'approved')
            ->sum('amount');
            
        $monthlyExpenses = Expense::whereYear('expense_date', $currentYear)
            ->whereMonth('expense_date', $currentMonth)
            ->where('approval_status', 'approved')
            ->sum('amount');
        
        // Monthly trends (last 12 months)
        $monthlyFinancials = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->format('M Y');
            
            $tithes = Tithe::whereYear('tithe_date', $date->year)
                ->whereMonth('tithe_date', $date->month)
                ->where('approval_status', 'approved')
                ->sum('amount');
                
            $offerings = Offering::whereYear('offering_date', $date->year)
                ->whereMonth('offering_date', $date->month)
                ->where('approval_status', 'approved')
                ->sum('amount');
                
            $donations = Donation::whereYear('donation_date', $date->year)
                ->whereMonth('donation_date', $date->month)
                ->where('approval_status', 'approved')
                ->sum('amount');
                
            $expenses = Expense::whereYear('expense_date', $date->year)
                ->whereMonth('expense_date', $date->month)
                ->where('approval_status', 'approved')
                ->sum('amount');
            
            $monthlyFinancials[] = [
                'month' => $month,
                'tithes' => $tithes,
                'offerings' => $offerings,
                'donations' => $donations,
                'expenses' => $expenses,
                'income' => $tithes + $offerings + $donations,
                'net' => ($tithes + $offerings + $donations) - $expenses
            ];
        }
        
        // Yearly trends (last 5 years)
        $yearlyFinancials = [];
        for ($i = 4; $i >= 0; $i--) {
            $year = Carbon::now()->subYears($i)->year;
            
            $tithes = Tithe::whereYear('tithe_date', $year)
                ->where('approval_status', 'approved')
                ->sum('amount');
                
            $offerings = Offering::whereYear('offering_date', $year)
                ->where('approval_status', 'approved')
                ->sum('amount');
                
            $donations = Donation::whereYear('donation_date', $year)
                ->where('approval_status', 'approved')
                ->sum('amount');
                
            $expenses = Expense::whereYear('expense_date', $year)
                ->where('approval_status', 'approved')
                ->sum('amount');
            
            $yearlyFinancials[] = [
                'year' => $year,
                'tithes' => $tithes,
                'offerings' => $offerings,
                'donations' => $donations,
                'expenses' => $expenses,
                'income' => $tithes + $offerings + $donations,
                'net' => ($tithes + $offerings + $donations) - $expenses
            ];
        }
        
        return [
            'totals' => [
                'tithes' => $totalTithes,
                'offerings' => $totalOfferings,
                'donations' => $totalDonations,
                'expenses' => $totalExpenses,
                'net_income' => $netIncome
            ],
            'monthly' => [
                'tithes' => $monthlyTithes,
                'offerings' => $monthlyOfferings,
                'donations' => $monthlyDonations,
                'expenses' => $monthlyExpenses,
                'net' => ($monthlyTithes + $monthlyOfferings + $monthlyDonations) - $monthlyExpenses
            ],
            'monthly_trends' => $monthlyFinancials,
            'yearly_trends' => $yearlyFinancials
        ];
    }
    
    private function getMemberAnalytics()
    {
        // Total counts
        $totalMembers = Member::count();
        $maleMembers = Member::whereRaw('LOWER(gender) = ?', ['male'])->count();
        $femaleMembers = Member::whereRaw('LOWER(gender) = ?', ['female'])->count();
        $totalChildren = Child::count();
        
        // Member type distribution
        $memberTypes = Member::selectRaw('member_type, COUNT(*) as count')
            ->groupBy('member_type')
            ->pluck('count', 'member_type');
        
        // Membership type distribution
        $membershipTypes = Member::selectRaw('membership_type, COUNT(*) as count')
            ->groupBy('membership_type')
            ->pluck('count', 'membership_type');
        
        // Monthly registration trends (last 12 months)
        $monthlyRegistrations = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Member::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $monthlyRegistrations[] = [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }
        
        // Yearly registration trends (last 5 years)
        $yearlyRegistrations = [];
        for ($i = 4; $i >= 0; $i--) {
            $year = Carbon::now()->subYears($i)->year;
            $count = Member::whereYear('created_at', $year)->count();
            
            $yearlyRegistrations[] = [
                'year' => $year,
                'count' => $count
            ];
        }
        
        // Age group distribution
        $ageGroups = Member::selectRaw('
            CASE
                WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) < 18 THEN "Under 18"
                WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 18 AND 25 THEN "18-25"
                WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 26 AND 35 THEN "26-35"
                WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 36 AND 50 THEN "36-50"
                WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 51 AND 65 THEN "51-65"
                ELSE "65+"
            END as age_group,
            COUNT(*) as count
        ')
        ->groupBy('age_group')
        ->pluck('count', 'age_group');
        
        return [
            'totals' => [
                'total' => $totalMembers,
                'male' => $maleMembers,
                'female' => $femaleMembers,
                'children' => $totalChildren
            ],
            'member_types' => $memberTypes,
            'membership_types' => $membershipTypes,
            'monthly_registrations' => $monthlyRegistrations,
            'yearly_registrations' => $yearlyRegistrations,
            'age_groups' => $ageGroups
        ];
    }
    
    private function getAttendanceAnalytics()
    {
        // Total attendance
        $totalAttendance = ServiceAttendance::count();
        
        // Monthly attendance trends (last 12 months)
        $monthlyAttendance = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = ServiceAttendance::whereYear('attended_at', $date->year)
                ->whereMonth('attended_at', $date->month)
                ->count();
            
            $monthlyAttendance[] = [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }
        
        // Service type distribution
        $serviceTypes = ServiceAttendance::selectRaw('service_type, COUNT(*) as count')
            ->groupBy('service_type')
            ->pluck('count', 'service_type');
        
        // Top attending members and children (last 30 days)
        $topMemberAttendees = ServiceAttendance::selectRaw('member_id, COUNT(*) as attendance_count')
            ->whereNotNull('member_id')
            ->where('attended_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('member_id')
            ->orderBy('attendance_count', 'desc')
            ->limit(10)
            ->with('member')
            ->get()
            ->map(function($att) {
                return [
                    'type' => 'member',
                    'id' => $att->member_id,
                    'name' => $att->member ? $att->member->full_name : 'Unknown',
                    'attendance_count' => $att->attendance_count
                ];
            });
        
        $topChildAttendees = ServiceAttendance::selectRaw('child_id, COUNT(*) as attendance_count')
            ->whereNotNull('child_id')
            ->where('attended_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('child_id')
            ->orderBy('attendance_count', 'desc')
            ->limit(10)
            ->with('child')
            ->get()
            ->map(function($att) {
                return [
                    'type' => 'child',
                    'id' => $att->child_id,
                    'name' => $att->child ? $att->child->full_name : 'Unknown',
                    'attendance_count' => $att->attendance_count
                ];
            });
        
        // Combine and sort by attendance count
        $topAttendees = $topMemberAttendees->concat($topChildAttendees)
            ->sortByDesc('attendance_count')
            ->take(10)
            ->values();
        
        // Average attendance per service
        $services = SundayService::where('service_date', '>=', Carbon::now()->subMonths(3))
            ->withCount('attendances')
            ->orderBy('service_date', 'desc')
            ->get();
        
        $avgAttendance = $services->count() > 0 
            ? round($services->sum('attendances_count') / $services->count(), 1)
            : 0;
        
        return [
            'total' => $totalAttendance,
            'monthly_trends' => $monthlyAttendance,
            'service_types' => $serviceTypes,
            'top_attendees' => $topAttendees,
            'average_attendance' => $avgAttendance,
            'recent_services' => $services->take(10)
        ];
    }
    
    private function getEventAnalytics()
    {
        $totalEvents = SpecialEvent::count();
        $upcomingEvents = SpecialEvent::where('event_date', '>=', now()->toDateString())->count();
        $pastEvents = SpecialEvent::where('event_date', '<', now()->toDateString())->count();
        
        $totalCelebrations = Celebration::count();
        $upcomingCelebrations = Celebration::where('celebration_date', '>=', now()->toDateString())->count();
        $pastCelebrations = Celebration::where('celebration_date', '<', now()->toDateString())->count();
        
        // Monthly event trends (last 12 months)
        $monthlyEvents = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $events = SpecialEvent::whereYear('event_date', $date->year)
                ->whereMonth('event_date', $date->month)
                ->count();
            $celebrations = Celebration::whereYear('celebration_date', $date->year)
                ->whereMonth('celebration_date', $date->month)
                ->count();
            
            $monthlyEvents[] = [
                'month' => $date->format('M Y'),
                'events' => $events,
                'celebrations' => $celebrations,
                'total' => $events + $celebrations
            ];
        }
        
        return [
            'events' => [
                'total' => $totalEvents,
                'upcoming' => $upcomingEvents,
                'past' => $pastEvents
            ],
            'celebrations' => [
                'total' => $totalCelebrations,
                'upcoming' => $upcomingCelebrations,
                'past' => $pastCelebrations
            ],
            'monthly_trends' => $monthlyEvents
        ];
    }
}

