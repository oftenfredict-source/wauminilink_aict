<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\Permission;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || !Auth::user()->isAdmin()) {
                abort(403, 'Unauthorized access. Administrator privileges required.');
            }
            return $next($request);
        });
    }

    /**
     * Display admin dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'active_sessions' => DB::table('sessions')
                ->where('last_activity', '>', now()->subHours(24)->timestamp)
                ->whereNotNull('user_id')
                ->distinct('user_id')
                ->count('user_id'),
            'total_activities' => ActivityLog::count(),
            'today_activities' => ActivityLog::whereDate('created_at', today())->count(),
        ];

        // Recent activities
        $recentActivities = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // Active sessions
        $activeSessions = DB::table('sessions')
            ->join('users', 'sessions.user_id', '=', 'users.id')
            ->where('sessions.last_activity', '>', now()->subHours(24)->timestamp)
            ->select('sessions.*', 'users.name', 'users.email', 'users.role')
            ->orderBy('sessions.last_activity', 'desc')
            ->get()
            ->map(function ($session) {
                $session->last_activity_formatted = Carbon::createFromTimestamp($session->last_activity)->diffForHumans();
                $session->is_current = $session->id === session()->getId();
                return $session;
            });

        // Activity by action type
        $activityByAction = ActivityLog::select('action', DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('action')
            ->orderBy('count', 'desc')
            ->get();

        // Top active users
        $topActiveUsers = ActivityLog::select('user_id', DB::raw('count(*) as activity_count'))
            ->with('user:id,name,email,role')
            ->where('created_at', '>=', now()->subDays(7))
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->orderBy('activity_count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentActivities',
            'activeSessions',
            'activityByAction',
            'topActiveUsers'
        ));
    }

    /**
     * Display activity logs
     */
    public function activityLogs(Request $request)
    {
        $query = ActivityLog::with('user');

        // Filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('route', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(25);
        
        $users = User::orderBy('name')->get();
        $actions = ActivityLog::distinct()->pluck('action');

        return view('admin.activity-logs', compact('logs', 'users', 'actions'));
    }

    /**
     * Display user sessions
     */
    public function sessions(Request $request)
    {
        $query = DB::table('sessions')
            ->join('users', 'sessions.user_id', '=', 'users.id')
            ->select('sessions.*', 'users.name', 'users.email', 'users.role');

        if ($request->filled('user_id')) {
            $query->where('sessions.user_id', $request->user_id);
        }

        if ($request->filled('active_only')) {
            $query->where('sessions.last_activity', '>', now()->subHours(24)->timestamp);
        }

        $sessions = $query->orderBy('sessions.last_activity', 'desc')
            ->get()
            ->map(function ($session) {
                $session->last_activity_formatted = Carbon::createFromTimestamp($session->last_activity)->format('Y-m-d H:i:s');
                $session->last_activity_human = Carbon::createFromTimestamp($session->last_activity)->diffForHumans();
                $session->is_current = $session->id === session()->getId();
                $session->is_active = $session->last_activity > now()->subHours(24)->timestamp;
                
                // Check if user is blocked from logging in
                $user = User::find($session->user_id);
                if ($user) {
                    $session->is_login_blocked = $user->isLoginBlocked();
                    $session->login_blocked_until = $user->login_blocked_until;
                    $session->remaining_block_time = $user->getRemainingBlockTime();
                } else {
                    $session->is_login_blocked = false;
                    $session->login_blocked_until = null;
                    $session->remaining_block_time = null;
                }
                
                return $session;
            });

        $users = User::orderBy('name')->get();

        return view('admin.sessions', compact('sessions', 'users'));
    }

    /**
     * Revoke a session
     */
    public function revokeSession(Request $request, string $sessionId)
    {
        // Prevent revoking own session
        if ($sessionId === session()->getId()) {
            return back()->with('error', 'You cannot revoke your own active session.');
        }

        // Get the user ID from the session before deleting it
        $session = DB::table('sessions')->where('id', $sessionId)->first();
        
        if (!$session || !$session->user_id) {
            return back()->with('error', 'Session not found or has no associated user.');
        }

        $userId = $session->user_id;

        // Delete the session
        DB::table('sessions')->where('id', $sessionId)->delete();

        // Block user from logging in for 3 minutes
        $blockedUntil = now()->addMinutes(3);
        User::where('id', $userId)->update([
            'login_blocked_until' => $blockedUntil
        ]);

        // Log this activity
        try {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'revoke',
                'description' => "Revoked session for user ID {$userId} and blocked login for 3 minutes",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'route' => $request->route()->getName(),
                'method' => $request->method(),
            ]);
        } catch (\Exception $e) {
            // Silently continue if logging fails
        }

        $user = User::find($userId);
        return back()->with('success', "Session revoked successfully. {$user->name} cannot login for 3 minutes.");
    }

    /**
     * Display user management
     */
    public function users()
    {
        $users = User::withCount('activityLogs')
            ->orderBy('name')
            ->get()
            ->map(function ($user) {
                $user->is_login_blocked = $user->isLoginBlocked();
                $user->remaining_block_time = $user->getRemainingBlockTime();
                return $user;
            });

        return view('admin.users', compact('users'));
    }

    /**
     * Unblock a user from logging in
     */
    public function unblockUser(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        
        $user->update(['login_blocked_until' => null]);

        // Log this activity
        try {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'unblock',
                'description' => "Manually unblocked user: {$user->name} (ID: {$userId})",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'route' => $request->route()->getName(),
                'method' => $request->method(),
            ]);
        } catch (\Exception $e) {
            // Silently continue if logging fails
        }

        return back()->with('success', "User {$user->name} has been unblocked and can now login.");
    }

    /**
     * Display roles and permissions
     */
    public function rolesPermissions()
    {
        $roles = ['admin', 'pastor', 'secretary', 'treasurer'];
        $permissions = Permission::orderBy('category')->orderBy('name')->get()->groupBy('category');

        $rolePermissions = [];
        foreach ($roles as $role) {
            $rolePermissions[$role] = DB::table('role_permissions')
                ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
                ->where('role_permissions.role', $role)
                ->pluck('permissions.slug')
                ->toArray();
        }

        return view('admin.roles-permissions', compact('roles', 'permissions', 'rolePermissions'));
    }

    /**
     * Update role permissions
     */
    public function updateRolePermissions(Request $request)
    {
        $request->validate([
            'role' => 'required|in:admin,pastor,secretary,treasurer',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,slug',
        ]);

        $role = $request->role;
        $permissionSlugs = $request->permissions ?? [];

        // Get permission IDs
        $permissionIds = Permission::whereIn('slug', $permissionSlugs)->pluck('id');

        // Delete existing permissions for this role
        DB::table('role_permissions')->where('role', $role)->delete();

        // Insert new permissions
        foreach ($permissionIds as $permissionId) {
            DB::table('role_permissions')->insert([
                'role' => $role,
                'permission_id' => $permissionId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Log this activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'description' => "Updated permissions for role: {$role}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'route' => $request->route()->getName(),
            'method' => $request->method(),
        ]);

        return back()->with('success', "Permissions updated successfully for {$role} role.");
    }

    /**
     * View user activity
     */
    public function userActivity($userId)
    {
        $user = User::findOrFail($userId);
        $activities = ActivityLog::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.user-activity', compact('user', 'activities'));
    }
}

