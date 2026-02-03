# How to Test SSH Connection

After adding your public key to the server, test if the connection works.

---

## 🧪 Method 1: Using the Test Script (Easier)

I've created a PowerShell script to test the connection automatically.

### Step 1: Open PowerShell

1. Press `Windows Key + X`
2. Click **"Windows PowerShell"** or **"Terminal"**
3. Navigate to your project folder:
   ```powershell
   cd C:\xampp\htdocs\WauminiLink
   ```

### Step 2: Run the Test Script

Run this command (replace with your actual server details):

```powershell
.\test-ssh-connection.ps1 -ServerHost "wauminilink.co.tz" -ServerUser "your_username" -ServerPort 22
```

**Replace:**
- `wauminilink.co.tz` with your server host
- `your_username` with your SSH username
- `22` with your SSH port (usually 22)

**Example:**
```powershell
.\test-ssh-connection.ps1 -ServerHost "wauminilink.co.tz" -ServerUser "wauminilink" -ServerPort 22
```

### Step 3: Check the Results

**✅ If successful, you'll see:**
```
✅ SSH connection successful!
Server Response:
Connection successful
/home/wauminilink
wauminilink
```

**❌ If it fails, you'll see:**
```
❌ SSH connection failed!
Error details:
[error message here]
```

---

## 🧪 Method 2: Manual Test (Alternative)

If the script doesn't work, test manually:

### Step 1: Open PowerShell or Command Prompt

### Step 2: Run SSH Command

```powershell
ssh -i C:\Users\often\.ssh\github_actions_wauminilink your_username@wauminilink.co.tz
```

**Replace:**
- `your_username` with your SSH username
- `wauminilink.co.tz` with your server host

**Example:**
```powershell
ssh -i C:\Users\often\.ssh\github_actions_wauminilink wauminilink@wauminilink.co.tz
```

### Step 3: What to Expect

**✅ If successful:**
- You'll be connected to your server
- You'll see a command prompt like: `[username@server ~]$`
- Type `exit` to disconnect

**❌ If it fails:**
- You'll see an error message
- Common errors:
  - "Permission denied" - Public key not added correctly
  - "Connection refused" - Server not accessible or SSH not enabled
  - "Host key verification failed" - First time connecting (type `yes`)

---

## 🔍 Troubleshooting

### Issue 1: "Permission denied (publickey)"

**Problem**: The public key is not added correctly to your server.

**Solution**:
1. Double-check that you added the public key to `~/.ssh/authorized_keys` on the server
2. Verify the key is on one line (no line breaks)
3. Check file permissions:
   - `.ssh` folder: `700`
   - `authorized_keys` file: `600`

### Issue 2: "Connection refused"

**Problem**: Cannot connect to the server.

**Solution**:
1. Check if SSH is enabled on your server
2. Verify the server host is correct
3. Check if the port is correct (usually 22)
4. Contact your hosting provider if SSH is not enabled

### Issue 3: "Host key verification failed"

**Problem**: First time connecting to this server.

**Solution**:
- Type `yes` when prompted
- This adds the server to your known hosts

### Issue 4: "Could not resolve hostname"

**Problem**: Server hostname is incorrect.

**Solution**:
- Verify the server host is correct
- Try using the IP address instead of domain name

### Issue 5: Script says "SSH key not found"

**Problem**: The SSH key file is missing.

**Solution**:
- Verify the key exists at: `C:\Users\often\.ssh\github_actions_wauminilink`
- If missing, we need to regenerate it

---

## ✅ Success Checklist

After testing, you should be able to:

- [ ] Connect to your server using the SSH key
- [ ] See a successful connection message
- [ ] Execute commands on the server (if using manual method)

---

## 🎯 What This Test Proves

If the test is successful, it means:
- ✅ Your public key is correctly added to the server
- ✅ The SSH key is working
- ✅ GitHub Actions will be able to connect to your server
- ✅ You're ready to add GitHub Secrets and start deploying!

---

## 📝 Quick Test Command

**Copy and paste this** (replace with your details):

```powershell
cd C:\xampp\htdocs\WauminiLink
.\test-ssh-connection.ps1 -ServerHost "wauminilink.co.tz" -ServerUser "your_username" -ServerPort 22
```

---

## 🆘 Still Having Issues?

If the test fails:

1. **Check the error message** - It usually tells you what's wrong
2. **Verify your server information** is correct
3. **Double-check the public key** was added correctly
4. **Contact your hosting provider** if SSH is not enabled

---

**Once the test is successful, you're ready for the next step: Adding GitHub Secrets!** 🚀










