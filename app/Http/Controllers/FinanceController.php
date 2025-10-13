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

class FinanceController extends Controller
{
    /**
     * Display the financial dashboard
     */
    public function dashboard()
    {
        \Log::info('FinanceController@dashboard called');
        $currentMonth = Carbon::now()->startOfMonth();
        $currentYear = Carbon::now()->year;
        
        // Get financial summary for current month
        $monthlyTithes = Tithe::whereMonth('tithe_date', $currentMonth->month)
            ->whereYear('tithe_date', $currentYear)
            ->sum('amount');
            
        $monthlyOfferings = Offering::whereMonth('offering_date', $currentMonth->month)
            ->whereYear('offering_date', $currentYear)
            ->sum('amount');
            
        $monthlyDonations = Donation::whereMonth('donation_date', $currentMonth->month)
            ->whereYear('donation_date', $currentYear)
            ->sum('amount');
            
        $monthlyExpenses = Expense::whereMonth('expense_date', $currentMonth->month)
            ->whereYear('expense_date', $currentYear)
            ->where('status', 'paid')
            ->sum('amount');
        
        $totalIncome = $monthlyTithes + $monthlyOfferings + $monthlyDonations;
        $netIncome = $totalIncome - $monthlyExpenses;
        
        // Get recent transactions
        $recentTithes = Tithe::with('member')
            ->orderBy('tithe_date', 'desc')
            ->limit(5)
            ->get();
            
        $recentOfferings = Offering::with('member')
            ->orderBy('offering_date', 'desc')
            ->limit(5)
            ->get();
            
        $recentDonations = Donation::with('member')
            ->orderBy('donation_date', 'desc')
            ->limit(5)
            ->get();
        
        // Get budget status
        $currentBudgets = Budget::current()->get();
        
        // Get pledge status
        $activePledges = Pledge::active()->with('member')->get();
        $overduePledges = Pledge::overdue()->with('member')->get();
        
        // Get monthly income trend (last 6 months)
        $incomeTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthIncome = Tithe::whereMonth('tithe_date', $month->month)
                ->whereYear('tithe_date', $month->year)
                ->sum('amount') +
                Offering::whereMonth('offering_date', $month->month)
                ->whereYear('offering_date', $month->year)
                ->sum('amount') +
                Donation::whereMonth('donation_date', $month->month)
                ->whereYear('donation_date', $month->year)
                ->sum('amount');
                
            $incomeTrend[] = [
                'month' => $month->format('M Y'),
                'income' => $monthIncome
            ];
        }
        
        // Get total members count for the layout
        $totalMembers = Member::count();
        
