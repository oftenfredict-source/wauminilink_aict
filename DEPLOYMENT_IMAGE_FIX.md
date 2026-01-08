# Deployment Guide: Image Upload to public/assets/images/

## ✅ Configuration

Images are now saved directly to `public/assets/images/members/profile-pictures/` for direct web access without symlinks.

### File Structure
```
public/
├── assets/
│   └── images/
│       └── members/
│           └── profile-pictures/
│               └── [uploaded images here]
├── attendance/
├── css/
├── data/
├── js/
└── storage/
```

---

## 📝 Code Configuration

### Controllers
- **MemberController.php**: Saves to `public/assets/images/members/profile-pictures/`
- **MemberDashboardController.php**: Saves to `public/assets/images/members/profile-pictures/`
- **Database Path**: `assets/images/members/profile-pictures/filename.jpg`

### Views
All views use: `asset($member->profile_picture)`
- This generates: `https://yourdomain.com/assets/images/members/profile-pictures/filename.jpg`

---

## 🚀 Deployment Steps

### Step 1: Ensure Directory Exists

**Via cPanel File Manager:**
1. Navigate to `public/assets/images/`
2. If `members` folder doesn't exist, create it
3. Inside `members`, create `profile-pictures` folder
4. Set permissions to **755** for both folders

**Via SSH:**
```bash
cd /home/wauminilink/demo
mkdir -p public/assets/images/members/profile-pictures
chmod -R 755 public/assets/images
```

### Step 2: Upload Modified Files

Upload these files to your live server:

**Controllers (2 files):**
- `app/Http/Controllers/MemberController.php`
- `app/Http/Controllers/MemberDashboardController.php`

**Views (8 files):**
- `resources/views/members/dashboard.blade.php`
- `resources/views/pastor/dashboard.blade.php`
- `resources/views/dashboard.blade.php`
- `resources/views/members/settings.blade.php`
- `resources/views/members/identity-card.blade.php`
- `resources/views/leaders/identity-card.blade.php`
- `resources/views/leaders/bulk-identity-cards.blade.php`
- `resources/views/members/partials/card-view.blade.php`

### Step 3: Clear Laravel Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Step 4: Migrate Existing Images (If Any)

**If you have images in storage/app/public/members/profile-pictures/:**

```bash
# Move files from storage to public/assets
mv storage/app/public/members/profile-pictures/* public/assets/images/members/profile-pictures/
```

**Update database paths:**

If database has old paths like `members/profile-pictures/filename.jpg`:
```sql
UPDATE members 
SET profile_picture = CONCAT('assets/images/', profile_picture)
WHERE profile_picture IS NOT NULL 
AND profile_picture NOT LIKE 'assets/images/%'
AND profile_picture LIKE 'members/profile-pictures/%';
```

---

## ✅ Verification

### Test Image Upload
1. Upload a new profile picture
2. Verify file exists: `public/assets/images/members/profile-pictures/[filename].jpg`
3. Check database: Should store `assets/images/members/profile-pictures/[filename].jpg`
4. Access image: `https://www.wauminilink.co.tz/demo/assets/images/members/profile-pictures/[filename].jpg`

### Expected Results
- ✅ Files save to: `public/assets/images/members/profile-pictures/`
- ✅ Database stores: `assets/images/members/profile-pictures/filename.jpg`
- ✅ Images accessible at: `https://yourdomain.com/assets/images/members/profile-pictures/filename.jpg`
- ✅ No symlink required - direct access

---

## 🔍 Troubleshooting

### Images Not Displaying?

1. **Check file exists:**
   ```bash
   ls -la public/assets/images/members/profile-pictures/
   ```

2. **Check database path:**
   ```sql
   SELECT id, full_name, profile_picture FROM members WHERE profile_picture IS NOT NULL LIMIT 5;
   ```
   Should show: `assets/images/members/profile-pictures/filename.jpg`

3. **Check permissions:**
   ```bash
   chmod -R 755 public/assets/images
   ```

4. **Check browser console (F12):**
   - Look for 404 errors
   - Verify URL being requested

### Files Still Going to Storage?

- Verify you uploaded the correct controller files
- Clear Laravel cache: `php artisan config:clear`
- Check that the code uses `public_path('assets/images/members/profile-pictures')` not `store()`

---

## 📋 Summary

**Storage Location:**
- Physical: `public/assets/images/members/profile-pictures/`
- Database: `assets/images/members/profile-pictures/filename.jpg`
- URL: `https://yourdomain.com/assets/images/members/profile-pictures/filename.jpg`

**Key Points:**
- ✅ Direct file access (no symlink needed)
- ✅ Files in public folder structure
- ✅ Works with cPanel file manager
- ✅ Simple and straightforward
