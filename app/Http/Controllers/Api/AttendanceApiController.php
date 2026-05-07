<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SundayService;
use App\Models\SpecialEvent;
use App\Models\ServiceAttendance;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AttendanceApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Record attendance via QR code scan
     */
    public function scan(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user->member_id) {
            return response()->json(['success' => false, 'message' => 'Only members can record attendance.'], 403);
        }

        $qrCode = $request->input('qr_code');

        if (!$qrCode) {
            return response()->json(['success' => false, 'message' => 'QR Code is required.'], 422);
        }

        // Search in Sunday Services
        $service = SundayService::where('qr_code', $qrCode)->first();
        $type = 'sunday_service';

        // If not found, search in Special Events
        if (!$service) {
            $service = SpecialEvent::where('qr_code', $qrCode)->first();
            $type = 'special_event';
        }

        if (!$service) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired QR code.'], 404);
        }

        // Check if already recorded
        $exists = ServiceAttendance::where('member_id', $user->member_id)
            ->where('service_id', $service->id)
            ->where('service_type', $type)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => true,
                'message' => 'Your attendance is already recorded for this session.',
                'data' => [
                    'service_name' => $service->theme ?? $service->title,
                    'type' => $type
                ]
            ]);
        }

        // Record attendance
        ServiceAttendance::create([
            'member_id' => $user->member_id,
            'service_id' => $service->id,
            'service_type' => $type,
            'attended_at' => now(),
            'recorded_by' => 'mobile_app_scan',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attendance recorded successfully!',
            'data' => [
                'service_name' => $service->theme ?? $service->title,
                'type' => $type,
                'time' => now()->toDateTimeString()
            ]
        ]);
    }
}
