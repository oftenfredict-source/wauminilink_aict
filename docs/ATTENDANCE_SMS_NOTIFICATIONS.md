# Attendance SMS Notifications

This system automatically sends SMS notifications to members who haven't attended church for 4 consecutive weeks.

## Features

- **Automatic Detection**: Identifies members who have missed 4+ consecutive Sunday services
- **SMS Notifications**: Sends personalized SMS messages to members
- **Manual Control**: Preview and send notifications manually through the web interface
- **Scheduled Execution**: Automatically runs every Monday at 9:00 AM
- **Dry Run Mode**: Test the system without sending actual SMS messages

## How It Works

### 1. Automatic Scheduling
- Runs every **Monday at 9:00 AM** (Tanzania time)
- Checks all permanent members with valid phone numbers
- Identifies those who missed the last 4 consecutive Sunday services
- Sends SMS notifications automatically

### 2. Manual Control
- Access via **Attendance Statistics** page
- **Preview Mode**: See which members would receive notifications (no SMS sent)
- **Send Mode**: Actually send SMS notifications to members
- **Refresh**: Update the list of members with missed attendance

### 3. SMS Message Template (Swahili)
```
Shalom [Member Name], ni muda sasa hatujakuona kanisani. Tunaendelea kukuombea, tukitumaini utaungana nasi tena karibuni. Kumbuka, wewe ni sehemu muhimu ya familia ya Mungu. WAEBRANIA 10:25
```

## Usage

### Manual Execution
```bash
# Preview mode (no SMS sent)
php artisan attendance:check-notifications --dry-run

# Send actual SMS notifications
php artisan attendance:check-notifications
```

### Web Interface
1. Go to **Attendance Statistics** page
2. Scroll to **Missed Attendance Notifications** section
3. Click **Preview** to see which members would be notified
4. Click **Send SMS** to actually send notifications
5. Click **Refresh** to update the member list

## Configuration

### Scheduling
The notification system is scheduled in `routes/console.php`:
```php
Schedule::command('attendance:check-notifications')
    ->weeklyOn(1, '9:00')
    ->timezone('Africa/Dar_es_Salaam')
    ->withoutOverlapping()
    ->runInBackground();
```

### SMS Service
Uses the existing `SmsService` class for sending messages.

### Member Criteria
- Must be a **permanent member** (`membership_type = 'permanent'`)
- Must have a **valid phone number**
- Must have **missed 4 consecutive Sunday services**

## Monitoring

### Logs
- All notification activities are logged in `storage/logs/laravel.log`
- Check logs for successful sends and any errors

### Web Interface
- View members with missed attendance in real-time
- See last attendance date and weeks missed
- Access member attendance history directly

## Troubleshooting

### Command Not Running
1. Check if Laravel scheduler is running:
   ```bash
   php artisan schedule:work
   ```

2. Check cron job setup (if using cron):
   ```bash
   * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
   ```

### SMS Not Sending
1. Check SMS service configuration
2. Verify member phone numbers are valid
3. Check SMS service logs for errors

### No Members Detected
1. Ensure Sunday services are being created
2. Check that attendance is being recorded
3. Verify member criteria (permanent members with phone numbers)

## Safety Features

- **Dry Run Mode**: Test without sending actual SMS
- **Confirmation Dialogs**: Prevent accidental sending
- **Logging**: Track all notification activities
- **Error Handling**: Graceful failure with detailed error messages
- **Overlap Prevention**: Prevents multiple instances running simultaneously

## Customization

### Message Template
Edit the message in `app/Console/Commands/CheckAttendanceNotifications.php`:
```php
private function getMissedAttendanceMessage(Member $member): string
{
    $memberName = $member->full_name;
    
    return "Shalom {$memberName}, ni muda sasa hatujakuona kanisani. Tunaendelea kukuombea, tukitumaini utaungana nasi tena karibuni. Kumbuka, wewe ni sehemu muhimu ya familia ya Mungu. WAEBRANIA 10:25";
}
```

### Schedule Timing
Modify the schedule in `routes/console.php`:
```php
Schedule::command('attendance:check-notifications')
    ->weeklyOn(1, '9:00')  // Change day and time
    ->timezone('Africa/Dar_es_Salaam');  // Change timezone
```

### Member Criteria
Adjust the criteria in the command's `handle()` method:
```php
$members = Member::where('membership_type', 'permanent')
    ->whereNotNull('phone_number')
    ->where('phone_number', '!=', '')
    ->get();
```
