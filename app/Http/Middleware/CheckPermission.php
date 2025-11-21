<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!Auth::check()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please log in to access this page.',
                    'error' => 'Unauthorized'
                ], 401);
            }
            return redirect()->route('login')
                ->with('error', 'Please log in to access this page.');
        }

        $user = Auth::user();

        // Admin has all permissions
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Check if user has the required permission
        if (!$user->hasPermission($permission)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to perform this action. Please contact your administrator.',
                    'error' => 'Forbidden',
                    'permission_required' => $permission
                ], 403);
            }
            abort(403, 'You do not have permission to perform this action.');
        }

        return $next($request);
    }
}





