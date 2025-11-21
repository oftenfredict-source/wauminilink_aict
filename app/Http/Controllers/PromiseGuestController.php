<?php

namespace App\Http\Controllers;

use App\Models\PromiseGuest;
use App\Models\SundayService;
use App\Services\SmsService;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PromiseGuestController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Display a listing of promise guests
     */
    public function index(Request $request)
    {
        $query = PromiseGuest::query()->with(['service', 'creator']);

        // Filter by service date
        if ($request->filled('service_date')) {
            $query->whereDate('promised_service_date', $request->date('service_date'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('from')) {
            $query->whereDate('promised_service_date', '>=', $request->date('from'));
        }
        if ($request->filled('to')) {
            $query->whereDate('promised_service_date', '<=', $request->date('to'));
        }

        // Search by name or phone
        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        $promiseGuests = $query->orderBy('promised_service_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $promiseGuests->appends($request->query());

        if ($request->wantsJson()) {
            return response()->json($promiseGuests);
        }

        // Get statistics
        $stats = [
            'total' => PromiseGuest::count(),
            'pending' => PromiseGuest::where('status', 'pending')->count(),
            'notified' => PromiseGuest::where('status', 'notified')->count(),
            'attended' => PromiseGuest::where('status', 'attended')->count(),
            'cancelled' => PromiseGuest::where('status', 'cancelled')->count(),
        ];

        // Get upcoming Sunday services for modal
        $upcomingServices = SundayService::whereDate('service_date', '>=', now())
            ->orderBy('service_date', 'asc')
            ->get();

        return view('promise-guests.index', compact('promiseGuests', 'stats', 'upcomingServices'));
    }

    /**
     * Show the form for creating a new promise guest
     */
    public function create()
    {
        // Get upcoming Sunday services
        $upcomingServices = SundayService::whereDate('service_date', '>=', now())
            ->orderBy('service_date', 'asc')
            ->get();

        return view('promise-guests.create', compact('upcomingServices'));
    }

    /**
     * Store a newly created promise guest
     */
    public function store(Request $request)
    {
        // Normalize phone number - ensure it starts with +255
        $phoneNumber = $request->input('phone_number');
        if ($phoneNumber && !str_starts_with($phoneNumber, '+255')) {
            // Remove any existing +255 and add it back
            $phoneNumber = preg_replace('/^\+?255/', '', $phoneNumber);
            $phoneNumber = '+255' . $phoneNumber;
            $request->merge(['phone_number' => $phoneNumber]);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => ['required', 'string', 'max:20', 'regex:/^\+255[0-9]{9,15}$/'],
            'email' => 'nullable|email|max:255',
            'promised_service_date' => 'required|date|after_or_equal:today',
            'service_id' => 'nullable|exists:sunday_services,id',
            'notes' => 'nullable|string',
        ], [
            'name.required' => 'Guest name is required.',
            'phone_number.required' => 'Phone number is required for SMS notifications.',
            'phone_number.regex' => 'Phone number must be in format +255 followed by 9-15 digits (e.g., +255712345678).',
            'promised_service_date.required' => 'Service date is required.',
            'promised_service_date.after_or_equal' => 'Service date must be today or in the future.',
            'service_id.exists' => 'Selected service does not exist.',
        ]);

        // If service_id is provided, ensure the service date matches
        if ($request->filled('service_id')) {
            $service = SundayService::findOrFail($request->service_id);
            if ($service->service_date->format('Y-m-d') !== $request->promised_service_date) {
                return back()->withErrors(['service_id' => 'Service date does not match the selected service.'])->withInput();
            }
        } else {
            // Try to find or create a service for this date
            $service = SundayService::firstOrCreate(
                ['service_date' => $request->promised_service_date],
                [
                    'service_type' => 'sunday_service',
                    'status' => 'scheduled',
                ]
            );
            $validated['service_id'] = $service->id;
        }

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'pending';

        $promiseGuest = PromiseGuest::create($validated);

        Log::info('Promise guest created', [
            'id' => $promiseGuest->id,
            'name' => $promiseGuest->name,
            'service_date' => $promiseGuest->promised_service_date,
        ]);

        if ($request->wantsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => 'Promise guest "' . $promiseGuest->name . '" added successfully!',
                'data' => $promiseGuest->load(['service', 'creator']),
            ], 201);
        }

        return redirect()->route('promise-guests.index')
            ->with('success', 'Promise guest "' . $promiseGuest->name . '" added successfully!');
    }

    /**
     * Display the specified promise guest
     */
    public function show(PromiseGuest $promiseGuest)
    {
        $promiseGuest->load(['service', 'creator']);
        return view('promise-guests.show', compact('promiseGuest'));
    }

    /**
     * Show the form for editing the specified promise guest
     */
    public function edit(PromiseGuest $promiseGuest)
    {
        $upcomingServices = SundayService::whereDate('service_date', '>=', now())
            ->orderBy('service_date', 'asc')
            ->get();

        return view('promise-guests.edit', compact('promiseGuest', 'upcomingServices'));
    }

    /**
     * Update the specified promise guest
     */
    public function update(Request $request, PromiseGuest $promiseGuest)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => ['required', 'string', 'max:20', 'regex:/^\+255[0-9]{9,15}$/'],
            'email' => 'nullable|email|max:255',
            'promised_service_date' => 'required|date',
            'service_id' => 'nullable|exists:sunday_services,id',
            'status' => 'required|in:pending,notified,attended,cancelled',
            'notes' => 'nullable|string',
        ], [
            'name.required' => 'Guest name is required.',
            'phone_number.required' => 'Phone number is required.',
            'phone_number.regex' => 'Phone number must be in format +255 followed by 9-15 digits (e.g., +255712345678).',
            'promised_service_date.required' => 'Service date is required.',
            'status.required' => 'Status is required.',
            'status.in' => 'Invalid status selected.',
        ]);

        $promiseGuest->update($validated);

        Log::info('Promise guest updated', [
            'id' => $promiseGuest->id,
            'name' => $promiseGuest->name,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Promise guest updated successfully.',
                'data' => $promiseGuest->load(['service', 'creator']),
            ]);
        }

        return redirect()->route('promise-guests.index')
            ->with('success', 'Promise guest updated successfully.');
    }

    /**
     * Remove the specified promise guest
     */
    public function destroy(PromiseGuest $promiseGuest)
    {
        $guestName = $promiseGuest->name;
        $promiseGuest->delete();

        Log::info('Promise guest deleted', [
            'id' => $promiseGuest->id,
            'name' => $guestName,
        ]);

        if (request()->wantsJson() || request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => 'Promise guest "' . $guestName . '" deleted successfully.',
            ]);
        }

        return redirect()->route('promise-guests.index')
            ->with('success', 'Promise guest "' . $guestName . '" deleted successfully.');
    }

    /**
     * Mark promise guest as attended (manual)
     */
    public function markAttended(PromiseGuest $promiseGuest)
    {
        $promiseGuest->update(['status' => 'attended']);

        Log::info('Promise guest marked as attended', [
            'id' => $promiseGuest->id,
            'name' => $promiseGuest->name,
        ]);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Promise guest marked as attended.',
                'data' => $promiseGuest->load(['service', 'creator']),
            ]);
        }

        return back()->with('success', 'Promise guest marked as attended.');
    }

    /**
     * Send notification manually to a promise guest
     */
    public function sendNotification(PromiseGuest $promiseGuest)
    {
        try {
            // Check if SMS is enabled
            $smsEnabled = SettingsService::get('enable_sms_notifications', false);
            if (!$smsEnabled) {
                Log::warning('SMS notifications are disabled', [
                    'promise_guest_id' => $promiseGuest->id,
                ]);
                return back()->withErrors(['error' => 'SMS notifications are disabled. Please enable them in System Settings.']);
            }

            // Get or create service
            $service = $promiseGuest->service;
            
            if (!$service) {
                // Try to find or create a service for this date
                $service = SundayService::firstOrCreate(
                    ['service_date' => $promiseGuest->promised_service_date],
                    [
                        'service_type' => 'sunday_service',
                        'status' => 'scheduled',
                    ]
                );
                $promiseGuest->update(['service_id' => $service->id]);
                Log::info('Service created for promise guest', [
                    'promise_guest_id' => $promiseGuest->id,
                    'service_id' => $service->id,
                ]);
            }

            // Validate phone number
            if (empty($promiseGuest->phone_number)) {
                return back()->withErrors(['error' => 'Phone number is required for SMS notifications.']);
            }

            // Build notification message
            $message = $this->smsService->buildPromiseGuestNotificationMessage(
                $promiseGuest->name,
                $service->service_date ?? $promiseGuest->promised_service_date,
                $service
            );

            Log::info('Attempting to send promise guest notification', [
                'promise_guest_id' => $promiseGuest->id,
                'name' => $promiseGuest->name,
                'phone' => $promiseGuest->phone_number,
                'message_length' => strlen($message),
            ]);

            // Send SMS with debug info
            $result = $this->smsService->sendDebug($promiseGuest->phone_number, $message);

            if ($result['ok'] === true) {
                $promiseGuest->update([
                    'status' => 'notified',
                    'notified_at' => now(),
                ]);

                Log::info('Promise guest notification sent successfully', [
                    'id' => $promiseGuest->id,
                    'name' => $promiseGuest->name,
                    'phone' => $promiseGuest->phone_number,
                ]);

                if (request()->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Notification sent successfully.',
                    ]);
                }

                return back()->with('success', 'Notification sent successfully to ' . $promiseGuest->phone_number);
            } else {
                $errorMessage = 'Failed to send notification.';
                if (isset($result['reason'])) {
                    if ($result['reason'] === 'disabled') {
                        $errorMessage = 'SMS notifications are disabled. Please enable them in System Settings.';
                    } elseif ($result['reason'] === 'config_missing') {
                        $errorMessage = 'SMS configuration is missing. Please configure SMS settings in System Settings.';
                    } else {
                        $errorMessage = 'Failed to send notification: ' . $result['reason'];
                    }
                } elseif (isset($result['error'])) {
                    $errorMessage = 'Error: ' . $result['error'];
                }

                Log::error('Failed to send promise guest notification', [
                    'promise_guest_id' => $promiseGuest->id,
                    'result' => $result,
                ]);

                if (request()->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage,
                        'debug' => $result,
                    ], 500);
                }

                return back()->withErrors(['error' => $errorMessage]);
            }
        } catch (\Exception $e) {
            Log::error('Error sending promise guest notification', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'promise_guest_id' => $promiseGuest->id,
            ]);

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
}

