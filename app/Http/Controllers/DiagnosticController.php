<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class DiagnosticController extends Controller
{
    public function imageDisplay()
    {
        echo "<h2>Image Display Diagnostic Report</h2>";
        echo "<style>
            body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
            .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
            .success { color: green; font-weight: bold; }
            .error { color: red; font-weight: bold; }
            .warning { color: orange; font-weight: bold; }
            table { border-collapse: collapse; width: 100%; margin: 20px 0; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; font-weight: bold; }
            .code { background: #f4f4f4; padding: 15px; margin: 10px 0; font-family: monospace; border-left: 4px solid #007bff; overflow-x: auto; }
            h3 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
            .fix-box { background: #fff3cd; border: 1px solid #ffc107; padding: 15px; margin: 10px 0; border-radius: 4px; }
        </style>";
        echo "<div class='container'>";

        // 1. Check members with profile pictures
        echo "<h3>1. Database Paths Check</h3>";
        $members = Member::whereNotNull('profile_picture')
            ->select('id', 'full_name', 'profile_picture')
            ->limit(10)
            ->get();

        if ($members->count() > 0) {
            echo "<table>";
            echo "<tr><th>ID</th><th>Name</th><th>Database Path</th><th>Path Type</th><th>Status</th></tr>";
            
            $hasWrongPaths = false;
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
                    $hasWrongPaths = true;
                } elseif (strpos($path, 'storage/') === 0 || strpos($path, 'member/profile-pictures/') === 0) {
                    $pathType = '❌ Old storage path';
                    $status = '❌';
                    $statusClass = 'error';
                    $hasWrongPaths = true;
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
            
            if ($hasWrongPaths) {
                echo "<div class='fix-box'>";
                echo "<strong>⚠️ Found incorrect paths!</strong><br>";
                echo "Run this SQL to fix:<br>";
                echo "<div class='code'>";
                echo "UPDATE members<br>";
                echo "SET profile_picture = CONCAT('assets/images/', profile_picture)<br>";
                echo "WHERE profile_picture IS NOT NULL<br>";
                echo "AND profile_picture NOT LIKE 'assets/images/%'<br>";
                echo "AND profile_picture LIKE 'members/profile-pictures/%';";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p class='warning'>⚠️ No members with profile pictures found in database.</p>";
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
                echo "<tr><th>Filename</th><th>Size</th><th>Modified</th><th>Accessible?</th></tr>";
                foreach (array_slice($files, 0, 10) as $file) {
                    $filename = basename($file);
                    $size = filesize($file);
                    $modified = date('Y-m-d H:i:s', filemtime($file));
                    $relativePath = 'assets/images/members/profile-pictures/' . $filename;
                    $url = asset($relativePath);
                    $accessible = file_exists(public_path($relativePath)) ? '✅ Yes' : '❌ No';
                    
                    echo "<tr>";
                    echo "<td>{$filename}</td>";
                    echo "<td>" . number_format($size / 1024, 2) . " KB</td>";
                    echo "<td>{$modified}</td>";
                    echo "<td>{$accessible}</td>";
                    echo "</tr>";
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
            echo "<strong>Sample Member:</strong> {$sampleMember->full_name} (ID: {$sampleMember->id})<br>";
            echo "<strong>Database Path:</strong> {$dbPath}<br>";
            echo "<strong>Base URL:</strong> {$baseUrl}<br>";
            echo "<strong>Generated URL:</strong> <a href='{$generatedUrl}' target='_blank'>{$generatedUrl}</a><br>";
            echo "</div>";
            
            // Check if URL matches file location
            $expectedPath = public_path($dbPath);
            if (file_exists($expectedPath)) {
                echo "<p class='success'>✅ File exists at expected location</p>";
            } else {
                echo "<p class='error'>❌ File NOT found at: <code>{$expectedPath}</code></p>";
                echo "<p class='warning'>This means the database path doesn't match the actual file location!</p>";
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

        // 5. Directory permissions
        echo "<h3>5. Directory Permissions Check</h3>";
        if (file_exists($uploadPath)) {
            $perms = substr(sprintf('%o', fileperms($uploadPath)), -4);
            if ($perms == '0755' || $perms == '0775') {
                echo "<p class='success'>✅ Directory permissions correct: {$perms}</p>";
            } else {
                echo "<p class='warning'>⚠️ Directory permissions: {$perms} (should be 755)</p>";
                echo "<p><strong>Fix:</strong> <code>chmod 755 {$uploadPath}</code></p>";
            }
        }

        // 6. Summary & Recommendations
        echo "<h3>6. Summary & Quick Fix</h3>";
        echo "<div class='fix-box'>";
        echo "<strong>Most Common Issue: Database Path Mismatch</strong><br><br>";
        echo "If images upload but don't display, the database likely has paths like:<br>";
        echo "<code>members/profile-pictures/filename.jpg</code> ❌<br><br>";
        echo "But should have:<br>";
        echo "<code>assets/images/members/profile-pictures/filename.jpg</code> ✅<br><br>";
        echo "<strong>Quick Fix SQL:</strong><br>";
        echo "<div class='code'>";
        echo "UPDATE members<br>";
        echo "SET profile_picture = CONCAT('assets/images/', profile_picture)<br>";
        echo "WHERE profile_picture IS NOT NULL<br>";
        echo "AND profile_picture NOT LIKE 'assets/images/%'<br>";
        echo "AND profile_picture LIKE 'members/profile-pictures/%';";
        echo "</div>";
        echo "<br>";
        echo "<strong>After running SQL:</strong><br>";
        echo "1. Clear cache: <code>php artisan view:clear</code><br>";
        echo "2. Hard refresh browser: <code>Ctrl + F5</code>";
        echo "</div>";

        echo "</div>"; // Close container
        echo "<hr>";
        echo "<p style='text-align: center; color: #666;'><em>Diagnostic completed at " . date('Y-m-d H:i:s') . "</em></p>";
    }
}


