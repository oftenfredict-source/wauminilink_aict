<?php

namespace App\Http\Controllers;

use App\Models\SpecialEvent;
use App\Models\Member;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class SpecialEventController extends Controller
{
    public function index(Request $request)
    {
        // Get total members count for the layout
        $totalMembers = Member::count();
        
        $query = SpecialEvent::query();
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s){
                $q->where('title','like',"%$s%")
                  ->orWhere('speaker','like',"%$s%")
                  ->orWhere('venue','like',"%$s%");
            });
        }
        if ($request->filled('from')) $query->whereDate('event_date','>=',$request->from);
        if ($request->filled('to')) $query->whereDate('event_date','<=',$request->to);
        $events = $query->orderBy('event_date','desc')->paginate(10);
        if ($request->wantsJson()) return response()->json($events);
        
        return view('services.special.page', compact('events', 'totalMembers'));
    }

    public function store(Request $request)
    {
        try {
            \Log::info('SpecialEvent store method called', ['request_data' => $request->all()]);
            \Log::info('Send notifications value', ['send_notifications' => $request->input('send_notifications'), 'type' => gettype($request->input('send_notifications'))]);
            
            $validated = $request->validate([
                'event_date' => 'nullable|date',
                'title' => 'nullable|string|max:255',
                'speaker' => 'nullable|string|max:255',
                'start_time' => 'nullable',
                'end_time' => 'nullable',
                'venue' => 'nullable|string|max:255',
                'attendance_count' => 'nullable|integer|min:0',
                'budget_amount' => 'nullable|numeric|min:0',
                'category' => 'nullable|string|max:100',
                'description' => 'nullable|string',
                'notes' => 'nullable|string',
                'send_notifications' => 'nullable|in:true,false,1,0,"true","false"',
                'notification_members' => 'nullable|array',
                'notification_members.*' => 'integer|exists:members,id',
            ]);
            
            \Log::info('Validation passed', ['validated_data' => $validated]);
            
            // Remove notification fields before creating event
            $eventData = collect($validated)->except(['send_notifications', 'notification_members'])->toArray();
            $event = SpecialEvent::create($eventData);
            
            \Log::info('Event created successfully', ['event_id' => $event->id]);
            
            // Send notifications if requested
            $notificationResult = null;
            $sendNotifications = $request->input('send_notifications');
            if ($sendNotifications === 'true' || $sendNotifications === true || $sendNotifications === '1' || $sendNotifications === 1) {
                $notificationService = new NotificationService();
                $memberIds = $request->input('notification_members');
                $notificationResult = $notificationService->sendEventNotifications($event, $memberIds, 'created');
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Event created successfully',
                'event' => $event,
                'notification_result' => $notificationResult
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', ['errors' => $e->errors()]);
            return response()->json(['success'=>false,'message'=>'Validation failed','errors'=>$e->errors()], 422);
        } catch (\Exception $e) {
            \Log::error('Error creating event', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['success'=>false,'message'=>'Server error: ' . $e->getMessage()], 500);
        }
    }

    public function show(SpecialEvent $specialEvent)
    {
        return response()->json($specialEvent);
    }

    public function update(Request $request, SpecialEvent $specialEvent)
    {
        $validated = $request->validate([
            'event_date' => 'nullable|date',
            'title' => 'nullable|string|max:255',
            'speaker' => 'nullable|string|max:255',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'venue' => 'nullable|string|max:255',
            'attendance_count' => 'nullable|integer|min:0',
            'budget_amount' => 'nullable|numeric|min:0',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        
        $specialEvent->update($validated);
        
        return response()->json(['success'=>true,'event'=>$specialEvent]);
    }

    public function destroy(SpecialEvent $specialEvent)
    {
        $specialEvent->delete();
        return response()->json(['success'=>true]);
    }

    /**
     * Get members for notification selection
     */
    public function getMembersForNotification()
    {
        $notificationService = new NotificationService();
        $members = $notificationService->getMembersForNotification();
        
        return response()->json([
            'success' => true,
            'members' => $members
        ]);
    }
}



