# Automated Deployment Setup Guide

This guide will help you set up automatic deployment from GitHub to your live server at www.wauminilink.co.tz.

## 🎯 Overview

When you push changes to the `main` branch on GitHub, the changes will automatically be deployed to your live server.

## 📋 Prerequisites

1. SSH access to your live server
2. Git installed on your live server
3. Composer installed on your live server
4. PHP and Laravel requirements on your server

## 🔧 Setup Instructions

### Option 1: GitHub Actions (Recommended)

This method uses GitHub Actions to automatically deploy when you push to GitHub.

#### Step 1: Set up GitHub Secrets

1. Go to your GitHub repository: https://github.com/oftenfredict-source/wauminilink_aict
2. Click on **Settings** → **Secrets and variables** → **Actions**
3. Click **New repository secret** and add the following secrets:

   - **SERVER_HOST**: Your server IP or domain (e.g., `wauminilink.co.tz` or `123.456.789.0`)
   - **SERVER_USER**: Your SSH username (e.g., `wauminilink` or `root`)
   - **SERVER_SSH_KEY**: Your private SSH key (the entire key content)
   - **SERVER_PORT**: SSH port (usually `22`)
   - **SERVER_PATH**: Full path to your project on the server (e.g., `/home/wauminilink/public_html` or `/var/www/wauminilink`)

#### Step 2: Generate SSH Key (if you don't have one)

On your local machine:
```bash
ssh-keygen -t rsa -b 4096 -C "github-actions-deploy"
```

Copy the **private key** (id_rsa) content and add it as `SERVER_SSH_KEY` secret in GitHub.

Copy the **public key** (id_rsa.pub) to your server:
```bash
ssh-copy-id -i ~/.ssh/id_rsa.pub username@wauminilink.co.tz
```

Or manually add it to `~/.ssh/authorized_keys` on your server.

#### Step 3: Verify Server Setup

On your live server, ensure:
1. Git is initialized in your project directory
2. The remote is set to your GitHub repository:
   ```bash
   cd /path/to/your/project
   git remote -v
   # Should show: origin https://github.com/oftenfredict-source/wauminilink_aict.git
   ```

3. If not set, add the remote:
   ```bash
   git remote add origin https://github.com/oftenfredict-source/wauminilink_aict.git
   ```

### Option 2: Server-Side Git Hook (Alternative)

If you prefer to deploy directly from the server without GitHub Actions:

#### Step 1: Update deploy.sh Script

1. Edit `deploy.sh` and update the project path:
   ```bash
   cd /home/wauminilink/public_html  # Your actual path
   ```

2. Make it executable:
   ```bash
   chmod +x deploy.sh
   ```

#### Step 2: Set up Git Hook

On your server, create a post-receive hook:

```bash
cd /path/to/your/project/.git/hooks
nano post-receive
```

Add this content:
```bash
#!/bin/bash
cd /path/to/your/project
git --git-dir=/path/to/your/project/.git --work-tree=/path/to/your/project checkout -f
/path/to/your/project/deploy.sh
```

Make it executable:
```bash
chmod +x post-receive
```

#### Step 3: Configure GitHub Webhook (Optional)

1. Go to GitHub repository → **Settings** → **Webhooks**
2. Click **Add webhook**
3. Payload URL: `https://wauminilink.co.tz/deploy-webhook` (you'll need to create this endpoint)
4. Content type: `application/json`
5. Events: Select "Just the push event"
6. Click **Add webhook**

Then create a route in Laravel to handle the webhook (see `routes/web.php` example below).

## 🔄 How It Works

### GitHub Actions Flow:
1. You push code to GitHub `main` branch
2. GitHub Actions workflow triggers
3. Connects to your server via SSH
4. Pulls latest code from GitHub
5. Runs composer install, migrations, and optimizations
6. Clears and rebuilds caches
7. Sets proper permissions

### Manual Deployment:
If you need to deploy manually, SSH into your server and run:
```bash
cd /path/to/your/project
./deploy.sh
```

## 🛠️ Laravel Webhook Endpoint (Optional)

If you want to use webhooks instead of GitHub Actions, add this to `routes/web.php`:

```php
Route::post('/deploy-webhook', function () {
    // Verify webhook secret (optional but recommended)
    $secret = env('GITHUB_WEBHOOK_SECRET');
    $signature = request()->header('X-Hub-Signature-256');
    
    if ($secret && $signature) {
        $hash = 'sha256=' . hash_hmac('sha256', request()->getContent(), $secret);
        if (!hash_equals($hash, $signature)) {
            abort(403, 'Invalid signature');
        }
    }
    
    // Execute deployment script
    $output = shell_exec('cd ' . base_path() . ' && ./deploy.sh 2>&1');
    
    return response()->json([
        'status' => 'success',
        'output' => $output
    ]);
})->middleware('throttle:10,1');
```

## ⚠️ Important Notes

1. **Environment File**: Make sure your `.env` file on the server is properly configured and not overwritten during deployment.

2. **Database Backups**: Consider adding database backup before migrations:
   ```bash
   php artisan backup:run  # If you have backup package
   ```

3. **Maintenance Mode**: You might want to enable maintenance mode during deployment:
   ```bash
   php artisan down
   # ... deployment steps ...
   php artisan up
   ```

4. **Permissions**: Adjust file ownership and permissions based on your server setup.

5. **Testing**: Test the deployment process on a staging server first if possible.

## 🐛 Troubleshooting

### SSH Connection Issues
- Verify SSH key is correctly added to GitHub secrets
- Test SSH connection manually: `ssh username@wauminilink.co.tz`
- Check firewall settings

### Permission Errors
- Ensure web server user has proper permissions
- Check `storage` and `bootstrap/cache` directories are writable

### Git Pull Issues
- Verify remote URL is correct on server
- Check if you need to use SSH URL instead of HTTPS
- Ensure server has access to pull from GitHub

## 📞 Support

If you encounter issues, check:
- GitHub Actions logs (if using GitHub Actions)
- Server error logs: `storage/logs/laravel.log`
- Web server error logs

---

**Last Updated**: 2025-01-08

