# 🚀 Quick Deployment Checklist

Follow these steps to complete your automated deployment setup:

## ✅ Step 1: Gather Server Information

You need the following information about your live server:

- [ ] **SERVER_HOST**: Domain or IP (e.g., `wauminilink.co.tz` or `123.456.789.0`)
- [ ] **SERVER_USER**: SSH username (e.g., `wauminilink`, `root`, or your cPanel username)
- [ ] **SERVER_PORT**: SSH port (usually `22`)
- [ ] **SERVER_PATH**: Full path to your project (e.g., `/home/wauminilink/public_html` or `/var/www/wauminilink`)

**How to find SERVER_PATH:**
- SSH into your server
- Navigate to your project directory
- Run: `pwd` (this shows the full path)

---

## ✅ Step 2: Add Public Key to Server

### Option A: Using SSH (Recommended)

1. Copy the public key from `SSH_KEYS.txt` (starts with `ssh-rsa...`)

2. SSH into your server:
   ```bash
   ssh your_username@wauminilink.co.tz
   ```

3. Run these commands:
   ```bash
   mkdir -p ~/.ssh
   chmod 700 ~/.ssh
   echo "YOUR_PUBLIC_KEY_HERE" >> ~/.ssh/authorized_keys
   chmod 600 ~/.ssh/authorized_keys
   ```

### Option B: Using cPanel

1. Log into cPanel
2. Go to **File Manager**
3. Navigate to your home directory
4. Show hidden files (gear icon → Show Hidden Files)
5. Open or create `.ssh` folder
6. Create or edit `authorized_keys` file
7. Paste the public key (one line, from `SSH_KEYS.txt`)
8. Set permissions:
   - `.ssh` folder: `700`
   - `authorized_keys` file: `600`

---

## ✅ Step 3: Test SSH Connection

### Using PowerShell Script (Windows):

```powershell
.\test-ssh-connection.ps1 -ServerHost "wauminilink.co.tz" -ServerUser "your_username" -ServerPort 22
```

### Manual Test:

```bash
ssh -i C:\Users\often\.ssh\github_actions_wauminilink your_username@wauminilink.co.tz
```

If connection is successful, you're ready for the next step!

---

## ✅ Step 4: Add GitHub Secrets

1. Go to: **https://github.com/oftenfredict-source/wauminilink_aict/settings/secrets/actions**

2. Click **"New repository secret"** for each:

   **Secret 1: SERVER_HOST**
   - Name: `SERVER_HOST`
   - Value: Your server domain/IP

   **Secret 2: SERVER_USER**
   - Name: `SERVER_USER`
   - Value: Your SSH username

   **Secret 3: SERVER_SSH_KEY**
   - Name: `SERVER_SSH_KEY`
   - Value: Copy the ENTIRE private key from `SSH_KEYS.txt`
     - Starts with: `-----BEGIN OPENSSH PRIVATE KEY-----`
     - Ends with: `-----END OPENSSH PRIVATE KEY-----`
     - Include the BEGIN and END lines!

   **Secret 4: SERVER_PORT**
   - Name: `SERVER_PORT`
   - Value: `22` (or your custom SSH port)

   **Secret 5: SERVER_PATH**
   - Name: `SERVER_PATH`
   - Value: Full path to your project (e.g., `/home/wauminilink/public_html`)

---

## ✅ Step 5: Verify Git on Server

SSH into your server and run:

```bash
cd /path/to/your/project  # Replace with your SERVER_PATH
git remote -v
```

If the remote is not set or incorrect:

```bash
git remote remove origin  # If exists
git remote add origin https://github.com/oftenfredict-source/wauminilink_aict.git
```

---

## ✅ Step 6: Test Deployment

1. Make a small change (e.g., add a comment to a file)

2. Commit and push:
   ```bash
   git add .
   git commit -m "Test automated deployment"
   git push origin main
   ```

3. Check GitHub Actions:
   - Go to: **https://github.com/oftenfredict-source/wauminilink_aict/actions**
   - You should see a workflow running
   - Wait for it to complete (should take 1-2 minutes)

4. Verify on live site:
   - Check www.wauminilink.co.tz
   - Your changes should be live!

---

## 🎉 Success!

If the workflow completes successfully, your automated deployment is working!

From now on, every time you:
1. Make changes locally
2. Commit: `git commit -m "your message"`
3. Push: `git push origin main`

Your live site will automatically update! 🚀

---

## 🐛 Troubleshooting

### Workflow Fails with "Permission Denied"
- Check if public key is correctly added to server
- Verify file permissions on server
- Ensure SERVER_USER has write access to SERVER_PATH

### Workflow Fails with "Git Pull Failed"
- Verify Git remote is set correctly on server
- Check if server has internet access
- Ensure you're using HTTPS or have SSH keys set up for Git

### Workflow Fails with "Composer Install Failed"
- Check PHP version on server (needs 8.1+)
- Verify Composer is installed
- Check server has enough disk space

### Workflow Fails with "Migration Failed"
- Check database connection in `.env` file
- Verify database credentials are correct
- Ensure database user has proper permissions

---

## 📞 Need Help?

Check the workflow logs:
1. Go to GitHub → Actions tab
2. Click on the failed workflow
3. Expand the failed step to see detailed error messages

---

**Status**: ⏳ Waiting for server information to complete setup










