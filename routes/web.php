<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CelebrationController;
use App\Http\Controllers\NotificationController;
use App\Http\Middleware\PreventBackHistory;


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

use App\Http\Controllers\MemberController;
use App\Http\Controllers\SundayServiceController;
use App\Http\Controllers\SpecialEventController;
use App\Http\Controllers\SettingsController;
use Illuminate\Http\Request;

// Auth routes with PreventBackHistory middleware
Route::middleware(['auth', PreventBackHistory::class])->group(function () {
    Route::get('/secretary/dashboard', [DashboardController::class, 'index'])->name('dashboard.secretary');
    Route::get('/members/view', [MemberController::class, 'view'])->name('members.view');
    // Sunday services UI route
    Route::get('/services/sunday', [SundayServiceController::class, 'index'])->name('services.sunday.index');
    // Special events UI route
    Route::get('/special-events', [SpecialEventController::class, 'index'])->name('special.events.index');
    // Celebrations UI route
    Route::get('/celebrations', [CelebrationController::class, 'index'])->name('celebrations.index');
    
    // Financial Management Routes
    Route::prefix('finance')->name('finance.')->group(function () {
        // Test route
        Route::get('/test', function() {
            return 'Finance test route works!';
        });
        
        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\FinanceController::class, 'dashboard'])->name('dashboard');
        
        // Tithes
        Route::get('/tithes', [App\Http\Controllers\FinanceController::class, 'tithes'])->name('tithes');
        Route::post('/tithes', [App\Http\Controllers\FinanceController::class, 'storeTithe'])->name('tithes.store');
        
        // Offerings
        Route::get('/offerings', [App\Http\Controllers\FinanceController::class, 'offerings'])->name('offerings');
        Route::post('/offerings', [App\Http\Controllers\FinanceController::class, 'storeOffering'])->name('offerings.store');
        
        // Donations
        Route::get('/donations', [App\Http\Controllers\FinanceController::class, 'donations'])->name('donations');
        Route::post('/donations', [App\Http\Controllers\FinanceController::class, 'storeDonation'])->name('donations.store');
        
        // Pledges
        Route::get('/pledges', [App\Http\Controllers\FinanceController::class, 'pledges'])->name('pledges');
        Route::post('/pledges', [App\Http\Controllers\FinanceController::class, 'storePledge'])->name('pledges.store');
        Route::post('/pledges/{pledge}/payment', [App\Http\Controllers\FinanceController::class, 'updatePledgePayment'])->name('pledges.payment');
        
        // Budgets
        Route::get('/budgets', [App\Http\Controllers\FinanceController::class, 'budgets'])->name('budgets');
        Route::post('/budgets', [App\Http\Controllers\FinanceController::class, 'storeBudget'])->name('budgets.store');
        
        // Expenses
        Route::get('/expenses', [App\Http\Controllers\FinanceController::class, 'expenses'])->name('expenses');
        Route::post('/expenses', [App\Http\Controllers\FinanceController::class, 'storeExpense'])->name('expenses.store');
        Route::post('/expenses/{expense}/approve', [App\Http\Controllers\FinanceController::class, 'approveExpense'])->name('expenses.approve');
        Route::post('/expenses/{expense}/paid', [App\Http\Controllers\FinanceController::class, 'markExpensePaid'])->name('expenses.paid');
    });
    
    // Financial Reports Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [App\Http\Controllers\ReportController::class, 'index'])->name('index');
        Route::get('/member-giving', [App\Http\Controllers\ReportController::class, 'memberGiving'])->name('member-giving');
        Route::get('/department-giving', [App\Http\Controllers\ReportController::class, 'departmentGiving'])->name('department-giving');
        Route::get('/income-vs-expenditure', [App\Http\Controllers\ReportController::class, 'incomeVsExpenditure'])->name('income-vs-expenditure');
        Route::get('/budget-performance', [App\Http\Controllers\ReportController::class, 'budgetPerformance'])->name('budget-performance');
        Route::get('/export/pdf', [App\Http\Controllers\ReportController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/export/excel', [App\Http\Controllers\ReportController::class, 'exportExcel'])->name('export.excel');
        Route::get('/member-receipt/{memberId}', [App\Http\Controllers\ReportController::class, 'generateMemberReceipt'])->name('member-receipt');
    });
});

