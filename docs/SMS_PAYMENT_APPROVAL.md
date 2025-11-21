# SMS Payment Approval Notifications

## Overview
This feature automatically sends SMS notifications to church members when their financial contributions (tithes, offerings, or pledges) are approved by the pastor.

## Implementation Details

### 1. SMS Service Enhancement (`app/Services/SmsService.php`)

Added new methods to handle payment approval notifications:

- **`sendPaymentApprovalNotification()`** - Send SMS notification for approved payments
- **`sendPaymentApprovalNotificationDebug()`** - Send SMS with debug information
- **`buildPaymentApprovalMessage()`** - Build the message from template with placeholders

**Template Placeholders:**
- `{{name}}` - Member's full name
- `{{payment_type}}` - Type of payment (Tithe, Offering, or Pledge)
- `{{amount}}` - Payment amount (formatted with commas)
- `{{date}}` - Payment date (formatted as DD/MM/YYYY)

**Default Message (Swahili):**
```
Hongera {{name}}! {{payment_type}} yako ya TZS {{amount}} tarehe {{date}} imethibitishwa na imepokelewa kikamilifu.

Asante kwa mchango wako wa kiroho. Mungu akubariki!

Waumini Link
```

### 2. Payment Approval Notification Class (`app/Notifications/PaymentApprovalNotification.php`)

Created a new notification class that handles:
- **Database notifications** - Always sent
- **Email notifications** - Sent if member has email
- **SMS notifications** - Sent if member has phone number

The notification automatically determines which channels to use based on member data availability.

### 3. Financial Approval Controller Enhancement (`app/Http/Controllers/FinancialApprovalController.php`)

Modified the controller to send notifications when payments are approved:

#### Updated Methods:
- **`approve()`** - Single record approval now sends member notification
- **`bulkApprove()`** - Bulk approval now sends notifications for each member

#### New Helper Methods:
- **`sendMemberApprovalNotification()`** - Coordinates sending notifications to members
- **`getPaymentTypeName()`** - Gets display name for payment type
- **`getAmount()`** - Extracts amount from different payment models
- **`getPaymentDate()`** - Extracts date from different payment models

### 4. Configuration (`config/settings.php`)

Added new system setting:

```php
'sms_payment_approval_template' => [
    'value' => "Hongera {{name}}! {{payment_type}} yako ya TZS {{amount}} tarehe {{date}} imethibitishwa na imepokelewa kikamilifu.\n\nAsante kwa mchango wako wa kiroho. Mungu akubariki!\n\nWaumini Link",
    'type' => 'text',
    'category' => 'notifications',
    'group' => 'advanced',
    'description' => 'SMS template for payment approval notifications. Use {{name}}, {{payment_type}}, {{amount}}, and {{date}} as placeholders.',
    'validation_rules' => ['nullable', 'string']
]
```

## How It Works

### Workflow:
1. Secretary/Treasurer records a tithe, offering, or pledge
2. Record is created with `approval_status = 'pending'`
3. Pastor reviews the financial record
4. Pastor approves the record through the approval dashboard
5. System updates record status to `'approved'`
6. System automatically:
   - Loads the member information
   - Prepares notification data
   - Sends notification through available channels (SMS, Email, Database)
   - Logs the notification activity

### Payment Types Supported:
- ✅ **Tithe** - Member's tithe contributions
- ✅ **Offering** - Member's offering contributions  
- ✅ **Pledge** - Member's pledge commitments

### What Members Receive:

**SMS Example:**
```
Hongera John Doe! Tithe yako ya TZS 50,000 tarehe 22/10/2025 imethibitishwa na imepokelewa kikamilifu.

Asante kwa mchango wako wa kiroho. Mungu akubariki!

Waumini Link
```

**Email:**
Members also receive a detailed email with:
- Payment type
- Amount
- Date
- Approval status
- Link to view details

**Database Notification:**
A notification record is created in the system that members can view in their dashboard.

## Configuration Requirements

### Enable SMS Notifications:
1. Go to Settings → Notification Settings
2. Enable "Enable SMS notifications"
3. Configure SMS provider details:
   - API URL
   - API Key or Username/Password
   - Sender ID

### Customize SMS Template:
1. Go to Settings → Notification Settings → Advanced
2. Edit "SMS Payment Approval Template"
3. Use placeholders: `{{name}}`, `{{payment_type}}`, `{{amount}}`, `{{date}}`
4. Save changes

## Member Requirements

For SMS notifications to be sent, members must have:
- ✅ Valid phone number in their profile
- ✅ Phone number in E.164 format (e.g., +255712345678)

## Logging and Debugging

### Success Logs:
```
Payment approval notification sent to member
- member_id: 123
- member_name: John Doe
- type: tithe
- record_id: 456
- amount: 50000
```

### Error Logs:
```
Failed to send payment approval notification to member: [error message]
- member_id: 123
- type: tithe
- record_id: 456
```

## Testing

### Test Single Approval:
1. Create a test tithe/offering/pledge for a member with a phone number
2. Login as pastor
3. Go to Financial Approval Dashboard
4. Approve the record
5. Check logs for SMS sending status
6. Verify member received SMS

### Test Bulk Approval:
1. Create multiple test records
2. Use bulk approve feature
3. Verify all members receive notifications
4. Check logs for any failures

## Security Considerations

- Only pastors and authorized users can approve financial records
- SMS sending is logged for audit purposes
- Failed SMS attempts are logged but don't block approval
- Member phone numbers are protected and only used for notifications

## Future Enhancements

Possible future improvements:
- SMS notification for rejected payments
- Payment reminders for pledges
- Monthly contribution summaries via SMS
- Two-way SMS confirmation
- SMS delivery status tracking





