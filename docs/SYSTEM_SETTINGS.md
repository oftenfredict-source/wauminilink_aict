# WauminiLink System Settings Documentation

## Overview

The WauminiLink system includes a comprehensive settings management system that allows administrators to configure various aspects of the church management system without requiring code changes. The settings are stored in the database and can be managed through a modern web interface.

## Features

### 🎯 **Comprehensive Configuration**
- **General Settings**: App name, version, timezone, date format, currency
- **Membership Settings**: Child age limits, member ID generation, phone verification
- **Finance Settings**: Enable/disable financial modules, approval thresholds
- **Notification Settings**: Email/SMS configuration, notification timing
- **Security Settings**: Session timeout, password requirements, login attempts
- **Appearance Settings**: Theme colors, sidebar style, display preferences

### 🔧 **Advanced Management**
- **Database Storage**: All settings stored in `system_settings` table
- **Caching**: Automatic caching for performance optimization
- **Validation**: Built-in validation rules for each setting
- **Import/Export**: Backup and restore settings via JSON files
- **Reset to Defaults**: One-click reset functionality
- **Category Organization**: Settings organized by logical categories

### 🎨 **Modern UI**
- **Tabbed Interface**: Easy navigation between setting categories
- **Responsive Design**: Works on desktop and mobile devices
- **Real-time Feedback**: Visual feedback when settings are changed
- **Modal Dialogs**: Confirmation dialogs for destructive actions
- **Form Validation**: Client-side and server-side validation

## Database Schema

### `system_settings` Table

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint | Primary key |
| `key` | string | Unique setting identifier |
| `value` | text | Setting value |
| `type` | string | Data type (string, integer, boolean, json, text) |
| `category` | string | Setting category (general, membership, finance, etc.) |
| `group` | string | Setting group within category (basic, advanced, system) |
| `description` | text | Human-readable description |
| `is_editable` | boolean | Whether setting can be modified |
| `is_public` | boolean | Whether setting is publicly accessible |
| `validation_rules` | json | Laravel validation rules |
| `options` | json | Available options for select/radio inputs |
| `sort_order` | integer | Display order within group |
| `created_at` | timestamp | Creation timestamp |
| `updated_at` | timestamp | Last update timestamp |

## Usage

### Accessing Settings in Code

```php
use App\Services\SettingsService;

// Get a specific setting
$churchName = SettingsService::get('church_name', 'Default Church');

// Get all settings by category
$membershipSettings = SettingsService::getCategory('membership');

// Get pre-configured setting groups
$churchInfo = SettingsService::getChurchInfo();
$financeSettings = SettingsService::getFinanceSettings();
$notificationSettings = SettingsService::getNotificationSettings();

// Check if a feature is enabled
if (SettingsService::isFeatureEnabled('enable_tithes')) {
    // Show tithes functionality
}

// Format currency
$formattedAmount = SettingsService::formatCurrency(150000); // "TSh 150,000"

// Format date
$formattedDate = SettingsService::formatDate(now()); // "15/10/2024"
```

### Using Settings in Blade Templates

```blade
{{-- Display church name --}}
<h1>{{ SettingsService::get('church_name') }}</h1>

{{-- Show feature based on setting --}}
@if(SettingsService::isFeatureEnabled('enable_donations'))
    <a href="/donations">Manage Donations</a>
@endif

{{-- Format currency --}}
<p>Total: {{ SettingsService::formatCurrency($total) }}</p>
```

## Setting Categories

### 1. General Settings
- **App Name**: Name of the church management system
- **App Version**: Current system version
- **Church Name**: Name of the church
- **Church Address**: Physical address
- **Church Phone**: Primary phone number
- **Church Email**: Primary email address
- **Timezone**: System timezone
- **Date Format**: Default date format
- **Currency**: Default currency

### 2. Membership Settings
- **Child Max Age**: Maximum age for child members
- **Age Reference**: Reference date for age calculations
- **Auto Generate Member ID**: Automatically generate member IDs
- **Member ID Prefix**: Prefix for member IDs
- **Require Phone Verification**: Require phone verification
- **Allow Duplicate Phone**: Allow duplicate phone numbers

