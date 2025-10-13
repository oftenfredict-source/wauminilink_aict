<?php

namespace App\Http\Controllers;

use App\Models\Tithe;
use App\Models\Offering;
use App\Models\Donation;
use App\Models\Pledge;
use App\Models\Budget;
use App\Models\Expense;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display financial reports dashboard
     */
    public function index()
    {
        $totalMembers = Member::count();
        return view('finance.reports.index', compact('totalMembers'));
    }
    
    /**
     * Generate member giving report
     */
    public function memberGiving(Request $request)
    {
        $memberId = $request->get('member_id');
        $startDate = $request->get('start_date', Carbon::now()->startOfYear());
        $endDate = $request->get('end_date', Carbon::now()->endOfYear());
        
        if (!$memberId) {
            return view('finance.reports.member-giving', [
                'members' => Member::orderBy('full_name')->get(),
                'member' => null,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'totalMembers' => Member::count()
            ]);
        }
        
        $member = Member::findOrFail($memberId);
        $members = Member::orderBy('full_name')->get();
        $totalMembers = Member::count();
        
        // Get member's financial data
        $tithes = Tithe::where('member_id', $memberId)
            ->whereBetween('tithe_date', [$startDate, $endDate])
            ->orderBy('tithe_date', 'desc')
            ->get();
            
        $offerings = Offering::where('member_id', $memberId)
            ->whereBetween('offering_date', [$startDate, $endDate])
            ->orderBy('offering_date', 'desc')
            ->get();
            
        $donations = Donation::where('member_id', $memberId)
            ->whereBetween('donation_date', [$startDate, $endDate])
            ->orderBy('donation_date', 'desc')
            ->get();
            
        $pledges = Pledge::where('member_id', $memberId)
            ->whereBetween('pledge_date', [$startDate, $endDate])
            ->orderBy('pledge_date', 'desc')
            ->get();
        
        // Calculate totals
        $totalTithes = $tithes->sum('amount');
        $totalOfferings = $offerings->sum('amount');
        $totalDonations = $donations->sum('amount');
        $totalPledged = $pledges->sum('pledge_amount');
        $totalPaid = $pledges->sum('amount_paid');
        $totalGiving = $totalTithes + $totalOfferings + $totalDonations;
        
        // Monthly breakdown
        $monthlyData = [];
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        while ($current->lte($end)) {
            $monthStart = $current->copy()->startOfMonth();
            $monthEnd = $current->copy()->endOfMonth();
            
            $monthTithes = Tithe::where('member_id', $memberId)
                ->whereBetween('tithe_date', [$monthStart, $monthEnd])
                ->sum('amount');
                
            $monthOfferings = Offering::where('member_id', $memberId)
                ->whereBetween('offering_date', [$monthStart, $monthEnd])
                ->sum('amount');
                
            $monthDonations = Donation::where('member_id', $memberId)
                ->whereBetween('donation_date', [$monthStart, $monthEnd])
                ->sum('amount');
            
            $monthlyData[] = [
                'month' => $current->format('M Y'),
                'tithes' => $monthTithes,
                'offerings' => $monthOfferings,
                'donations' => $monthDonations,
                'total' => $monthTithes + $monthOfferings + $monthDonations
            ];
            
            $current->addMonth();
        }
        
        return view('finance.reports.member-giving', compact(
            'member',
            'members',
            'totalMembers',
            'tithes',
            'offerings',
            'donations',
            'pledges',
            'totalTithes',
            'totalOfferings',
            'totalDonations',
            'totalPledged',
            'totalPaid',
            'totalGiving',
            'monthlyData',
            'startDate',
            'endDate'
        ));
    }
    
    /**
     * Generate department giving report
     */
    public function departmentGiving(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfYear());
        $endDate = $request->get('end_date', Carbon::now()->endOfYear());
        
        // Get offering types as "departments"
        $offeringTypes = Offering::whereBetween('offering_date', [$startDate, $endDate])
            ->select('offering_type', DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as transaction_count'))
            ->groupBy('offering_type')
            ->orderBy('total_amount', 'desc')
            ->get();
        
        // Get donation types
        $donationTypes = Donation::whereBetween('donation_date', [$startDate, $endDate])
            ->select('donation_type', DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as transaction_count'))
            ->groupBy('donation_type')
            ->orderBy('total_amount', 'desc')
            ->get();
        
        // Get pledge types
        $pledgeTypes = Pledge::whereBetween('pledge_date', [$startDate, $endDate])
            ->select('pledge_type', 
                DB::raw('SUM(pledge_amount) as total_pledged'), 
                DB::raw('SUM(amount_paid) as total_paid'),
                DB::raw('COUNT(*) as pledge_count'))
            ->groupBy('pledge_type')
            ->orderBy('total_pledged', 'desc')
            ->get();
        
        $totalMembers = Member::count();
        
        return view('finance.reports.department-giving', compact(
            'offeringTypes',
            'donationTypes',
            'pledgeTypes',
            'startDate',
            'endDate',
            'totalMembers'
        ));
    }
    
    /**
     * Generate income vs expenditure report
     */
    public function incomeVsExpenditure(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfYear());
        $endDate = $request->get('end_date', Carbon::now()->endOfYear());
        
        // Get income data
        $tithes = Tithe::whereBetween('tithe_date', [$startDate, $endDate])->sum('amount');
        $offerings = Offering::whereBetween('offering_date', [$startDate, $endDate])->sum('amount');
        $donations = Donation::whereBetween('donation_date', [$startDate, $endDate])->sum('amount');
        
        $totalIncome = $tithes + $offerings + $donations;
        
        // Get expenditure data
        $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->where('status', 'paid')
            ->get();
        
        $totalExpenses = $expenses->sum('amount');
        
        // Get expenses by category
        $expensesByCategory = $expenses->groupBy('expense_category')
            ->map(function ($categoryExpenses) {
                return [
                    'total' => $categoryExpenses->sum('amount'),
                    'count' => $categoryExpenses->count()
                ];
            })
            ->sortByDesc('total');
        
        // Monthly breakdown
        $monthlyData = [];
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        while ($current->lte($end)) {
            $monthStart = $current->copy()->startOfMonth();
            $monthEnd = $current->copy()->endOfMonth();
            
            $monthTithes = Tithe::whereBetween('tithe_date', [$monthStart, $monthEnd])->sum('amount');
            $monthOfferings = Offering::whereBetween('offering_date', [$monthStart, $monthEnd])->sum('amount');
            $monthDonations = Donation::whereBetween('donation_date', [$monthStart, $monthEnd])->sum('amount');
            $monthExpenses = Expense::whereBetween('expense_date', [$monthStart, $monthEnd])
                ->where('status', 'paid')
                ->sum('amount');
            
            $monthlyData[] = [
                'month' => $current->format('M Y'),
                'income' => $monthTithes + $monthOfferings + $monthDonations,
                'expenses' => $monthExpenses,
                'net' => ($monthTithes + $monthOfferings + $monthDonations) - $monthExpenses
            ];
            
            $current->addMonth();
        }
        
        $netIncome = $totalIncome - $totalExpenses;
        $totalMembers = Member::count();
        
        return view('finance.reports.income-vs-expenditure', compact(
            'tithes',
            'offerings',
            'donations',
            'totalIncome',
            'totalExpenses',
            'netIncome',
            'expensesByCategory',
            'monthlyData',
            'startDate',
            'endDate',
            'totalMembers'
        ));
    }
    
    /**
     * Generate budget performance report
     */
    public function budgetPerformance(Request $request)
    {
        $budgetId = $request->get('budget_id');
        $startDate = $request->get('start_date', Carbon::now()->startOfYear());
        $endDate = $request->get('end_date', Carbon::now()->endOfYear());
        
        if (!$budgetId) {
            return view('finance.reports.budget-performance', [
                'budgets' => Budget::orderBy('fiscal_year', 'desc')->get(),
                'budget' => null,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'totalMembers' => Member::count()
            ]);
        }
        
        $budget = Budget::findOrFail($budgetId);
        
        // Get expenses for this budget
        $expenses = Expense::where('budget_id', $budgetId)
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->orderBy('expense_date', 'desc')
            ->get();
        
        // Get expenses by category
        $expensesByCategory = $expenses->groupBy('expense_category')
            ->map(function ($categoryExpenses) {
                return [
                    'total' => $categoryExpenses->sum('amount'),
                    'count' => $categoryExpenses->count(),
                    'avg' => $categoryExpenses->avg('amount')
                ];
            })
            ->sortByDesc('total');
        
        // Monthly breakdown
        $monthlyData = [];
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        while ($current->lte($end)) {
            $monthStart = $current->copy()->startOfMonth();
            $monthEnd = $current->copy()->endOfMonth();
            
            $monthExpenses = Expense::where('budget_id', $budgetId)
                ->whereBetween('expense_date', [$monthStart, $monthEnd])
                ->where('status', 'paid')
                ->sum('amount');
            
            $monthlyData[] = [
                'month' => $current->format('M Y'),
                'spent' => $monthExpenses,
                'budget' => $budget->total_budget,
                'utilization' => $budget->total_budget > 0 ? round(($monthExpenses / $budget->total_budget) * 100, 2) : 0
            ];
            
            $current->addMonth();
        }
        
        $totalMembers = Member::count();
        
        return view('finance.reports.budget-performance', compact(
            'budget',
            'budgets',
            'expenses',
            'expensesByCategory',
            'monthlyData',
            'startDate',
            'endDate',
            'totalMembers'
        ));
    }
    
    /**
     * Export report to PDF
     */
    public function exportPdf(Request $request)
    {
        $reportType = $request->get('report_type');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        // This would integrate with a PDF library like DomPDF or TCPDF
        // For now, return a placeholder response
        return response()->json([
            'message' => 'PDF export functionality will be implemented with a PDF library',
            'report_type' => $reportType,
            'date_range' => $startDate . ' to ' . $endDate
        ]);
    }
    
    /**
     * Export report to Excel
     */
    public function exportExcel(Request $request)
    {
        $reportType = $request->get('report_type');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        // This would integrate with Laravel Excel (Maatwebsite)
        // For now, return a placeholder response
        return response()->json([
            'message' => 'Excel export functionality will be implemented with Laravel Excel',
            'report_type' => $reportType,
            'date_range' => $startDate . ' to ' . $endDate
        ]);
    }
    
    /**
     * Generate member giving receipt
     */
    public function generateMemberReceipt($memberId, Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfYear());
        $endDate = $request->get('end_date', Carbon::now()->endOfYear());
        
        $member = Member::findOrFail($memberId);
        
        // Get member's financial data for the period
        $tithes = Tithe::where('member_id', $memberId)
            ->whereBetween('tithe_date', [$startDate, $endDate])
            ->orderBy('tithe_date', 'desc')
            ->get();
            
        $offerings = Offering::where('member_id', $memberId)
            ->whereBetween('offering_date', [$startDate, $endDate])
            ->orderBy('offering_date', 'desc')
            ->get();
            
        $donations = Donation::where('member_id', $memberId)
            ->whereBetween('donation_date', [$startDate, $endDate])
            ->orderBy('donation_date', 'desc')
            ->get();
            
        $pledges = Pledge::where('member_id', $memberId)
            ->whereBetween('pledge_date', [$startDate, $endDate])
            ->orderBy('pledge_date', 'desc')
            ->get();
        
        // Calculate totals
        $totalTithes = $tithes->sum('amount');
        $totalOfferings = $offerings->sum('amount');
        $totalDonations = $donations->sum('amount');
        $totalPledged = $pledges->sum('pledge_amount');
        $totalPaid = $pledges->sum('amount_paid');
        $totalGiving = $totalTithes + $totalOfferings + $totalDonations;
        
        // Church information
        $churchInfo = [
            'name' => 'Waumini Link Ministry',
            'address' => 'Dar es Salaam, Tanzania',
            'phone' => '+255 XXX XXX XXX',
            'email' => 'info@wauminilink.org',
            'website' => 'www.wauminilink.org'
        ];
        
        return view('finance.reports.member-receipt', compact(
            'member',
            'tithes',
            'offerings', 
            'donations',
            'pledges',
            'totalTithes',
            'totalOfferings',
            'totalDonations',
            'totalPledged',
            'totalPaid',
            'totalGiving',
            'startDate',
            'endDate',
            'churchInfo'
        ));
    }
}
