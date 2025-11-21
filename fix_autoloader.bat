@echo off
echo Regenerating Composer autoloader...
composer dump-autoload
if %errorlevel% neq 0 (
    echo Trying composer install...
    composer install --no-interaction
)
echo Done!
pause

