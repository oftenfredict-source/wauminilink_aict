<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AuthApiController extends Controller
{
    /**
     * Login with phone number and password
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string',
            'password' => 'required|string',
        ], [
            'phone_number.required' => 'Phone number is required.',
            'password.required' => 'Password is required.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $phoneNumber = $request->input('phone_number');
        $password = $request->input('password');

        // Normalize phone number (remove spaces, dashes, etc.)
        $phoneNumber = preg_replace('/[^0-9+]/', '', $phoneNumber);

        // Try to find user by phone_number in users table
        $user = User::where('phone_number', $phoneNumber)
            ->orWhere('phone_number', 'like', '%' . $phoneNumber . '%')
            ->first();

        // If not found in users table, try to find through member relationship
        if (!$user) {
            $member = Member::where('phone_number', $phoneNumber)
                ->orWhere('phone_number', 'like', '%' . $phoneNumber . '%')
                ->first();

            if ($member) {
                // Find user associated with this member
                $user = User::where('member_id', $member->id)->first();
            }
        }

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid phone number or password.'
            ], 401);
        }

        // Check if user is blocked from logging in
        if ($user->login_blocked_until && $user->login_blocked_until->isFuture()) {
            $remainingMinutes = now()->diffInMinutes($user->login_blocked_until);
            
            return response()->json([
                'success' => false,
                'message' => "Your account is temporarily blocked. Please try again in {$remainingMinutes} minute(s).",
                'blocked_until' => $user->login_blocked_until->toISOString()
            ], 403);
        }

        // Clear the block if it has expired
        if ($user->login_blocked_until && $user->login_blocked_until->isPast()) {
            $user->update(['login_blocked_until' => null]);
        }

        // Verify password
        if (!Hash::check($password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid phone number or password.'
            ], 401);
        }

        // Ensure user is a member
        if (!$user->isMember() || !$user->member_id) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Only members can access the mobile app.'
            ], 403);
        }

        // Verify member exists
        $member = $user->member;
        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member record not found.'
            ], 404);
        }

        // Revoke existing tokens (optional - for single device login)
        // $user->tokens()->delete();

        // Create Sanctum token
        $token = $user->createToken('mobile-app')->plainTextToken;

        // Log login activity
        try {
            \App\Models\ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'login',
                'description' => 'User logged in via mobile app',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'route' => 'api.login',
                'method' => 'POST',
            ]);
        } catch (\Exception $e) {
            // Table might not exist yet - silently continue
            Log::warning('Failed to log login activity: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone_number' => $user->phone_number ?? $member->phone_number,
                    'role' => $user->role,
                ],
                'member' => [
                    'id' => $member->id,
                    'member_id' => $member->member_id,
                    'full_name' => $member->full_name,
                    'email' => $member->email,
                    'phone_number' => $member->phone_number,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
            ]
        ], 200);
    }

    /**
     * Logout user and revoke token
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user) {
            // Log logout activity
            try {
                \App\Models\ActivityLog::create([
                    'user_id' => $user->id,
                    'action' => 'logout',
                    'description' => 'User logged out via mobile app',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'route' => 'api.logout',
                    'method' => 'POST',
                ]);
            } catch (\Exception $e) {
                Log::warning('Failed to log logout activity: ' . $e->getMessage());
            }

            // Revoke current token
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ], 200);
    }

    /**
     * Get authenticated user information
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user->isMember() || !$user->member_id) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Only members can access the mobile app.'
            ], 403);
        }

        $member = $user->member;

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member record not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone_number' => $user->phone_number ?? $member->phone_number,
                    'role' => $user->role,
                ],
                'member' => [
                    'id' => $member->id,
                    'member_id' => $member->member_id,
                    'full_name' => $member->full_name,
                    'email' => $member->email,
                    'phone_number' => $member->phone_number,
                ],
            ]
        ], 200);
    }
}