// Member routes
Route::middleware(['auth'])->group(function () {
    Route::post('/members', [MemberController::class, 'store'])->name('members.store');
    // Test route for debugging
    Route::get('/test-member', function() {
        try {
            $member = new App\Models\Member();
            return response()->json(['status' => 'success', 'message' => 'Member model works', 'fillable' => $member->getFillable()]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    });
    Route::get('/members', [MemberController::class, 'index'])->name('members.index');
    Route::get('/members/add', function () { return view('members.add-members'); })->name('members.add');
    Route::get('/members/next-id', [MemberController::class, 'nextId'])->name('members.next_id');
    Route::get('/members/export/csv', [MemberController::class, 'exportCsv'])->name('members.export.csv');
    
    // PUT and DELETE routes must come before GET routes with parameters
    Route::put('/members/{member}', [MemberController::class, 'update'])->name('members.update');
    Route::delete('/members/{member}', [MemberController::class, 'destroy'])->name('members.destroy');
    Route::delete('/members/archived/{memberId}', [MemberController::class, 'destroyArchived'])->name('members.destroy.archived');
    Route::delete('/members/{member}/archive', [MemberController::class, 'archive'])->name('members.archive');
    
    // GET route with parameter should come last
    Route::get('/members/{id}', [MemberController::class, 'show'])->name('members.show')->where('id', '[0-9]+');
    
    // Test route to check if member exists
    Route::get('/test-member/{id}', function($id) {
        $member = \App\Models\Member::find($id);
        if ($member) {
            return response()->json([
                'success' => true,
                'member' => [
                    'id' => $member->id,
                    'member_id' => $member->member_id,
                    'full_name' => $member->full_name
                ]
            ]);
        }
        return response()->json(['success' => false, 'message' => 'Member not found']);
    });
    
    // List all members for debugging
    Route::get('/list-members', function() {
        $members = \App\Models\Member::select('id', 'member_id', 'full_name')->get();
        $archivedMembers = \App\Models\DeletedMember::select('id', 'member_id', 'member_snapshot')->get();
        
        return response()->json([
            'success' => true,
            'active_members' => [
                'count' => $members->count(),
                'members' => $members
            ],
            'archived_members' => [
                'count' => $archivedMembers->count(),
                'members' => $archivedMembers->map(function($archived) {
                    $snapshot = $archived->member_snapshot;
                    return [
                        'id' => $archived->id,
                        'member_id' => $archived->member_id,
                        'full_name' => $snapshot['full_name'] ?? 'Unknown',
                        'archived_at' => $archived->deleted_at_actual
                    ];
                })
            ]
        ]);
    });
    

    // Sunday services routes
    Route::post('/services/sunday', [SundayServiceController::class, 'store'])->name('services.sunday.store');
    Route::get('/services/sunday/{sundayService}', [SundayServiceController::class, 'show'])->name('services.sunday.show');
    Route::put('/services/sunday/{sundayService}', [SundayServiceController::class, 'update'])->name('services.sunday.update');
    Route::delete('/services/sunday/{sundayService}', [SundayServiceController::class, 'destroy'])->name('services.sunday.destroy');
    Route::get('/services/sunday-export/csv', [SundayServiceController::class, 'exportCsv'])->name('services.sunday.export.csv');

    // Special events routes
    Route::post('/special-events', [SpecialEventController::class, 'store'])->name('special.events.store');
    Route::get('/special-events/{specialEvent}', [SpecialEventController::class, 'show'])->name('special.events.show');
    Route::put('/special-events/{specialEvent}', [SpecialEventController::class, 'update'])->name('special.events.update');
    Route::delete('/special-events/{specialEvent}', [SpecialEventController::class, 'destroy'])->name('special.events.destroy');
    Route::get('/special-events-members/notification', [SpecialEventController::class, 'getMembersForNotification'])->name('special.events.members.notification');

    // Celebrations routes
    Route::post('/celebrations', [CelebrationController::class, 'store'])->name('celebrations.store');
    Route::get('/celebrations/{celebration}', [CelebrationController::class, 'show'])->name('celebrations.show');
    Route::put('/celebrations/{celebration}', [CelebrationController::class, 'update'])->name('celebrations.update');
    Route::delete('/celebrations/{celebration}', [CelebrationController::class, 'destroy'])->name('celebrations.destroy');
    Route::get('/celebrations-export/csv', [CelebrationController::class, 'exportCsv'])->name('celebrations.export.csv');
});

// Settings routes
Route::middleware(['auth', PreventBackHistory::class])->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/{category}', [SettingsController::class, 'updateCategory'])->name('settings.update.category');
    Route::post('/settings/reset', [SettingsController::class, 'reset'])->name('settings.reset');
    Route::get('/settings/export', [SettingsController::class, 'export'])->name('settings.export');
    Route::post('/settings/import', [SettingsController::class, 'import'])->name('settings.import');
    Route::get('/settings/get/{key}', [SettingsController::class, 'getValue'])->name('settings.get');
    Route::post('/settings/set/{key}', [SettingsController::class, 'setValue'])->name('settings.set');
    Route::get('/settings/audit-logs', [SettingsController::class, 'auditLogs'])->name('settings.audit-logs');
    Route::get('/settings/backup', [SettingsController::class, 'backup'])->name('settings.backup');
    Route::post('/settings/restore', [SettingsController::class, 'restore'])->name('settings.restore');
    Route::get('/settings/help', function() { return view('settings.help'); })->name('settings.help');
    Route::get('/settings/analytics', [SettingsController::class, 'analytics'])->name('settings.analytics');
});



