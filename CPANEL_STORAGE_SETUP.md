# cPanel Storage Setup Guide

## Overview

This application uses Laravel's Storage system to handle file uploads. Files are stored in `storage/app/public/members/profile-pictures/` and accessed via a symbolic link from `public/storage/` to `storage/app/public/`.

---

## ✅ Code Configuration

### File Storage Location
- **Physical Location:** `storage/app/public/members/profile-pictures/`
- **Database Path:** `members/profile-pictures/filename.jpg` (relative path only)
- **Public Access:** Via symlink `public/storage/` → `storage/app/public/`
- **URL Format:** `https://yourdomain.com/storage/members/profile-pictures/filename.jpg`

### Controllers
- `MemberController.php` - Uses `$file->store('members/profile-pictures', 'public')`
- `MemberDashboardController.php` - Uses `$file->store('members/profile-pictures', 'public')`

### Views
All views use: `asset('storage/' . $member->profile_picture)`

---

## 🚀 Setup Steps for cPanel

### Step 1: Create Storage Directory Structure

**Via SSH:**
```bash
cd /path/to/your/laravel/project
mkdir -p storage/app/public/members/profile-pictures
chmod -R 755 storage/app/public
```

**Via cPanel File Manager:**
1. Navigate to your Laravel project root
2. Go to `storage/app/`
3. Create `public` folder if it doesn't exist
4. Inside `public`, create `members` folder
5. Inside `members`, create `profile-pictures` folder
6. Set permissions to **755** for all folders

### Step 2: Create Storage Symlink

The symlink connects `public/storage/` to `storage/app/public/` so files can be accessed via the web.

**Option A: Via SSH (Recommended)**

```bash
cd /path/to/your/laravel/project
php artisan storage:link
```

This command creates: `public/storage` → `storage/app/public`

**Option B: Via cPanel File Manager**

1. Log in to cPanel
2. Open **File Manager**
3. Navigate to your Laravel project's `public/` directory
4. Click **+ File** or **+ Folder** → Select **Symbolic Link**
5. **Link Name:** `storage`
6. **Link Target:** `../storage/app/public`
   - Or use absolute path: `/home/username/public_html/yourproject/storage/app/public`
7. Click **Create**

**Option C: Via Terminal in cPanel**

1. Go to cPanel → **Terminal** (or **Advanced** → **Terminal**)
2. Navigate to your project:
   ```bash
   cd ~/public_html/yourproject
   php artisan storage:link
   ```

### Step 3: Verify Symlink

**Check if symlink exists:**
```bash
ls -la public/ | grep storage
```

Should show:
```
lrwxrwxrwx 1 user user   20 Jan 15 10:00 storage -> ../storage/app/public
```

**Test file access:**
1. Upload a test image via the application
2. Check if file exists: `storage/app/public/members/profile-pictures/test.jpg`
3. Try accessing via browser: `https://yourdomain.com/storage/members/profile-pictures/test.jpg`
4. If you get 404, the symlink is not working correctly

### Step 4: Set Correct Permissions

```bash
# Storage directory permissions
chmod -R 755 storage/app/public
chown -R user:user storage/app/public

# Public symlink permissions
chmod 755 public/storage
```

**Via cPanel File Manager:**
- Right-click `storage/app/public` → **Change Permissions** → Set to **755**
- Right-click `public/storage` → **Change Permissions** → Set to **755**

---

## 🔧 Troubleshooting

### Problem: 404 Error When Accessing Images

**Solution 1: Check Symlink Exists**
```bash
ls -la public/storage
```
If it shows a regular directory or doesn't exist, recreate the symlink.

**Solution 2: Check Symlink Target**
```bash
readlink public/storage
```
Should show: `../storage/app/public` or the absolute path.

**Solution 3: Recreate Symlink**
```bash
# Remove old symlink if exists
rm public/storage

# Create new symlink
php artisan storage:link
```

### Problem: Permission Denied

**Solution:**
```bash
chmod -R 755 storage/app/public
chown -R $(whoami):$(whoami) storage/app/public
```

### Problem: Symlink Not Working in cPanel

Some cPanel configurations don't allow symlinks. Alternative solutions:

**Option 1: Use .htaccess Rewrite (if symlinks disabled)**

Create/edit `public/.htaccess`:
```apache
# If symlink doesn't work, add this rewrite rule
RewriteEngine On
RewriteCond %{REQUEST_URI} ^/storage/(.*)$
RewriteRule ^storage/(.*)$ ../storage/app/public/$1 [L]
```

**Option 2: Use Absolute Path in Storage Config**

Edit `config/filesystems.php`:
```php
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
    'throw' => false,
],
```

### Problem: Files Upload But Don't Display

1. **Check database path:**
   ```sql
   SELECT profile_picture FROM members WHERE profile_picture IS NOT NULL LIMIT 5;
   ```
   Should show: `members/profile-pictures/filename.jpg` (NOT `storage/...` or `assets/images/...`)

2. **Check file exists:**
   ```bash
   ls -la storage/app/public/members/profile-pictures/
   ```

3. **Check view code:**
   Views should use: `asset('storage/' . $member->profile_picture)`

---

## ✅ Verification Checklist

After setup, verify:

- [ ] `storage/app/public/members/profile-pictures/` directory exists
- [ ] Directory permissions are 755
- [ ] `public/storage` symlink exists and points to `storage/app/public`
- [ ] Can access test file via: `https://yourdomain.com/storage/members/profile-pictures/test.jpg`
- [ ] Database stores paths as: `members/profile-pictures/filename.jpg`
- [ ] Views use: `asset('storage/' . $member->profile_picture)`
- [ ] Upload a new profile picture and verify it displays

---

## 📝 Migration from Old Paths

If you have existing images in `public/assets/images/members/profile-pictures/`:

### Step 1: Move Files
```bash
# Move files from old location to new location
mv public/assets/images/members/profile-pictures/* storage/app/public/members/profile-pictures/
```

### Step 2: Update Database Paths

**If database has old paths like `assets/images/members/profile-pictures/filename.jpg`:**
```sql
UPDATE members 
SET profile_picture = REPLACE(profile_picture, 'assets/images/members/profile-pictures/', 'members/profile-pictures/')
WHERE profile_picture LIKE 'assets/images/members/profile-pictures/%';
```

**If database has old paths like `member/profile-pictures/filename.jpg`:**
```sql
UPDATE members 
SET profile_picture = REPLACE(profile_picture, 'member/profile-pictures/', 'members/profile-pictures/')
WHERE profile_picture LIKE 'member/profile-pictures/%';
```

---

## 🎯 Summary

**Storage System:**
- Files saved to: `storage/app/public/members/profile-pictures/`
- Database stores: `members/profile-pictures/filename.jpg`
- Public access via: `public/storage/` symlink
- URL format: `https://yourdomain.com/storage/members/profile-pictures/filename.jpg`

**Key Commands:**
```bash
# Create symlink
php artisan storage:link

# Set permissions
chmod -R 755 storage/app/public

# Verify symlink
ls -la public/storage
```

**After Setup:**
1. Upload a test image
2. Verify it saves to `storage/app/public/members/profile-pictures/`
3. Verify database stores `members/profile-pictures/filename.jpg`
4. Verify image displays at `https://yourdomain.com/storage/members/profile-pictures/filename.jpg`


