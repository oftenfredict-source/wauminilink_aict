# Administrator Account & Role-Based Permission System Setup Guide

## Overview

This system provides:
- **Administrator Account**: Full system access with monitoring capabilities
- **Role-Based Permissions**: Granular permission system for all roles (admin, pastor, secretary, treasurer)
- **Activity Logging**: Complete tracking of all user activities
- **Session Management**: Monitor and manage active user sessions

## Setup Instructions

### 1. Run Migrations

First, run the database migrations to create the necessary tables:

```bash
php artisan migrate
```

This will create:
- `permissions` table - Stores all system permissions
- `role_permissions` table - Maps permissions to roles
- `activity_logs` table - Stores all user activities

### 2. Seed Administrator Account

Run the seeder to create the administrator account and set up permissions:

```bash
php artisan db:seed --class=AdminUserSeeder
```

Or run all seeders:

```bash
php artisan db:seed
```

### 3. Administrator Credentials

After running the seeder, you can login with:

- **Email**: `administrator@waumini.com`
- **Password**: `Admin@2024!`

**⚠️ IMPORTANT**: Change the password immediately after first login!

### 4. Access Admin Dashboard

Once logged in as administrator, you'll be redirected to:
- **URL**: `/admin/dashboard`

## Features

### Administrator Dashboard
- View system statistics (total users, active sessions, activities)
- Monitor recent activities
- View active user sessions
- Quick access to all admin features

### Activity Logs
- Track all user actions (create, update, delete, view, approve)
- Filter by user, action type, date range
- Search functionality
- View detailed activity information including IP address and user agent

### Session Management
- View all active user sessions
- See last activity time for each session
- Revoke sessions (except your own)
- Filter by user or active status

### User Management
- View all system users
- See user activity counts
- View individual user activity history

### Roles & Permissions
- Manage permissions for each role (admin, pastor, secretary, treasurer)
- Granular control over what each role can do
- Categories: admin, finance, members, leaders, services, settings, reports, analytics

## Permission Categories

The system includes permissions in the following categories:

1. **Admin**: Dashboard access, logs, sessions, user management
2. **Members**: View, create, edit, delete members
3. **Leaders**: Manage leadership positions and assignments
4. **Finance**: Financial records, budgets, approvals, reports
5. **Services**: Sunday services, attendance management
6. **Settings**: System configuration
7. **Reports**: View and export reports
8. **Analytics**: System analytics and insights

## Default Permissions

- **Admin Role**: Has ALL permissions by default
- **Pastor Role**: Can be configured via Roles & Permissions page
- **Secretary Role**: Can be configured via Roles & Permissions page
- **Treasurer Role**: Can be configured via Roles & Permissions page

## Activity Tracking

The system automatically tracks:
- User logins and logouts
- All CRUD operations (create, read, update, delete)
- Financial approvals
- Route access
- IP addresses and user agents

## Session Tracking

- Active sessions are tracked automatically
- Sessions expire after 24 hours of inactivity
- Administrators can revoke any session (except their own)

## Security Notes

1. **Change Default Password**: Immediately change the administrator password after first login
2. **Review Permissions**: Regularly review and update role permissions as needed
3. **Monitor Activity Logs**: Regularly check activity logs for suspicious activity
4. **Session Management**: Revoke suspicious or inactive sessions

## Troubleshooting

### Cannot Access Admin Dashboard
- Ensure you're logged in with an account that has `role = 'admin'`
- Check that the routes are properly registered

### Activity Logs Not Showing
- Ensure the `ActivityLogMiddleware` is registered in `bootstrap/app.php`
- Check that the `activity_logs` table exists

### Permissions Not Working
- Run the `AdminUserSeeder` to populate permissions
- Check that `role_permissions` table has data
- Verify user role is correct in the database

## API Usage

### Check User Permissions in Code

```php
// Check if user has a specific permission
if (auth()->user()->hasPermission('finance.approve')) {
    // User can approve finances
}

// Check if user is admin (has all permissions)
if (auth()->user()->isAdmin()) {
    // User is administrator
}

// Get all permissions for user's role
$permissions = auth()->user()->getPermissions();

// Note: Use hasPermission() method, not can() which conflicts with Laravel's built-in method
```

### Log Custom Activities

```php
use App\Models\ActivityLog;

ActivityLog::create([
    'user_id' => auth()->id(),
    'action' => 'custom_action',
    'description' => 'Custom activity description',
    'ip_address' => request()->ip(),
    'user_agent' => request()->userAgent(),
    'route' => request()->route()->getName(),
    'method' => request()->method(),
]);
```

## Support

For issues or questions, check:
1. Laravel logs: `storage/logs/laravel.log`
2. Database migrations status: `php artisan migrate:status`
3. Route list: `php artisan route:list | grep admin`

