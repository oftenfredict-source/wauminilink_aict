<?php
/**
 * Force regenerate Composer autoloader using Composer's internal API
 */

// Change to project directory
chdir(__DIR__);

// Check if composer.json exists
if (!file_exists('composer.json')) {
    die("ERROR: composer.json not found\n");
}

echo "Regenerating Composer autoloader...\n\n";

// Method 1: Try using Composer's autoloader directly
if (file_exists('vendor/composer/ClassLoader.php')) {
    require_once 'vendor/composer/ClassLoader.php';
}

// Method 2: Try to find and use Composer
$composerPaths = [
    'composer',
    'composer.phar',
    getenv('COMPOSER_HOME') . '/composer.phar' ?? null,
    getenv('LOCALAPPDATA') . '/ComposerSetup/bin/composer.bat' ?? null,
];

$composerCmd = null;
foreach ($composerPaths as $path) {
    if ($path && (file_exists($path) || shell_exec("where $path 2>nul"))) {
        $composerCmd = $path;
        break;
    }
}

if (!$composerCmd) {
    // Try to find composer in PATH
    $output = shell_exec('where composer 2>nul');
    if ($output && trim($output)) {
        $composerCmd = 'composer';
    }
}

if (!$composerCmd) {
    die("ERROR: Composer not found. Please install Composer or add it to your PATH.\n");
}

echo "Found Composer at: $composerCmd\n";
echo "Running: $composerCmd dump-autoload\n\n";

// Run composer dump-autoload
$command = escapeshellcmd($composerCmd) . ' dump-autoload 2>&1';
echo "Command: $command\n\n";

$output = [];
$returnCode = 0;
exec($command, $output, $returnCode);

foreach ($output as $line) {
    echo $line . "\n";
}

echo "\n";

if ($returnCode === 0) {
    echo "✓ SUCCESS!\n\n";
    
    // Verify files
    $files = [
        'vendor/composer/autoload_real.php',
        'vendor/composer/autoload_static.php',
        'vendor/composer/autoload_psr4.php',
    ];
    
    $allGood = true;
    foreach ($files as $file) {
        if (file_exists($file)) {
            echo "✓ $file exists\n";
        } else {
            echo "✗ $file MISSING\n";
            $allGood = false;
        }
    }
    
    if ($allGood) {
        echo "\n✓ All autoloader files regenerated successfully!\n";
    }
} else {
    echo "✗ FAILED (return code: $returnCode)\n";
    echo "\nTrying alternative: composer install\n\n";
    
    $command2 = escapeshellcmd($composerCmd) . ' install --no-interaction 2>&1';
    exec($command2, $output2, $returnCode2);
    
    foreach ($output2 as $line) {
        echo $line . "\n";
    }
    
    if ($returnCode2 === 0) {
        echo "\n✓ Autoloader regenerated via composer install!\n";
    } else {
        echo "\n✗ Both methods failed. Please run manually:\n";
        echo "   composer dump-autoload\n";
        echo "   or\n";
        echo "   composer install\n";
    }
}

