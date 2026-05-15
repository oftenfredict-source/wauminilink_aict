<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BereavementEvent;
use App\Models\BereavementContribution;
use App\Models\Member;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BereavementApiController extends Controller
{
    /**
     * Get all active bereavement events
     */
    public function index(): JsonResponse
    {
        $events = BereavementEvent::where('status', 'open')
            ->orderBy('incident_date', 'desc')
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'deceased_name' => $event->deceased_name,
                    'incident_date' => $event->incident_date->format('Y-m-d'),
                    'contribution_end_date' => $event->contribution_end_date->format('Y-m-d'),
                    'status' => $event->status,
                    'total_contributed' => (double)$event->total_contributions,
                    'days_remaining' => $event->days_remaining,
                    'notes' => $event->notes,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    /**
     * Get details of a specific bereavement event
     */
    public function show($id): JsonResponse
    {
        $event = BereavementEvent::with('contributions.member')->find($id);

        if (!$event) {
            return response()->json(['success' => false, 'message' => 'Event not found.'], 404);
        }

        $user = Auth::user();
        $member = $user->member;
        
        $myContribution = null;
        if ($member) {
            $myContribution = BereavementContribution::where('bereavement_event_id', $id)
                ->where('member_id', $member->id)
                ->first();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'event' => [
                    'id' => $event->id,
                    'deceased_name' => $event->deceased_name,
                    'family_details' => $event->family_details,
                    'incident_date' => $event->incident_date->format('Y-m-d'),
                    'contribution_start_date' => $event->contribution_start_date->format('Y-m-d'),
                    'contribution_end_date' => $event->contribution_end_date->format('Y-m-d'),
                    'notes' => $event->notes,
                    'total_contributions' => (double)$event->total_contributions,
                    'contributors_count' => $event->contributors_count,
                ],
                'my_contribution' => $myContribution ? [
                    'amount' => (double)$myContribution->amount,
                    'has_contributed' => (bool)$myContribution->has_contributed,
                    'date' => $myContribution->contribution_date ? $myContribution->contribution_date->format('Y-m-d') : null,
                ] : null
            ]
        ]);
    }

    /**
     * Get user's contribution history
     */
    public function myHistory(): JsonResponse
    {
        $user = Auth::user();
        $member = $user->member;

        if (!$member) {
            return response()->json(['success' => false, 'message' => 'Member not found.'], 404);
        }

        $history = BereavementContribution::with('bereavementEvent')
            ->where('member_id', $member->id)
            ->where('has_contributed', true)
            ->orderBy('contribution_date', 'desc')
            ->get()
            ->map(function ($contribution) {
                return [
                    'id' => $contribution->id,
                    'event_name' => optional($contribution->bereavementEvent)->deceased_name ?? 'N/A',
                    'amount' => (double)$contribution->amount,
                    'date' => $contribution->contribution_date ? $contribution->contribution_date->format('Y-m-d') : null,
                    'payment_method' => $contribution->payment_method,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }
}
