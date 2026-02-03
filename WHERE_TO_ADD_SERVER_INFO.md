# Where to Add Your Server Information

## 🎯 Answer: Add them as GitHub Secrets

You need to add this information to **GitHub Secrets** so that GitHub Actions can use them to connect to your server.

---

## 📍 Step-by-Step: Where to Add This Information

### Step 1: Go to GitHub Secrets Page

1. **Open your browser**
2. **Go to this URL**:
   ```
   https://github.com/oftenfredict-source/wauminilink_aict/settings/secrets/actions
   ```
   
   Or follow these steps:
   - Go to: https://github.com/oftenfredict-source/wauminilink_aict
   - Click on **"Settings"** tab (at the top of the repository)
   - In the left sidebar, click **"Secrets and variables"**
   - Click **"Actions"**

### Step 2: You'll See This Page

You'll see a page that says:
- **"Repository secrets"** at the top
- A button that says **"New repository secret"**

### Step 3: Add Each Secret One by One

Click **"New repository secret"** button **5 times** (once for each piece of information).

---

## 📝 Adding Each Secret

### Secret 1: SERVER_HOST

1. Click **"New repository secret"**
2. **Name**: Type exactly: `SERVER_HOST`
3. **Secret**: Type your website address (e.g., `wauminilink.co.tz`)
4. Click **"Add secret"**

**Example:**
```
Name: SERVER_HOST
Secret: wauminilink.co.tz
```

---

### Secret 2: SERVER_USER

1. Click **"New repository secret"** again
2. **Name**: Type exactly: `SERVER_USER`
3. **Secret**: Type your SSH/cPanel username
4. Click **"Add secret"**

**Example:**
```
Name: SERVER_USER
Secret: wauminilink
```

---

### Secret 3: SERVER_PORT

1. Click **"New repository secret"** again
2. **Name**: Type exactly: `SERVER_PORT`
3. **Secret**: Type `22` (usually this is the number)
4. Click **"Add secret"**

**Example:**
```
Name: SERVER_PORT
Secret: 22
```

---

### Secret 4: SERVER_PATH

1. Click **"New repository secret"** again
2. **Name**: Type exactly: `SERVER_PATH`
3. **Secret**: Type the full path to your project (e.g., `/home/wauminilink/public_html`)
4. Click **"Add secret"**

**Example:**
```
Name: SERVER_PATH
Secret: /home/wauminilink/public_html
```

---

### Secret 5: SERVER_SSH_KEY

1. Click **"New repository secret"** again
2. **Name**: Type exactly: `SERVER_SSH_KEY`
3. **Secret**: 
   - Open the file `SSH_KEYS.txt` in your project
   - Find the section that says **"PRIVATE KEY"**
   - Copy the ENTIRE private key (starts with `-----BEGIN OPENSSH PRIVATE KEY-----` and ends with `-----END OPENSSH PRIVATE KEY-----`)
   - Paste it in the Secret field
4. Click **"Add secret"**

**Important**: Copy the ENTIRE private key including:
- The `-----BEGIN OPENSSH PRIVATE KEY-----` line
- All the lines in between
- The `-----END OPENSSH PRIVATE KEY-----` line

---

## ✅ After Adding All Secrets

You should see **5 secrets** listed:
1. ✅ SERVER_HOST
2. ✅ SERVER_USER
3. ✅ SERVER_PORT
4. ✅ SERVER_PATH
5. ✅ SERVER_SSH_KEY

---

## 📸 Visual Guide

Here's what it looks like:

```
GitHub Repository
└── Settings
    └── Secrets and variables
        └── Actions
            └── Repository secrets
                ├── [New repository secret] button
                └── (Your secrets will appear here after adding)
```

**When you click "New repository secret", you'll see:**
```
┌─────────────────────────────────────┐
│ Name *                              │
│ [________________]                  │
│                                     │
│ Secret *                            │
│ [________________]                  │
│ [________________]                  │
│                                     │
│ [Add secret] button                 │
└─────────────────────────────────────┘
```

---

## 🎯 Quick Reference

| What to Add | Where to Get It | Where to Put It |
|------------|----------------|-----------------|
| SERVER_HOST | Your website: `wauminilink.co.tz` | GitHub Secrets |
| SERVER_USER | Your cPanel username | GitHub Secrets |
| SERVER_PORT | Usually `22` | GitHub Secrets |
| SERVER_PATH | From cPanel File Manager | GitHub Secrets |
| SERVER_SSH_KEY | From `SSH_KEYS.txt` file | GitHub Secrets |

---

## 🔗 Direct Link

**Click here to go directly to the secrets page:**
👉 https://github.com/oftenfredict-source/wauminilink_aict/settings/secrets/actions

---

## ⚠️ Important Notes

1. **Keep these secrets private** - Don't share them publicly
2. **Type the names exactly** - They must match exactly (case-sensitive):
   - `SERVER_HOST` (not `server_host` or `Server_Host`)
   - `SERVER_USER` (not `server_user`)
   - etc.
3. **No spaces** - Don't add extra spaces before or after the values
4. **Copy exactly** - For SERVER_SSH_KEY, copy the entire private key exactly as it appears

---

## 🆘 Need Help?

If you can't find the Settings page:
1. Make sure you're logged into GitHub
2. Make sure you have access to the repository
3. The URL should be: `https://github.com/oftenfredict-source/wauminilink_aict`

If you get an error:
- Check that you typed the secret names exactly (case-sensitive)
- Make sure you copied the entire private key for SERVER_SSH_KEY

---

**That's it!** Once you add all 5 secrets, GitHub Actions will be able to connect to your server automatically! 🚀










