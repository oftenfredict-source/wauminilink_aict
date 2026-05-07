<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PrayerRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PrayerRequestApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the member's prayer requests.
     */
    public function index(): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user->member_id) {
            return response()->json(['success' => false, 'message' => 'Not a member.'], 403);
        }

        $requests = PrayerRequest::where('member_id', $user->member_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $requests
        ]);
    }

    /**
     * Store a newly created prayer request in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user->member_id) {
            return response()->json(['success' => false, 'message' => 'Not a member.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'is_anonymous' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $prayerRequest = PrayerRequest::create([
            'member_id' => $user->member_id,
            'subject' => $request->subject,
            'content' => $request->content,
            'is_anonymous' => $request->is_anonymous ?? false,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Prayer request sent successfully. We are praying with you.',
            'data' => $prayerRequest
        ], 201);
    }

    /**
     * Display the specified prayer request.
     */
    public function show($id): JsonResponse
    {
        $user = Auth::user();
        $prayerRequest = PrayerRequest::find($id);

        if (!$prayerRequest || ($prayerRequest->member_id != $user->member_id && !$user->isAdmin() && !$user->isPastor())) {
            return response()->json(['success' => false, 'message' => 'Not found or unauthorized.'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $prayerRequest
        ]);
    }
}
