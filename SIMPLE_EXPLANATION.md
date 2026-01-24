# Simple Explanation - What You Need to Know

## 🤔 What is "Server Information"?

Your **server** is the computer where your website (www.wauminilink.co.tz) lives. To automatically deploy, we need to know how to connect to it.

---

## 📋 Step 2: What Information Do You Need?

Think of it like this - to visit someone's house, you need:
- **Address** (where is it?)
- **Your name** (who are you?)
- **Door number** (which door?)
- **Room location** (where inside the house?)

For your server, you need:

### 1. SERVER_HOST (The Address)
**Question**: Where is your website hosted?
**Answer**: This is your website domain or IP address
- Example: `wauminilink.co.tz`
- Or: `123.456.789.0` (if you have an IP address)

**How to find it**: 
- It's your website domain: `wauminilink.co.tz`
- Or check your hosting provider (cPanel, etc.)

---

### 2. SERVER_USER (Your Name/Username)
**Question**: What username do you use to log into your server?
**Answer**: This is your SSH/cPanel username

**How to find it**:
- Check your hosting account/cPanel
- Usually the same as your cPanel username
- Examples: `wauminilink`, `wauminil`, `root`, or your hosting username

**If you don't know**:
- Log into your hosting control panel (cPanel)
- Your username is usually shown at the top
- Or check your hosting provider's email

---

### 3. SERVER_PORT (The Door Number)
**Question**: Which port does SSH use?
**Answer**: Usually `22` (this is the default)

**Most of the time**: Just use `22`
- Only change if your hosting provider told you a different port

---

### 4. SERVER_PATH (Where Your Files Are)
**Question**: Where exactly are your website files located on the server?
**Answer**: The full folder path where your Laravel project is

**Common paths**:
- `/home/wauminilink/public_html`
- `/home/wauminilink/public_html/WauminiLink`
- `/var/www/wauminilink`
- `/home/your_username/public_html`

**How to find it** (if you have SSH access):

**Method 1: Using cPanel File Manager**
1. Log into cPanel
2. Go to **File Manager**
3. Navigate to your project folder
4. Look at the path shown at the top (e.g., `/home/wauminilink/public_html`)

**Method 2: Using SSH** (if you have SSH access)
```bash
# Connect to your server
ssh your_username@wauminilink.co.tz

# Navigate to your project
cd public_html  # or wherever your project is
cd WauminiLink  # if it's in a subfolder

# Show the full path
pwd
# This will show something like: /home/wauminilink/public_html/WauminiLink
```

**Method 3: Ask Your Hosting Provider**
- Contact your hosting support
- Ask: "What is the full path to my website files?"

---

## 🔑 Step 3: Adding the Public Key (Giving Access)

Think of this like giving someone a **key to your house** so they can come in automatically.

### What is a "Public Key"?
- It's like a special password that allows GitHub Actions to access your server
- It's safe to share (that's why it's called "public")
- You already have it in the file `SSH_KEYS.txt`

### Why Do You Need This?
- So GitHub Actions can automatically connect to your server
- Without it, the deployment won't work

### How to Add It?

**Option A: Using cPanel (Easier - No SSH needed)**

1. **Open `SSH_KEYS.txt` file** in your project
2. **Find the PUBLIC KEY** (starts with `ssh-rsa AAAAB3NzaC1yc2E...`)
3. **Copy the entire line** (it's one long line)

4. **Log into cPanel**
5. **Go to File Manager**
6. **Click the gear icon** → **Show Hidden Files** (to see folders starting with `.`)
7. **Navigate to your home directory** (usually `/home/your_username/`)
8. **If `.ssh` folder doesn't exist**:
   - Click **+ Folder** → Name it `.ssh`
9. **Open the `.ssh` folder**
10. **If `authorized_keys` file doesn't exist**:
    - Click **+ File** → Name it `authorized_keys`
11. **Open `authorized_keys` file**
12. **Paste your public key** (the long line from SSH_KEYS.txt)
13. **Save the file**
14. **Right-click on `.ssh` folder** → **Change Permissions** → Set to `700`
15. **Right-click on `authorized_keys` file** → **Change Permissions** → Set to `600`

**Option B: Using SSH** (If you have SSH access)

1. **Open `SSH_KEYS.txt`** and copy the public key
2. **Connect to your server**:
   ```bash
   ssh your_username@wauminilink.co.tz
   ```
3. **Run these commands** (one by one):
   ```bash
   mkdir -p ~/.ssh
   chmod 700 ~/.ssh
   echo "PASTE_YOUR_PUBLIC_KEY_HERE" >> ~/.ssh/authorized_keys
   chmod 600 ~/.ssh/authorized_keys
   ```
4. **Replace `PASTE_YOUR_PUBLIC_KEY_HERE`** with the actual key from SSH_KEYS.txt

---

## 📝 Quick Checklist

Fill in these blanks:

- [ ] **SERVER_HOST**: `_________________` (e.g., wauminilink.co.tz)
- [ ] **SERVER_USER**: `_________________` (your username)
- [ ] **SERVER_PORT**: `22` (usually this)
- [ ] **SERVER_PATH**: `_________________` (full path to your project)
- [ ] **Public Key Added**: `Yes / No`

---

## 🆘 Still Confused?

**If you don't know your server information:**

1. **Check your hosting account/cPanel**
   - Log into your hosting provider
   - Look for "Account Information" or "Server Details"

2. **Contact your hosting support**
   - Ask them for:
     - SSH username
     - Full path to your website files
     - SSH port (usually 22)

3. **Check your hosting provider's documentation**
   - Most providers have guides on finding this information

---

## 💡 Example

Let's say your information is:
- **SERVER_HOST**: `wauminilink.co.tz`
- **SERVER_USER**: `wauminilink`
- **SERVER_PORT**: `22`
- **SERVER_PATH**: `/home/wauminilink/public_html`

This means:
- Your website is at `wauminilink.co.tz`
- Your username is `wauminilink`
- SSH uses port 22 (standard)
- Your files are in `/home/wauminilink/public_html` folder

---

**Need more help?** Let me know which part is still confusing! 😊









