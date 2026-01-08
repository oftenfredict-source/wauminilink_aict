<?php
/**
 * Diagnostic Script: Check Image Upload & Display Issues
 * 
 * Run this from browser: https://yourdomain.com/demo/diagnose_image_display.php
 * Or via command line: php diagnose_image_display.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Member;
use Illuminate\Support\Facades\DB;

echo "<h2>Image Display Diagnostic Report</h2>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { color: green; }
    .error { color: red; }
    .warning { color: orange; }
    table { border-collapse: collapse; width: 100%; margin: 20px 0; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
    .code { background: #f4f4f4; padding: 10px; margin: 10px 0; font-family: monospace; }
</style>";

// 1. Check members with profile pictures
echo "<h3>1. Database Paths Check</h3>";
$members = Member::whereNotNull('profile_picture')
    ->select('id', 'full_name', 'profile_picture')
    ->limit(10)
    ->get();

if ($members->count() > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th><th>Database Path</th><th>Path Type</th><th>Status</th></tr>";
    
    foreach ($members as $member) {
        $path = $member->profile_picture;
        $pathType = 'Unknown';
        $status = '❌';
        $statusClass = 'error';
        
        if (strpos($path, 'assets/images/') === 0) {
            $pathType = '✅ Correct (assets/images/)';
            $status = '✅';
            $statusClass = 'success';
        } elseif (strpos($path, 'members/profile-pictures/') === 0) {
            $pathType = '⚠️ Missing assets/images/ prefix';
            $status = '⚠️';
            $statusClass = 'warning';
        } elseif (strpos($path, 'storage/') === 0 || strpos($path, 'member/profile-pictures/') === 0) {
            $pathType = '❌ Old storage path';
            $status = '❌';
            $statusClass = 'error';
        }
        
        echo "<tr>";
        echo "<td>{$member->id}</td>";
        echo "<td>{$member->full_name}</td>";
        echo "<td><code>{$path}</code></td>";
        echo "<td>{$pathType}</td>";
        echo "<td class='{$statusClass}'>{$status}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p class='warning'>No members with profile pictures found in database.</p>";
}

// 2. Check file existence
echo "<h3>2. File Existence Check</h3>";
$uploadPath = public_path('assets/images/members/profile-pictures');
echo "<p><strong>Upload Directory:</strong> <code>{$uploadPath}</code></p>";

if (file_exists($uploadPath)) {
    echo "<p class='success'>✅ Directory exists</p>";
    $files = glob($uploadPath . '/*.{jpg,jpeg,png,JPG,JPEG,PNG}', GLOB_BRACE);
    echo "<p><strong>Files found:</strong> " . count($files) . "</p>";
    
    if (count($files) > 0) {
        echo "<table>";
        echo "<tr><th>Filename</th><th>Size</th><th>Modified</th></tr>";
        foreach (array_slice($files, 0, 10) as $file) {
            $filename = basename($file);
            $size = filesize($file);
            $modified = date('Y-m-d H:i:s', filemtime($file));
            echo "<tr><td>{$filename}</td><td>" . number_format($size / 1024, 2) . " KB</td><td>{$modified}</td></tr>";
        }
        echo "</table>";
    }
} else {
    echo "<p class='error'>❌ Directory does not exist: {$uploadPath}</p>";
    echo "<p><strong>Fix:</strong> Create directory: <code>mkdir -p {$uploadPath}</code></p>";
}

// 3. Check URL generation
echo "<h3>3. URL Generation Check</h3>";
if ($members->count() > 0) {
    $sampleMember = $members->first();
    $dbPath = $sampleMember->profile_picture;
    $generatedUrl = asset($dbPath);
    $baseUrl = url('/');
    
    echo "<div class='code'>";
    echo "<strong>Database Path:</strong> {$dbPath}<br>";
    echo "<strong>Base URL:</strong> {$baseUrl}<br>";
    echo "<strong>Generated URL:</strong> {$generatedUrl}<br>";
    echo "</div>";
    
    // Check if URL matches file location
    $expectedPath = public_path($dbPath);
    if (file_exists($expectedPath)) {
        echo "<p class='success'>✅ File exists at expected location</p>";
    } else {
        echo "<p class='error'>❌ File NOT found at: <code>{$expectedPath}</code></p>";
    }
}

// 4. Check view code
echo "<h3>4. View Code Check</h3>";
$dashboardView = resource_path('views/members/dashboard.blade.php');
if (file_exists($dashboardView)) {
    $content = file_get_contents($dashboardView);
    if (strpos($content, "asset(\$member->profile_picture)") !== false) {
        echo "<p class='success'>✅ View uses correct format: <code>asset(\$member->profile_picture)</code></p>";
    } elseif (strpos($content, "asset('storage/' . \$member->profile_picture)") !== false) {
        echo "<p class='error'>❌ View uses WRONG format: <code>asset('storage/' . \$member->profile_picture)</code></p>";
        echo "<p><strong>Fix:</strong> Remove 'storage/' prefix from view file</p>";
    } else {
        echo "<p class='warning'>⚠️ Could not determine view format</p>";
    }
}

// 5. Recommendations
echo "<h3>5. Recommendations</h3>";
echo "<ul>";

// Check for path mismatches
$wrongPaths = Member::whereNotNull('profile_picture')
    ->where(function($query) {
        $query->where('profile_picture', 'NOT LIKE', 'assets/images/%')
              ->orWhere('profile_picture', 'LIKE', 'storage/%')
              ->orWhere('profile_picture', 'LIKE', 'member/profile-pictures/%');
    })
    ->count();

if ($wrongPaths > 0) {
    echo "<li class='error'><strong>❌ {$wrongPaths} members have incorrect database paths</strong></li>";
    echo "<li>Run this SQL to fix paths:</li>";
    echo "<div class='code'>";
    echo "UPDATE members<br>";
    echo "SET profile_picture = CONCAT('assets/images/', profile_picture)<br>";
    echo "WHERE profile_picture IS NOT NULL<br>";
    echo "AND profile_picture NOT LIKE 'assets/images/%'<br>";
    echo "AND profile_picture LIKE 'members/profile-pictures/%';";
    echo "</div>";
}

// Check directory permissions
if (file_exists($uploadPath)) {
    $perms = substr(sprintf('%o', fileperms($uploadPath)), -4);
    if ($perms != '0755' && $perms != '0775') {
        echo "<li class='warning'>⚠️ Directory permissions: {$perms} (should be 755)</li>";
        echo "<li>Fix: <code>chmod 755 {$uploadPath}</code></li>";
    } else {
        echo "<li class='success'>✅ Directory permissions correct: {$perms}</li>";
    }
}

echo "</ul>";

// 6. Quick Fix SQL
echo "<h3>6. Quick Fix SQL Queries</h3>";
echo "<div class='code'>";
echo "<strong>Fix paths missing 'assets/images/' prefix:</strong><br>";
echo "UPDATE members<br>";
echo "SET profile_picture = CONCAT('assets/images/', profile_picture)<br>";
echo "WHERE profile_picture IS NOT NULL<br>";
echo "AND profile_picture NOT LIKE 'assets/images/%'<br>";
echo "AND profile_picture LIKE 'members/profile-pictures/%';<br><br>";
echo "<strong>Check current paths:</strong><br>";
echo "SELECT id, full_name, profile_picture FROM members WHERE profile_picture IS NOT NULL LIMIT 10;";
echo "</div>";

echo "<hr>";
echo "<p><em>Run this diagnostic after uploading files to identify any issues.</em></p>";