### 3. Finance Settings
- **Enable Tithes**: Enable tithes management
- **Enable Offerings**: Enable offerings management
- **Enable Donations**: Enable donations management
- **Enable Pledges**: Enable pledges management
- **Enable Budgets**: Enable budget management
- **Enable Expenses**: Enable expense management
- **Require Expense Approval**: Require approval for expenses
- **Expense Approval Threshold**: Amount threshold for approval
- **Auto Generate Receipts**: Automatically generate receipts

### 4. Notification Settings
- **Enable Email Notifications**: Enable email notifications
- **Enable SMS Notifications**: Enable SMS notifications
- **Notification Email**: Email for sending notifications
- **Notification SMS Provider**: SMS provider selection
- **Celebrations Notification Days**: Days in advance for celebration notifications
- **Events Notification Days**: Days in advance for event notifications

### 5. Security Settings
- **Session Timeout**: Session timeout in minutes
- **Require Password Change**: Require password change on first login
- **Password Min Length**: Minimum password length
- **Max Login Attempts**: Maximum login attempts before lockout
- **Lockout Duration**: Lockout duration in minutes

### 6. Appearance Settings
- **Theme Color**: Primary theme color
- **Sidebar Style**: Sidebar appearance (dark/light)
- **Show Member Photos**: Show member photos in lists
- **Items Per Page**: Number of items per page in lists

## API Endpoints

### Settings Management
- `GET /settings` - Display settings page
- `POST /settings` - Update all settings
- `POST /settings/{category}` - Update category-specific settings
- `POST /settings/reset` - Reset settings to defaults
- `GET /settings/export` - Export settings as JSON
- `POST /settings/import` - Import settings from JSON

### AJAX Endpoints
- `GET /settings/get/{key}` - Get specific setting value
- `POST /settings/set/{key}` - Set specific setting value

## Configuration Files

### `config/settings.php`
Contains default settings configuration with:
- Default values for all settings
- Setting metadata (type, category, group, description)
- Validation rules
- Available options for select inputs
- Category and group definitions

## Best Practices

### 1. Setting Naming
- Use descriptive, snake_case names
- Group related settings with common prefixes
- Examples: `church_name`, `enable_tithes`, `expense_approval_threshold`

### 2. Validation Rules
- Always define validation rules for settings
- Use appropriate validation types (required, integer, email, etc.)
- Set reasonable min/max values for numeric settings

### 3. Caching
- Settings are automatically cached for performance
- Use `SettingsService::clearCache()` after bulk updates
- Cache is cleared automatically on setting updates

### 4. Error Handling
- Always provide default values when accessing settings
- Handle validation errors gracefully
- Log setting changes for audit purposes

### 5. Security
- Mark sensitive settings as non-public
- Validate all setting inputs
- Use proper authorization for setting modifications

## Troubleshooting

### Common Issues

1. **Settings not updating**
   - Check if setting is marked as editable
   - Verify validation rules
   - Clear cache: `SettingsService::clearCache()`

2. **Import/Export not working**
   - Ensure JSON file format is correct
   - Check file permissions
   - Verify setting keys exist in system

3. **Performance issues**
   - Settings are cached automatically
   - Consider reducing cache time if needed
   - Monitor database query performance

### Debug Commands

```bash
# Clear settings cache
php artisan cache:clear

# Reset all settings to defaults
php artisan tinker
>>> App\Services\SettingsService::clearCache()

# Check specific setting
>>> App\Services\SettingsService::get('church_name')
```

## Future Enhancements

- **Setting Dependencies**: Settings that depend on other settings
- **User-specific Settings**: Per-user setting overrides
- **Setting History**: Track changes to settings over time
- **Bulk Operations**: Update multiple settings at once
- **Setting Templates**: Pre-configured setting sets for different church types
- **API Integration**: External API for setting management
- **Audit Logging**: Detailed logs of setting changes

## Support

For technical support or questions about the settings system, please contact the development team or refer to the Laravel documentation for additional information about validation, caching, and database operations.
