# GitHub Actions Setup Guide - Step by Step

## ✅ Step 1: SSH Keys Generated

Your SSH keys have been generated successfully!

**Private Key Location**: `C:\Users\often\.ssh\github_actions_wauminilink`
**Public Key Location**: `C:\Users\often\.ssh\github_actions_wauminilink.pub`

---

## 📋 Step 2: Add GitHub Secrets

1. Go to your GitHub repository:
   **https://github.com/oftenfredict-source/wauminilink_aict/settings/secrets/actions**

2. Click **"New repository secret"** and add each of the following:

### Secret 1: SERVER_HOST
- **Name**: `SERVER_HOST`
- **Value**: Your server domain or IP (e.g., `wauminilink.co.tz` or `123.456.789.0`)

### Secret 2: SERVER_USER
- **Name**: `SERVER_USER`
- **Value**: Your SSH username (e.g., `wauminilink`, `root`, or your cPanel username)

### Secret 3: SERVER_SSH_KEY
- **Name**: `SERVER_SSH_KEY`
- **Value**: Copy the ENTIRE private key content (starts with `-----BEGIN OPENSSH PRIVATE KEY-----` and ends with `-----END OPENSSH PRIVATE KEY-----`)
- **⚠️ Important**: Copy the entire key including the BEGIN and END lines

### Secret 4: SERVER_PORT
- **Name**: `SERVER_PORT`
- **Value**: Usually `22` (default SSH port)

### Secret 5: SERVER_PATH
- **Name**: `SERVER_PATH`
- **Value**: Full path to your project on the server (e.g., `/home/wauminilink/public_html` or `/var/www/wauminilink`)

---

## 🔑 Step 3: Add Public Key to Your Server

You need to add the public key to your server so GitHub Actions can connect.

### Option A: Using SSH (if you have SSH access)

1. Copy your public key (see below)
2. SSH into your server:
   ```bash
   ssh username@wauminilink.co.tz
   ```
3. Add the public key to authorized_keys:
   ```bash
   mkdir -p ~/.ssh
   chmod 700 ~/.ssh
   echo "YOUR_PUBLIC_KEY_HERE" >> ~/.ssh/authorized_keys
   chmod 600 ~/.ssh/authorized_keys
   ```

### Option B: Using cPanel File Manager

1. Log into cPanel
2. Go to **File Manager**
3. Navigate to your home directory
4. Show hidden files (if needed)
5. Open or create `.ssh` folder
6. Create or edit `authorized_keys` file
7. Paste your public key (one line)
8. Set permissions:
   - `.ssh` folder: `700`
   - `authorized_keys` file: `600`

### Your Public Key:
```
ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAACAQCy10FI0kwiYE3gaPo+zJ17q1tcSlh8v8XcVYod5kDsKbtIIQ8pXkY9wi5MxwHIY2fJs/2I16KBR1sVGKkQAap/QoUgTwd9ryDRApaFGzrXhGKXzvGZoQV8x8+6DI+7Xw0g4UYqMoRKv6XrSWwwR4u5LT0Pll/nkPrYiiJQNYlm16kjXaRs5mKqsT+9p2j0kN3q8IoD5hdB+bIfRtT/nEh03zW4Ywd4r9JXT2zhVXQQVKLzfD+PJsFUKyFWcX4xhLr4uDt8RpGZRQ25fxPAru3+7k2+GLbTXhaobqA4RPN1PBT3ybRMgTsfCoesfSgivpl1W6gHC/rdPwalYbfRXFREoTlA/GKSUXzZ2RYbOrcpgwYrW35yqBB5HzZcyIsDp7DhB0cTXECu8dDcwknuqKDlvRowTuS6WecfEjE+x/QhfdHLaeSL7aT5vXMkKFmTRbfwjiqV1E+H09BSDPAfpskKTOS/uqNwYIhsME9BdKY1QZfBY+S3ygALYlvhlIdHN/wUayfolv5mr4lRH/2/Hrwct0agSEYD2BlW5YtOfCBhYemeFepCy8C5fOOJgXba67sG9tv0/nk5DtDaTZVTwQH2+08aAfZDNxfLNcp0OoV10Ol/iI3h8AOQN51N9L8rV+HI+0s1fKAIlDd2+HFrrNTr7QH6MeRoS67YrNz+jDmbAQ== github-actions-wauminilink
```

---

## 🗄️ Step 4: Set Up Git on Your Server

SSH into your server and ensure Git is set up:

```bash
cd /path/to/your/project  # Replace with your actual path
git remote -v
```

If the remote is not set or incorrect:
```bash
git remote remove origin  # If exists
git remote add origin https://github.com/oftenfredict-source/wauminilink_aict.git
```

Or if you prefer SSH (more secure):
```bash
git remote set-url origin git@github.com:oftenfredict-source/wauminilink_aict.git
```

---

## ✅ Step 5: Test the Setup

1. Make a small change to your code
2. Commit and push:
   ```bash
   git add .
   git commit -m "Test deployment"
   git push origin main
   ```
3. Go to GitHub → **Actions** tab
4. You should see a workflow running
5. Check if it completes successfully

---

## 🔍 Troubleshooting

### SSH Connection Failed
- Verify SERVER_HOST, SERVER_USER, and SERVER_PORT are correct
- Check if your server allows SSH connections
- Verify the public key is in `~/.ssh/authorized_keys` on the server

### Permission Denied
- Check file permissions on the server
- Ensure the user has write access to the project directory
- Verify `storage` and `bootstrap/cache` are writable

### Git Pull Failed
- Ensure Git is initialized on the server
- Check if the remote URL is correct
- Verify the server has internet access to GitHub

### Composer/Migration Errors
- Check PHP version on server (should be 8.1+)
- Verify Composer is installed
- Check database connection in `.env` file

---

## 📞 Need Help?

Check the GitHub Actions logs:
1. Go to: https://github.com/oftenfredict-source/wauminilink_aict/actions
2. Click on the failed workflow
3. Expand the failed step to see error details

---

**Next**: After completing these steps, your deployment will be automatic! 🚀










