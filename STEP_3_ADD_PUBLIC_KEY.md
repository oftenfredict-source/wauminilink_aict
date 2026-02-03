# Step 3: Add Public Key to Your Server

## ✅ You've Completed Step 2!
Great! You've gathered your server information. Now let's add the public key to your server.

---

## 🔑 What You Need to Do

You need to add the **public key** to your server so GitHub Actions can connect automatically.

---

## 📋 Your Public Key

Open the file `SSH_KEYS.txt` in your project and find the **PUBLIC KEY** section.

Your public key looks like this (it's one long line):
```
ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAACAQCy10FI0kwiYE3gaPo+zJ17q1tcSlh8v8XcVYod5kDsKbtIIQ8pXkY9wi5MxwHIY2fJs/2I16KBR1sVGKkQAap/QoUgTwd9ryDRApaFGzrXhGKXzvGZoQV8x8+6DI+7Xw0g4UYqMoRKv6XrSWwwR4u5LT0Pll/nkPrYiiJQNYlm16kjXaRs5mKqsT+9p2j0kN3q8IoD5hdB+bIfRtT/nEh03zW4Ywd4r9JXT2zhVXQQVKLzfD+PJsFUKyFWcX4xhLr4uDt8RpGZRQ25fxPAru3+7k2+GLbTXhaobqA4RPN1PBT3ybRMgTsfCoesfSgivpl1W6gHC/rdPwalYbfRXFREoTlA/GKSUXzZ2RYbOrcpgwYrW35yqBB5HzZcyIsDp7DhB0cTXECu8dDcwknuqKDlvRowTuS6WecfEjE+x/QhfdHLaeSL7aT5vXMkKFmTRbfwjiqV1E+H09BSDPAfpskKTOS/uqNwYIhsME9BdKY1QZfBY+S3ygALYlvhlIdHN/wUayfolv5mr4lRH/2/Hrwct0agSEYD2BlW5YtOfCBhYemeFepCy8C5fOOJgXba67sG9tv0/nk5DtDaTZVTwQH2+08aAfZDNxfLNcp0OoV10Ol/iI3h8AOQN51N9L8rV+HI+0s1fKAIlDd2+HFrrNTr7QH6MeRoS67YrNz+jDmbAQ== github-actions-wauminilink
```

**Copy this entire line** (it starts with `ssh-rsa` and ends with `github-actions-wauminilink`)

---

## 🎯 Choose Your Method

### Method A: Using cPanel (Easier - Recommended if you have cPanel)

**Step 1: Log into cPanel**
- Go to your hosting control panel
- Log in with your credentials

**Step 2: Open File Manager**
- Find and click **"File Manager"** in cPanel

**Step 3: Show Hidden Files**
- Click the **gear icon** (⚙️) at the top
- Check **"Show Hidden Files"** or **"Show Hidden Files (dotfiles)"**
- Click **"Save"**

**Step 4: Navigate to Your Home Directory**
- You should see folders starting with `.` (like `.ssh`)
- If you don't see a `.ssh` folder, you'll create it

**Step 5: Create `.ssh` Folder (if it doesn't exist)**
- Click **"+ Folder"** button
- Name it exactly: `.ssh` (with the dot at the beginning)
- Click **"Create New Folder"**

**Step 6: Open `.ssh` Folder**
- Double-click the `.ssh` folder to open it

**Step 7: Create `authorized_keys` File (if it doesn't exist)**
- Click **"+ File"** button
- Name it exactly: `authorized_keys` (no extension)
- Click **"Create New File"**

**Step 8: Edit `authorized_keys` File**
- Right-click on `authorized_keys` file
- Click **"Edit"** or **"Code Edit"**
- **Paste your public key** (the long line you copied)
- Make sure it's on **one line** (no line breaks)
- Click **"Save Changes"**

**Step 9: Set Permissions**
- Right-click on `.ssh` folder → **"Change Permissions"**
- Set to: `700` (or check: Owner: Read, Write, Execute)
- Click **"Change Permissions"**

- Right-click on `authorized_keys` file → **"Change Permissions"**
- Set to: `600` (or check: Owner: Read, Write)
- Click **"Change Permissions"**

**✅ Done!** Your public key is now added to your server.

---

### Method B: Using SSH (If you have SSH access)

**Step 1: Connect to Your Server**
```bash
ssh your_username@wauminilink.co.tz
```
(Replace `your_username` with your actual username)

**Step 2: Create `.ssh` Directory (if it doesn't exist)**
```bash
mkdir -p ~/.ssh
chmod 700 ~/.ssh
```

**Step 3: Add Your Public Key**
```bash
echo "YOUR_PUBLIC_KEY_HERE" >> ~/.ssh/authorized_keys
```
(Replace `YOUR_PUBLIC_KEY_HERE` with the actual public key from SSH_KEYS.txt)

**Step 4: Set Permissions**
```bash
chmod 600 ~/.ssh/authorized_keys
```

**✅ Done!** Your public key is now added to your server.

---

## 🧪 Test the Connection

After adding the public key, you can test if it works:

**Using PowerShell (on your Windows computer):**
```powershell
.\test-ssh-connection.ps1 -ServerHost "wauminilink.co.tz" -ServerUser "your_username" -ServerPort 22
```

**Or manually:**
```bash
ssh -i C:\Users\often\.ssh\github_actions_wauminilink your_username@wauminilink.co.tz
```

If the connection is successful, you'll see a message like:
```
✅ SSH connection successful!
```

---

## ⚠️ Common Issues

### Issue 1: "Permission denied"
- **Solution**: Check that you set permissions correctly:
  - `.ssh` folder: `700`
  - `authorized_keys` file: `600`

### Issue 2: "No such file or directory"
- **Solution**: Make sure you created the `.ssh` folder in your home directory (not in public_html)

### Issue 3: "Connection refused"
- **Solution**: 
  - Check if SSH is enabled on your server
  - Verify your server host and port are correct
  - Contact your hosting provider if SSH is not enabled

---

## ✅ Checklist

- [ ] Opened `SSH_KEYS.txt` file
- [ ] Copied the PUBLIC KEY (starts with `ssh-rsa...`)
- [ ] Added public key to server using cPanel or SSH
- [ ] Set correct permissions:
  - [ ] `.ssh` folder: `700`
  - [ ] `authorized_keys` file: `600`
- [ ] Tested the connection (optional but recommended)

---

## 🎯 Next Step

Once you've added the public key, you're ready for:
- **Step 4**: Add GitHub Secrets (the server information you gathered in Step 2)

---

**Need help?** Let me know if you encounter any issues! 😊