Route::get('/', function () {
    return view('welcome');
})->name('landing_page');


// Redirect /dashboard to /secretary/dashboard
Route::get('/dashboard', function () {
    return redirect()->route('dashboard.secretary');
});



// Test route for debugging member creation
Route::get('/test-member-creation', function () {
    try {
        $member = \App\Models\Member::create([
            'member_id' => \App\Models\Member::generateMemberId(),
            'member_type' => 'independent',
            'membership_type' => 'permanent',
            'full_name' => 'Test User',
            'phone_number' => '+255712345678',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'profession' => 'Developer',
            'region' => 'Dar es Salaam',
            'district' => 'Kinondoni',
            'ward' => 'Test Ward',
            'street' => 'Test Street',
            'address' => 'Test Address',
            'tribe' => 'Test Tribe',
        ]);
        
        return response()->json(['success' => true, 'member' => $member]);
    } catch (Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()]);
    }
});

// Test route for debugging special event creation
Route::get('/test-special-event-creation', function () {
    try {
        $event = \App\Models\SpecialEvent::create([
            'event_date' => '2024-01-01',
            'title' => 'Test Event',
            'speaker' => 'Test Speaker',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'venue' => 'Test Venue',
            'attendance_count' => 50,
            'budget_amount' => 1000.00,
            'category' => 'Test Category',
            'description' => 'Test Description',
            'notes' => 'Test Notes',
        ]);
        
        return response()->json(['success' => true, 'event' => $event]);
    } catch (Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()]);
    }
});

// Test route to get CSRF token
Route::get('/test-csrf', function () {
    return response()->json([
        'csrf_token' => csrf_token(),
        'message' => 'CSRF token generated'
    ]);
});

// Debug route to test special event creation
Route::post('/debug-special-events', function (Request $request) {
    \Log::info('Debug special event route called', ['request_data' => $request->all()]);
    
    try {
        $event = \App\Models\SpecialEvent::create([
            'event_date' => $request->event_date,
            'title' => $request->title,
            'speaker' => $request->speaker,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'venue' => $request->venue,
            'attendance_count' => $request->attendance_count,
            'budget_amount' => $request->budget_amount,
            'category' => $request->category,
            'description' => $request->description,
            'notes' => $request->notes,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Event created successfully',
            'event' => $event
        ], 200);
    } catch (Exception $e) {
        \Log::error('Debug special event error', ['error' => $e->getMessage()]);
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
});

// Test email configuration
Route::get('/test-email', function () {
    try {
        \Mail::raw('Test email from Waumini Link notification system!', function($message) {
            $message->to('oftenfred.ict@gmail.com')
                    ->subject('Waumini Link - Email Test');
        });
        
        return response()->json([
            'success' => true,
            'message' => 'Test email sent successfully to oftenfred.ict@gmail.com'
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Email error: ' . $e->getMessage()
        ]);
    }
});

// Notification data route
Route::get('/notifications/data', [NotificationController::class, 'getNotificationData'])->name('notifications.data');

// Test route to check database records
Route::get('/test-notifications', function() {
    try {
        $events = \App\Models\SpecialEvent::count();
        $celebrations = \App\Models\Celebration::count();
        $services = \App\Models\SundayService::count();
        
        return response()->json([
            'success' => true,
            'events' => $events,
            'celebrations' => $celebrations,
            'services' => $services,
            'total' => $events + $celebrations + $services
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});

// Debug route to test notification controller
Route::get('/debug-notifications', function() {
    try {
        $controller = new \App\Http\Controllers\NotificationController();
        $response = $controller->getNotificationData();
        return $response;
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});