        return view('finance.dashboard', compact(
            'monthlyTithes',
            'monthlyOfferings', 
            'monthlyDonations',
            'monthlyExpenses',
            'totalIncome',
            'netIncome',
            'recentTithes',
            'recentOfferings',
            'recentDonations',
            'currentBudgets',
            'activePledges',
            'overduePledges',
            'incomeTrend',
            'totalMembers'
        ));
    }
    
    /**
     * Display tithes management
     */
    public function tithes(Request $request)
    {
        $query = Tithe::with('member');
        
        // Apply filters
        if ($request->filled('member_id')) {
            $query->where('member_id', $request->member_id);
        }
        
        if ($request->filled('date_from')) {
            $query->where('tithe_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('tithe_date', '<=', $request->date_to);
        }
        
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        
        $tithes = $query->orderBy('tithe_date', 'desc')->paginate(20);
        $members = Member::orderBy('full_name')->get();
        $totalMembers = Member::count();
        
        return view('finance.tithes', compact('tithes', 'members', 'totalMembers'));
    }
    
    /**
     * Store a new tithe
     */
    public function storeTithe(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric|min:0',
            'tithe_date' => 'required|date',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_verified' => 'boolean'
        ]);
        
        $validated['recorded_by'] = auth()->user()->name ?? 'System';
        
        Tithe::create($validated);
        
        return redirect()->route('finance.tithes')
            ->with('success', 'Tithe recorded successfully');
    }
    
    /**
     * Display offerings management
     */
    public function offerings(Request $request)
    {
        $query = Offering::with('member');
        
        // Apply filters
        if ($request->filled('offering_type')) {
            $query->where('offering_type', $request->offering_type);
        }
        
        if ($request->filled('date_from')) {
            $query->where('offering_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('offering_date', '<=', $request->date_to);
        }
        
        $offerings = $query->orderBy('offering_date', 'desc')->paginate(20);
        $members = Member::orderBy('full_name')->get();
        $totalMembers = Member::count();
        
        return view('finance.offerings', compact('offerings', 'members', 'totalMembers'));
    }
    
    /**
     * Store a new offering
     */
    public function storeOffering(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'nullable|exists:members,id',
            'amount' => 'required|numeric|min:0',
            'offering_date' => 'required|date',
            'offering_type' => 'required|string',
            'service_type' => 'nullable|string',
            'service_id' => 'nullable|integer',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_verified' => 'boolean'
        ]);
        
        $validated['recorded_by'] = auth()->user()->name ?? 'System';
        
        Offering::create($validated);
        
        return redirect()->route('finance.offerings')
            ->with('success', 'Offering recorded successfully');
    }
    
    /**
     * Display donations management
     */
    public function donations(Request $request)
    {
        $query = Donation::with('member');
        
        // Apply filters
        if ($request->filled('donation_type')) {
            $query->where('donation_type', $request->donation_type);
        }
        
        if ($request->filled('date_from')) {
            $query->where('donation_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('donation_date', '<=', $request->date_to);
        }
        
        $donations = $query->orderBy('donation_date', 'desc')->paginate(20);
        $members = Member::orderBy('full_name')->get();
        $totalMembers = Member::count();
        
        return view('finance.donations', compact('donations', 'members', 'totalMembers'));
    }
    
    /**
     * Store a new donation
     */
    public function storeDonation(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'nullable|exists:members,id',
            'donor_name' => 'nullable|string|required_without:member_id',
            'donor_email' => 'nullable|email',
            'donor_phone' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'donation_date' => 'required|date',
            'donation_type' => 'required|string',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|string',
            'purpose' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_verified' => 'boolean',
            'is_anonymous' => 'boolean'
        ]);
        
        $validated['recorded_by'] = auth()->user()->name ?? 'System';
        
        Donation::create($validated);
        
        return redirect()->route('finance.donations')
            ->with('success', 'Donation recorded successfully');
    }
    
    /**
     * Display pledges management
     */
    public function pledges(Request $request)
    {
        $query = Pledge::with('member');
        
        // Apply filters
        if ($request->filled('member_id')) {
            $query->where('member_id', $request->member_id);
        }
        
        if ($request->filled('pledge_type')) {
            $query->where('pledge_type', $request->pledge_type);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $pledges = $query->orderBy('pledge_date', 'desc')->paginate(20);
        $members = Member::orderBy('full_name')->get();
        $totalMembers = Member::count();
        
        return view('finance.pledges', compact('pledges', 'members', 'totalMembers'));
    }
    
    /**
     * Store a new pledge
     */
    public function storePledge(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'pledge_amount' => 'required|numeric|min:0',
            'pledge_date' => 'required|date',
            'due_date' => 'nullable|date|after:pledge_date',
            'pledge_type' => 'required|string',
            'payment_frequency' => 'required|string',
            'purpose' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);
        
        $validated['recorded_by'] = auth()->user()->name ?? 'System';
        $validated['amount_paid'] = 0;
        $validated['status'] = 'active';
        
        Pledge::create($validated);
        
        return redirect()->route('finance.pledges')
            ->with('success', 'Pledge recorded successfully');
    }
    
    /**
     * Update pledge payment
     */
    public function updatePledgePayment(Request $request, Pledge $pledge)
    {
        $validated = $request->validate([
            'payment_amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date'
        ]);
        
        $newAmountPaid = $pledge->amount_paid + $validated['payment_amount'];
        
        // Update pledge
        $pledge->update([
            'amount_paid' => $newAmountPaid,
            'status' => $newAmountPaid >= $pledge->pledge_amount ? 'completed' : 'active'
        ]);
        
        return redirect()->route('finance.pledges')
            ->with('success', 'Pledge payment recorded successfully');
    }
    
    /**
     * Display budgets management
     */
    public function budgets(Request $request)
    {
        $query = Budget::query();
        
        // Apply filters
        if ($request->filled('fiscal_year')) {
            $query->where('fiscal_year', $request->fiscal_year);
        }
        
        if ($request->filled('budget_type')) {
            $query->where('budget_type', $request->budget_type);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $budgets = $query->orderBy('fiscal_year', 'desc')
            ->orderBy('start_date', 'desc')
            ->paginate(20);
        $totalMembers = Member::count();
        
        return view('finance.budgets', compact('budgets', 'totalMembers'));
    }
    
    /**
     * Store a new budget
     */
    public function storeBudget(Request $request)
    {
        $validated = $request->validate([
            'budget_name' => 'required|string',
            'budget_type' => 'required|string',
            'fiscal_year' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'total_budget' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);
        
        $validated['created_by'] = auth()->user()->name ?? 'System';
        $validated['allocated_amount'] = 0;
        $validated['spent_amount'] = 0;
        $validated['status'] = 'active';
        
        Budget::create($validated);
        
        return redirect()->route('finance.budgets')
            ->with('success', 'Budget created successfully');
    }
    
    /**
     * Display expenses management
     */
    public function expenses(Request $request)
    {
        $query = Expense::with('budget');
        
        // Apply filters
        if ($request->filled('expense_category')) {
            $query->where('expense_category', $request->expense_category);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('date_from')) {
            $query->where('expense_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('expense_date', '<=', $request->date_to);
        }
        
        $expenses = $query->orderBy('expense_date', 'desc')->paginate(20);
        $budgets = Budget::active()->get();
        $totalMembers = Member::count();
        
        return view('finance.expenses', compact('expenses', 'budgets', 'totalMembers'));
    }
    
    /**
     * Store a new expense
     */
    public function storeExpense(Request $request)
    {
        $validated = $request->validate([
            'budget_id' => 'nullable|exists:budgets,id',
            'expense_category' => 'required|string',
            'expense_name' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|string',
            'description' => 'nullable|string',
            'vendor' => 'nullable|string',
            'receipt_number' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);
        
        $validated['recorded_by'] = auth()->user()->name ?? 'System';
        $validated['status'] = 'pending';
        
        Expense::create($validated);
        
        return redirect()->route('finance.expenses')
            ->with('success', 'Expense recorded successfully');
    }
    
    /**
     * Approve an expense
     */
    public function approveExpense(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'approved_by' => 'required|string'
        ]);
        
        $expense->update([
            'status' => 'approved',
            'approved_by' => $validated['approved_by'],
            'approved_date' => now()
        ]);
        
        return redirect()->route('finance.expenses')
            ->with('success', 'Expense approved successfully');
    }
    
    /**
     * Mark expense as paid
     */
    public function markExpensePaid(Expense $expense)
    {
        $expense->update(['status' => 'paid']);
        
        // Update budget spent amount
        if ($expense->budget_id) {
            $budget = Budget::find($expense->budget_id);
            $budget->increment('spent_amount', $expense->amount);
        }
        
        return redirect()->route('finance.expenses')
            ->with('success', 'Expense marked as paid');
    }
}
