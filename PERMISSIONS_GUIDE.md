# Roles & Permissions Guide

## Overview

The system now supports granular permission control. You can enable or disable specific permissions for each role (admin, pastor, secretary, treasurer).

## How to Manage Permissions

### Step 1: Access Roles & Permissions Page

1. Log in as **Administrator**
2. Go to **Administration** → **System Management** → **Roles & Permissions**
   - Or directly visit: `/admin/roles-permissions`

### Step 2: Configure Permissions for Each Role

For each role (Admin, Pastor, Secretary, Treasurer), you'll see a list of permissions organized by category:

- **Admin**: Dashboard access, logs, sessions, user management
- **Members**: View, create, edit, delete members
- **Leaders**: Manage leadership positions
- **Finance**: Financial records, budgets, approvals
- **Services**: Sunday services, attendance
- **Settings**: System configuration
- **Reports**: View and export reports
- **Analytics**: System analytics

### Step 3: Enable/Disable Permissions

- **Check the checkbox** to enable a permission for that role
- **Uncheck the checkbox** to disable a permission for that role
- Click **"Update [Role] Permissions"** to save changes

### Example: Disable Pastor from Registering Members

1. Go to **Roles & Permissions** page
2. Find the **Pastor Role Permissions** section
3. Under **Members** category, find **"Create Members"**
4. **Uncheck** the checkbox
5. Click **"Update Pastor Permissions"**

Now pastors will:
- ❌ **NOT** be able to access the "Add Member" page
- ❌ **NOT** be able to submit member registration forms
- ✅ Still be able to view members (if "View Members" is checked)
- ✅ Still be able to edit members (if "Edit Members" is checked)

## Permission Enforcement

The system automatically enforces permissions:

1. **Route Protection**: Routes are protected by middleware - users without permission get a 403 error
2. **Menu Visibility**: Menu items are hidden if the user doesn't have permission
3. **Button Visibility**: Action buttons are conditionally shown based on permissions

## Available Permissions

### Member Permissions
- `members.view` - View member list
- `members.create` - Add new members
- `members.edit` - Edit member information
- `members.delete` - Delete members
- `members.details` - View detailed member information

### Leader Permissions
- `leaders.view` - View leader list
- `leaders.create` - Add new leaders
- `leaders.edit` - Edit leader information
- `leaders.delete` - Delete leaders
- `leaders.manage` - Full leadership management

### Finance Permissions
- `finance.dashboard` - Access finance dashboard
- `finance.view` - View all financial records
- `finance.create` - Create tithes, offerings, donations, expenses
- `finance.edit` - Edit financial records
- `finance.approve` - Approve financial transactions
- `finance.budgets` - Create and manage budgets
- `finance.reports` - View financial reports

### Service Permissions
- `services.view` - View Sunday services
- `services.create` - Create Sunday services
- `services.edit` - Edit Sunday services
- `services.attendance` - Manage service attendance

### Settings Permissions
- `settings.view` - View system settings
- `settings.edit` - Edit system settings

### Reports Permissions
- `reports.view` - View all reports
- `reports.export` - Export reports to CSV/PDF

### Analytics Permissions
- `analytics.view` - View system analytics

## Important Notes

1. **Admin Role**: Administrators have **ALL permissions** by default and cannot be restricted
2. **Permission Changes**: Changes take effect immediately after saving
3. **Default Permissions**: When you first run the seeder, all permissions are assigned to admin role
4. **Other Roles**: You need to manually configure permissions for pastor, secretary, and treasurer roles

## Testing Permissions

After changing permissions:

1. **Log out** and **log in again** as the affected user (or clear session cache)
2. Try accessing the restricted feature
3. You should see:
   - Menu items hidden (if no permission)
   - 403 error page (if trying to access directly)
   - Success message (if permission is granted)

## Troubleshooting

### Permission Not Working?

1. **Check the permission is saved**: Go back to Roles & Permissions and verify the checkbox is unchecked
2. **Clear browser cache**: Hard refresh (Ctrl+F5) or clear cache
3. **Log out and log in again**: Permissions are checked on login
4. **Check user role**: Make sure the user has the correct role assigned

### Need to Restore All Permissions?

Run the seeder again to reset permissions:
```bash
php artisan db:seed --class=AdminUserSeeder
```

This will:
- Keep existing permissions
- Add any new permissions
- Reset admin role to have all permissions

## Best Practices

1. **Principle of Least Privilege**: Only grant permissions that are necessary for each role
2. **Regular Review**: Periodically review and update permissions as roles change
3. **Document Changes**: Keep track of permission changes for audit purposes
4. **Test Changes**: Always test permission changes with a test account before applying to production





