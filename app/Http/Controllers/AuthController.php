<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Show login form
    public function showLogin()
    {
        // If user is already authenticated, redirect to dashboard
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->isPastor()) {
                return redirect()->route('dashboard.pastor');
            } elseif ($user->isTreasurer()) {
                return redirect()->route('finance.dashboard');
            } elseif ($user->isMember()) {
                return redirect()->route('member.dashboard');
            } else {
                return redirect()->route('dashboard.secretary');
            }
        }
        
        // Prevent caching of login page
        return response()->view('login')
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        $emailOrMemberId = $request->input('email');
        $password = $request->input('password');

        try {
            // Check if input is an email or member_id
            // If it contains @, treat as email, otherwise treat as member_id
            if (strpos($emailOrMemberId, '@') !== false) {
                // It's an email
                $user = \App\Models\User::where('email', $emailOrMemberId)->first();
            } else {
                // It's a member_id - find user by email field which stores member_id
                $user = \App\Models\User::where('email', $emailOrMemberId)->first();
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // Database connection error
            if (strpos($e->getMessage(), 'Connection refused') !== false || 
                strpos($e->getMessage(), 'No connection could be made') !== false) {
                return back()->withErrors([
                    'email' => 'Database connection failed. Please ensure MySQL is running in XAMPP.',
                ])->withInput($request->only('email'));
            }
            throw $e; // Re-throw if it's a different database error
        }

        // Check if user exists and is blocked from logging in
        
        if ($user && $user->login_blocked_until && $user->login_blocked_until->isFuture()) {
            $remainingMinutes = now()->diffInMinutes($user->login_blocked_until);
            $remainingSeconds = now()->diffInSeconds($user->login_blocked_until);
            
            if ($remainingMinutes > 0) {
                $message = "Your account is temporarily blocked from logging in. Please try again in {$remainingMinutes} minute(s).";
            } else {
                $message = "Your account is temporarily blocked from logging in. Please try again in {$remainingSeconds} second(s).";
            }
            
            return back()->withErrors([
                'email' => $message,
            ])->withInput($request->only('email'));
        }

        // Clear the block if it has expired
        if ($user && $user->login_blocked_until && $user->login_blocked_until->isPast()) {
            $user->update(['login_blocked_until' => null]);
        }

        // Prepare credentials for authentication
        $credentials = [
            'email' => $emailOrMemberId,
            'password' => $password,
        ];

        try {
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                // Log login activity (if table exists)
                try {
                    \App\Models\ActivityLog::create([
                        'user_id' => Auth::id(),
                        'action' => 'login',
                        'description' => 'User logged in',
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'route' => 'login.post',
                        'method' => 'POST',
                    ]);
                } catch (\Exception $e) {
                    // Table might not exist yet - silently continue
                    // This allows login to work even if migrations haven't been run
                }

                // Redirect based on role
                $user = Auth::user();
                if ($user->role === 'secretary') {
                    return redirect()->route('dashboard.secretary')
                        ->with('success', 'Login successful! Welcome back.');
                } elseif ($user->role === 'pastor') {
                    return redirect()->route('dashboard.pastor')
                        ->with('success', 'Login successful! Welcome Pastor.');
                } elseif ($user->role === 'admin') {
                    return redirect()->route('admin.dashboard')
                        ->with('success', 'Login successful! Welcome Admin.');
                } elseif ($user->role === 'treasurer') {
                    return redirect()->route('finance.dashboard')
                        ->with('success', 'Login successful! Welcome Treasurer.');
                } elseif ($user->role === 'member') {
                    return redirect()->route('member.dashboard')
                        ->with('success', 'Login successful! Welcome.');
                } else {
                    Auth::logout();
                    return back()->withErrors(['role' => 'Unauthorized role.']);
                }
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // Database connection error during authentication
            if (strpos($e->getMessage(), 'Connection refused') !== false || 
                strpos($e->getMessage(), 'No connection could be made') !== false) {
                return back()->withErrors([
                    'email' => 'Database connection failed. Please ensure MySQL is running in XAMPP.',
                ])->withInput($request->only('email'));
            }
            throw $e; // Re-throw if it's a different database error
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    // Logout
    public function logout(Request $request)
    {
        // Log logout activity (if table exists)
        if (Auth::check()) {
            try {
                \App\Models\ActivityLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'logout',
                    'description' => 'User logged out',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'route' => 'logout',
                    'method' => 'POST',
                ]);
            } catch (\Exception $e) {
                // Table might not exist yet - silently continue
            }
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('info', 'You have been logged out successfully.');
    }
}