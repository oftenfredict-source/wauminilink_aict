<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AnnouncementView;
use App\Models\Member;
use App\Models\SundayService;
use App\Models\SpecialEvent;
use App\Models\Leader;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class MemberApiController extends Controller
{
    /**
     * Get dashboard data for the member
     */
    /**
     * Get user's annual fees status
     */
    public function annualFees(): JsonResponse
    {
        $user = Auth::user();
        $member = $user->member;

        if (!$member) {
            return response()->json(['success' => false, 'message' => 'Member not found.'], 404);
        }

        // Get the target amounts from settings
        $adultAmount = (double)\App\Models\SystemSetting::getValue('annual_fee_adult', 2000);
        $childAmount = (double)\App\Models\SystemSetting::getValue('annual_fee_child', 1000);

        $fees = \App\Models\AnnualFee::where('member_id', $member->id)
            ->orderBy('year', 'desc')
            ->get()
            ->map(function ($fee) use ($adultAmount, $childAmount) {
                // Determine target amount based on category
                $target = (strtolower($fee->category) == 'child') ? $childAmount : $adultAmount;
                
                return [
                    'id' => $fee->id,
                    'year' => $fee->year,
                    'category' => $fee->category ?? 'Adult',
                    'amount' => $target, // The amount they SHOULD pay
                    'amount_paid' => (double)$fee->amount, // The amount they HAVE paid
                    'balance' => (double)($target - $fee->amount),
                    'status' => $fee->approval_status == 'approved' ? 'paid' : 'pending',
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $fees
        ]);
    }

    /**
     * Get weekly assignments
     */
    public function assignments(): JsonResponse
    {
        $assignments = \App\Models\WeeklyAssignment::where('is_active', true)
            ->where('date', '>=', Carbon::today())
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($assignment) {
                return [
                    'id' => $assignment->id,
                    'title' => $assignment->title,
                    'description' => $assignment->description,
                    'date' => $assignment->date->format('Y-m-d'),
                    'assigned_to' => $assignment->assigned_to,
                    'type' => $assignment->type,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $assignments
        ]);
    }

    public function dashboard(): JsonResponse
    {
        try {
            $user = Auth::user();
            $member = $user->member;

            // Get basic info even if not a member
            $memberInfo = $member ? [
                'member_id' => $member->member_id,
                'full_name' => $member->full_name,
                'email' => $member->email,
                'phone_number' => $member->phone_number,
            ] : [
                'full_name' => $user->name,
                'email' => $user->email,
                'member_id' => 'ADMIN',
            ];

            Log::info("Dashboard requested for user: " . $user->id);
            // Get financial summary (only if member exists)
            $financialSummary = [
                'total_tithes' => 0,
                'total_offerings' => 0,
                'total_donations' => 0,
                'total_pledges' => 0,
                'total_pledge_payments' => 0,
                'remaining_pledges' => 0,
                'recent_transactions' => [],
            ];

            if ($member) {
                try {
                    Log::info("Fetching financial summary for member: " . $member->id);
                    $financialSummary = $this->getFinancialSummary($member);
                } catch (\Exception $e) {
                    Log::error("Dashboard Financial Summary Error: " . $e->getMessage());
                }
            }

            // Get announcements
            $announcements = [];
            try {
                Log::info("Fetching announcements");
                $announcements = $this->getAnnouncementsData($member);
            } catch (\Exception $e) {
                Log::error("Dashboard Announcements Error: " . $e->getMessage());
            }

            // Get unread announcements count
            $unreadCount = 0;
            if ($member) {
                try {
                    Log::info("Fetching unread count");
                    $unreadCount = $this->getUnreadAnnouncementsCount($member);
                } catch (\Exception $e) {
                    Log::error("Dashboard Unread Count Error: " . $e->getMessage());
                }
            }

            // Get leadership data
            $leadershipData = [
                'all_leaders' => [],
                'my_positions' => []
            ];
            try {
                Log::info("Fetching leadership data");
                $leadershipData = $this->getLeadershipDataHelper($member);
            } catch (\Exception $e) {
                Log::error("Dashboard Leadership Error: " . $e->getMessage());
            }

            // Get upcoming services & events
            $upcomingData = [
                'services' => [],
                'events' => []
            ];
            try {
                Log::info("Fetching upcoming data");
                $upcomingData = $this->getUpcomingData();
            } catch (\Exception $e) {
                Log::error("Dashboard Upcoming Data Error: " . $e->getMessage());
            }
            Log::info("Dashboard data compiled successfully");

            return response()->json([
                'success' => true,
                'data' => [
                    'member_info' => $memberInfo,
                    'financial_summary' => $financialSummary,
                    'announcements' => $announcements,
                    'unread_announcements_count' => $unreadCount,
                    'leadership' => $leadershipData,
                    'upcoming_services' => $upcomingData['services'],
                    'upcoming_events' => $upcomingData['events'],
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error("Critical Dashboard Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Hitilafu imetokea wakati wa kupakia dashboard.',
            ], 500);
        }
    }

    /**
     * Get full member profile details
     */
    public function profile(): JsonResponse
    {
        $user = Auth::user();
        $member = $user->member;

        if (!$member) {
            return response()->json([
                'success' => true,
                'data' => [
                    'personal_info' => [
                        'full_name' => $user->name,
                        'member_id' => 'ADMIN',
                        'email' => $user->email,
                        'phone_number' => $user->phone_number ?? 'N/A',
                    ]
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'personal_info' => [
                    'full_name' => $member->full_name,
                    'member_id' => $member->member_id,
                    'gender' => $member->gender == 'male' ? 'Mwanaume' : 'Mwanamke',
                    'date_of_birth' => $member->date_of_birth ? $member->date_of_birth->format('d-m-Y') : 'N/A',
                    'marital_status' => $member->marital_status,
                    'email' => $member->email ?? $user->email,
                    'phone_number' => $member->phone_number,
                ],
                'church_info' => [
                    'member_type' => $member->member_type,
                    'membership_type' => $member->membership_type,
                    'envelope_number' => $member->envelope_number ?? 'N/A',
                ],
                'professional_info' => [
                    'education_level' => $member->education_level,
                    'profession' => $member->profession,
                ],
                'address_info' => [
                    'residence' => "{$member->residence_region}, {$member->residence_district}, {$member->residence_ward}",
                    'street' => $member->residence_street,
                    'house_number' => $member->residence_house_number ?? 'N/A',
                ],
                'spouse_info' => $member->marital_status == 'married' ? [
                    'name' => $member->spouse_full_name,
                    'phone' => $member->spouse_phone_number,
                ] : null,
            ]
        ]);
    }

    /**
     * Get Sunday Services
     */
    public function services(): JsonResponse
    {
        $services = SundayService::orderBy('service_date', 'desc')
            ->get()
            ->map(function ($service) {
                return [
                    'id' => $service->id,
                    'title' => $service->theme ?? $service->service_type,
                    'theme' => $service->theme,
                    'service_type' => $service->service_type,
                    'preacher' => $service->preacher,
                    'coordinator' => optional($service->coordinator)->full_name ?? 'N/A',
                    'church_elder' => optional($service->churchElder)->full_name ?? 'N/A',
                    'start_time' => $service->start_time,
                    'end_time' => $service->end_time,
                    'venue' => $service->venue,
                    'attendance' => $service->attendance_count,
                    'guests' => $service->guests_count,
                    'choir' => $service->choir,
                    'scripture_readings' => $service->scripture_readings,
                    'announcements' => $service->announcements,
                    'notes' => $service->notes,
                    'date' => $service->service_date,
                    'status' => $service->status,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }

    /**
     * Get Special Events
     */
    public function events(): JsonResponse
    {
        $events = SpecialEvent::orderBy('event_date', 'desc')
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'speaker' => $event->speaker,
                    'event_date' => $event->event_date,
                    'start_time' => $event->start_time,
                    'end_time' => $event->end_time,
                    'location' => $event->venue,
                    'category' => $event->category,
                    'description' => $event->description,
                    'notes' => $event->notes,
                    'attendance' => $event->attendance_count,
                    'budget' => $event->budget_amount,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    /**
     * Get Announcements
     */
    public function announcements(): JsonResponse
    {
        $user = Auth::user();
        $member = $user->member;
        
        $announcements = Announcement::orderBy('is_pinned', 'desc')
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
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $announcements
        ]);
    }

    /**
     * Mark announcement as read
     */
    public function markAnnouncementAsRead($announcementId): JsonResponse
    {
        $user = Auth::user();
        $member = $user->member;

        if (!$member) {
            return response()->json(['success' => false, 'message' => 'Only members can mark announcements as read.'], 403);
        }

        AnnouncementView::firstOrCreate([
            'announcement_id' => $announcementId,
            'member_id' => $member->id
        ], [
            'viewed_at' => now()
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Helper to get financial summary
     */
    private function getFinancialSummary($member)
    {
        Log::info("Calculating sums for member ID: " . $member->id);
        $tithes = $member->tithes()->sum('amount') ?? 0;
        $offerings = $member->offerings()->sum('amount') ?? 0;
        $donations = $member->donations()->sum('amount') ?? 0;
        
        $pledgeTotal = $member->pledges()->sum('pledge_amount') ?? 0;
        $pledgePaid = $member->pledges()->sum('amount_paid') ?? 0;
        
        Log::info("Sums calculated: Tithes=$tithes, Offerings=$offerings, Donations=$donations");

        return [
            'total_tithes' => (double)$tithes,
            'total_offerings' => (double)$offerings,
            'total_donations' => (double)$donations,
            'total_pledges' => (double)$pledgeTotal,
            'total_pledge_payments' => (double)$pledgePaid,
            'remaining_pledges' => (double)($pledgeTotal - $pledgePaid),
            'recent_transactions' => [], // Add if needed
        ];
    }

    /**
     * Helper to get announcements data for dashboard
     */
    private function getAnnouncementsData($member)
    {
        return Announcement::orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
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
                    'is_pinned' => $announcement->is_pinned,
                    'is_unread' => $isUnread,
                ];
            });
    }

    /**
     * Helper to get unread announcements count
     */
    private function getUnreadAnnouncementsCount($member)
    {
        $totalActiveCount = Announcement::where('is_active', true)->count();
        $readCount = AnnouncementView::where('member_id', $member->id)->count();
        return max(0, $totalActiveCount - $readCount);
    }

    /**
     * Get leadership data endpoint
     */
    public function leaders(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->getLeadershipDataHelper(Auth::user()->member)
        ]);
    }

    private function getLeadershipDataHelper($member)
    {
        $allLeaders = Leader::with('member')
            ->where('is_active', true)
            ->get()
            ->map(function ($leader) {
                return [
                    'id' => $leader->id,
                    'position' => $leader->position,
                    'member_name' => optional($leader->member)->full_name ?? 'N/A',
                    'member_phone' => optional($leader->member)->phone_number,
                    'appointment_date' => $leader->appointment_date ? $leader->appointment_date->format('Y-m-d') : null,
                ];
            });

        $myPositions = collect([]);
        if ($member) {
            $myPositions = $member->leadershipPositions()
                ->where('is_active', true)
                ->get()
                ->map(function ($pos) {
                    return [
                        'id' => $pos->id,
                        'position' => $pos->position,
                    ];
                });
        }

        return [
            'all_leaders' => $allLeaders,
            'my_positions' => $myPositions
        ];
    }

    /**
     * Helper to get upcoming services and events
     */
    private function getUpcomingData()
    {
        $services = SundayService::orderBy('service_date', 'desc')
            ->take(3)
            ->get()
            ->map(function($s) {
                return [
                    'id' => $s->id,
                    'title' => $s->theme ?? $s->service_type,
                    'date' => $s->service_date,
                    'start_time' => $s->start_time,
                ];
            });

        $events = SpecialEvent::orderBy('event_date', 'desc')
            ->take(3)
            ->get()
            ->map(function($e) {
                return [
                    'id' => $e->id,
                    'title' => $e->title,
                    'date' => $e->event_date,
                    'start_time' => $e->start_time,
                ];
            });

        return [
            'services' => $services,
            'events' => $events,
        ];
    }

    /**
     * Get Public Celebrations
     */
    public function celebrations(): JsonResponse
    {
        $celebrations = \App\Models\Celebration::where('is_public', true)
            ->where('celebration_date', '>=', now())
            ->orderBy('celebration_date', 'asc')
            ->get()
            ->map(function ($c) {
                return [
                    'id' => $c->id,
                    'title' => $c->title,
                    'description' => $c->description,
                    'date' => $c->celebration_date->format('Y-m-d'),
                    'time' => $c->start_time,
                    'venue' => $c->venue,
                    'type' => $c->type,
                    'celebrant' => $c->celebrant_name,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $celebrations
        ]);
    }

    /**
     * Upload payment receipt
     */
    public function uploadReceipt(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'receipt_type' => 'required|string',
            'amount' => 'nullable|numeric',
            'reference_number' => 'nullable|string',
            'receipt_image' => 'required|image|max:5120', // Max 5MB
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $member = $user->member;

        if (!$member) {
            return response()->json(['success' => false, 'message' => 'Member record not found.'], 404);
        }

        try {
            if ($request->hasFile('receipt_image')) {
                $file = $request->file('receipt_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('receipts/' . $member->id, $filename, 'public');

                $receipt = \App\Models\PaymentReceipt::create([
                    'member_id' => $member->id,
                    'receipt_type' => $request->receipt_type,
                    'amount' => $request->amount,
                    'reference_number' => $request->reference_number,
                    'file_path' => $path,
                    'notes' => $request->notes,
                    'uploaded_at' => now(),
                    'status' => 'pending'
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Receipt uploaded successfully.',
                    'data' => $receipt
                ]);
            }

            return response()->json(['success' => false, 'message' => 'No file uploaded.'], 400);
        } catch (\Exception $e) {
            Log::error("Receipt Upload Error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to upload receipt.'], 500);
        }
    }
}
