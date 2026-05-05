<?php
/**
 * WauminiLink Emergency Repair Script
 * Place this file in your website root folder (where index.php and vendor folder are)
 * and visit: https://aict-moshi.wauminilink.co.tz/repair.php
 */

define('LARAVEL_START', microtime(true));

// Auto-detect path to vendor and bootstrap
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
} elseif (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
} else {
    die("Could not find Laravel installation. Please ensure this file is in the same folder as 'vendor' or 'public'.");
}

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

// Bootstrap Laravel to access database and Artisan
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "<div style='font-family: sans-serif; padding: 20px; max-width: 600px; margin: auto; border: 1px solid #ccc; border-radius: 10px; margin-top: 50px;'>";
echo "<h1 style='color: maroon;'>WauminiLink Repair</h1>";
echo "<p><strong>Environment Info:</strong></p>";
echo "<ul>";
echo "<li>APP_URL: " . config('app.url') . "</li>";
echo "<li>APP_ENV: " . config('app.env') . "</li>";
echo "<li>Scheme: " . (request()->isSecure() ? 'HTTPS' : 'HTTP') . "</li>";
echo "</ul>";
echo "<ul style='line-height: 1.6;'>";

try {
    // 1. Force HTTPS
    echo "<li>Forcing HTTPS Scheme...</li>";
    URL::forceScheme('https');

    // 2. Clear Caches
    echo "<li>Clearing all Laravel caches (Optimize, View, Config, Route)...</li>";
    Artisan::call('optimize:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');

    echo "<li style='color: blue;'>Caches cleared successfully.</li>";

    // 3. Fix SMS/OTP Settings in Database
    echo "<li>Checking SMS/OTP Settings in Database...</li>";
    $settingsToFix = [
        ['key' => 'enable_sms_notifications', 'value' => '1', 'type' => 'boolean'],
        ['key' => 'enable_otp', 'value' => '1', 'type' => 'boolean'],
        ['key' => 'sms_sender_id', 'value' => 'WauminiLnk', 'type' => 'string'],
    ];

    foreach ($settingsToFix as $s) {
        DB::table('system_settings')->updateOrInsert(
            ['key' => $s['key']],
            [
                'value' => $s['value'],
                'type' => $s['type'],
                'category' => 'notifications',
                'is_editable' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        );
    }

    echo "<li style='color: blue;'>Database settings verified and fixed.</li>";

    // 4. Final Clear
    Artisan::call('optimize:clear');

    echo "</ul>";
    echo "<div style='background: #e6ffed; padding: 15px; border-radius: 5px; color: #22863a; border: 1px solid #34d058;'>";
    echo "<strong>SUCCESS! System is Repaired.</strong>";
    echo "</div>";

    echo "<p style='margin-top: 20px;'><strong>IMPORTANT NEXT STEPS:</strong></p>";
    echo "<ol>";
    echo "<li><strong>DELETE this file (repair.php)</strong> from your server immediately for security!</li>";
    echo "<li>Open your website login page.</li>";
    echo "<li>Press <strong>Ctrl + F5</strong> (Hard Refresh) to clear old CSS cache from your browser.</li>";
    echo "</ol>";

    echo "<a href='/login' style='display: inline-block; padding: 10px 20px; background: maroon; color: white; text-decoration: none; border-radius: 5px; margin-top: 10px;'>Go to Login</a>";

} catch (\Exception $e) {
    echo "<li style='color: red;'><strong>Error:</strong> " . $e->getMessage() . "</li>";
    echo "</ul>";
}

echo "</div>";
