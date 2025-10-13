<?php

namespace App\Http\Controllers;

use App\Models\SpecialEvent;
use App\Models\Celebration;
use App\Models\SundayService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function getNotificationData()
    {
        try {
            $now = Carbon::now();
            $startDate = $now->toDateString(); // start from today
            $next30Days = $now->copy()->addDays(30);
            
            \Log::info('NotificationController: Fetching data for date range', [
                'startDate' => $startDate,
                'endDate' => $next30Days->toDateString()
            ]);
            
            // Get upcoming events (next 30 days)
            $events = SpecialEvent::whereDate('event_date', '>=', $startDate)
                ->whereDate('event_date', '<=', $next30Days->toDateString())
                ->orderBy('event_date')
                ->get()
                ->map(function ($event) use ($now) {
                    $eventDate = Carbon::parse($event->event_date);
                    $eventTime = ($event->start_time && trim($event->start_time) !== '') ? $event->start_time : '23:59:59';
                    $eventDateTime = $eventDate->copy()->setTimeFromTimeString($eventTime);
                    $daysRemaining = (int) $now->diffInDays($eventDate, false);
                    $hoursRemaining = max(0, (int) $now->diffInHours($eventDateTime, false));

                    return [
                        'id' => $event->id,
                        'title' => $event->title,
                        'date' => $event->event_date,
                        'time' => $event->start_time,
                        'venue' => $event->venue,
                        'speaker' => $event->speaker,
                        'days_remaining' => $daysRemaining,
                        'hours_remaining' => $daysRemaining === 0 ? $hoursRemaining : null,
                        'type' => 'event'
                    ];
                });
            
            // Get upcoming celebrations (next 30 days)
            $celebrations = Celebration::whereDate('celebration_date', '>=', $startDate)
                ->whereDate('celebration_date', '<=', $next30Days->toDateString())
                ->orderBy('celebration_date')
                ->get()
                ->map(function ($celebration) use ($now) {
                    $celebrationDate = Carbon::parse($celebration->celebration_date);
                    $celebrationTime = ($celebration->start_time && trim($celebration->start_time) !== '') ? $celebration->start_time : '23:59:59';
                    $celebrationDateTime = $celebrationDate->copy()->setTimeFromTimeString($celebrationTime);
                    $daysRemaining = (int) $now->diffInDays($celebrationDate, false);
                    $hoursRemaining = max(0, (int) $now->diffInHours($celebrationDateTime, false));

                    return [
                        'id' => $celebration->id,
                        'title' => $celebration->title,
                        'date' => $celebration->celebration_date,
                        'time' => $celebration->start_time,
                        'venue' => $celebration->venue,
                        'celebrant' => $celebration->celebrant_name,
                        'celebration_type' => $celebration->type,
                        'days_remaining' => $daysRemaining,
                        'hours_remaining' => $daysRemaining === 0 ? $hoursRemaining : null,
                        'type' => 'celebration'
                    ];
                });
            
            // Get upcoming Sunday services (next 30 days)
            $services = SundayService::whereDate('service_date', '>=', $startDate)
                ->whereDate('service_date', '<=', $next30Days->toDateString())
                ->orderBy('service_date')
                ->get()
                ->map(function ($service) use ($now) {
                    $serviceDate = Carbon::parse($service->service_date);
                    $serviceTime = ($service->start_time && trim($service->start_time) !== '') ? $service->start_time : '23:59:59';
                    $serviceDateTime = $serviceDate->copy()->setTimeFromTimeString($serviceTime);
                    $daysRemaining = (int) $now->diffInDays($serviceDate, false);
                    $hoursRemaining = max(0, (int) $now->diffInHours($serviceDateTime, false));

                    return [
                        'id' => $service->id,
                        'title' => 'Sunday Service',
                        'date' => $service->service_date,
                        'time' => $service->start_time,
                        'venue' => $service->venue,
                        // Use correct field name from model; keep 'speaker' for compatibility
                        'preacher' => $service->preacher,
                        'speaker' => $service->preacher,
                        'theme' => $service->theme,
                        'days_remaining' => $daysRemaining,
                        'hours_remaining' => $daysRemaining === 0 ? $hoursRemaining : null,
                        'type' => 'service'
                    ];
                });
            
            // Calculate total notifications
            $totalNotifications = $events->count() + $celebrations->count() + $services->count();
            
            \Log::info('NotificationController: Data counts', [
                'events_count' => $events->count(),
                'celebrations_count' => $celebrations->count(),
                'services_count' => $services->count(),
                'total' => $totalNotifications
            ]);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'events' => $events,
                    'celebrations' => $celebrations,
                    'services' => $services,
                    'counts' => [
                        'events' => $events->count(),
                        'celebrations' => $celebrations->count(),
                        'services' => $services->count(),
                        'total' => $totalNotifications
                    ],
                    'last_updated' => $now->format('M j, Y g:i A')
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to fetch notification data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch notification data'
            ], 500);
        }
    }
}
