<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leader;
use App\Models\Member;
use Illuminate\Support\Facades\Validator;
use App\Services\SmsService;
use App\Services\SettingsService;

class LeaderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->canManageLeadership()) {
                abort(403, 'Unauthorized. Only Pastors and Secretaries can manage leadership positions.');
            }
            return $next($request);
        })->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaders = Leader::with('member')
            ->active()
            ->orderBy('position')
            ->orderBy('appointment_date', 'desc')
            ->get();

        // Filter out leaders without members (data integrity issue)
        $leaders = $leaders->filter(function($leader) {
            return $leader->member !== null;
        });

        // Group leaders by position
        $leadersByPosition = $leaders->groupBy('position');

        return view('leaders.index', compact('leaders', 'leadersByPosition'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $members = Member::orderBy('full_name')->get();
        $positions = $this->getPositionOptions();
        
        return view('leaders.create', compact('members', 'positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'member_id' => 'required|exists:members,id',
            'position' => 'required|string|in:pastor,assistant_pastor,secretary,assistant_secretary,treasurer,assistant_treasurer,elder,deacon,deaconess,youth_leader,children_leader,worship_leader,choir_leader,usher_leader,evangelism_leader,prayer_leader,other',
            'position_title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'appointment_date' => 'required|date',
            'end_date' => 'nullable|date|after:appointment_date',
            'appointed_by' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Please correct the errors in the form.')
                ->withErrors($validator)
                ->withInput();
        }

        // Check if member already has an active position of the same type
        $existingLeader = Leader::where('member_id', $request->member_id)
            ->where('position', $request->position)
            ->where('is_active', true)
            ->first();

        if ($existingLeader) {
            return redirect()->back()
                ->with('error', 'This member already holds an active ' . $existingLeader->position_display . ' position.')
                ->withErrors(['position' => 'This member already holds an active ' . $existingLeader->position_display . ' position.'])
                ->withInput();
        }

        $leader = Leader::create($request->all());
        
        // Load member relationship for notification
        $leader->load('member');

        // Send database notification to the appointed leader
        if ($leader->member) {
            $leader->member->notify(new \App\Notifications\LeaderAppointmentNotification($leader));
        }

        // Send SMS notification to the appointed leader
        $this->sendLeaderAppointmentSms($leader);

        return redirect()->route('leaders.index')
            ->with('success', 'Leader position assigned successfully! Notification sent to the appointed leader.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Leader $leader)
    {
        $leader->load('member');
        return view('leaders.show', compact('leader'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Leader $leader)
    {
        $members = Member::orderBy('full_name')->get();
        $positions = $this->getPositionOptions();
        
        return view('leaders.edit', compact('leader', 'members', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Leader $leader)
    {
        $validator = Validator::make($request->all(), [
            'member_id' => 'required|exists:members,id',
            'position' => 'required|string|in:pastor,assistant_pastor,secretary,assistant_secretary,treasurer,assistant_treasurer,elder,deacon,deaconess,youth_leader,children_leader,worship_leader,choir_leader,usher_leader,evangelism_leader,prayer_leader,other',
            'position_title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'appointment_date' => 'required|date',
            'end_date' => 'nullable|date|after:appointment_date',
            'is_active' => 'boolean',
            'appointed_by' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Please correct the errors in the form.')
                ->withErrors($validator)
                ->withInput();
        }

        // Check if another member already has an active position of the same type
        if ($request->is_active) {
            $existingLeader = Leader::where('member_id', $request->member_id)
                ->where('position', $request->position)
                ->where('is_active', true)
                ->where('id', '!=', $leader->id)
                ->first();

            if ($existingLeader) {
                return redirect()->back()
                    ->with('error', 'This member already holds an active ' . $existingLeader->position_display . ' position.')
                    ->withErrors(['position' => 'This member already holds an active ' . $existingLeader->position_display . ' position.'])
                    ->withInput();
            }
        }

        $leader->update($request->all());

        return redirect()->route('leaders.index')
            ->with('success', 'Leader position updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leader $leader)
    {
        $leader->delete();

        return redirect()->route('leaders.index')
            ->with('success', 'Leader position removed successfully!');
    }

    /**
     * Deactivate a leader position
     */
    public function deactivate(Leader $leader)
    {
        $leader->update(['is_active' => false]);

        return redirect()->route('leaders.index')
            ->with('success', 'Leader position deactivated successfully!');
    }

    /**
     * Reactivate a leader position
     */
    public function reactivate(Leader $leader)
    {
        $leader->update(['is_active' => true]);

        return redirect()->route('leaders.index')
            ->with('success', 'Leader position reactivated successfully!');
    }

    /**
     * Display leadership reports
     */
    public function reports()
    {
        $leaders = Leader::with('member')->get();
        
        // Group by position
        $leadersByPosition = $leaders->groupBy('position');
        
        // Active vs Inactive
        $activeLeaders = $leaders->where('is_active', true);
        $inactiveLeaders = $leaders->where('is_active', false);
        
        // By appointment year
        $leadersByYear = $leaders->groupBy(function($leader) {
            return $leader->appointment_date->year;
        });
        
        // Recent appointments (last 6 months)
        $recentAppointments = $leaders->where('appointment_date', '>=', now()->subMonths(6));
        
        // Expiring terms (next 3 months)
        $expiringTerms = $leaders->where('end_date', '>=', now())
            ->where('end_date', '<=', now()->addMonths(3));
        
        return view('leaders.reports', compact(
            'leaders', 
            'leadersByPosition', 
            'activeLeaders', 
            'inactiveLeaders',
            'leadersByYear',
            'recentAppointments',
            'expiringTerms'
        ));
    }

    /**
     * Export leadership report as CSV
     */
    public function exportCsv()
    {
        $leaders = Leader::with('member')->get();
        
        $filename = 'leadership_report_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($leaders) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Member ID',
                'Member Name',
                'Position',
                'Position Title',
                'Appointment Date',
                'End Date',
                'Status',
                'Appointed By',
                'Description',
                'Notes'
            ]);
            
            // CSV Data
            foreach ($leaders as $leader) {
                fputcsv($file, [
                    $leader->member->member_id,
                    $leader->member->full_name,
                    $leader->position_display,
                    $leader->position_title ?? '',
                    $leader->appointment_date->format('Y-m-d'),
                    $leader->end_date ? $leader->end_date->format('Y-m-d') : '',
                    $leader->is_active ? 'Active' : 'Inactive',
                    $leader->appointed_by ?? '',
                    $leader->description ?? '',
                    $leader->notes ?? ''
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export leadership report as PDF
     */
    public function exportPdf()
    {
        $leaders = Leader::with('member')->get();
        $leadersByPosition = $leaders->groupBy('position');
        $activeLeaders = $leaders->where('is_active', true);
        
        // For now, return a simple HTML view that can be printed as PDF
        // In a real application, you'd use a PDF library like DomPDF or TCPDF
        return view('leaders.reports-pdf', compact('leaders', 'leadersByPosition', 'activeLeaders'));
    }

    /**
     * Generate identity card for a specific leader
     */
    public function identityCard(Leader $leader)
    {
        $leader->load('member');
        
        // Get church information from settings or use defaults
        $churchName = \App\Services\SettingsService::get('church_name', 'Waumini Church');
        $churchAddress = \App\Services\SettingsService::get('church_address', 'Dar es Salaam, Tanzania');
        $churchPhone = \App\Services\SettingsService::get('church_phone', '+255 XXX XXX XXX');
        $churchEmail = \App\Services\SettingsService::get('church_email', 'info@waumini.org');
        
        return view('leaders.identity-card', compact('leader', 'churchName', 'churchAddress', 'churchPhone', 'churchEmail'));
    }

    /**
     * Generate identity cards for all active leaders
     */
    public function bulkIdentityCards()
    {
        $leaders = Leader::with('member')->active()->get();
        
        // Get church information from settings or use defaults
        $churchName = \App\Services\SettingsService::get('church_name', 'Waumini Church');
        $churchAddress = \App\Services\SettingsService::get('church_address', 'Dar es Salaam, Tanzania');
        $churchPhone = \App\Services\SettingsService::get('church_phone', '+255 XXX XXX XXX');
        $churchEmail = \App\Services\SettingsService::get('church_email', 'info@waumini.org');
        
        return view('leaders.bulk-identity-cards', compact('leaders', 'churchName', 'churchAddress', 'churchPhone', 'churchEmail'));
    }

    /**
     * Generate identity card for a specific position
     */
    public function positionIdentityCards($position)
    {
        $leaders = Leader::with('member')->where('position', $position)->active()->get();
        
        if ($leaders->isEmpty()) {
            return redirect()->back()->with('error', 'No active leaders found for this position.');
        }
        
        // Get church information from settings or use defaults
        $churchName = \App\Services\SettingsService::get('church_name', 'Waumini Church');
        $churchAddress = \App\Services\SettingsService::get('church_address', 'Dar es Salaam, Tanzania');
        $churchPhone = \App\Services\SettingsService::get('church_phone', '+255 XXX XXX XXX');
        $churchEmail = \App\Services\SettingsService::get('church_email', 'info@waumini.org');
        
        return view('leaders.bulk-identity-cards', compact('leaders', 'churchName', 'churchAddress', 'churchPhone', 'churchEmail'));
    }

    /**
     * Send SMS notification for leader appointment
     */
    private function sendLeaderAppointmentSms(Leader $leader)
    {
        try {
            // Check if SMS notifications are enabled
            if (!SettingsService::get('enable_sms_notifications', false)) {
                \Log::info('SMS notifications disabled, skipping leader appointment notification');
                return false;
            }

            // Check if member has a phone number
            if (empty($leader->member->phone_number)) {
                \Log::info('Member has no phone number, skipping SMS notification for leader: ' . $leader->member->full_name);
                return false;
            }

            // Get church name from settings (use same default as member registration)
            $churchName = SettingsService::get('church_name', 'AIC Moshi Kilimanjaro');
            
            // Build the message using the requested template/content
            $message = "Hongera {$leader->member->full_name}! Umechaguliwa rasmi kuwa {$leader->position_display} wa kanisa la {$churchName}. Mungu akupe hekima, ujasiri na neema katika kutimiza wajibu huu wa kiroho. Tunaamini uongozi wako utaleta umoja, upendo, na maendeleo katika huduma ya Bwana.";
            
            // Send SMS using the same method as member registration (sendDebug)
            $smsService = app(SmsService::class);
            $resp = $smsService->sendDebug($leader->member->phone_number, $message);
            
            \Log::info('Leader appointment SMS provider response', [
                'leader_name' => $leader->member->full_name,
                'position' => $leader->position_display,
                'phone' => $leader->member->phone_number,
                'ok' => $resp['ok'] ?? null,
                'status' => $resp['status'] ?? null,
                'body' => $resp['body'] ?? null,
                'reason' => $resp['reason'] ?? null,
                'error' => $resp['error'] ?? null,
                'request' => $resp['request'] ?? null,
            ]);

            return $resp['ok'] ?? false;
        } catch (\Exception $e) {
            \Log::error('Error sending leader appointment SMS: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get available position options
     */
    private function getPositionOptions()
    {
        return [
            'pastor' => 'Pastor',
            'assistant_pastor' => 'Assistant Pastor',
            'secretary' => 'Secretary',
            'assistant_secretary' => 'Assistant Secretary',
            'treasurer' => 'Treasurer',
            'assistant_treasurer' => 'Assistant Treasurer',
            'elder' => 'Church Elder',
            'deacon' => 'Deacon',
            'deaconess' => 'Deaconess',
            'youth_leader' => 'Youth Leader',
            'children_leader' => 'Children Leader',
            'worship_leader' => 'Worship Leader',
            'choir_leader' => 'Choir Leader',
            'usher_leader' => 'Usher Leader',
            'evangelism_leader' => 'Evangelism Leader',
            'prayer_leader' => 'Prayer Leader',
            'other' => 'Other (Custom Position)'
        ];
    }
}
