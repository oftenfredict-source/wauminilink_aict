# Promise Guests Feature - Setup Guide

## ✅ Implementation Complete!

The Promise Guests notification system has been fully implemented. Follow these steps to activate and use it.

## 📋 Step 1: Run Database Migration

Run the migration to create the `promise_guests` table:

```bash
php artisan migrate
```

This will create the necessary database table with all required fields.

## 📋 Step 2: Verify Scheduled Task

The notification system is automatically scheduled to run **daily at 9:00 AM (Tanzania time)**. The schedule is already configured in `routes/console.php`.

To verify it's working, you can test the command manually:

```bash
# Test without sending SMS (dry-run mode)
php artisan promise-guests:send-notifications --dry-run

# Actually send notifications (use with caution)
php artisan promise-guests:send-notifications
```

## 📋 Step 3: Access the Feature

1. **Login** to your application
2. Navigate to **Events & Services** → **Promise Guests** in the sidebar menu
3. Or directly visit: `/promise-guests`

## 📋 Step 4: Add Your First Promise Guest

1. Click **"Add Promise Guest"** button
2. Fill in the form:
   - **Name**: Guest's full name (required)
   - **Phone Number**: For SMS notifications (required)
   - **Email**: Optional
   - **Promised Service Date**: The Sunday they promised to attend (required)
   - **Sunday Service**: Optional - select from existing services
   - **Notes**: Optional additional information
3. Click **"Save Promise Guest"**

## 📋 Step 5: How It Works

### Automatic Notifications
- The system automatically sends SMS notifications **1 day before** the promised service date
- Notifications run daily at 9:00 AM
- Only guests with status "pending" will receive notifications
- After sending, status automatically changes to "notified"

### Manual Actions
- **Send Notification**: Manually send notification to a pending guest
- **Mark as Attended**: Manually mark a guest as attended when they show up
- **Edit/Delete**: Manage promise guest records

## 📋 Step 6: SMS Configuration

Make sure your SMS settings are configured in **System Settings**:
- SMS notifications must be enabled
- SMS API credentials must be set up
- Sender ID must be configured

The SMS message template can be customized in System Settings with key: `sms_promise_guest_template`

Default template:
```
Shalom {{name}}, tunakukumbusha kuhusu ahadi yako ya kuhudhuria ibada ya Jumapili tarehe {{date}}.

Tunatarajia kukuona na kukushukuru kwa kuwa sehemu ya familia yetu ya kiroho.

{{service_details}}

Karibu sana! Mungu akubariki.
```

## 📋 Step 7: Testing

### Test Adding a Promise Guest
1. Add a test guest with tomorrow's date
2. Run the command manually: `php artisan promise-guests:send-notifications`
3. Check if SMS was sent (verify phone number is correct)

### Test with Dry-Run
```bash
php artisan promise-guests:send-notifications --dry-run
```
This shows what would be sent without actually sending SMS.

## 📊 Features Overview

- ✅ **No Limit**: Add unlimited promise guests
- ✅ **SMS Only**: Notifications sent via SMS (no email)
- ✅ **Manual Attendance**: Mark attendance manually (prevents false positives)
- ✅ **1 Day Before**: Notifications sent exactly 1 day before service
- ✅ **Status Tracking**: Track pending, notified, attended, cancelled
- ✅ **Statistics Dashboard**: View counts by status
- ✅ **Filtering**: Filter by date, status, search by name/phone
- ✅ **Service Integration**: Links to Sunday Services

## 🔧 Troubleshooting

### Notifications Not Sending
1. Check SMS is enabled in System Settings
2. Verify SMS API credentials are correct
3. Check phone number format (should be in E.164 format: +255XXXXXXXXX)
4. Review logs: `storage/logs/laravel.log`

### Command Not Running
1. Ensure Laravel scheduler is running: `php artisan schedule:work` (development) or set up cron job (production)
2. Check timezone is set to `Africa/Dar_es_Salaam` in `config/app.php`

### Database Errors
1. Ensure migration ran successfully: `php artisan migrate:status`
2. Check database connection in `.env`

## 📝 Notes

- Promise guests are **not automatically marked as attended** - this must be done manually
- If a service doesn't exist for the promised date, one will be automatically created
- The system checks for guests needing notification every day at 9:00 AM
- Only guests with status "pending" and service date of tomorrow will receive notifications

## 🎉 You're All Set!

The Promise Guests feature is now ready to use. Start adding promise guests and the system will automatically send them reminders 1 day before their promised service date!




