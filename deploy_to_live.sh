#!/bin/bash

# ============================================
# DEPLOYMENT SCRIPT FOR WAUMINI LINK
# ============================================
# 
# INSTRUCTIONS:
# 1. Edit the variables below with your server details
# 2. Make the script executable: chmod +x deploy_to_live.sh
# 3. Run: ./deploy_to_live.sh
#
# ============================================

# ============================================
# CHANGE THESE VARIABLES FOR YOUR SERVER
# ============================================

# Your server SSH connection
# Format: username@server-ip-or-domain
SSH_CONNECTION="username@wauminilink.co.tz"

# Path to your project on the live server
# Common paths: /home/username/public_html/demo or /var/www/html/demo
PROJECT_PATH="/home/wauminilink/demo"

# PHP version on your server (check with: php -v)
# Options: php, php8.0, php8.1, php8.2, php8.3
PHP_VERSION="php8.1"

# Web server type
# Options: apache2, nginx
WEB_SERVER="apache2"

# ============================================
# DO NOT CHANGE BELOW THIS LINE
# ============================================

echo "============================================"
echo "Waumini Link - Deployment Script"
echo "============================================"
echo ""
echo "Deploying to: $SSH_CONNECTION"
echo "Project path: $PROJECT_PATH"
echo ""

# Step 1: Pull latest changes
echo "Step 1: Pulling latest changes from GitHub..."
ssh $SSH_CONNECTION "cd $PROJECT_PATH && git pull origin main"
if [ $? -ne 0 ]; then
    echo "❌ Error: Failed to pull changes"
    exit 1
fi
echo "✅ Changes pulled successfully"
echo ""

# Step 2: Clear Laravel caches
echo "Step 2: Clearing Laravel caches..."
ssh $SSH_CONNECTION "cd $PROJECT_PATH && $PHP_VERSION artisan config:clear"
ssh $SSH_CONNECTION "cd $PROJECT_PATH && $PHP_VERSION artisan cache:clear"
ssh $SSH_CONNECTION "cd $PROJECT_PATH && $PHP_VERSION artisan view:clear"
ssh $SSH_CONNECTION "cd $PROJECT_PATH && $PHP_VERSION artisan route:clear"
echo "✅ Caches cleared"
echo ""

# Step 3: Restart web server (optional, uncomment if needed)
# echo "Step 3: Restarting web server..."
# ssh $SSH_CONNECTION "sudo service $WEB_SERVER restart"
# echo "✅ Web server restarted"
# echo ""

# Step 4: Restart PHP-FPM (optional, uncomment if needed)
# echo "Step 4: Restarting PHP-FPM..."
# ssh $SSH_CONNECTION "sudo service $PHP_VERSION-fpm restart"
# echo "✅ PHP-FPM restarted"
# echo ""

echo "============================================"
echo "✅ Deployment completed successfully!"
echo "============================================"
echo ""
echo "Your changes are now live at: https://www.wauminilink.co.tz/demo"
echo ""









