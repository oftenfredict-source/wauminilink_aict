<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Sermon;
use App\Models\ServiceRequest;
use App\Models\SpecialEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ServiceApiController extends Controller
{
    /**
     * Get all fellowships (Departments in the system)
     */
    public function fellowships(): JsonResponse
    {
        $fellowships = Department::where('status', 'active')->get()->map(function($dept) {
            return [
                'id' => $dept->id,
                'name' => $dept->name,
                'description' => $dept->description,
                'head_name' => $dept->head ? $dept->head->full_name : 'N/A',
                'member_count' => $dept->members()->count(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $fellowships
        ]);
    }

    /**
     * Get sermons
     */
    public function sermons(): JsonResponse
    {
        $sermons = Sermon::orderBy('date', 'desc')->get()->map(function($sermon) {
            return [
                'id' => $sermon->id,
                'title' => $sermon->title,
                'preacher' => $sermon->preacher,
                'date' => $sermon->date->format('Y-m-d'),
                'summary' => $sermon->summary,
                'video_url' => $sermon->video_url,
                'audio_url' => $sermon->audio_url,
                'thumbnail_url' => $sermon->thumbnail_url,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $sermons
        ]);
    }

    /**
     * Submit a service request (Baptism, Marriage, etc.)
     */
    public function submitRequest(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|string',
            'preferred_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $user = Auth::user();
        $member = $user->member;

        if (!$member) {
            return response()->json(['success' => false, 'message' => 'Member not found.'], 404);
        }

        $serviceRequest = ServiceRequest::create([
            'member_id' => $member->id,
            'type' => $request->type,
            'preferred_date' => $request->preferred_date,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ombi lako limepokelewa na linafanyiwa kazi.',
            'data' => $serviceRequest
        ]);
    }

    /**
     * Get member's service requests
     */
    public function myRequests(): JsonResponse
    {
        $user = Auth::user();
        $member = $user->member;

        if (!$member) {
            return response()->json(['success' => false, 'message' => 'Member not found.'], 404);
        }

        $requests = ServiceRequest::where('member_id', $member->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $requests
        ]);
    }

    /**
     * Get calendar events
     */
    public function calendar(): JsonResponse
    {
        $events = SpecialEvent::where('event_date', '>=', Carbon::today())
            ->orderBy('event_date', 'asc')
            ->get()
            ->map(function($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'speaker' => $event->speaker,
                    'date' => $event->event_date->format('Y-m-d'),
                    'time' => $event->start_time . ' - ' . $event->end_time,
                    'venue' => $event->venue,
                    'description' => $event->description,
                    'category' => $event->category,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }
}
