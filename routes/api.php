<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SettingsApiController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\MemberApiController;
use App\Http\Controllers\Api\DepartmentApiController;
use App\Http\Controllers\Api\PrayerRequestApiController;
use App\Http\Controllers\Api\AttendanceApiController;
use App\Http\Controllers\Api\BereavementApiController;
use App\Http\Controllers\Api\ServiceApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Mobile App Authentication Routes (Public)
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthApiController::class, 'login']);
    Route::post('/logout', [AuthApiController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/me', [AuthApiController::class, 'me'])->middleware('auth:sanctum');
});

// Protected Mobile API Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [MemberApiController::class, 'dashboard']);
    Route::get('/profile', [MemberApiController::class, 'profile']);
    Route::post('/profile', [MemberApiController::class, 'updateProfile']);
    Route::get('/services', [MemberApiController::class, 'services']);
    Route::get('/events', [MemberApiController::class, 'events']);
    Route::get('/announcements', [MemberApiController::class, 'announcements']);
    Route::post('/announcements/{announcementId}/read', [MemberApiController::class, 'markAnnouncementAsRead']);
    Route::get('/leaders', [MemberApiController::class, 'leaders']);
    
    // Prayer Request Routes
    Route::get('/prayer-requests', [PrayerRequestApiController::class, 'index']);
    Route::post('/prayer-requests', [PrayerRequestApiController::class, 'store']);
    Route::get('/prayer-requests/{id}', [PrayerRequestApiController::class, 'show']);
    
    // Attendance QR Route
    Route::post('/attendance/scan', [AttendanceApiController::class, 'scan']);
    
    // Bereavement Routes
    Route::get('/bereavement', [BereavementApiController::class, 'index']);
    Route::get('/bereavement/history', [BereavementApiController::class, 'myHistory']);
    Route::get('/bereavement/{id}', [BereavementApiController::class, 'show']);
    
    // Additional Service Routes
    Route::get('/annual-fees', [MemberApiController::class, 'annualFees']);
    Route::get('/celebrations', [MemberApiController::class, 'celebrations']);
    Route::post('/upload-receipt', [MemberApiController::class, 'uploadReceipt']);
    Route::get('/assignments', [MemberApiController::class, 'assignments']);

    // Service Explorer Routes
    Route::get('/fellowships', [ServiceApiController::class, 'fellowships']);
    Route::get('/sermons', [ServiceApiController::class, 'sermons']);
    Route::get('/calendar', [ServiceApiController::class, 'calendar']);
    Route::get('/service-requests', [ServiceApiController::class, 'myRequests']);
    Route::post('/service-requests', [ServiceApiController::class, 'submitRequest']);
});

// Department API Routes (Protected)
Route::prefix('departments')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [DepartmentApiController::class, 'index']);
    Route::get('/{id}', [DepartmentApiController::class, 'show']);
});

// Settings API Routes
Route::prefix('settings')->middleware('auth:sanctum')->group(function () {
    // Basic CRUD operations
    Route::get('/', [SettingsApiController::class, 'index']);
    Route::get('/grouped', [SettingsApiController::class, 'getGrouped']);
    Route::get('/statistics', [SettingsApiController::class, 'statistics']);
    Route::get('/{key}', [SettingsApiController::class, 'show']);
    Route::get('/{key}/value', [SettingsApiController::class, 'getValue']);
    Route::post('/{key}/value', [SettingsApiController::class, 'setValue']);
    
    // Category operations
    Route::get('/category/{category}', [SettingsApiController::class, 'getByCategory']);
    
    // Bulk operations
    Route::post('/bulk-update', [SettingsApiController::class, 'bulkUpdate']);
    Route::post('/import', [SettingsApiController::class, 'import']);
    Route::get('/export', [SettingsApiController::class, 'export']);
    Route::post('/reset', [SettingsApiController::class, 'reset']);
    
    // Audit logs
    Route::get('/audit-logs', [SettingsApiController::class, 'getAuditLogs']);
});

// Public settings (no authentication required)
Route::prefix('public/settings')->group(function () {
    Route::get('/', function () {
        $settings = \App\Models\SystemSetting::where('is_public', true)
            ->get()
            ->mapWithKeys(function ($setting) {
                return [$setting->key => $setting->value];
            });
        
        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    });
    
    Route::get('/{key}', function ($key) {
        $setting = \App\Models\SystemSetting::where('key', $key)
            ->where('is_public', true)
            ->first();
        
        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'Setting not found or not public'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'key' => $key,
            'value' => $setting->value
        ]);
    });
});
