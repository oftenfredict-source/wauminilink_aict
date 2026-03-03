@extends('layouts.index')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="mt-4">Departments</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Departments</li>
                </ol>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                <i class="fas fa-plus me-2"></i>Add Department
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-layer-group me-2"></i>Church Departments
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Department Name</th>
                                <th>Head of Department</th>
                                <th>Members</th>
                                <th>Status</th>
                                <th>Description</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($departments as $dept)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold">{{ $dept->name }}</div>
                                    </td>
                                    <td>
                                        @if($dept->head)
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-light text-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                    style="width: 32px; height: 32px;">
                                                    <i class="fas fa-user-tie small"></i>
                                                </div>
                                                {{ $dept->head->full_name }}
                                            </div>
                                        @else
                                            <span class="text-muted fst-italic">No head assigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php $memberCount = $dept->members->count(); @endphp
                                        <span class="badge bg-info text-dark"
                                              style="cursor:pointer;"
                                              data-bs-toggle="modal"
                                              data-bs-target="#assignMembersModal{{ $dept->id }}"
                                              title="Click to manage members">
                                            <i class="fas fa-users me-1"></i>{{ $memberCount }}
                                        </span>
                                        @if($memberCount > 0)
                                            <small class="text-muted ms-1">assigned</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($dept->status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">
                                        {{ Str::limit($dept->description, 50) ?: '-' }}
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-info"
                                                data-bs-toggle="modal"
                                                data-bs-target="#assignMembersModal{{ $dept->id }}"
                                                title="Assign Members">
                                                <i class="fas fa-user-plus"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editDepartmentModal{{ $dept->id }}"
                                                title="Edit Department">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="confirmDelete({{ $dept->id }})"
                                                title="Delete Department">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <form id="delete-form-{{ $dept->id }}"
                                                action="{{ route('departments.destroy', $dept->id) }}" method="POST"
                                                class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                {{-- ==================== ASSIGN MEMBERS MODAL ==================== --}}
                                <div class="modal fade" id="assignMembersModal{{ $dept->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-users me-2"></i>Assign Members — {{ $dept->name }}
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('departments.assign-members', $dept->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <p class="text-muted small mb-3">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Select the members to assign to this department. Previously assigned members will be retained if re-selected.
                                                    </p>

                                                    {{-- Search box --}}
                                                    <div class="mb-3">
                                                        <input type="text"
                                                               class="form-control form-control-sm"
                                                               placeholder="Search members..."
                                                               onkeyup="filterMembers(this, 'memberList{{ $dept->id }}')"
                                                               id="memberSearch{{ $dept->id }}">
                                                    </div>

                                                    {{-- Select / Deselect All --}}
                                                    <div class="d-flex gap-2 mb-3">
                                                        <button type="button" class="btn btn-sm btn-outline-success"
                                                            onclick="selectAll('memberList{{ $dept->id }}')">
                                                            <i class="fas fa-check-square me-1"></i>Select All
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                                            onclick="deselectAll('memberList{{ $dept->id }}')">
                                                            <i class="fas fa-square me-1"></i>Deselect All
                                                        </button>
                                                        <span class="ms-auto text-muted small align-self-center"
                                                              id="selectedCount{{ $dept->id }}">
                                                            {{ $dept->members->count() }} selected
                                                        </span>
                                                    </div>

                                                    {{-- Member checkbox list --}}
                                                    <div id="memberList{{ $dept->id }}"
                                                         style="max-height: 350px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 8px; padding: 8px;">
                                                        @forelse($members as $member)
                                                            <div class="member-item d-flex align-items-center py-2 px-2 rounded"
                                                                 style="border-bottom: 1px solid #f0f0f0;"
                                                                 data-name="{{ strtolower($member->full_name) }}">
                                                                <input class="form-check-input me-3 member-checkbox"
                                                                       type="checkbox"
                                                                       name="member_ids[]"
                                                                       value="{{ $member->id }}"
                                                                       id="member_{{ $dept->id }}_{{ $member->id }}"
                                                                       data-list="memberList{{ $dept->id }}"
                                                                       {{ $dept->members->contains($member->id) ? 'checked' : '' }}>
                                                                <label class="form-check-label w-100"
                                                                       for="member_{{ $dept->id }}_{{ $member->id }}"
                                                                       style="cursor: pointer;">
                                                                    <span class="fw-bold">{{ $member->full_name }}</span>
                                                                    @if($member->member_id)
                                                                        <small class="text-muted ms-2">#{{ $member->member_id }}</small>
                                                                    @endif
                                                                </label>
                                                            </div>
                                                        @empty
                                                            <p class="text-muted text-center py-3">No members found.</p>
                                                        @endforelse
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-light">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-save me-2"></i>Save Members
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                {{-- ==================== EDIT DEPARTMENT MODAL ==================== --}}
                                <div class="modal fade" id="editDepartmentModal{{ $dept->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Department: {{ $dept->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('departments.update', $dept->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="name{{ $dept->id }}" class="form-label">Department Name</label>
                                                        <input type="text" class="form-control" id="name{{ $dept->id }}"
                                                            name="name" value="{{ $dept->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="head_id{{ $dept->id }}" class="form-label">Head of Department</label>
                                                        <select class="form-select select2-basic" id="head_id{{ $dept->id }}" name="head_id">
                                                            <option value="">-- No Head --</option>
                                                            @foreach($members as $member)
                                                                <option value="{{ $member->id }}" {{ $dept->head_id == $member->id ? 'selected' : '' }}>
                                                                    {{ $member->full_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="status{{ $dept->id }}" class="form-label">Status</label>
                                                        <select class="form-select" id="status{{ $dept->id }}" name="status" required>
                                                            <option value="active" {{ $dept->status === 'active' ? 'selected' : '' }}>Active</option>
                                                            <option value="inactive" {{ $dept->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="description{{ $dept->id }}" class="form-label">Description</label>
                                                        <textarea class="form-control" id="description{{ $dept->id }}"
                                                            name="description" rows="3">{{ $dept->description }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted py-3">
                                            <i class="fas fa-layer-group fa-3x mb-3 opacity-25"></i>
                                            <p>No departments found. Click "Add Department" to create one.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== ADD DEPARTMENT MODAL ==================== --}}
    <div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Add New Department</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('departments.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Department Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}" placeholder="e.g. Worship Department" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="head_id" class="form-label fw-bold">Head of Department</label>
                            <select class="form-select select2-add" id="head_id" name="head_id">
                                <option value="">-- No Head --</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ old('head_id') == $member->id ? 'selected' : '' }}>
                                        {{ $member->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label fw-bold">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                placeholder="Briefly describe the department's role...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Department</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This department will be permanently deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        /**
         * Filter member checkboxes by name
         */
        function filterMembers(input, listId) {
            const filter = input.value.toLowerCase();
            const items = document.querySelectorAll(`#${listId} .member-item`);
            items.forEach(item => {
                item.style.display = item.dataset.name.includes(filter) ? '' : 'none';
            });
        }

        /**
         * Select all visible checkboxes in a list
         */
        function selectAll(listId) {
            const checkboxes = document.querySelectorAll(`#${listId} .member-checkbox`);
            checkboxes.forEach(cb => {
                if (cb.closest('.member-item').style.display !== 'none') cb.checked = true;
            });
            updateCount(listId);
        }

        /**
         * Deselect all checkboxes in a list
         */
        function deselectAll(listId) {
            const checkboxes = document.querySelectorAll(`#${listId} .member-checkbox`);
            checkboxes.forEach(cb => cb.checked = false);
            updateCount(listId);
        }

        /**
         * Update the selected count badge
         */
        function updateCount(listId) {
            const checked = document.querySelectorAll(`#${listId} .member-checkbox:checked`).length;
            const countEl = document.getElementById('selectedCount' + listId.replace('memberList', ''));
            if (countEl) countEl.textContent = checked + ' selected';
        }

        // Attach change listeners to all checkboxes to update counts
        document.querySelectorAll('.member-checkbox').forEach(cb => {
            cb.addEventListener('change', function () {
                updateCount(this.dataset.list);
            });
        });

        $(document).ready(function () {
            if ($('.select2-basic').length) {
                $('.select2-basic').select2({
                    dropdownParent: function (e) {
                        return $(e).closest('.modal');
                    },
                    width: '100%'
                });
            }

            if ($('.select2-add').length) {
                $('.select2-add').select2({
                    dropdownParent: $('#addDepartmentModal'),
                    width: '100%'
                });
            }
        });
    </script>
@endsection