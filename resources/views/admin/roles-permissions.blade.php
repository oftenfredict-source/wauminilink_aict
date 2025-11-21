@extends('layouts.index')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm dashboard-header" style="background:#17082d;">
                <div class="card-body text-white py-2 px-3">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center border border-white border-2" style="width:48px; height:48px; background:rgba(255,255,255,.15);">
                                <i class="fas fa-shield-alt text-white"></i>
                            </div>
                            <div class="lh-sm">
                                <h5 class="mb-0 fw-semibold" style="color: white !important;">Roles & Permissions</h5>
                                <small style="color: white !important;">Manage role-based access control</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach($roles as $role)
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                {{ ucfirst($role) }} Role Permissions
                <span class="badge badge-info">{{ count($rolePermissions[$role] ?? []) }} permissions</span>
            </h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.roles-permissions.update') }}">
                @csrf
                <input type="hidden" name="role" value="{{ $role }}">
                
                @foreach($permissions as $category => $categoryPermissions)
                <div class="mb-4">
                    <h6 class="font-weight-bold text-uppercase text-muted mb-3">{{ $category }}</h6>
                    <div class="row">
                        @foreach($categoryPermissions as $permission)
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input 
                                    class="form-check-input" 
                                    type="checkbox" 
                                    name="permissions[]" 
                                    value="{{ $permission->slug }}"
                                    id="perm_{{ $role }}_{{ $permission->id }}"
                                    {{ in_array($permission->slug, $rolePermissions[$role] ?? []) ? 'checked' : '' }}
                                >
                                <label class="form-check-label" for="perm_{{ $role }}_{{ $permission->id }}">
                                    {{ $permission->name }}
                                    @if($permission->description)
                                    <br><small class="text-muted">{{ $permission->description }}</small>
                                    @endif
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update {{ ucfirst($role) }} Permissions
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endsection

