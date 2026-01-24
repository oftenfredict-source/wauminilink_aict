# Deployment Instructions

## Quick Deployment Scripts

Two deployment scripts have been created for you:

1. **`deploy_to_live.sh`** - For Linux/Mac users
2. **`deploy_to_live.bat`** - For Windows users

## What You Need to Change

### 1. Edit the Script Variables

Open the appropriate script file and change these variables:

#### For Linux/Mac (`deploy_to_live.sh`):
```bash
# Change these lines:
SSH_CONNECTION="username@wauminilink.co.tz"        # Your SSH username and server
PROJECT_PATH="/home/wauminilink/demo"              # Path to your project on server
PHP_VERSION="php8.1"                               # Your PHP version
WEB_SERVER="apache2"                               # apache2 or nginx
```

#### For Windows (`deploy_to_live.bat`):
```batch
REM Change these lines:
set SSH_CONNECTION=username@wauminilink.co.tz      REM Your SSH username and server
set PROJECT_PATH=/home/wauminilink/demo            REM Path to your project on server
set PHP_VERSION=php8.1                             REM Your PHP version
```

### 2. Find Your Server Details

#### SSH Connection:
- **Format**: `username@server-ip-or-domain`
- **Example**: `wauminilink@wauminilink.co.tz` or `root@123.45.67.89`
- **How to find**: Check your hosting provider's control panel or ask your server admin

#### Project Path:
- **Common paths**:
  - `/home/username/public_html/demo`
  - `/var/www/html/demo`
  - `/home/wauminilink/demo`
- **How to find**: SSH into your server and run `pwd` in your project directory

#### PHP Version:
- **How to find**: SSH into your server and run `php -v`
- **Common versions**: `php8.0`, `php8.1`, `php8.2`, `php8.3`
- **Note**: Some servers use just `php` instead of `php8.1`

## How to Use

### Option 1: Using the Script (Recommended)

#### Linux/Mac:
```bash
# Make script executable
chmod +x deploy_to_live.sh

# Edit the script first (change variables)
nano deploy_to_live.sh

# Run the script
./deploy_to_live.sh
```

#### Windows:
```batch
# Edit the script first (change variables)
notepad deploy_to_live.bat

# Run the script
deploy_to_live.bat
```

### Option 2: Manual Deployment (SSH)

If you prefer to run commands manually:

```bash
# 1. SSH into your server
ssh username@wauminilink.co.tz

# 2. Navigate to project
cd /home/wauminilink/demo

# 3. Pull changes
git pull origin main

# 4. Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 5. Optional: Restart services (if needed)
sudo service apache2 restart
# OR
sudo service php8.1-fpm restart
```

## Troubleshooting

### "Permission denied" error
- Make sure your SSH key is set up correctly
- Check that you have the correct username
- Verify SSH access in your hosting control panel

### "Command not found: php8.1"
- Try using just `php` instead
- Check your PHP version: `php -v`
- Some servers use: `php8.0`, `php8.2`, etc.

### "git pull" fails
- Make sure you're in the correct directory
- Check that git is installed on the server
- Verify you have the correct permissions

### Changes not showing
- Clear browser cache (Ctrl+F5)
- Make sure you cleared Laravel caches
- Restart PHP-FPM or Apache if using opcache

## Need Help?

If you're unsure about any of these values:
1. Check your hosting provider's documentation
2. Contact your server administrator
3. Check your cPanel or hosting control panel for server details








