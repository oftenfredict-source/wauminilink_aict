<?php
/**
 * Script to regenerate Composer autoloader files
 * Run this with: php fix_composer.php
 */

echo "========================================\n";
echo "Composer Autoloader Regeneration Script\n";
echo "========================================\n\n";

// Check if we're in the right directory
if (!file_exists('composer.json')) {
    die("ERROR: composer.json not found. Please run this script from your project root.\n");
}

echo "✓ Found composer.json\n";

// Check if vendor directory exists
if (!is_dir('vendor')) {
    die("ERROR: vendor directory not found. Please run 'composer install' first.\n");
}

echo "✓ Found vendor directory\n\n";

// Try to run composer dump-autoload
echo "Attempting to regenerate autoloader...\n";
echo "Running: composer dump-autoload\n\n";

$output = [];
$returnCode = 0;

// Use proc_open for better control
$descriptorspec = [
    0 => ['pipe', 'r'],  // stdin
    1 => ['pipe', 'w'],  // stdout
    2 => ['pipe', 'w']   // stderr
];

$process = proc_open('composer dump-autoload', $descriptorspec, $pipes);

if (is_resource($process)) {
    // Close stdin
    fclose($pipes[0]);
    
    // Read stdout
    $stdout = stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    
    // Read stderr
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[2]);
    
    // Get return code
    $returnCode = proc_close($process);
    
    echo $stdout;
    if ($stderr) {
        echo "STDERR: " . $stderr . "\n";
    }
} else {
    // Fallback to exec
    echo "Using fallback method...\n";
    exec('composer dump-autoload 2>&1', $output, $returnCode);
    foreach ($output as $line) {
        echo $line . "\n";
    }
}

echo "\n";

if ($returnCode === 0) {
    echo "✓ SUCCESS! Autoloader regenerated.\n\n";
    
    // Verify the files exist
    $requiredFiles = [
        'vendor/composer/autoload_real.php',
        'vendor/composer/autoload_static.php',
        'vendor/composer/autoload_psr4.php',
        'vendor/composer/autoload_classmap.php',
        'vendor/composer/autoload_namespaces.php'
    ];
    
    echo "Verifying autoloader files...\n";
    $allExist = true;
    foreach ($requiredFiles as $file) {
        if (file_exists($file)) {
            echo "  ✓ $file\n";
        } else {
            echo "  ✗ $file (MISSING)\n";
            $allExist = false;
        }
    }
    
    if ($allExist) {
        echo "\n✓ All autoloader files are present!\n";
        echo "You can now run: php artisan serve\n";
    } else {
        echo "\n⚠ Some files are still missing. Try running 'composer install' instead.\n";
    }
} else {
    echo "✗ FAILED with return code: $returnCode\n";
    echo "\nTry running manually:\n";
    echo "  composer dump-autoload\n";
    echo "Or:\n";
    echo "  composer install\n";
}

echo "\n";

