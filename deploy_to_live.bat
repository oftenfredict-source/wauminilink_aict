@echo off
REM ============================================
REM DEPLOYMENT SCRIPT FOR WAUMINI LINK (Windows)
REM ============================================
REM 
REM INSTRUCTIONS:
REM 1. Edit the variables below with your server details
REM 2. Make sure you have SSH access configured
REM 3. Run: deploy_to_live.bat
REM
REM ============================================

REM ============================================
REM CHANGE THESE VARIABLES FOR YOUR SERVER
REM ============================================

REM Your server SSH connection
REM Format: username@server-ip-or-domain
set SSH_CONNECTION=username@wauminilink.co.tz

REM Path to your project on the live server
REM Common paths: /home/username/public_html/demo or /var/www/html/demo
set PROJECT_PATH=/home/wauminilink/demo

REM PHP version on your server
REM Options: php, php8.0, php8.1, php8.2, php8.3
set PHP_VERSION=php8.1

REM ============================================
REM DO NOT CHANGE BELOW THIS LINE
REM ============================================

echo ============================================
echo Waumini Link - Deployment Script
echo ============================================
echo.
echo Deploying to: %SSH_CONNECTION%
echo Project path: %PROJECT_PATH%
echo.

REM Step 1: Pull latest changes
echo Step 1: Pulling latest changes from GitHub...
ssh %SSH_CONNECTION% "cd %PROJECT_PATH% && git pull origin main"
if errorlevel 1 (
    echo [ERROR] Failed to pull changes
    exit /b 1
)
echo [OK] Changes pulled successfully
echo.

REM Step 2: Clear Laravel caches
echo Step 2: Clearing Laravel caches...
ssh %SSH_CONNECTION% "cd %PROJECT_PATH% && %PHP_VERSION% artisan config:clear"
ssh %SSH_CONNECTION% "cd %PROJECT_PATH% && %PHP_VERSION% artisan cache:clear"
ssh %SSH_CONNECTION% "cd %PROJECT_PATH% && %PHP_VERSION% artisan view:clear"
ssh %SSH_CONNECTION% "cd %PROJECT_PATH% && %PHP_VERSION% artisan route:clear"
echo [OK] Caches cleared
echo.

echo ============================================
echo [OK] Deployment completed successfully!
echo ============================================
echo.
echo Your changes are now live at: https://www.wauminilink.co.tz/demo
echo.

pause









