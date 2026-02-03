# Live Server Deployment Guide - PDF Generation Fix

## Step-by-Step Deployment Instructions

### Step 1: Upload Changed Files to Live Server

Upload these files to your live server (via FTP, SFTP, or hosting control panel):

1. **`app/Http/Controllers/ReportController.php`** - Updated with DomPDF
2. **`composer.json`** - Contains new DomPDF dependency
3. **`composer.lock`** - Lock file with exact versions

**Upload location:** Your project root directory (same location as your current files)

---

### Step 2: Connect to Live Server via SSH

**Option A: Using SSH Terminal (Recommended)**
```bash
ssh username@your-server-ip
# OR
ssh username@www.wauminilink.co.tz
```

**Option B: Using Hosting Control Panel**
- Most hosting providers have a "Terminal" or "SSH" option in their control panel
- Look for: cPanel → Terminal, Plesk → SSH Access, or similar

**Option C: Using FTP/SFTP Client with Terminal**
- Some FTP clients (like FileZilla) have built-in terminal access

---

### Step 3: Navigate to Project Directory

Once connected via SSH, navigate to your Laravel project:

```bash
cd /path/to/your/project
# Example paths:
# cd /home/username/public_html
# cd /var/www/html
# cd /home/username/wauminilink
# cd /var/www/wauminilink
```

**Note:** Replace with your actual project path. If unsure, ask your hosting provider.

---

### Step 4: Install Dependencies

Run composer install to download DomPDF and its dependencies:

```bash
composer install --no-dev --optimize-autoloader
```

**Flags explained:**
- `--no-dev` - Skips development packages (faster, smaller)
- `--optimize-autoloader` - Optimizes for production

**Alternative (if composer install fails):**
```bash
composer update barryvdh/laravel-dompdf --no-dev
```

---

### Step 5: Clear All Caches

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear
```

---

### Step 6: Set Proper Permissions (if needed)

```bash
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage
```

---

### Step 7: Test the PDF Download

1. Go to your live site: `https://www.wauminilink.co.tz/demo`
2. Navigate to any report page
3. Click "Download PDF" or "Export PDF"
4. Download the file
5. Try opening it in a PDF reader - it should work!

---

## Alternative: If You Don't Have SSH Access

If your hosting provider doesn't allow SSH access:

### Option 1: Use Hosting Control Panel Terminal
- Most modern hosting providers have a web-based terminal
- Access it through your hosting control panel
- Follow steps 3-5 above

### Option 2: Upload vendor Directory
If you can't run composer on the server:

1. **On your local machine**, run:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

2. **Upload the entire `vendor` directory** to your live server
   - This is a large directory (100+ MB)
   - Use FTP/SFTP to upload
   - Make sure to upload to the project root

3. **Clear caches** (if you have access to run artisan commands via hosting panel)

### Option 3: Ask Your Hosting Provider
- Contact your hosting provider support
- Ask them to run `composer install` in your project directory
- Provide them with the project path

---

## Troubleshooting

### Error: "composer: command not found"
**Solution:** Composer might not be installed on the server. Ask your hosting provider to install it, or use Option 2 above.

### Error: "Permission denied"
**Solution:** 
```bash
chmod +x composer.phar  # If using composer.phar
# OR ask hosting provider to fix permissions
```

### Error: "Memory limit exceeded"
**Solution:**
```bash
php -d memory_limit=512M composer install --no-dev
```

### Error: "Class 'Barryvdh\DomPDF\Facade\Pdf' not found"
**Solution:** 
- Make sure `composer install` completed successfully
- Check that `vendor/barryvdh/laravel-dompdf` directory exists
- Run `composer dump-autoload`

---

## Quick Command Summary

```bash
# 1. Navigate to project
cd /path/to/your/project

# 2. Install dependencies
composer install --no-dev --optimize-autoloader

# 3. Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# 4. Test (via browser)
# Visit your site and download a PDF report
```

---

## Files to Upload (Summary)

✅ `app/Http/Controllers/ReportController.php` - Updated controller
✅ `composer.json` - Dependency list
✅ `composer.lock` - Version lock file

**After running composer install:**
✅ `vendor/` directory will be created/updated (don't upload manually if using composer)

---

## Need Help?

If you encounter issues:
1. Check your hosting provider's documentation for SSH/terminal access
2. Contact your hosting provider support
3. Verify file permissions are correct
4. Check Laravel logs: `storage/logs/laravel.log`








