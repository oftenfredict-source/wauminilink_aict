# Promise Guests SMS Troubleshooting Guide

## Issue: SMS Not Sending When Clicking "Send Notification"

### ✅ Fixes Applied

1. **Auto-create Service**: If a service doesn't exist for the promise guest, it will now be automatically created
2. **Better Error Messages**: More detailed error messages showing exactly what went wrong
3. **SMS Configuration Check**: The system now checks if SMS is enabled before attempting to send
4. **Debug Mode**: Uses debug mode to get detailed error information
5. **Visual Error Display**: Errors now show in both alert boxes and toastr notifications

### 🔍 Common Issues and Solutions

#### 1. SMS Notifications Disabled
**Error Message**: "SMS notifications are disabled. Please enable them in System Settings."

**Solution**:
1. Go to **System Settings** → **Notifications**
2. Enable **"Enable SMS notifications"**
3. Save settings

#### 2. SMS Configuration Missing
**Error Message**: "SMS configuration is missing. Please configure SMS settings in System Settings."

**Solution**:
1. Go to **System Settings** → **Notifications**
2. Configure the following:
   - **SMS API URL**: Your SMS provider endpoint
   - **SMS Username**: Your SMS provider username
   - **SMS Password**: Your SMS provider password
   - **SMS Sender ID**: Your sender ID (max 11 characters)
3. Save settings

#### 3. No Service Found
**Fix Applied**: The system now automatically creates a service if one doesn't exist for the promise guest's date.

#### 4. Phone Number Issues
**Error Message**: "Phone number is required for SMS notifications."

**Solution**:
- Ensure the promise guest has a valid phone number
- Phone number should be in format: `+255XXXXXXXXX` or `255XXXXXXXXX`

#### 5. SMS Provider Rejection
**Error Message**: "Failed to send notification: [reason]"

**Solution**:
- Check your SMS provider account balance
- Verify phone number format is correct
- Check SMS provider logs for rejection reasons
- Ensure sender ID is approved by your provider

### 🔧 How to Check What's Wrong

1. **Check Laravel Logs**:
   ```bash
   tail -f storage/logs/laravel.log
   ```
   Look for entries with "Promise guest notification" or "SMS"

2. **Test SMS Configuration**:
   - Go to System Settings
   - Look for SMS test functionality
   - Or use the test route: `/test-sms` (if available)

3. **Verify Settings**:
   ```php
   // Check if SMS is enabled
   SettingsService::get('enable_sms_notifications')
   
   // Check SMS credentials
   SettingsService::get('sms_username')
   SettingsService::get('sms_password')
   SettingsService::get('sms_api_url')
   ```

### 📋 Step-by-Step Debugging

1. **Check SMS is Enabled**:
   - Go to System Settings → Notifications
   - Verify "Enable SMS notifications" is ON

2. **Verify SMS Credentials**:
   - Check all SMS settings are filled:
     - SMS API URL
     - SMS Username
     - SMS Password
     - SMS Sender ID

3. **Test with a Known Good Number**:
   - Add a test promise guest with your own phone number
   - Try sending notification
   - Check if you receive the SMS

4. **Check Phone Number Format**:
   - Ensure phone number is in correct format
   - Tanzanian numbers: `+255712345678` or `255712345678`
   - Remove any spaces or special characters

5. **Review Error Messages**:
   - When you click "Send Notification", check the error message displayed
   - The message will tell you exactly what's wrong

### 🎯 Quick Fixes

**If SMS is disabled**:
```bash
php artisan sms:enable
```

**If you need to check SMS status**:
- Go to System Settings → Notifications section
- Look for "Enable SMS notifications" toggle

**If service doesn't exist**:
- This is now automatically handled
- The system will create a service for the promise guest's date

### 📞 Testing the Fix

1. **Add a Test Promise Guest**:
   - Name: Test Guest
   - Phone: Your phone number
   - Service Date: Tomorrow's date

2. **Click "Send Notification"**:
   - You should see either:
     - Success message: "Notification sent successfully to [phone]"
     - Error message explaining what's wrong

3. **Check Your Phone**:
   - If successful, you should receive an SMS
   - If not, check the error message for details

### 🔍 What Changed in the Code

1. **Auto-service Creation**: If service doesn't exist, it's created automatically
2. **Better Error Handling**: Uses `sendDebug()` to get detailed error information
3. **Configuration Checks**: Verifies SMS is enabled before attempting to send
4. **Detailed Logging**: All attempts are logged with full details
5. **User-Friendly Messages**: Error messages explain exactly what needs to be fixed

### 💡 Tips

- Always check the error message - it tells you exactly what's wrong
- Ensure SMS settings are configured before adding promise guests
- Test with your own phone number first
- Check Laravel logs for detailed error information
- Verify SMS provider account has sufficient balance

---

**If issues persist**, check:
1. Laravel logs: `storage/logs/laravel.log`
2. SMS provider dashboard/account
3. Network connectivity
4. SMS provider API status




