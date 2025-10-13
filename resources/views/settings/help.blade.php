@extends('layouts.index')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-question-circle me-2"></i>Settings Help & Documentation
        </h1>
        <a href="{{ route('settings.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-1"></i>Back to Settings
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Quick Start Guide -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-rocket me-2"></i>Quick Start Guide
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-cog text-primary me-2"></i>Basic Configuration</h6>
                            <ol>
                                <li>Start with <strong>General Settings</strong> to configure your church information</li>
                                <li>Set up <strong>Membership Settings</strong> for member management rules</li>
                                <li>Configure <strong>Finance Settings</strong> for financial modules</li>
                                <li>Adjust <strong>Appearance Settings</strong> for UI preferences</li>
                            </ol>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-shield-alt text-success me-2"></i>Security Best Practices</h6>
                            <ul>
                                <li>Regularly review <strong>Security Settings</strong></li>
                                <li>Set appropriate session timeouts</li>
                                <li>Use strong password requirements</li>
                                <li>Monitor audit logs regularly</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Categories -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Settings Categories Explained
                    </h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="settingsAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="generalHeading">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#generalCollapse">
                                    <i class="fas fa-cog text-primary me-2"></i>General Settings
                                </button>
                            </h2>
                            <div id="generalCollapse" class="accordion-collapse collapse show" data-bs-parent="#settingsAccordion">
                                <div class="accordion-body">
                                    <p>Basic system configuration including church information, timezone, and display preferences.</p>
                                    <ul>
                                        <li><strong>Church Name:</strong> The official name of your church</li>
                                        <li><strong>Church Address:</strong> Physical location for official documents</li>
                                        <li><strong>Timezone:</strong> Affects all date/time displays in the system</li>
                                        <li><strong>Currency:</strong> Default currency for financial transactions</li>
                                        <li><strong>Date Format:</strong> How dates are displayed throughout the system</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="membershipHeading">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#membershipCollapse">
                                    <i class="fas fa-users text-success me-2"></i>Membership Settings
                                </button>
                            </h2>
                            <div id="membershipCollapse" class="accordion-collapse collapse" data-bs-parent="#settingsAccordion">
                                <div class="accordion-body">
                                    <p>Configure how member data is managed and processed in the system.</p>
                                    <ul>
                                        <li><strong>Child Max Age:</strong> Age limit for child members before auto-conversion</li>
                                        <li><strong>Age Reference:</strong> How age calculations are performed</li>
                                        <li><strong>Auto Generate Member ID:</strong> Automatically create unique member IDs</li>
                                        <li><strong>Member ID Prefix:</strong> Prefix for generated member IDs (e.g., "WM")</li>
                                        <li><strong>Phone Verification:</strong> Require phone number verification</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="financeHeading">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#financeCollapse">
                                    <i class="fas fa-money-bill-wave text-warning me-2"></i>Finance Settings
                                </button>
                            </h2>
                            <div id="financeCollapse" class="accordion-collapse collapse" data-bs-parent="#settingsAccordion">
                                <div class="accordion-body">
                                    <p>Control financial modules and approval workflows.</p>
                                    <ul>
                                        <li><strong>Enable Modules:</strong> Turn financial features on/off</li>
                                        <li><strong>Expense Approval:</strong> Require approval for expenses above threshold</li>
                                        <li><strong>Approval Threshold:</strong> Amount requiring approval (in TZS)</li>
                                        <li><strong>Auto Generate Receipts:</strong> Automatically create receipts</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="notificationsHeading">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#notificationsCollapse">
                                    <i class="fas fa-bell text-info me-2"></i>Notification Settings
                                </button>
                            </h2>
                            <div id="notificationsCollapse" class="accordion-collapse collapse" data-bs-parent="#settingsAccordion">
                                <div class="accordion-body">
                                    <p>Configure email and SMS notification systems.</p>
                                    <ul>
                                        <li><strong>Email Notifications:</strong> Enable/disable email alerts</li>
                                        <li><strong>SMS Notifications:</strong> Enable/disable SMS alerts</li>
                                        <li><strong>Notification Email:</strong> Sender email address</li>
                                        <li><strong>SMS Provider:</strong> Choose SMS service provider</li>
                                        <li><strong>Notification Timing:</strong> Days in advance for alerts</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="securityHeading">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#securityCollapse">
                                    <i class="fas fa-shield-alt text-danger me-2"></i>Security Settings
                                </button>
                            </h2>
                            <div id="securityCollapse" class="accordion-collapse collapse" data-bs-parent="#settingsAccordion">
                                <div class="accordion-body">
                                    <p>Security and authentication configuration.</p>
                                    <ul>
                                        <li><strong>Session Timeout:</strong> Minutes before automatic logout</li>
                                        <li><strong>Password Requirements:</strong> Minimum length and complexity</li>
                                        <li><strong>Login Attempts:</strong> Maximum attempts before lockout</li>
                                        <li><strong>Lockout Duration:</strong> Minutes of lockout after failed attempts</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="appearanceHeading">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#appearanceCollapse">
                                    <i class="fas fa-palette text-secondary me-2"></i>Appearance Settings
                                </button>
                            </h2>
                            <div id="appearanceCollapse" class="accordion-collapse collapse" data-bs-parent="#settingsAccordion">
                                <div class="accordion-body">
                                    <p>Customize the user interface and display preferences.</p>
                                    <ul>
                                        <li><strong>Theme Color:</strong> Primary color scheme</li>
                                        <li><strong>Sidebar Style:</strong> Dark or light sidebar</li>
                                        <li><strong>Member Photos:</strong> Show photos in member lists</li>
                                        <li><strong>Items Per Page:</strong> Number of items in paginated lists</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Common Issues -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Common Issues & Solutions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-question-circle text-warning me-2"></i>Settings Not Saving</h6>
                            <ul>
                                <li>Check if the setting is marked as editable</li>
                                <li>Verify validation rules are met</li>
                                <li>Clear browser cache and try again</li>
                                <li>Check for JavaScript errors in browser console</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-sync text-info me-2"></i>Performance Issues</h6>
                            <ul>
                                <li>Settings are automatically cached for performance</li>
                                <li>Use the "Clear Cache" button if needed</li>
                                <li>Large numbers of settings may take time to load</li>
                                <li>Use search and filters to find specific settings</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('settings.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-cog me-2"></i>Go to Settings
                        </a>
                        <a href="{{ route('settings.export') }}" class="btn btn-outline-success">
                            <i class="fas fa-download me-2"></i>Export Settings
                        </a>
                        <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#auditModal">
                            <i class="fas fa-history me-2"></i>View Audit Log
                        </button>
                    </div>
                </div>
            </div>

            <!-- API Documentation -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-code me-2"></i>API Documentation
                    </h6>
                </div>
                <div class="card-body">
                    <p class="small">Access settings programmatically via REST API:</p>
                    <div class="code-block bg-light p-2 rounded">
                        <code class="small">
                            GET /api/settings<br>
                            GET /api/settings/{key}<br>
                            POST /api/settings/{key}/value<br>
                            POST /api/settings/bulk-update
                        </code>
                    </div>
                    <a href="/api/documentation" class="btn btn-sm btn-outline-primary mt-2">
                        <i class="fas fa-book me-1"></i>Full API Docs
                    </a>
                </div>
            </div>

            <!-- Support -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-life-ring me-2"></i>Need Help?
                    </h6>
                </div>
                <div class="card-body">
                    <p class="small">If you need additional assistance:</p>
                    <ul class="small">
                        <li>Check the audit log for recent changes</li>
                        <li>Export settings before making major changes</li>
                        <li>Contact your system administrator</li>
                        <li>Refer to the system documentation</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Audit Log Modal (reused from settings page) -->
<div class="modal fade" id="auditModal" tabindex="-1" aria-labelledby="auditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="auditModalLabel">
                    <i class="fas fa-history me-2"></i>Settings Audit Log
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Setting</th>
                                <th>Action</th>
                                <th>Old Value</th>
                                <th>New Value</th>
                                <th>User</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    <i class="fas fa-spinner fa-spin me-2"></i>Loading audit logs...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
