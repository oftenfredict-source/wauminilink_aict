<?php
// Script to regenerate Composer autoloader
echo "Regenerating Composer autoloader...\n";

// Use passthru to see real-time output
passthru('composer dump-autoload 2>&1', $returnCode);

if ($returnCode === 0) {
    echo "\nAutoloader regenerated successfully!\n";
} else {
    echo "\nError regenerating autoloader. Return code: $returnCode\n";
    echo "Trying alternative method...\n";
    passthru('composer install --no-interaction 2>&1', $returnCode2);
    if ($returnCode2 === 0) {
        echo "\nAutoloader regenerated via composer install!\n";
    }
}

