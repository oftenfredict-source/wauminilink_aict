@extends('layouts.index')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-cog me-2"></i>System Settings
        </h1>
        <div class="btn-group" role="group">
            <a href="{{ route('settings.help') }}" class="btn btn-outline-info">
                <i class="fas fa-question-circle me-1"></i>Help
            </a>
            <a href="{{ route('settings.analytics') }}" class="btn btn-outline-secondary">
                <i class="fas fa-chart-bar me-1"></i>Analytics
            </a>
            <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#auditModal">
                <i class="fas fa-history me-1"></i>Audit Log
            </button>
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fas fa-upload me-1"></i>Import
            </button>
            <a href="{{ route('settings.export') }}" class="btn btn-outline-success">
                <i class="fas fa-download me-1"></i>Export
            </a>
            <button type="button" class="btn btn-outline-warning" onclick="resetSettings()">
                <i class="fas fa-undo me-1"></i>Reset to Defaults
            </button>
        </div>
    </div>

    <!-- Search and Filter Bar -->
    <div class="card shadow-sm mb-4">
                    <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="settingsSearch" placeholder="Search settings...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="categoryFilter">
                        <option value="">All Categories</option>
                        @foreach($categories as $key => $category)
                            <option value="{{ $key }}">{{ $category['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="groupFilter">
                        <option value="">All Groups</option>
                        @foreach($groups as $key => $group)
                            <option value="{{ $key }}">{{ $group['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                        <i class="fas fa-times me-1"></i>Clear
                    </button>
                </div>
            </div>
        </div>
    </div>

                        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
                        @endif

    <!-- Settings Navigation Tabs -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <ul class="nav nav-tabs card-header-tabs" id="settingsTabs" role="tablist">
                @foreach($categories as $categoryKey => $category)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                id="{{ $categoryKey }}-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#{{ $categoryKey }}" 
                                type="button" 
                                role="tab" 
                                aria-controls="{{ $categoryKey }}" 
                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                            <i class="{{ $category['icon'] }} me-2 text-{{ $category['color'] }}"></i>
                            {{ $category['name'] }}
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="settingsTabContent">
                @foreach($groupedSettings as $categoryKey => $categorySettings)
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                         id="{{ $categoryKey }}" 
                         role="tabpanel" 
                         aria-labelledby="{{ $categoryKey }}-tab">
                        
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-{{ $categories[$categoryKey]['color'] }} mb-3">
                                    <i class="{{ $categories[$categoryKey]['icon'] }} me-2"></i>
                                    {{ $categories[$categoryKey]['name'] }}
                                </h5>
                                <p class="text-muted mb-4">{{ $categories[$categoryKey]['description'] }}</p>
                            </div>
                        </div>

                        @foreach($categorySettings as $groupKey => $groupSettings)
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card border-0 bg-light">
                                        <div class="card-header bg-transparent border-0">
                                            <h6 class="mb-0 text-dark">
                                                <i class="fas fa-layer-group me-2"></i>
                                                {{ $groups[$groupKey]['name'] }}
                                            </h6>
                                            <small class="text-muted">{{ $groups[$groupKey]['description'] }}</small>
                                        </div>
                                        <div class="card-body">
                                            <form method="POST" action="{{ route('settings.update.category', $categoryKey) }}" class="settings-form">
                            @csrf
                            <div class="row g-3">
                                                    @foreach($groupSettings as $setting)
                                                        <div class="col-md-6 col-lg-4">
                                                            <div class="form-group">
                                                                <label for="{{ $setting->key }}" class="form-label fw-bold">
                                                                    {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                                                                    @if($setting->is_editable)
                                                                        <span class="text-danger">*</span>
                                                                    @endif
                                                                    @if($setting->description)
                                                                        <i class="fas fa-info-circle text-info ms-1" 
                                                                           data-bs-toggle="tooltip" 
                                                                           data-bs-placement="top" 
                                                                           title="{{ $setting->description }}"></i>
                                                                    @endif
                                                                </label>
                                                                
                                                                @if($setting->type === 'boolean')
                                                                    <div class="form-check form-switch">
                                                                        <input class="form-check-input" 
                                                                               type="checkbox" 
                                                                               id="{{ $setting->key }}" 
                                                                               name="{{ $setting->key }}" 
                                                                               value="1"
                                                                               {{ $setting->value ? 'checked' : '' }}
                                                                               {{ !$setting->is_editable ? 'disabled' : '' }}>
                                                                        <label class="form-check-label" for="{{ $setting->key }}">
                                                                            {{ $setting->value ? 'Enabled' : 'Disabled' }}
                                                                        </label>
                                </div>
                                                                @elseif($setting->type === 'string' && isset($setting->options))
                                                                    <select class="form-select" 
                                                                            id="{{ $setting->key }}" 
                                                                            name="{{ $setting->key }}"
                                                                            {{ !$setting->is_editable ? 'disabled' : '' }}>
                                                                        @foreach($setting->options as $value => $label)
                                                                            <option value="{{ $value }}" {{ $setting->value == $value ? 'selected' : '' }}>
                                                                                {{ $label }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                @elseif($setting->type === 'integer' && isset($setting->options))
                                                                    <select class="form-select" 
                                                                            id="{{ $setting->key }}" 
                                                                            name="{{ $setting->key }}"
                                                                            {{ !$setting->is_editable ? 'disabled' : '' }}>
                                                                        @foreach($setting->options as $value => $label)
                                                                            <option value="{{ $value }}" {{ $setting->value == $value ? 'selected' : '' }}>
                                                                                {{ $label }}
                                                                            </option>
                                                                        @endforeach
                                    </select>
                                                                @elseif($setting->type === 'text')
                                                                    <textarea class="form-control" 
                                                                              id="{{ $setting->key }}" 
                                                                              name="{{ $setting->key }}" 
                                                                              rows="3"
                                                                              {{ !$setting->is_editable ? 'disabled' : '' }}>{{ $setting->value }}</textarea>
                                                                @elseif($setting->type === 'integer')
                                                                    <input type="number" 
                                                                           class="form-control" 
                                                                           id="{{ $setting->key }}" 
                                                                           name="{{ $setting->key }}" 
                                                                           value="{{ $setting->value }}"
                                                                           {{ !$setting->is_editable ? 'disabled' : '' }}>
                                                                @elseif($setting->type === 'float')
                                                                    <input type="number" 
                                                                           class="form-control" 
                                                                           id="{{ $setting->key }}" 
                                                                           name="{{ $setting->key }}" 
                                                                           value="{{ $setting->value }}" 
                                                                           step="0.01"
                                                                           {{ !$setting->is_editable ? 'disabled' : '' }}>
                                                                @else
                                                                    <input type="text" 
                                                                           class="form-control" 
                                                                           id="{{ $setting->key }}" 
                                                                           name="{{ $setting->key }}" 
                                                                           value="{{ $setting->value }}"
                                                                           {{ !$setting->is_editable ? 'disabled' : '' }}>
                                                                @endif
                                                                
                                                                @if($setting->description)
                                                                    <div class="form-text">{{ $setting->description }}</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                
                                                <div class="d-flex justify-content-end mt-3">
                                                    <button type="submit" class="btn btn-{{ $categories[$categoryKey]['color'] }}">
                                                        <i class="fas fa-save me-2"></i>Save {{ $categories[$categoryKey]['name'] }}
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Import Settings Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">
                    <i class="fas fa-upload me-2"></i>Import Settings
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('settings.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="settings_file" class="form-label">Select Settings File</label>
                        <input type="file" class="form-control" id="settings_file" name="settings_file" accept=".json" required>
                        <div class="form-text">Select a JSON file exported from the settings system.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i>Import Settings
                    </button>
                            </div>
                        </form>
        </div>
    </div>
</div>

<!-- Audit Log Modal -->
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
                <div class="row mb-3">
                    <div class="col-md-4">
                        <select class="form-select" id="auditActionFilter">
                            <option value="">All Actions</option>
                            <option value="created">Created</option>
                            <option value="updated">Updated</option>
                            <option value="deleted">Deleted</option>
                            <option value="reset">Reset</option>
                            <option value="imported">Imported</option>
                            <option value="exported">Exported</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="date" class="form-control" id="auditDateFrom" placeholder="From Date">
                    </div>
                    <div class="col-md-4">
                        <input type="date" class="form-control" id="auditDateTo" placeholder="To Date">
                    </div>
                </div>
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
                        <tbody id="auditLogTableBody">
                            <!-- Audit logs will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
                <div id="auditLogPagination" class="d-flex justify-content-center">
                    <!-- Pagination will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="refreshAuditLog()">
                    <i class="fas fa-refresh me-1"></i>Refresh
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reset Confirmation Modal -->
<div class="modal fade" id="resetModal" tabindex="-1" aria-labelledby="resetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetModalLabel">
                    <i class="fas fa-exclamation-triangle me-2 text-warning"></i>Reset Settings
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to reset all settings to their default values?</p>
                <p class="text-danger"><strong>Warning:</strong> This action cannot be undone!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('settings.reset') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-undo me-2"></i>Reset to Defaults
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Global variables
let currentAuditPage = 1;
let auditFilters = {};

function resetSettings() {
    const modal = new bootstrap.Modal(document.getElementById('resetModal'));
    modal.show();
}

function clearFilters() {
    document.getElementById('settingsSearch').value = '';
    document.getElementById('categoryFilter').value = '';
    document.getElementById('groupFilter').value = '';
    filterSettings();
}

function filterSettings() {
    const searchTerm = document.getElementById('settingsSearch').value.toLowerCase();
    const categoryFilter = document.getElementById('categoryFilter').value;
    const groupFilter = document.getElementById('groupFilter').value;
    
    const settingsCards = document.querySelectorAll('.settings-form');
    
    settingsCards.forEach(card => {
        const settingInputs = card.querySelectorAll('input, select, textarea');
        let shouldShow = true;
        
        // Check if any setting in this form matches the filters
        let hasMatchingSetting = false;
        settingInputs.forEach(input => {
            const settingKey = input.name;
            const settingValue = input.value.toLowerCase();
            const settingLabel = input.closest('.form-group').querySelector('label').textContent.toLowerCase();
            
            // Search filter
            if (searchTerm && !settingKey.toLowerCase().includes(searchTerm) && 
                !settingLabel.includes(searchTerm) && !settingValue.includes(searchTerm)) {
                return;
            }
            
            // Category filter (check parent tab)
            if (categoryFilter) {
                const tabPane = input.closest('.tab-pane');
                if (tabPane && tabPane.id !== categoryFilter) {
                    return;
                }
            }
            
            hasMatchingSetting = true;
        });
        
        if (!hasMatchingSetting) {
            shouldShow = false;
        }
        
        // Show/hide the setting group
        const settingGroup = card.closest('.row');
        if (shouldShow) {
            settingGroup.style.display = '';
        } else {
            settingGroup.style.display = 'none';
        }
    });
    
    // Also filter tabs based on category filter
    if (categoryFilter) {
        const tabs = document.querySelectorAll('.nav-link');
        tabs.forEach(tab => {
            if (tab.getAttribute('data-bs-target') === '#' + categoryFilter) {
                tab.classList.add('active');
                document.getElementById(categoryFilter).classList.add('show', 'active');
            } else {
                tab.classList.remove('active');
                document.querySelector(tab.getAttribute('data-bs-target')).classList.remove('show', 'active');
            }
        });
    }
}

function loadAuditLogs(page = 1) {
    currentAuditPage = page;
    
    const filters = {
        action: document.getElementById('auditActionFilter').value,
        date_from: document.getElementById('auditDateFrom').value,
        date_to: document.getElementById('auditDateTo').value,
        page: page,
        limit: 20
    };
    
    // Remove empty filters
    Object.keys(filters).forEach(key => {
        if (!filters[key]) {
            delete filters[key];
        }
    });
    
    fetch(`/settings/audit-logs?${new URLSearchParams(filters)}`)
        .then(response => response.json())
        .then(data => {
            displayAuditLogs(data.logs);
            displayAuditPagination(data.pagination);
        })
        .catch(error => {
            console.error('Error loading audit logs:', error);
            document.getElementById('auditLogTableBody').innerHTML = 
                '<tr><td colspan="6" class="text-center text-danger">Error loading audit logs</td></tr>';
        });
}

function displayAuditLogs(logs) {
    const tbody = document.getElementById('auditLogTableBody');
    
    if (logs.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No audit logs found</td></tr>';
        return;
    }
    
    tbody.innerHTML = logs.map(log => `
        <tr>
            <td>
                <code>${log.setting_key}</code>
                <br><small class="text-muted">${log.setting_name || ''}</small>
            </td>
            <td>
                <span class="badge bg-${getActionBadgeColor(log.action)}">${log.action_description}</span>
            </td>
            <td>
                <code class="text-muted">${log.old_value || '-'}</code>
            </td>
            <td>
                <code class="text-success">${log.new_value || '-'}</code>
            </td>
            <td>
                ${log.user ? log.user.name : 'System'}
                <br><small class="text-muted">${log.ip_address}</small>
            </td>
            <td>
                ${new Date(log.created_at).toLocaleString()}
            </td>
        </tr>
    `).join('');
}

function displayAuditPagination(pagination) {
    const container = document.getElementById('auditLogPagination');
    
    if (!pagination || pagination.total_pages <= 1) {
        container.innerHTML = '';
        return;
    }
    
    let html = '<nav><ul class="pagination">';
    
    // Previous button
    if (pagination.current_page > 1) {
        html += `<li class="page-item">
            <a class="page-link" href="#" onclick="loadAuditLogs(${pagination.current_page - 1})">Previous</a>
        </li>`;
    }
    
    // Page numbers
    for (let i = 1; i <= pagination.total_pages; i++) {
        if (i === pagination.current_page) {
            html += `<li class="page-item active">
                <span class="page-link">${i}</span>
            </li>`;
        } else {
            html += `<li class="page-item">
                <a class="page-link" href="#" onclick="loadAuditLogs(${i})">${i}</a>
            </li>`;
        }
    }
    
    // Next button
    if (pagination.current_page < pagination.total_pages) {
        html += `<li class="page-item">
            <a class="page-link" href="#" onclick="loadAuditLogs(${pagination.current_page + 1})">Next</a>
        </li>`;
    }
    
    html += '</ul></nav>';
    container.innerHTML = html;
}

function getActionBadgeColor(action) {
    const colors = {
        'created': 'success',
        'updated': 'primary',
        'deleted': 'danger',
        'reset': 'warning',
        'imported': 'info',
        'exported': 'secondary',
        'bulk_updated': 'dark'
    };
    return colors[action] || 'secondary';
}

function refreshAuditLog() {
    loadAuditLogs(1);
}

// Real-time validation
function validateSetting(input) {
    const settingKey = input.name;
    const value = input.value;
    
    // Basic validation based on input type
    if (input.type === 'number') {
        const min = input.getAttribute('min');
        const max = input.getAttribute('max');
        
        if (min && parseFloat(value) < parseFloat(min)) {
            showValidationError(input, `Value must be at least ${min}`);
            return false;
        }
        
        if (max && parseFloat(value) > parseFloat(max)) {
            showValidationError(input, `Value must be at most ${max}`);
            return false;
        }
    }
    
    if (input.hasAttribute('required') && !value) {
        showValidationError(input, 'This field is required');
        return false;
    }
    
    clearValidationError(input);
    return true;
}

function showValidationError(input, message) {
    input.classList.add('is-invalid');
    
    let feedback = input.parentNode.querySelector('.invalid-feedback');
    if (!feedback) {
        feedback = document.createElement('div');
        feedback.className = 'invalid-feedback';
        input.parentNode.appendChild(feedback);
    }
    feedback.textContent = message;
}

function clearValidationError(input) {
    input.classList.remove('is-invalid');
    const feedback = input.parentNode.querySelector('.invalid-feedback');
    if (feedback) {
        feedback.remove();
    }
}

// Auto-save functionality for better UX
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('.settings-form');
    
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            input.addEventListener('change', function() {
                // Validate input
                if (validateSetting(this)) {
                    // Add visual feedback
                    this.classList.add('border-success');
                    setTimeout(() => {
                        this.classList.remove('border-success');
                    }, 1000);
                }
            });
            
            input.addEventListener('blur', function() {
                validateSetting(this);
            });
        });
    });
    
    // Search and filter event listeners
    document.getElementById('settingsSearch').addEventListener('input', filterSettings);
    document.getElementById('categoryFilter').addEventListener('change', filterSettings);
    document.getElementById('groupFilter').addEventListener('change', filterSettings);
    
    // Audit log event listeners
    document.getElementById('auditActionFilter').addEventListener('change', () => loadAuditLogs(1));
    document.getElementById('auditDateFrom').addEventListener('change', () => loadAuditLogs(1));
    document.getElementById('auditDateTo').addEventListener('change', () => loadAuditLogs(1));
    
    // Load audit logs when modal is shown
    document.getElementById('auditModal').addEventListener('shown.bs.modal', function() {
        loadAuditLogs(1);
    });
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection



