@extends('layouts.index')

@section('styles')
    <style>
        .logo-white-section {
            background-color: white !important;
            border-radius: 8px;
            margin: 8px 0;
            padding: 8px 16px !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .logo-white-section:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .navbar-brand .logo {
            transition: all 0.3s ease;
        }

        .navbar-brand .logo:hover {
            transform: scale(1.05);
        }

        .navbar-brand {
            min-height: 60px;
            display: flex !important;
            align-items: center !important;
        }



        .wizard-step.active .step-label {
            color: #940000;
            font-weight: 700;
        }

        .wizard-step.completed .step-label {
            color: #198754;
            font-weight: 700;
        }

        .wizard-step:not(:last-child)::after {
            content: "";
            position: absolute;
            top: 22px;
            right: -32px;
            width: 64px;
            height: 4px;
            background: linear-gradient(90deg, #940000 0%, #ff4d4d 100%);
            border-radius: 2px;
            z-index: 0;
            opacity: 0.15;
            transition: background 0.3s;
        }

        .wizard-step.completed:not(:last-child)::after {
            background: linear-gradient(90deg, #198754 0%, #940000 100%);
            opacity: 0.3;
        }

        @media (max-width: 767px) {
            .wizard-step:not(:last-child)::after {
                width: 32px;
                right: -18px;
            }
        }

        /* Transition animations for steps */
        .fade-in {
            animation: fadeInStep 0.5s cubic-bezier(.4, 0, .2, 1);
        }

        .fade-out {
            animation: fadeOutStep 0.5s cubic-bezier(.4, 0, .2, 1);
        }

        @keyframes fadeInStep {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        @keyframes fadeOutStep {
            from {
                opacity: 1;
                transform: none;
            }

            to {
                opacity: 0;
                transform: translateY(-24px);
            }
        }

        /* Mobile Responsive Styles for Navbar */
        @media (max-width: 991.98px) {

            /* Hide logo on mobile */
            .sb-topnav .navbar-brand {
                display: none !important;
            }

            /* Ensure navbar has proper padding on mobile to prevent cutoff */
            .sb-topnav {
                padding-left: 0.75rem !important;
                padding-right: 0.5rem !important;
                overflow-x: hidden !important;
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                z-index: 1040 !important;
                max-width: 100vw !important;
                width: 100% !important;
            }

            /* Add margin-top to content to account for fixed navbar */
            #layoutSidenav_content {
                margin-top: -10px !important;
            }

            /* Ensure sidebar doesn't override navbar */
            #layoutSidenav_nav,
            .sb-sidenav {
                z-index: 1035 !important;
            }

            /* Ensure sidebar links are clickable on mobile */
            #sidenavAccordion .nav-link {
                pointer-events: auto !important;
                z-index: 1040 !important;
            }

            /* Remove overlay that might block clicks */
            body.sb-sidenav-toggled #layoutSidenav #layoutSidenav_content:before,
            #layoutSidenav.sb-sidenav-toggled #layoutSidenav_content:before {
                pointer-events: none !important;
                z-index: 1034 !important;
            }

            /* Ensure main content is always clickable */
            #layoutSidenav_content {
                pointer-events: auto !important;
            }

            /* Ensure sidebar overlay doesn't cover navbar */
            #layoutSidenav_content:before,
            body.sb-sidenav-toggled #layoutSidenav_content:before {
                z-index: 1034 !important;
            }

            /* Notification dropdown positioning on mobile */
            #notificationDropdown .notification-dropdown,
            #notificationDropdown .dropdown-menu-end.notification-dropdown,
            #notificationDropdown.show .notification-dropdown,
            #notificationDropdown.show .dropdown-menu-end.notification-dropdown,
            .notification-dropdown.dropdown-menu-end,
            .dropdown-menu-end.notification-dropdown {
                width: calc(100vw - 1rem) !important;
                max-width: calc(100vw - 1rem) !important;
                margin: 0 !important;
                left: 0.5rem !important;
                right: 0.5rem !important;
                transform: none !important;
                position: fixed !important;
                top: 60px !important;
                max-height: calc(100vh - 120px) !important;
                border-radius: 12px !important;
                z-index: 1055 !important;
                inset: 60px 0.5rem auto 0.5rem !important;
            }

            /* Ensure dropdown parent has proper positioning */
            .sb-topnav .nav-item.dropdown {
                position: relative !important;
            }

            /* Ensure profile dropdown menu is visible when active */
            .sb-topnav .dropdown-menu:not(.notification-dropdown) {
                position: absolute !important;
                z-index: 1050 !important;
                right: 0 !important;
                left: auto !important;
                margin-top: 0.5rem !important;
                min-width: 180px !important;
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
                background-color: #fff !important;
                border: 1px solid rgba(0, 0, 0, 0.15) !important;
                border-radius: 0.375rem !important;
            }

            .sb-topnav .dropdown-menu:not(.notification-dropdown).show {
                display: block !important;
            }

            /* Ensure dropdown items are visible */
            .sb-topnav .dropdown-menu .dropdown-item {
                padding: 0.5rem 1rem !important;
                font-size: 0.9rem !important;
                white-space: nowrap !important;
                color: #212529 !important;
                display: block !important;
            }

            .sb-topnav .dropdown-menu .dropdown-item:hover {
                background-color: #f8f9fa !important;
                color: #212529 !important;
            }

            /* Ensure profile dropdown is positioned correctly on mobile */
            @media (max-width: 575.98px) {
                .sb-topnav .dropdown-menu:not(.notification-dropdown) {
                    min-width: 160px !important;
                    max-width: calc(100vw - 1rem) !important;
                }
            }

            /* Ensure navbar container doesn't cut off content */
            body.sb-nav-fixed .sb-topnav {
                margin-left: 0 !important;
                width: 100% !important;
                max-width: 100vw !important;
            }

            /* Ensure navbar content doesn't overflow */
            .sb-topnav .navbar-nav,
            .sb-topnav .d-flex {
                max-width: 100% !important;
                overflow-x: hidden !important;
            }

            /* Ensure navbar doesn't clip dropdown menus */
            .sb-topnav {
                overflow: visible !important;
            }

            .sb-topnav .navbar-nav {
                overflow: visible !important;
            }

            /* Ensure navbar nav items are visible and don't shrink */
            .sb-topnav .navbar-nav {
                flex-shrink: 0 !important;
                display: flex !important;
                align-items: center !important;
            }

            .sb-topnav .navbar-nav .nav-item {
                flex-shrink: 0 !important;
                display: flex !important;
                align-items: center !important;
            }

            /* Profile dropdown icon - ensure it's always visible */
            #navbarDropdown {
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                padding: 0.5rem !important;
                min-width: 40px !important;
                min-height: 40px !important;
            }

            #navbarDropdown i {
                font-size: 1.1rem !important;
                display: block !important;
            }

            /* Welcome message on mobile */
            .sb-topnav .navbar-text {
                font-size: 0.9rem !important;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 150px;
            }

            /* Notification dropdown on mobile */
            .notification-dropdown {
                width: calc(100vw - 2rem) !important;
                max-width: 400px !important;
            }

            /* Form card responsive padding */
            .main-form-card {
                margin: 0.5rem !important;
            }

            .card-body {
                padding: 1rem !important;
            }

            /* Card header responsive */
            .card-header {
                padding: 1rem !important;
            }

            /* Wizard steps responsive */
            .wizard-step {
                flex: 0 0 auto;
                min-width: 60px;
            }

            .step-label {
                font-size: 0.7rem !important;
            }

            /* Form columns stack on mobile */
            .row.g-4>[class*="col-"] {
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 575.98px) {

            /* Extra small devices */
            #layoutSidenav_content {
                margin-top: -10px !important;
            }

            .sb-topnav .navbar-text {
                font-size: 0.8rem !important;
                max-width: 120px;
            }

            .card-header .btn {
                font-size: 0.75rem !important;
                padding: 0.25rem 0.5rem !important;
            }

            .card-header .btn i {
                margin-right: 0.25rem !important;
            }

            .wizard-step {
                min-width: 50px;
            }

            .step-circle {
                width: 30px !important;
                height: 30px !important;
                font-size: 0.8rem !important;
            }

            /* Form inputs on mobile */
            .form-floating>label {
                font-size: 0.875rem !important;
            }

            .form-control,
            .form-select {
                font-size: 0.9rem !important;
            }

            /* Card header title on mobile */
            .card-header .fs-5 {
                font-size: 1rem !important;
            }

            /* Button spacing on mobile */
            .card-header .mt-3 {
                margin-top: 0.75rem !important;
            }

            .card-header .btn {
                margin-bottom: 0.5rem;
            }

            /* Wizard step labels */
            .step-label {
                font-size: 0.65rem !important;
            }

            /* Form row gaps */
            .row.g-4 {
                --bs-gutter-y: 1rem;
            }

            /* Notification dropdown on extra small mobile */
            #notificationDropdown .notification-dropdown,
            #notificationDropdown .dropdown-menu-end.notification-dropdown,
            #notificationDropdown.show .notification-dropdown,
            #notificationDropdown.show .dropdown-menu-end.notification-dropdown,
            .notification-dropdown.dropdown-menu-end,
            .dropdown-menu-end.notification-dropdown {
                width: calc(100vw - 0.5rem) !important;
                max-width: calc(100vw - 0.5rem) !important;
                margin: 0 !important;
                left: 0.25rem !important;
                right: 0.25rem !important;
                transform: none !important;
                position: fixed !important;
                top: 60px !important;
                max-height: calc(100vh - 100px) !important;
                border-radius: 10px !important;
                z-index: 1055 !important;
                inset: 60px 0.25rem auto 0.25rem !important;
            }
        }

        .card-header.bg-gradient-primary {
            background: linear-gradient(45deg, #940000, #ff4d4d) !important;
        }

        .btn-primary {
            background-color: #940000 !important;
            border-color: #940000 !important;
        }

        .btn-primary:hover {
            background-color: #7a0000 !important;
            border-color: #7a0000 !important;
        }

        .btn-outline-primary {
            color: #940000 !important;
            border-color: #940000 !important;
        }

        .btn-outline-primary:hover {
            background-color: #940000 !important;
            color: white !important;
        }

        .bg-primary {
            background-color: #940000 !important;
        }

        .text-primary {
            color: #940000 !important;
        }

        .step-circle.bg-primary {
            background-color: #940000 !important;
        }

        .wizard-step.active .step-label {
            color: #940000 !important;
        }
    </style>
@endsection

@section('content')
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var userId = @json(session('user_id'));
                var name = @json(session('name'));
                var membershipType = @json(session('membership_type'));
                var tab = (membershipType === 'temporary') ? 'temporary' : 'permanent';
                // Show processing spinner first
                Swal.fire({
                    title: 'Processing...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                setTimeout(function () {
                    Swal.close();
                    Swal.fire({
                        icon: 'success',
                        title: 'Registration Successful',
                        html: `<div style='font-size:1.1em;text-align:left'><b>User ID:</b> ${userId}<br><b>Name:</b> ${name}<br><b>Membership Type:</b> ${membershipType}</div>`,
                        showConfirmButton: true,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#940000',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('members.view') }}?tab=" + tab;
                        }
                    });
                }, 1200); // 1.2 seconds spinner
            });
        </script>
    @endif
    <div class="container-fluid px-2 px-md-5 py-4 animated fadeIn">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden main-form-card">
            <div
                class="card-header bg-gradient-primary d-flex flex-column flex-md-row justify-content-between align-items-center py-4 px-4 border-0">
                <span class="fs-5 fw-bold text-white d-flex align-items-center">
                    <i class="fas fa-user-plus me-2"></i> <span id="stepHeaderTitle">Add Member</span>
                    <small class="ms-3 text-warning" id="liveMemberId"></small>
                </span>
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('members.add') }}" class="btn btn-light btn-sm me-2 shadow-sm"><i
                            class="fas fa-user-plus me-1"></i> Add Member</a>
                    <a href="{{ route('members.view') }}" class="btn btn-outline-light btn-sm shadow-sm"><i
                            class="fas fa-list me-1"></i> All Members</a>
                </div>
            </div>
            <div class="card-body bg-light px-4 py-4">
                @if ($errors->any())
                    <div class="alert alert-danger rounded-3 shadow-sm">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="addMemberForm" method="POST" action="{{ route('members.store') }}" enctype="multipart/form-data"
                    autocomplete="off">
                    @csrf

                    <!-- Step Wizard -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-center align-items-center gap-4 flex-wrap" id="wizardSteps">
                            <div class="wizard-step position-relative text-center active" data-step="1">
                                <div class="step-circle bg-primary text-white shadow">1</div>
                                <div class="step-label mt-2 small">Personal Info</div>
                            </div>
                            <div class="wizard-step position-relative text-center" data-step="2">
                                <div class="step-circle bg-secondary text-white shadow">2</div>
                                <div class="step-label mt-2 small">Other Info</div>
                            </div>
                            <div class="wizard-step position-relative text-center" data-step="3">
                                <div class="step-circle bg-secondary text-white shadow">3</div>
                                <div class="step-label mt-2 small">Residence</div>
                            </div>
                            <div class="wizard-step position-relative text-center" data-step="4">
                                <div class="step-circle bg-secondary text-white shadow">4</div>
                                <div class="step-label mt-2 small">Family Information</div>
                            </div>
                            <div class="wizard-step position-relative text-center" data-step="5">
                                <div class="step-circle bg-secondary text-white shadow">5</div>
                                <div class="step-label mt-2 small">Summary</div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 1: Personal Information -->
                    <div id="step1">
                        <div class="row g-4 mb-3">
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <select name="membership_type" id="membership_type" class="form-select select2"
                                        required>
                                        <option value=""></option>
                                        <option value="permanent">Permanent</option>
                                        <option value="temporary">Temporary</option>
                                    </select>
                                    <label for="membership_type">Membership Type</label>
                                </div>
                            </div>
                            <div class="col-md-4" id="memberTypeWrapper">
                                <div class="form-floating">
                                    <select name="member_type" id="member_type" class="form-select select2" required>
                                        <option value=""></option>
                                        <option value="father">Father</option>
                                        <option value="mother">Mother</option>
                                        <option value="independent">Independent Person</option>
                                    </select>
                                    <label for="member_type">Member Type</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="envelope_number" id="envelope_number"
                                        required>
                                    <label for="envelope_number">Envelope Number</label>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="full_name" id="full_name" required>
                                    <label for="full_name">Full Name</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <select class="form-select select2" name="gender" id="gender" required>
                                        <option value=""></option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                    <label for="gender">Gender</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="date" class="form-control" name="date_of_birth" id="date_of_birth"
                                        required>
                                    <label for="date_of_birth">Date of Birth</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <select class="form-select select2" name="education_level" id="education_level">
                                        <option value=""></option>
                                        <option value="primary">Primary</option>
                                        <option value="secondary">Secondary</option>
                                        <option value="high_level">High Level</option>
                                        <option value="certificate">Certificate</option>
                                        <option value="diploma">Diploma</option>
                                        <option value="bachelor_degree">Bachelor Degree</option>
                                        <option value="masters">Masters</option>
                                        <option value="phd">PhD</option>
                                        <option value="professor">Professor</option>
                                        <option value="not_studied">Not Studied</option>
                                    </select>
                                    <label for="education_level">Education Level</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="profession" id="profession" required>
                                    <label for="profession">Profession</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="nida_number" id="nida_number"
                                        minlength="20" maxlength="20">
                                    <label for="nida_number">NIDA Number (optional)</label>
                                </div>
                            </div>
                        </div>

                        <!-- Passport Picture Upload -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="profile_picture" class="form-label">
                                        <i class="fas fa-camera me-2"></i>Passport Picture (Optional)
                                    </label>
                                    <input type="file" class="form-control" name="profile_picture" id="profile_picture"
                                        accept="image/*" onchange="handleImagePreview(this)">
                                    <small class="text-muted">Upload a clear passport-sized photo (JPG, PNG, max
                                        2MB)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-center">
                                    <div id="imagePreview" class="border rounded p-3" style="display: none;">
                                        <img id="previewImg" src="" alt="Preview" class="img-thumbnail"
                                            style="max-width: 150px; max-height: 150px;">
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-danger" id="removeImage"
                                                onclick="removeImagePreview()">
                                                <i class="fas fa-trash me-1"></i>Remove
                                            </button>
                                        </div>
                                    </div>
                                    <div id="noImagePlaceholder" class="border rounded p-4 text-muted">
                                        <i class="fas fa-image fa-3x mb-2"></i>
                                        <p class="mb-0">No image selected</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-lg px-4 shadow-sm next-step"
                                id="nextStep1">Next <i class="fas fa-arrow-right ms-1"></i></button>
                        </div>
                    </div>

                    <!-- Step 2: Other Information -->
                    <div id="step2" style="display:none;">
                        <div class="row g-4 mb-4">
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <div class="input-group">
                                        <span class="input-group-text">+255</span>
                                        <input type="text" class="form-control" name="phone_number" id="phone_number"
                                            placeholder="744000000" required>
                                    </div>
                                    <label for="phone_number"></label>
                                </div>
                                <small class="text-muted ms-1">Enter your phone number without +255 (e.g.,
                                    712345678)</small>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="email" class="form-control" name="email" id="email">
                                    <label for="email">Email (optional)</label>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <select class="form-select select2" id="region" name="region" required></select>
                                    <label for="region">Region</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <select class="form-select select2" id="district" name="district" required></select>
                                    <label for="district">District</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="ward" id="ward" required>
                                    <label for="ward">Ward</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="street" id="street" required>
                                    <label for="street">Street</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="address" id="address"
                                        style="height: 48px;" required />
                                    <label for="address">P O Box</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <select class="form-select select2" id="tribe" name="tribe" required></select>
                                    <label for="tribe">Tribe</label>
                                </div>
                            </div>
                            <div class="col-md-3" id="otherTribeWrapper" style="display:none;">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="other_tribe" id="other_tribe">
                                    <label for="other_tribe">Other Tribe</label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary btn-lg px-4 shadow-sm prev-step"
                                id="prevStep2"><i class="fas fa-arrow-left me-1"></i>Back</button>
                            <button type="button" class="btn btn-primary btn-lg px-4 shadow-sm next-step"
                                id="nextStep2">Next <i class="fas fa-arrow-right ms-1"></i></button>
                        </div>
                    </div>

                    <!-- Step 3: Current Residence -->
                    <div id="step3" style="display:none;">
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="residence_region" id="residence_region" class="form-select select2"
                                        required>
                                        <option value=""></option>
                                    </select>
                                    <label for="residence_region">Region</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="residence_district" id="residence_district" class="form-select select2"
                                        required>
                                        <option value=""></option>
                                    </select>
                                    <label for="residence_district">District</label>
                                </div>
                            </div>
                        </div>
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="residence_ward" id="residence_ward"
                                        required>
                                    <label for="residence_ward">Ward</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="residence_street" id="residence_street"
                                        required>
                                    <label for="residence_street">Street</label>
                                </div>
                            </div>
                        </div>
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="residence_road" id="residence_road">
                                    <label for="residence_road">Road Name (Optional)</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="residence_house_number"
                                        id="residence_house_number">
                                    <label for="residence_house_number">House Number (Optional)</label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary btn-lg px-4 shadow-sm prev-step"
                                id="prevStep3"><i class="fas fa-arrow-left me-1"></i>Back</button>
                            <button type="button" class="btn btn-primary btn-lg px-4 shadow-sm next-step"
                                id="nextStep3">Next <i class="fas fa-arrow-right ms-1"></i></button>
                        </div>
                    </div>

                    <!-- Step 4: Family Information -->
                    <div id="step4" style="display:none;">
                        <!-- Marital Status Section (conditional for mother/father) -->
                        <div id="maritalStatusSection" class="border rounded-3 p-4 mb-4 bg-white shadow-sm"
                            style="display:none;">
                            <h6 class="mb-3 text-primary fw-bold" id="maritalStatusTitle"><i
                                    class="fas fa-heart me-2"></i>Marital Status</h6>
                            <div class="mb-3">
                                <div class="form-floating">
                                    <select class="form-select" name="marital_status" id="marital_status">
                                        <option value=""></option>
                                        <option value="married">Married</option>
                                        <option value="divorced">Divorced</option>
                                        <option value="widowed">Widowed</option>
                                        <option value="separated">Separated</option>
                                    </select>
                                    <label for="marital_status">Marital Status</label>
                                </div>
                            </div>
                            <!-- Spouse Information (only shown when married) -->
                            <div id="spouseInfoFields" style="display:none;">
                                <h6 class="mb-3 text-primary fw-bold" id="spouseSectionTitle"><i
                                        class="fas fa-user me-2"></i>Spouse Information</h6>
                                <div class="row g-4 mb-3">
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="spouse_full_name"
                                                id="spouse_full_name">
                                            <label for="spouse_full_name">Full Name</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="date" class="form-control" name="spouse_date_of_birth"
                                                id="spouse_date_of_birth">
                                            <label for="spouse_date_of_birth">Date of Birth</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select class="form-select select2" name="spouse_education_level"
                                                id="spouse_education_level">
                                                <option value=""></option>
                                                <option value="primary">Primary</option>
                                                <option value="secondary">Secondary</option>
                                                <option value="high_level">High Level</option>
                                                <option value="certificate">Certificate</option>
                                                <option value="diploma">Diploma</option>
                                                <option value="bachelor_degree">Bachelor Degree</option>
                                                <option value="masters">Masters</option>
                                                <option value="phd">PhD</option>
                                                <option value="professor">Professor</option>
                                                <option value="not_studied">Not Studied</option>
                                            </select>
                                            <label for="spouse_education_level">Education Level</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-4 mb-3">
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="spouse_profession"
                                                id="spouse_profession">
                                            <label for="spouse_profession">Profession</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="spouse_nida_number"
                                                id="spouse_nida_number" minlength="20" maxlength="20">
                                            <label for="spouse_nida_number">NIDA Number (optional)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="email" class="form-control" name="spouse_email" id="spouse_email">
                                            <label for="spouse_email">Email (optional)</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-4 mb-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select" name="spouse_church_member"
                                                id="spouse_church_member">
                                                <option value=""></option>
                                                <option value="yes">Yes, spouse is a church member</option>
                                                <option value="no">No, spouse is not a church member</option>
                                            </select>
                                            <label for="spouse_church_member">Is your spouse a member of this
                                                church?</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <div class="input-group">
                                                <span class="input-group-text">+255</span>
                                                <input type="text" class="form-control" name="spouse_phone_number"
                                                    id="spouse_phone_number" placeholder="744000000">
                                            </div>
                                            <label for="spouse_phone_number"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-4 mb-3" id="spouseEnvelopeWrapper" style="display:none;">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="spouse_envelope_number"
                                                id="spouse_envelope_number">
                                            <label for="spouse_envelope_number">Spouse Envelope Number</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-4 mb-3">
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select class="form-select select2" name="spouse_tribe" id="spouse_tribe">
                                            </select>
                                            <label for="spouse_tribe">Spouse Tribe</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4" id="spouseOtherTribeWrapper" style="display:none;">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="spouse_other_tribe"
                                                id="spouse_other_tribe">
                                            <label for="spouse_other_tribe">Spouse Other Tribe</label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Spouse Passport Picture Upload -->
                                <div class="row g-4 mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="spouse_profile_picture" class="form-label">
                                                <i class="fas fa-camera me-2"></i>Spouse Passport Picture (Optional)
                                            </label>
                                            <input type="file" class="form-control" name="spouse_profile_picture"
                                                id="spouse_profile_picture" accept="image/*"
                                                onchange="handleSpouseImagePreview(this)">
                                            <small class="text-muted">Upload a clear passport-sized photo (JPG, PNG, max
                                                2MB)</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-center">
                                            <div id="spouseImagePreview" class="border rounded p-3" style="display: none;">
                                                <img id="spousePreviewImg" src="" alt="Spouse Preview" class="img-thumbnail"
                                                    style="max-width: 150px; max-height: 150px;">
                                                <div class="mt-2">
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        id="removeSpouseImage" onclick="removeSpouseImagePreview()">
                                                        <i class="fas fa-trash me-1"></i>Remove
                                                    </button>
                                                </div>
                                            </div>
                                            <div id="spouseNoImagePlaceholder" class="border rounded p-4 text-muted">
                                                <i class="fas fa-image fa-3x mb-2"></i>
                                                <p class="mb-0">No image selected</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Guardian Section -->
                        <div id="guardianSection" class="border rounded-3 p-4 mb-4 bg-white shadow-sm"
                            style="display:none;">
                            <h6 class="mb-3 text-primary fw-bold"><i class="fas fa-user-shield me-2"></i>Guardian /
                                Responsible Person</h6>
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="guardian_name" id="guardian_name">
                                        <label for="guardian_name">Guardian Name</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <div class="input-group">
                                            <span class="input-group-text">+255</span>
                                            <input type="text" class="form-control" name="guardian_phone"
                                                id="guardian_phone" placeholder="744000000">
                                        </div>
                                        <label for="guardian_phone"></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <select class="form-select" name="guardian_relationship" id="guardian_relationship">
                                            <option value=""></option>
                                            <option value="Parent">Parent</option>
                                            <option value="Relative">Relative</option>
                                            <option value="Neighbor">Neighbor</option>
                                            <option value="Friend">Friend</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        <label for="guardian_relationship">Relationship</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Children Section -->
                        <div id="childrenSection" class="border rounded-3 p-4 mb-4 bg-white shadow-sm"
                            style="display:none;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="mb-0 text-primary fw-bold"><i class="fas fa-users-cog me-2"></i>Family Members /
                                    Dependents</h6>
                                <small class="text-muted">Including children, parents, or other relatives</small>
                            </div>
                            <div class="mb-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" id="addChildBtn"><i
                                        class="fas fa-plus me-1"></i>Add Member</button>
                            </div>
                            <div id="childrenContainer"></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary btn-lg px-4 shadow-sm prev-step"
                                id="prevStep4"><i class="fas fa-arrow-left me-1"></i>Back</button>
                            <button type="button" class="btn btn-primary btn-lg px-4 shadow-sm next-step"
                                id="nextStep4">Next <i class="fas fa-arrow-right ms-1"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <style>
        .main-form-card {
            background: linear-gradient(135deg, #f8f9fa 60%, #e9e4f0 100%);
        }

        /* Advanced Toggle Switch Styles */
        .toggle-switch-wrapper {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .toggle-switch-input {
            display: none;
        }

        .toggle-switch-label {
            position: relative;
            display: inline-block;
            width: 56px;
            height: 32px;
            cursor: pointer;
            user-select: none;
        }

        .toggle-switch-inner {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #dc3545;
            border-radius: 32px;
            transition: background 0.3s;
        }

        .toggle-switch-switch {
            position: absolute;
            top: 3px;
            left: 4px;
            width: 26px;
            height: 26px;
            background: #fff;
            border-radius: 50%;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
            transition: left 0.3s;
        }

        .toggle-switch-input:checked+.toggle-switch-label .toggle-switch-inner {
            background: #198754;
        }

        .toggle-switch-input:checked+.toggle-switch-label .toggle-switch-switch {
            left: 26px;
        }

        .toggle-switch-label:after {
            content: '';
            display: none;
        }

        .toggle-switch-label {
            margin-bottom: 0;
        }

        .toggle-switch-text {
            font-weight: 600;
            color: #7a0000;
            min-width: 32px;
            text-align: left;
            transition: color 0.3s;
        }

        .toggle-switch-input:checked~.toggle-switch-text {
            color: #198754;
        }

        .toggle-switch-label .toggle-switch-inner:before {
            content: 'No';
            position: absolute;
            left: 10px;
            top: 6px;
            color: #fff;
            font-size: 0.95em;
            font-weight: 500;
            transition: opacity 0.3s;
            opacity: 1;
        }

        .toggle-switch-input:checked+.toggle-switch-label .toggle-switch-inner:before {
            content: 'Yes';
            left: 28px;
            color: #fff;
            opacity: 1;
        }

        .bg-gradient-primary {
            background: linear-gradient(90deg, #7a0000 0%, #940000 100%) !important;
        }

        /* Next button styling to match header gradient */
        .next-step {
            background: linear-gradient(90deg, #7a0000 0%, #940000 100%) !important;
            border: none !important;
            color: #fff !important;
            transition: all 0.3s ease !important;
        }

        .next-step:hover {
            background: linear-gradient(90deg, #6b3a96 0%, #2f3b7c 100%) !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(91, 42, 134, 0.4) !important;
        }

        .next-step:active {
            transform: translateY(0);
        }

        .step-circle {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 1.3rem;
            font-weight: 600;
            margin: 0 auto;
            border: 3px solid #fff;
            box-shadow: 0 2px 8px rgba(91, 42, 134, 0.08);
            transition: background 0.3s, color 0.3s;
        }

        .wizard-step.active .step-circle {
            background: #7a0000 !important;
            color: #fff !important;
            border-color: #940000;
        }

        .wizard-step.completed .step-circle {
            background: #198754 !important;
            color: #fff !important;
            border-color: #198754;
            box-shadow: 0 0 0 4px #e9e4f0;
        }

        .wizard-step .step-label {
            color: #7a0000;
            font-weight: 500;
            letter-spacing: 0.01em;
        }

        .wizard-step.active .step-label {
            color: #940000;
            font-weight: 700;
        }

        .wizard-step.completed .step-label {
            color: #198754;
            font-weight: 700;
        }

        .wizard-step:not(:last-child)::after {
            content: "";
            position: absolute;
            top: 22px;
            right: -32px;
            width: 64px;
            height: 4px;
            background: linear-gradient(90deg, #7a0000 0%, #940000 100%);
            border-radius: 2px;
            z-index: 0;
            opacity: 0.15;
        }

        .wizard-step.completed:not(:last-child)::after {
            background: linear-gradient(90deg, #198754 0%, #7a0000 100%);
            opacity: 0.3;
        }

        @media (max-width: 767px) {
            .wizard-step:not(:last-child)::after {
                width: 32px;
                right: -18px;
            }
        }

        .form-floating>.form-control:focus,
        .form-floating>.form-select:focus {
            border-color: #7a0000;
            box-shadow: 0 0 0 0.2rem rgba(91, 42, 134, 0.08);
        }

        .form-floating>label {
            color: #7a0000;
            font-weight: 500;
        }

        .form-floating>.form-control.is-valid,
        .form-floating>.form-select.is-valid {
            border-color: #198754;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.08);
        }

        .form-floating>.form-control.is-invalid,
        .form-floating>.form-select.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.08);
        }

        .animated.fadeIn {
            animation: fadeIn 0.7s cubic-bezier(.4, 0, .2, 1);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        /* Custom transitions */
        .fade-in {
            opacity: 0;
            animation: fadeIn 0.5s forwards;
        }

        .fade-out {
            opacity: 1;
            animation: fadeOut 0.5s forwards;
        }

        @keyframes fadeOut {
            0% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // Wait for DOM and jQuery to be ready
        document.addEventListener('DOMContentLoaded', function () {
            // Wait a bit more to ensure jQuery is loaded
            setTimeout(function () {
                const membershipTypeEl = document.getElementById('membership_type');
                const memberTypeWrapper = document.getElementById('memberTypeWrapper');
                const memberTypeEl = document.getElementById('member_type');

                const childrenSection = document.getElementById('childrenSection');
                const childrenContainer = document.getElementById('childrenContainer');
                const addChildBtn = document.getElementById('addChildBtn');
                const guardianSection = document.getElementById('guardianSection');

                // Loading indicators for fetch requests
                const regionEl = document.getElementById('region');
                const districtEl = document.getElementById('district');
                const wardEl = document.getElementById('ward');

                // Residence dropdowns
                const residenceRegionEl = document.getElementById('residence_region');
                const residenceDistrictEl = document.getElementById('residence_district');

                regionEl.innerHTML = '<option value="">Loading regions...</option>';
                residenceRegionEl.innerHTML = '<option value="">Loading regions...</option>';

                fetch('{{ asset('data/tanzania-locations.json') }}').then(r => r.json()).then(data => {
                    const regions = data.regions || [];
                    const regionOptions = '<option value=""></option>' + regions.map(r => `<option value="${r.name}">${r.name}</option>`).join('');

                    regionEl.innerHTML = regionOptions;
                    residenceRegionEl.innerHTML = regionOptions;

                    regionEl.addEventListener('change', function () {
                        const selected = regions.find(x => x.name === this.value);
                        const districts = selected ? selected.districts : [];
                        districtEl.innerHTML = districts.length ? '<option value=""></option>' + districts.map(d => `<option value="${d.name}">${d.name}</option>`).join('') : '<option value="">Loading districts...</option>';
                        districtEl.dispatchEvent(new Event('change'));
                    });

                    residenceRegionEl.addEventListener('change', function () {
                        const selected = regions.find(x => x.name === this.value);
                        const districts = selected ? selected.districts : [];
                        residenceDistrictEl.innerHTML = districts.length ? '<option value=""></option>' + districts.map(d => `<option value="${d.name}">${d.name}</option>`).join('') : '<option value="">Loading districts...</option>';
                    });

                    districtEl.addEventListener('change', function () {
                        // Ward is now a text input, no need to load options
                    });

                    residenceDistrictEl.addEventListener('change', function () {
                        // Ward is now a text input, no need to load options
                    });

                    $('.select2').select2({ width: '100%' });
                    $(regionEl).on('change', () => $(districtEl).trigger('change.select2'));
                    $(residenceRegionEl).on('change', () => $(residenceDistrictEl).trigger('change.select2'));
                });

                // Tribes dataset with "Other" support and loading indicator
                const tribeEl = document.getElementById('tribe');
                const otherTribeWrapper = document.getElementById('otherTribeWrapper');
                tribeEl.innerHTML = '<option value="">Loading tribes...</option>';
                fetch('{{ asset('data/tribes.json') }}').then(r => r.json()).then(data => {
                    const tribes = data.tribes || [];
                    tribeEl.innerHTML = '<option value=""></option>' + tribes.map(t => `<option value="${t}">${t}</option>`).join('');
                    // Populate spouse tribe select with same dataset
                    const spouseTribeEl = document.getElementById('spouse_tribe');
                    if (spouseTribeEl) {
                        spouseTribeEl.innerHTML = '<option value=""></option>' + tribes.map(t => `<option value="${t}">${t}</option>`).join('');
                        spouseTribeEl.addEventListener('change', function () {
                            const spouseOtherWrapper = document.getElementById('spouseOtherTribeWrapper');
                            const spouseOtherInput = document.getElementById('spouse_other_tribe');
                            if (this.value === 'Other') {
                                spouseOtherWrapper.style.display = '';
                                spouseOtherInput.setAttribute('required', 'required');
                            } else {
                                spouseOtherWrapper.style.display = 'none';
                                spouseOtherInput.removeAttribute('required');
                                spouseOtherInput.value = '';
                            }
                        });
                        $('.select2').select2({ width: '100%' });
                    }
                    tribeEl.addEventListener('change', function () {
                        const otherTribeInput = document.getElementById('other_tribe');
                        if (this.value === 'Other') {
                            otherTribeWrapper.style.display = '';
                            otherTribeInput.setAttribute('required', 'required');
                        } else {
                            otherTribeWrapper.style.display = 'none';
                            otherTribeInput.removeAttribute('required');
                            otherTribeInput.value = ''; // Clear the value when hidden
                        }
                    });
                    $('.select2').select2({ width: '100%' });
                });

                const step1 = document.getElementById('step1');
                const step2 = document.getElementById('step2');
                const step3 = document.getElementById('step3');
                const step4 = document.getElementById('step4');
                const step5 = document.getElementById('step5');
                // Smoother step transitions
                function setStepActive(step) {
                    document.querySelectorAll('#wizardSteps .wizard-step').forEach((s, idx) => {
                        const stepNum = parseInt(s.getAttribute('data-step'));
                        if (stepNum < step) {
                            s.classList.add('completed');
                            s.classList.remove('active');
                        } else if (stepNum === step) {
                            s.classList.add('active');
                            s.classList.remove('completed');
                        } else {
                            s.classList.remove('active');
                            s.classList.remove('completed');
                        }
                    });
                }
                function showStep(stepToShow, stepToHide) {
                    const showEl = document.getElementById('step' + stepToShow);
                    const hideEl = document.getElementById('step' + stepToHide);
                    hideEl.classList.add('fade-out');
                    showEl.classList.add('fade-in');
                    setTimeout(() => {
                        hideEl.style.display = 'none';
                        hideEl.classList.remove('fade-out');
                        showEl.style.display = '';
                        setTimeout(() => { showEl.classList.remove('fade-in'); }, 500);
                    }, 500);
                    // Only show dynamic member type title for steps 1 and 2
                    const stepHeaderTitle = document.getElementById('stepHeaderTitle');
                    if (stepToShow === 1 || stepToShow === 2) {
                        const type = document.getElementById('member_type').value;
                        let memberLabel = '';
                        if (type === 'father') memberLabel = 'Father';
                        else if (type === 'mother') memberLabel = 'Mother';
                        else if (type === 'independent') memberLabel = 'Independent';
                        if (memberLabel) {
                            stepHeaderTitle.textContent = `Personal ${memberLabel} Information`;
                        } else {
                            stepHeaderTitle.textContent = 'Add Member';
                        }
                    } else if (stepToShow === 3) {
                        stepHeaderTitle.textContent = 'Current Residence';
                    } else if (stepToShow === 4) {
                        stepHeaderTitle.textContent = 'Family Information';
                    } else {
                        stepHeaderTitle.textContent = 'Add Member';
                    }
                }

                document.getElementById('nextStep1').addEventListener('click', function () {
                    if (!validateStep(1)) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Incomplete Information',
                            text: 'Please fill in all required fields in Step 1.',
                            confirmButtonColor: '#7a0000'
                        });
                        return;
                    }
                    showStep(2, 1); setStepActive(2);
                });
                document.getElementById('nextStep2').addEventListener('click', function () {
                    if (!validateStep(2)) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Incomplete Information',
                            text: 'Please fill in all required fields in Step 2.',
                            confirmButtonColor: '#7a0000'
                        });
                        return;
                    }
                    showStep(3, 2); setStepActive(3);
                });
                document.getElementById('nextStep3').addEventListener('click', function () {
                    if (!validateStep(3)) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Incomplete Information',
                            text: 'Please fill in all required fields in Step 3.',
                            confirmButtonColor: '#7a0000'
                        });
                        return;
                    }
                    showStep(4, 3); setStepActive(4);
                });
                document.getElementById('prevStep2').addEventListener('click', function () { showStep(1, 2); setStepActive(1); });
                document.getElementById('prevStep3').addEventListener('click', function () { showStep(2, 3); setStepActive(2); });
                document.getElementById('prevStep4').addEventListener('click', function () { showStep(3, 4); setStepActive(3); });

                // Dynamic children add/remove
                let childCount = 0;
                function renderChildren() {
                    // Attach validation and age calculation for all children
                    childrenContainer.querySelectorAll('.child-fullname').forEach(el => { el.addEventListener('input', () => markValid(el, !!el.value.trim())); });
                    childrenContainer.querySelectorAll('.child-gender').forEach(el => { el.addEventListener('change', () => markValid(el, !!el.value)); });
                    childrenContainer.querySelectorAll('.child-dob').forEach((el) => {
                        el.addEventListener('change', () => {
                            const d = new Date(el.value);
                            markValid(el, !!el.value && d < new Date());
                            const ageSpan = el.parentElement.querySelector('.child-age');
                            if (!!el.value && d < new Date()) {
                                const today = new Date();
                                let age = today.getFullYear() - d.getFullYear();
                                const m = today.getMonth() - d.getMonth();
                                if (m < 0 || (m === 0 && today.getDate() < d.getDate())) {
                                    age--;
                                }
                                ageSpan.textContent = age + ' yrs';
                                ageSpan.style.display = '';
                            } else {
                                ageSpan.textContent = '';
                                ageSpan.style.display = 'none';
                            }
                        });
                    });
                }

                function addChild() {
                    const idx = childCount;
                    const row = document.createElement('div');
                    row.className = 'row g-3 mb-2 align-items-end child-row';
                    row.innerHTML = `
                                                                                                                <div class="col-md-3">
                                                                                                                    <label class="form-label">Full Name</label>
                                                                                                                    <input type="text" class="form-control child-fullname" name="children[${idx}][full_name]" required>
                                                                                                                </div>
                                                                                                                <div class="col-md-2">
                                                                                                                    <label class="form-label">Relationship</label>
                                                                                                                    <select class="form-select child-relationship" name="children[${idx}][relationship]" required>
                                                                                                                        <option value="Son/Daughter">Son/Daughter</option>
                                                                                                                        <option value="Father">Father</option>
                                                                                                                        <option value="Mother">Mother</option>
                                                                                                                        <option value="Brother">Brother</option>
                                                                                                                        <option value="Sister">Sister</option>
                                                                                                                        <option value="Grandparent">Grandparent</option>
                                                                                                                        <option value="Relative">Relative</option>
                                                                                                                        <option value="Other">Other</option>
                                                                                                                    </select>
                                                                                                                </div>
                                                                                                                <div class="col-md-2">
                                                                                                                    <label class="form-label">Gender</label>
                                                                                                                    <select class="form-select child-gender" name="children[${idx}][gender]" required>
                                                                                                                        <option value=""></option>
                                                                                                                        <option value="male">Male</option>
                                                                                                                        <option value="female">Female</option>
                                                                                                                    </select>
                                                                                                                </div>
                                                                                                                <div class="col-md-2">
                                                                                                                    <label class="form-label">Date of Birth</label>
                                                                                                                    <div class="input-group">
                                                                                                                        <input type="date" class="form-control child-dob" name="children[${idx}][date_of_birth]" required>
                                                                                                                        <span class="input-group-text child-age" style="min-width:40px;display:none;padding:2px 5px;font-size:0.8rem;"></span>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                 <div class="col-md-2">
                                                                                                                     <label class="form-label">Church Member</label>
                                                                                                                     <select class="form-select child-church-member" name="children[${idx}][is_church_member]" onchange="toggleChildEnvelope(this, ${idx})">
                                                                                                                         <option value="no">No</option>
                                                                                                                         <option value="yes">Yes</option>
                                                                                                                     </select>
                                                                                                                 </div>
                                                                                                                <div class="col-md-2" id="childEnvelopeWrapper_${idx}" style="display:none;">
                                                                                                                    <label class="form-label">Envelope #</label>
                                                                                                                    <input type="text" class="form-control child-envelope" name="children[${idx}][envelope_number]">
                                                                                                                </div>
                                                                                                                <div class="col-md-1 text-end">
                                                                                                                    <button type="button" class="btn btn-danger btn-sm remove-child-btn" title="Remove Member"><i class="fas fa-trash"></i></button>
                                                                                                            </div>
                                                                                                                       `;
                    childrenContainer.appendChild(row);
                    childCount++;
                    renderChildren();
                    // Remove child handler
                    row.querySelector('.remove-child-btn').addEventListener('click', function () {
                        row.remove();
                        childCount--;
                        // Re-index names for children
                        Array.from(childrenContainer.querySelectorAll('.child-row')).forEach((r, i) => {
                            r.querySelector('.form-label').textContent = `Full Name`;
                            r.querySelector('.child-fullname').setAttribute('name', `children[${i}][full_name]`);
                            r.querySelector('.child-relationship').setAttribute('name', `children[${i}][relationship]`);
                            r.querySelector('.child-gender').setAttribute('name', `children[${i}][gender]`);
                            r.querySelector('.child-dob').setAttribute('name', `children[${i}][date_of_birth]`);
                        });
                    });

                    // Re-evaluate envelope visibility when DOB changes
                    row.querySelector('.child-dob').addEventListener('change', function () {
                        const churchMemberSelect = row.querySelector('.child-church-member');
                        if (churchMemberSelect) {
                            toggleChildEnvelope(churchMemberSelect, idx);
                        }
                    });
                }

                addChildBtn.addEventListener('click', function () {
                    addChild();
                });

                // If you want to start with zero children, comment out below
                // addChild();


                window.toggleChildEnvelope = function (select, idx) {
                    const wrapper = document.getElementById(`childEnvelopeWrapper_${idx}`);
                    const row = select.closest('.child-row');
                    const dobInput = row ? row.querySelector('.child-dob') : null;

                    // Envelope is required for ALL children marked as church members
                    // BUT for children 21+, it's mandatory as they will become full members
                    let isAdult = false;
                    if (dobInput && dobInput.value) {
                        const dob = new Date(dobInput.value);
                        const today = new Date();
                        let age = today.getFullYear() - dob.getFullYear();
                        const m = today.getMonth() - dob.getMonth();
                        if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
                        isAdult = age >= 21;
                    }

                    if (select.value === 'yes') {
                        wrapper.style.display = 'block';
                        // Only require envelope for children aged 21+
                        if (isAdult) {
                            wrapper.querySelector('input').setAttribute('required', 'required');
                        } else {
                            wrapper.querySelector('input').removeAttribute('required');
                        }
                    } else {
                        wrapper.style.display = 'none';
                        wrapper.querySelector('input').removeAttribute('required');
                        wrapper.querySelector('input').value = '';
                    }
                }
                    ;


                function updateVisibility() {
                    const membership = membershipTypeEl.value;
                    const type = memberTypeEl.value;

                    // Dynamic step header title
                    const stepHeaderTitle = document.getElementById('stepHeaderTitle');
                    if (step1.style.display !== 'none' || step2.style.display !== 'none') {
                        let memberLabel = '';
                        if (type === 'father') memberLabel = 'Father';
                        else if (type === 'mother') memberLabel = 'Mother';
                        else if (type === 'independent') memberLabel = 'Independent';
                        if (memberLabel) {
                            stepHeaderTitle.textContent = `Personal ${memberLabel} Information`;
                        } else {
                            stepHeaderTitle.textContent = 'Add Member';
                        }
                    } else if (step3.style.display !== 'none') {
                        stepHeaderTitle.textContent = 'Current Residence';
                    } else if (step4.style.display !== 'none') {
                        stepHeaderTitle.textContent = 'Family Information';
                    } else {
                        stepHeaderTitle.textContent = 'Add Member';
                    }

                    // Hide member type if temporary
                    if (membership === 'temporary') {
                        memberTypeWrapper.style.display = 'none';
                        memberTypeEl.value = '';
                        memberTypeEl.removeAttribute('required');
                    } else {
                        memberTypeWrapper.style.display = '';
                        memberTypeEl.setAttribute('required', 'required');
                    }

                    // Gender field visibility logic
                    const genderField = document.getElementById('gender');
                    const genderWrapper = genderField.closest('.col-md-3');

                    if (membership === 'permanent' && type === 'father') {
                        // Hide gender field for father (always male)
                        genderWrapper.style.display = 'none';
                        genderField.value = 'male'; // Auto-set to male
                        genderField.removeAttribute('required');
                    } else if (membership === 'permanent' && type === 'mother') {
                        // Hide gender field for mother (always female)
                        genderWrapper.style.display = 'none';
                        genderField.value = 'female'; // Auto-set to female
                        genderField.removeAttribute('required');
                    } else {
                        // Show gender field for independent person or temporary members
                        genderWrapper.style.display = '';
                        genderField.setAttribute('required', 'required');
                        // Don't auto-set value for independent persons
                    }

                    // Marital status section logic
                    const maritalStatusSection = document.getElementById('maritalStatusSection');
                    const spouseSectionTitle = document.getElementById('spouseSectionTitle');
                    if (membership === 'permanent' && (type === 'father' || type === 'mother')) {
                        maritalStatusSection.style.display = '';
                        if (type === 'father') {
                            spouseSectionTitle.innerHTML = '<i class="fas fa-female me-2"></i>Wife Information';
                        } else {
                            spouseSectionTitle.innerHTML = '<i class="fas fa-male me-2"></i>Husband Information';
                        }
                    } else {
                        maritalStatusSection.style.display = 'none';
                        document.getElementById('marital_status').value = '';
                        document.getElementById('spouseInfoFields').style.display = 'none';
                    }

                    // Children visible for permanent father/mother only
                    if (membership === 'permanent') {
                        if (type === 'father' || type === 'mother' || type === 'independent') {
                            childrenSection.style.display = '';
                        } else {
                            childrenSection.style.display = 'none';
                            renderChildren(0);
                        }
                    } else {
                        // Temporary: no children, show guardian
                        childrenSection.style.display = 'none';
                        renderChildren(0);
                    }

                    // Guardian for temporary members and independent persons
                    if (membership === 'temporary' || (membership === 'permanent' && type === 'independent')) {
                        guardianSection.style.display = '';
                        // Make guardian fields required
                        document.getElementById('guardian_name').setAttribute('required', 'required');
                        document.getElementById('guardian_phone').setAttribute('required', 'required');
                        document.getElementById('guardian_relationship').setAttribute('required', 'required');
                    } else {
                        guardianSection.style.display = 'none';
                        // Remove required from guardian fields
                        document.getElementById('guardian_name').removeAttribute('required');
                        document.getElementById('guardian_phone').removeAttribute('required');
                        document.getElementById('guardian_relationship').removeAttribute('required');
                    }
                }
                // Marital status change logic
                const maritalStatusSelect = document.getElementById('marital_status');
                const spouseInfoFields = document.getElementById('spouseInfoFields');
                maritalStatusSelect.addEventListener('change', function () {
                    if (this.value === 'married') {
                        spouseInfoFields.style.display = '';
                    } else {
                        spouseInfoFields.style.display = 'none';
                        // Clear spouse info fields when not married
                        document.getElementById('spouse_full_name').value = '';
                        document.getElementById('spouse_date_of_birth').value = '';
                        document.getElementById('spouse_education_level').value = '';
                        document.getElementById('spouse_profession').value = '';
                        document.getElementById('spouse_nida_number').value = '';
                        document.getElementById('spouse_email').value = '';
                        document.getElementById('spouse_phone_number').value = '';
                        document.getElementById('spouse_tribe').value = '';
                        document.getElementById('spouse_other_tribe').value = '';
                        document.getElementById('spouse_church_member').value = '';
                        document.getElementById('spouse_envelope_number').value = '';
                        document.getElementById('spouseEnvelopeWrapper').style.display = 'none';
                    }
                });

                // Spouse church member change logic
                const spouseChurchMemberSelect = document.getElementById('spouse_church_member');
                const spouseEnvelopeWrapper = document.getElementById('spouseEnvelopeWrapper');
                const spouseEnvelopeInput = document.getElementById('spouse_envelope_number');

                spouseChurchMemberSelect.addEventListener('change', function () {
                    if (this.value === 'yes') {
                        spouseEnvelopeWrapper.style.display = '';
                    } else {
                        spouseEnvelopeWrapper.style.display = 'none';
                        spouseEnvelopeInput.value = '';
                        markValid(spouseEnvelopeInput, true); // Reset validation state
                    }
                });

                membershipTypeEl.addEventListener('change', updateVisibility);
                memberTypeEl.addEventListener('change', updateVisibility);
                // childrenCountEl removed

                // Live member ID display when typing name
                const liveIdEl = document.getElementById('liveMemberId');
                const nameEl = document.querySelector('input[name="full_name"]');
                let cachedId = '';
                function refreshMemberId() {
                    if (!nameEl.value.trim()) { liveIdEl.textContent = ''; return; }
                    if (cachedId) { liveIdEl.textContent = '(' + cachedId + ')'; return; }
                    fetch('{{ route('members.next_id') }}').then(r => r.json()).then(j => { cachedId = j.next_id; liveIdEl.textContent = '(' + cachedId + ')'; });
                }
                nameEl.addEventListener('input', refreshMemberId);

                // Real-time validation
                const emailInput = document.querySelector('input[name="email"]');
                const phoneLocalInput = document.getElementById('phone_number');
                const dobInput = document.querySelector('input[name="date_of_birth"]');
                function markValid(el, valid) {
                    el.classList.remove('is-invalid');
                    el.classList.remove('is-valid');
                    el.classList.add(valid ? 'is-valid' : 'is-invalid');
                }
                function validateEmail() { const v = emailInput.value.trim(); if (!v) return true; const ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); markValid(emailInput, ok); return ok; }
                function validatePhone() { const v = phoneLocalInput.value.replace(/\s+/g, ''); const ok = /^[0-9]{9,15}$/.test(v); markValid(phoneLocalInput, ok); return ok; }
                function validateDob() { const v = dobInput.value; if (!v) return false; const d = new Date(v); const ok = d < new Date(); markValid(dobInput, ok); return ok; }
                emailInput.addEventListener('input', validateEmail);
                phoneLocalInput.addEventListener('input', validatePhone);
                dobInput.addEventListener('change', validateDob);

                function validateStep(step) {
                    if (step === 1) {
                        const membershipType = document.getElementById('membership_type').value;
                        const memberType = document.getElementById('member_type').value;
                        const req = ['membership_type', 'full_name', 'date_of_birth', 'profession', 'envelope_number'];

                        // Only require member_type for permanent members
                        if (membershipType === 'permanent') {
                            req.push('member_type');
                        }

                        // Only require gender for independent persons and temporary members
                        // (Father/Mother have gender auto-set and field hidden)
                        if (membershipType === 'temporary' || (membershipType === 'permanent' && memberType === 'independent')) {
                            req.push('gender');
                        }

                        let ok = true; req.forEach(n => { const el = document.getElementsByName(n)[0]; if (el && (el.offsetParent !== null)) { const v = el.value.trim(); const pass = !!v; markValid(el, pass); ok = ok && pass; } });
                        ok = ok && validateDob();
                        return ok;
                    }
                    if (step === 2) {
                        let ok = validatePhone() && validateEmail();
                        // Validate required address fields
                        const region = document.getElementById('region');
                        const district = document.getElementById('district');
                        const ward = document.getElementById('ward');
                        const street = document.getElementById('street');
                        const address = document.getElementById('address');
                        const tribe = document.getElementById('tribe');

                        const regionOk = !!region.value.trim();
                        const districtOk = !!district.value.trim();
                        const wardOk = !!ward.value.trim();
                        const streetOk = !!street.value.trim();
                        const addressOk = !!address.value.trim();
                        const tribeOk = !!tribe.value.trim();

                        markValid(region, regionOk);
                        markValid(district, districtOk);
                        markValid(ward, wardOk);
                        markValid(street, streetOk);
                        markValid(address, addressOk);
                        markValid(tribe, tribeOk);

                        ok = ok && regionOk && districtOk && wardOk && streetOk && addressOk && tribeOk;

                        return ok;
                    }
                    if (step === 3) {
                        // Validate residence fields
                        const residenceRegion = document.getElementById('residence_region');
                        const residenceDistrict = document.getElementById('residence_district');
                        const residenceWard = document.getElementById('residence_ward');
                        const residenceStreet = document.getElementById('residence_street');

                        const residenceRegionOk = !!residenceRegion.value.trim();
                        const residenceDistrictOk = !!residenceDistrict.value.trim();
                        const residenceWardOk = !!residenceWard.value.trim();
                        const residenceStreetOk = !!residenceStreet.value.trim();

                        markValid(residenceRegion, residenceRegionOk);
                        markValid(residenceDistrict, residenceDistrictOk);
                        markValid(residenceWard, residenceWardOk);
                        markValid(residenceStreet, residenceStreetOk);

                        return residenceRegionOk && residenceDistrictOk && residenceWardOk && residenceStreetOk;
                    }
                    if (step === 4) {
                        let ok = true;
                        const mType = document.getElementById('member_type').value;
                        const mShip = document.getElementById('membership_type').value;

                        // Marital status required for Mother/Father
                        if (mShip === 'permanent' && (mType === 'father' || mType === 'mother')) {
                            const ms = document.getElementById('marital_status');
                            const msOk = !!ms.value;
                            markValid(ms, msOk);
                            ok = ok && msOk;
                        }

                        // Validate guardian fields for temporary members
                        if (mShip === 'temporary' || (mShip === 'permanent' && mType === 'independent')) {
                            const gName = document.getElementById('guardian_name');
                            const gPhone = document.getElementById('guardian_phone');
                            const gRel = document.getElementById('guardian_relationship');
                            const gNameOk = !!gName.value.trim();
                            const gPhoneDigits = gPhone.value.replace(/\s+/g, '');
                            const gPhoneOk = /^[0-9]{9,15}$/.test(gPhoneDigits);
                            const gRelOk = !!gRel.value.trim();
                            markValid(gName, gNameOk);
                            markValid(gPhone, gPhoneOk);
                            markValid(gRel, gRelOk);
                            ok = ok && gNameOk && gPhoneOk && gRelOk;
                        }
                        // Validate spouse fields if married
                        if (document.getElementById('marital_status').value === 'married') {
                            const sName = document.getElementById('spouse_full_name');
                            const sDob = document.getElementById('spouse_date_of_birth');
                            const sChurch = document.getElementById('spouse_church_member');
                            const sEnv = document.getElementById('spouse_envelope_number');

                            const sNameOk = !!sName.value.trim();
                            const sDobOk = !!sDob.value;
                            const sChurchOk = !!sChurch.value;
                            let sEnvOk = true;
                            if (sChurch.value === 'yes') {
                                sEnvOk = !!sEnv.value.trim();
                                markValid(sEnv, sEnvOk);
                            }

                            markValid(sName, sNameOk);
                            markValid(sDob, sDobOk);
                            markValid(sChurch, sChurchOk);

                            ok = ok && sNameOk && sDobOk && sChurchOk && sEnvOk;
                        }
                        // children required when count > 0
                        const childRows = childrenContainer.querySelectorAll('.child-row');
                        if (childRows.length > 0) {
                            childRows.forEach(row => {
                                const fullName = row.querySelector('.child-fullname');
                                const gender = row.querySelector('.child-gender');
                                const dob = row.querySelector('.child-dob');
                                const passName = !!fullName.value.trim();
                                const passGender = !!gender.value;
                                const d = new Date(dob.value);
                                const passDob = !!dob.value && d < new Date();
                                markValid(fullName, passName);
                                markValid(gender, passGender);
                                markValid(dob, passDob);
                                ok = ok && passName && passGender && passDob;
                            });
                        }
                        return ok;
                    }
                    if (step === 5) {
                        // summary step, always valid
                        return true;
                    }
                    return true;
                }

                updateVisibility();

                // Add summary step before final submission
                // Create summary step element
                const summaryStep = document.createElement('div');
                summaryStep.id = 'step5';
                summaryStep.style.display = 'none';
                summaryStep.innerHTML = `
                                                                                                            <div class="card p-4 mb-4">
                                                                                                                <h5 class="mb-3 text-primary fw-bold"><i class="fas fa-eye me-2"></i>Review Information</h5>
                                                                                                                <div id="summaryContent"></div>
                                                                                                                <div class="d-flex justify-content-between mt-4">
                                                                                                                    <button type="button" class="btn btn-outline-secondary btn-lg px-4 shadow-sm prev-step" id="prevStep5"><i class="fas fa-arrow-left me-1"></i>Back</button>
                                                                                                                    <button type="submit" class="btn btn-success btn-lg px-4 shadow-sm"><i class="fas fa-save me-2"></i>Save Member</button>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        `;
                document.getElementById('addMemberForm').appendChild(summaryStep);

                // Verify step 5 was created
                console.log('Step 5 element created:', document.getElementById('step5'));

                // Summary step is now in HTML

                // Next button on step3 is now in HTML

                document.getElementById('nextStep4').addEventListener('click', function () {
                    if (!validateStep(4)) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Incomplete Information',
                            text: 'Please fill in all required fields in Step 4.',
                            confirmButtonColor: '#7a0000'
                        });
                        return;
                    }
                    console.log('Step 4 validation passed, moving to step 5 (summary)');
                    console.log('Step 5 element exists:', !!document.getElementById('step5'));
                    // Categorize summary preview by form steps
                    const summaryContent = document.getElementById('summaryContent');
                    // Personal Information (all fields)
                    const personalFields = [
                        { label: 'Membership Type', value: membershipTypeEl.value },
                        { label: 'Member Type', value: memberTypeEl.value },
                        { label: 'Full Name', value: document.getElementById('full_name').value },
                        { label: 'Gender', value: document.getElementById('gender').value },
                        { label: 'Date of Birth', value: document.getElementById('date_of_birth').value },
                        { label: 'Education Level', value: document.getElementById('education_level').value },
                        { label: 'Profession', value: document.getElementById('profession').value },
                        { label: 'NIDA Number', value: document.getElementById('nida_number').value || 'Not provided' },
                        { label: 'Profile Picture', value: document.getElementById('profile_picture').files.length > 0 ? 'Uploaded' : 'Not provided' }
                    ];
                    // Other Information (all fields)
                    const otherFields = [
                        { label: 'Phone', value: '+255' + document.getElementById('phone_number').value },
                        { label: 'Email', value: document.getElementById('email').value || 'Not provided' },
                        { label: 'Region', value: regionEl.value },
                        { label: 'District', value: districtEl.value },
                        { label: 'Ward', value: wardEl.value },
                        { label: 'Street', value: document.getElementById('street').value },
                        { label: 'Address', value: document.getElementById('address').value },
                        { label: 'Tribe', value: tribeEl.value + (tribeEl.value === 'Other' ? ' (' + document.getElementById('other_tribe').value + ')' : '') }
                    ];
                    // Current Residence Information
                    const residenceFields = [
                        { label: 'Residence Region', value: residenceRegionEl.value },
                        { label: 'Residence District', value: residenceDistrictEl.value },
                        { label: 'Residence Ward', value: document.getElementById('residence_ward').value },
                        { label: 'Residence Street', value: document.getElementById('residence_street').value },
                        { label: 'Road Name', value: document.getElementById('residence_road').value || 'Not provided' },
                        { label: 'House Number', value: document.getElementById('residence_house_number').value || 'Not provided' }
                    ];
                    // Family Information (marital status logic)
                    const familyFields = [];
                    if (membershipTypeEl.value === 'permanent' && (memberTypeEl.value === 'father' || memberTypeEl.value === 'mother')) {
                        const maritalStatus = document.getElementById('marital_status').value;
                        familyFields.push({ label: 'Marital Status', value: maritalStatus ? maritalStatus.charAt(0).toUpperCase() + maritalStatus.slice(1) : 'Not selected' });
                        if (maritalStatus === 'married') {
                            familyFields.push({ label: (memberTypeEl.value === 'father' ? 'Wife' : 'Husband') + ' Full Name', value: document.getElementById('spouse_full_name').value });
                            // Gender is automatically determined: father -> wife (Female), mother -> husband (Male)
                            const spouseGender = memberTypeEl.value === 'father' ? 'Female' : 'Male';
                            familyFields.push({ label: (memberTypeEl.value === 'father' ? 'Wife' : 'Husband') + ' Gender', value: spouseGender });
                            familyFields.push({ label: (memberTypeEl.value === 'father' ? 'Wife' : 'Husband') + ' Date of Birth', value: document.getElementById('spouse_date_of_birth').value });
                            familyFields.push({ label: (memberTypeEl.value === 'father' ? 'Wife' : 'Husband') + ' Education Level', value: document.getElementById('spouse_education_level').value });
                            familyFields.push({ label: (memberTypeEl.value === 'father' ? 'Wife' : 'Husband') + ' Profession', value: document.getElementById('spouse_profession').value });
                            const spouseTribeVal = (document.getElementById('spouse_tribe').value || '') + (document.getElementById('spouse_tribe').value === 'Other' ? ' (' + (document.getElementById('spouse_other_tribe').value || '') + ')' : '');
                            familyFields.push({ label: (memberTypeEl.value === 'father' ? 'Wife' : 'Husband') + ' Tribe', value: spouseTribeVal });
                            familyFields.push({ label: (memberTypeEl.value === 'father' ? 'Wife' : 'Husband') + ' NIDA Number', value: document.getElementById('spouse_nida_number').value });
                            familyFields.push({ label: (memberTypeEl.value === 'father' ? 'Wife' : 'Husband') + ' Email', value: document.getElementById('spouse_email').value });
                            familyFields.push({ label: (memberTypeEl.value === 'father' ? 'Wife' : 'Husband') + ' Phone', value: '+255' + document.getElementById('spouse_phone_number').value });
                            const spouseChurchMember = document.getElementById('spouse_church_member').value;
                            familyFields.push({ label: (memberTypeEl.value === 'father' ? 'Wife' : 'Husband') + ' Church Member', value: spouseChurchMember ? (spouseChurchMember === 'yes' ? 'Yes' : 'No') : 'Not specified' });
                            if (spouseChurchMember === 'yes') {
                                familyFields.push({ label: (memberTypeEl.value === 'father' ? 'Wife' : 'Husband') + ' Envelope Number', value: document.getElementById('spouse_envelope_number').value });
                            }
                        }
                    }
                    // Guardian Information (all fields)
                    if (membershipTypeEl.value === 'temporary') {
                        familyFields.push({ label: 'Guardian Name', value: document.getElementById('guardian_name').value });
                        familyFields.push({ label: 'Guardian Phone', value: '+255' + document.getElementById('guardian_phone').value });
                        familyFields.push({ label: 'Guardian Relationship', value: document.getElementById('guardian_relationship').value });
                    }
                    // Render summary by category
                    let html = '';
                    function renderFields(fields, title) {
                        html += `<h6 class='fw-bold text-primary mt-3 mb-2'>${title}</h6><div class='row'>`;
                        for (let i = 0; i < fields.length; i += 2) {
                            html += '<div class="col-md-6 mb-2">'
                                + '<div class="border rounded p-2 bg-light">'
                                + '<b>' + fields[i].label + ':</b> ' + (fields[i].value ? fields[i].value : '<span class="text-secondary">None</span>') + '</div></div>';
                            if (fields[i + 1]) {
                                html += '<div class="col-md-6 mb-2">'
                                    + '<div class="border rounded p-2 bg-light">'
                                    + '<b>' + fields[i + 1].label + ':</b> ' + (fields[i + 1].value ? fields[i + 1].value : '<span class="text-secondary">None</span>') + '</div></div>';
                            }
                        }
                        html += '</div>';
                    }
                    // Dynamic section titles based on member type
                    let personalTitle = 'Personal Information';
                    let otherTitle = 'Other Information';
                    if (memberTypeEl.value === 'mother') {
                        personalTitle = 'Mother Personal Information';
                        otherTitle = 'Mother Other Information';
                    } else if (memberTypeEl.value === 'father') {
                        personalTitle = 'Father Personal Information';
                        otherTitle = 'Father Other Information';
                    }
                    renderFields(personalFields, personalTitle);
                    renderFields(otherFields, otherTitle);
                    renderFields(residenceFields, 'Current Residence');
                    renderFields(familyFields, 'Family Information');
                    // Children
                    const childRows = childrenContainer.querySelectorAll('.child-row');
                    html += `<h6 class='fw-bold text-primary mt-3 mb-2'>Children</h6><div class="row mt-1"><div class="col-md-12"><ul>`;
                    if (childRows.length > 0) {
                        childRows.forEach((row, idx) => {
                            const name = row.querySelector('.child-fullname').value;
                            const gender = row.querySelector('.child-gender').value;
                            const dob = row.querySelector('.child-dob').value;
                            const isMember = row.querySelector('.child-church-member').value;
                            const envNum = row.querySelector('.child-envelope').value;

                            let childInfo = `Child ${idx + 1}: <b>Name:</b> ${name ? name : '<span class=\"text-secondary\">None</span>'}, <b>Gender:</b> ${gender ? gender : '<span class=\"text-secondary\">None</span>'}, <b>DOB:</b> ${dob ? dob : '<span class=\"text-secondary\">None</span>'}, <b>Member:</b> ${isMember === 'yes' ? 'Yes' : 'No'}`;
                            if (isMember === 'yes') {
                                childInfo += `, <b>Envelope:</b> ${envNum ? envNum : '<span class=\"text-secondary\">Required</span>'}`;
                            }
                            html += `<li>${childInfo}</li>`;
                        });
                    } else {
                        html += '<li class="text-secondary">No children added</li>';
                    }
                    html += '</ul></div></div>';
                    summaryContent.innerHTML = html;
                    console.log('About to show step 5');
                    console.log('Step 5 element before showStep:', document.getElementById('step5'));
                    showStep(5, 4);
                    console.log('About to set step 5 as active');
                    setStepActive(5);
                    console.log('Step 5 should now be visible and active');
                    console.log('Step 5 element after showStep:', document.getElementById('step5'));

                    // Ensure step 5 is properly visible
                    setTimeout(() => {
                        const step5 = document.getElementById('step5');
                        console.log('Step 5 after timeout:', step5);
                        console.log('Step 5 display after timeout:', step5 ? step5.style.display : 'undefined');
                        console.log('Step 5 offsetParent after timeout:', step5 ? step5.offsetParent : 'undefined');
                    }, 1000);
                });

                document.getElementById('prevStep5').addEventListener('click', function () { showStep(4, 5); setStepActive(4); });

                // Add click event handler to Save Member button for debugging
                document.addEventListener('click', function (e) {
                    if (e.target && e.target.textContent && e.target.textContent.includes('Save Member')) {
                        console.log('Save Member button clicked!');
                        console.log('Button type:', e.target.type);
                        console.log('Button form:', e.target.form);
                    }
                });

                // Simplified form submission for debugging
                document.getElementById('addMemberForm').addEventListener('submit', function (e) {
                    console.log('=== FORM SUBMISSION STARTED ===');

                    // Check if we're on the summary step (step 5)
                    const step5 = document.getElementById('step5');
                    const isStep5Visible = step5 && step5.style.display !== 'none';
                    console.log('Step 5 element:', step5);
                    console.log('Step 5 display style:', step5 ? step5.style.display : 'undefined');
                    console.log('Is step 5 visible:', isStep5Visible);

                    // Allow submission if we're on step 5, otherwise prevent it
                    if (!step5 || step5.style.display === 'none') {
                        console.log('Step 5 not visible, preventing submission');
                        Swal.fire('Error', 'Please complete all steps before submitting the form. Click "Next" on Step 4 to proceed to the summary.', 'error');
                        e.preventDefault();
                        return false;
                    }

                    console.log('Step 5 is visible, proceeding with submission');

                    // Add +255 prefix to phone number before submission
                    const v = phoneLocalInput.value.replace(/\s+/g, '');
                    console.log('Phone number processing:', v);

                    if (v && /^[0-9]{9,15}$/.test(v)) {
                        phoneLocalInput.value = '+255' + v;
                        console.log('Phone number updated to:', phoneLocalInput.value);
                    } else if (v) {
                        Swal.fire('Phone Number Error', 'Please enter a valid phone number (9-15 digits without +255)', 'error');
                        e.preventDefault();
                        return false;
                    }

                    // Also handle spouse phone number if provided
                    const spousePhoneInput = document.getElementById('spouse_phone_number');
                    if (spousePhoneInput && spousePhoneInput.value.trim()) {
                        const spouseV = spousePhoneInput.value.replace(/\s+/g, '');
                        if (spouseV && /^[0-9]{9,15}$/.test(spouseV)) {
                            spousePhoneInput.value = '+255' + spouseV;
                            console.log('Spouse phone number updated to:', spousePhoneInput.value);
                        } else if (spouseV) {
                            Swal.fire('Spouse Phone Number Error', 'Please enter a valid spouse phone number (9-15 digits without +255)', 'error');
                            e.preventDefault();
                            return false;
                        }
                    }

                    // Guardian phone when temporary or independent person
                    if (membershipTypeEl.value === 'temporary' || (membershipTypeEl.value === 'permanent' && memberTypeEl.value === 'independent')) {
                        const guardianPhoneInput = document.getElementById('guardian_phone');
                        if (guardianPhoneInput && guardianPhoneInput.value.trim()) {
                            const gv = guardianPhoneInput.value.replace(/\s+/g, '');
                            if (/^[0-9]{9,15}$/.test(gv)) {
                                guardianPhoneInput.value = '+255' + gv;
                            } else {
                                Swal.fire('Guardian Phone Error', 'Please enter a valid guardian phone (9-15 digits without +255)', 'error');
                                e.preventDefault();
                                return false;
                            }
                        }
                    }

                    // Add children count to form data
                    const childrenCount = childrenContainer.querySelectorAll('.child-row').length;
                    console.log('Children count:', childrenCount);

                    const childrenCountInput = document.createElement('input');
                    childrenCountInput.type = 'hidden';
                    childrenCountInput.name = 'children_count';
                    childrenCountInput.value = childrenCount;
                    this.appendChild(childrenCountInput);

                    console.log('Form data prepared, submitting...');

                    // Log form data for debugging
                    const formData = new FormData(this);
                    console.log('Form data entries:');
                    for (let [key, value] of formData.entries()) {
                        console.log(key, value);
                    }

                    // Show processing spinner immediately
                    Swal.fire({
                        title: 'Processing...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    console.log('=== ALLOWING FORM SUBMISSION ===');
                    console.log('Form action:', this.action);
                    console.log('Form method:', this.method);
                });

                // realtime validation for text/select inputs in steps 1 & 2
                const fullNameEl = document.getElementsByName('full_name')[0];
                fullNameEl.addEventListener('input', () => markValid(fullNameEl, fullNameEl.value.trim().length >= 2));
                document.getElementsByName('gender')[0].addEventListener('change', (e) => markValid(e.target, !!e.target.value));
                document.getElementsByName('membership_type')[0].addEventListener('change', (e) => markValid(e.target, !!e.target.value));
                document.getElementsByName('member_type')[0].addEventListener('change', (e) => markValid(e.target, !!e.target.value || document.getElementsByName('membership_type')[0].value === 'temporary'));
                document.getElementsByName('education_level')[0].addEventListener('change', (e) => markValid(e.target, !!e.target.value));
                const professionEl = document.getElementsByName('profession')[0];
                professionEl.addEventListener('input', () => markValid(professionEl, professionEl.value.trim().length >= 2));
                // Real-time validation for mother's profession field
                const motherProfessionEl = document.getElementsByName('mother_profession')[0];
                if (motherProfessionEl) {
                    motherProfessionEl.addEventListener('input', () => markValid(motherProfessionEl, motherProfessionEl.value.trim().length >= 2));
                }
                document.getElementsByName('region')[0].addEventListener('change', (e) => markValid(e.target, !!e.target.value));
                document.getElementsByName('district')[0].addEventListener('change', (e) => markValid(e.target, !!e.target.value));
                document.getElementsByName('ward')[0].addEventListener('change', (e) => markValid(e.target, !!e.target.value));
                document.getElementsByName('street')[0].addEventListener('input', (e) => markValid(e.target, e.target.value.trim().length >= 2));
                document.getElementsByName('address')[0].addEventListener('input', (e) => markValid(e.target, e.target.value.trim().length >= 5));
                document.getElementsByName('tribe')[0].addEventListener('change', (e) => markValid(e.target, !!e.target.value));

                // Initialize Select2 on static selects too
                $('.select2').select2({ width: '100%' });

                // Image preview functionality - initialize immediately
                setTimeout(function () {
                    initializeImagePreview();
                }, 200);
            }, 100); // Small delay to ensure jQuery is loaded

            // Image preview functionality
            function initializeImagePreview() {
                const profilePictureInput = document.getElementById('profile_picture');
                const imagePreview = document.getElementById('imagePreview');
                const previewImg = document.getElementById('previewImg');
                const noImagePlaceholder = document.getElementById('noImagePlaceholder');
                const removeImageBtn = document.getElementById('removeImage');

                if (!profilePictureInput || !imagePreview || !previewImg || !noImagePlaceholder || !removeImageBtn) {
                    console.error('Image preview elements not found');
                    console.log('profilePictureInput:', profilePictureInput);
                    console.log('imagePreview:', imagePreview);
                    console.log('previewImg:', previewImg);
                    console.log('noImagePlaceholder:', noImagePlaceholder);
                    console.log('removeImageBtn:', removeImageBtn);
                    return;
                }

                console.log('Image preview initialized successfully');

                profilePictureInput.addEventListener('change', function (e) {
                    console.log('File input changed');
                    const file = e.target.files[0];
                    console.log('Selected file:', file);

                    if (file) {
                        // Validate file type
                        if (!file.type.startsWith('image/')) {
                            Swal.fire('Invalid File', 'Please select an image file.', 'error');
                            this.value = '';
                            return;
                        }

                        // Validate file size (2MB max)
                        if (file.size > 2 * 1024 * 1024) {
                            Swal.fire('File Too Large', 'Please select an image smaller than 2MB.', 'error');
                            this.value = '';
                            return;
                        }

                        // Show preview
                        console.log('Showing image preview');
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            console.log('FileReader loaded, setting image src');
                            previewImg.src = e.target.result;
                            imagePreview.style.display = 'block';
                            noImagePlaceholder.style.display = 'none';
                            console.log('Image preview should now be visible');
                        };
                        reader.readAsDataURL(file);
                    } else {
                        hideImagePreview();
                    }
                });

                removeImageBtn.addEventListener('click', function () {
                    profilePictureInput.value = '';
                    hideImagePreview();
                });

                function hideImagePreview() {
                    imagePreview.style.display = 'none';
                    noImagePlaceholder.style.display = 'block';
                    previewImg.src = '';
                }
            }
        });
    </script>
@endsection

@section('scripts')
    <!-- jQuery must be loaded before Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Global function for image preview - works immediately
        function handleImagePreview(input) {
            console.log('handleImagePreview called');
            const file = input.files[0];
            const imagePreview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            const noImagePlaceholder = document.getElementById('noImagePlaceholder');

            console.log('File selected:', file);
            console.log('Elements found:', {
                imagePreview: !!imagePreview,
                previewImg: !!previewImg,
                noImagePlaceholder: !!noImagePlaceholder
            });

            if (file) {
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    Swal.fire('Invalid File', 'Please select an image file.', 'error');
                    input.value = '';
                    return;
                }

                // Validate file size (2MB max)
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire('File Too Large', 'Please select an image smaller than 2MB.', 'error');
                    input.value = '';
                    return;
                }

                // Show preview
                console.log('Showing image preview');
                const reader = new FileReader();
                reader.onload = function (e) {
                    console.log('FileReader loaded, setting image src');
                    if (previewImg) previewImg.src = e.target.result;
                    if (imagePreview) imagePreview.style.display = 'block';
                    if (noImagePlaceholder) noImagePlaceholder.style.display = 'none';
                    console.log('Image preview should now be visible');
                };
                reader.readAsDataURL(file);
            } else {
                // Hide preview
                if (imagePreview) imagePreview.style.display = 'none';
                if (noImagePlaceholder) noImagePlaceholder.style.display = 'block';
                if (previewImg) previewImg.src = '';
            }
        }

        // Global function to remove image preview
        function removeImagePreview() {
            console.log('removeImagePreview called');
            const profilePictureInput = document.getElementById('profile_picture');
            const imagePreview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            const noImagePlaceholder = document.getElementById('noImagePlaceholder');

            if (profilePictureInput) profilePictureInput.value = '';
            if (imagePreview) imagePreview.style.display = 'none';
            if (noImagePlaceholder) noImagePlaceholder.style.display = 'block';
            if (previewImg) previewImg.src = '';
        }

        // Global function for spouse image preview
        function handleSpouseImagePreview(input) {
            console.log('handleSpouseImagePreview called');
            const file = input.files[0];
            const imagePreview = document.getElementById('spouseImagePreview');
            const previewImg = document.getElementById('spousePreviewImg');
            const noImagePlaceholder = document.getElementById('spouseNoImagePlaceholder');

            console.log('File selected:', file);
            console.log('Elements found:', {
                imagePreview: !!imagePreview,
                previewImg: !!previewImg,
                noImagePlaceholder: !!noImagePlaceholder
            });

            if (file) {
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    Swal.fire('Invalid File', 'Please select an image file.', 'error');
                    input.value = '';
                    return;
                }

                // Validate file size (2MB max)
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire('File Too Large', 'Please select an image smaller than 2MB.', 'error');
                    input.value = '';
                    return;
                }

                // Show preview
                console.log('Showing spouse image preview');
                const reader = new FileReader();
                reader.onload = function (e) {
                    console.log('FileReader loaded, setting spouse image src');
                    if (previewImg) previewImg.src = e.target.result;
                    if (imagePreview) imagePreview.style.display = 'block';
                    if (noImagePlaceholder) noImagePlaceholder.style.display = 'none';
                    console.log('Spouse image preview should now be visible');
                };
                reader.readAsDataURL(file);
            } else {
                // Hide preview
                if (imagePreview) imagePreview.style.display = 'none';
                if (noImagePlaceholder) noImagePlaceholder.style.display = 'block';
                if (previewImg) previewImg.src = '';
            }
        }

        // Global function to remove spouse image preview
        function removeSpouseImagePreview() {
            console.log('removeSpouseImagePreview called');
            const spouseProfilePictureInput = document.getElementById('spouse_profile_picture');
            const imagePreview = document.getElementById('spouseImagePreview');
            const previewImg = document.getElementById('spousePreviewImg');
            const noImagePlaceholder = document.getElementById('spouseNoImagePlaceholder');

            if (spouseProfilePictureInput) spouseProfilePictureInput.value = '';
            if (imagePreview) imagePreview.style.display = 'none';
            if (noImagePlaceholder) noImagePlaceholder.style.display = 'block';
            if (previewImg) previewImg.src = '';
        }
    </script>
    <script>
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>

    <script>
        // Load notifications function
        function loadNotifications() {
            fetch('{{ route("notifications.data") }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.success) {
                        window.currentNotificationData = {
                            events: data.events || [],
                            celebrations: data.celebrations || [],
                            services: data.services || [],
                            counts: data.counts || { events: 0, celebrations: 0, services: 0, total: 0 }
                        };

                        const eventsCountEl = document.getElementById('eventsCount');
                        const celebrationsCountEl = document.getElementById('celebrationsCount');
                        const servicesCountEl = document.getElementById('servicesCount');

                        if (eventsCountEl) eventsCountEl.textContent = (data.counts && data.counts.events) || 0;
                        if (celebrationsCountEl) celebrationsCountEl.textContent = (data.counts && data.counts.celebrations) || 0;
                        if (servicesCountEl) servicesCountEl.textContent = (data.counts && data.counts.services) || 0;

                        const totalCount = (data.counts && data.counts.total) || 0;
                        const badge = document.getElementById('notificationBadge');
                        if (badge) {
                            badge.textContent = totalCount;
                            badge.style.display = totalCount > 0 ? 'inline' : 'none';
                        }

                        const eventsList = document.getElementById('eventsList');
                        if (eventsList) {
                            eventsList.innerHTML = generateEventList(data.events || []);
                        }

                        const celebrationsList = document.getElementById('celebrationsList');
                        if (celebrationsList) {
                            celebrationsList.innerHTML = generateCelebrationList(data.celebrations || []);
                        }

                        const servicesList = document.getElementById('servicesList');
                        if (servicesList) {
                            servicesList.innerHTML = generateServiceList(data.services || []);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    const badge = document.getElementById('notificationBadge');
                    if (badge) {
                        badge.textContent = '0';
                        badge.style.display = 'none';
                    }
                });
        }

        function generateEventList(events) {
            if (!events || events.length === 0) {
                return '<div class="empty-notification-state"><i class="fas fa-calendar-times"></i><span>No upcoming events</span></div>';
            }
            return events.map((event, index) => {
                const eventDate = new Date(event.date).toLocaleDateString('en-US', {
                    weekday: 'short',
                    month: 'short',
                    day: 'numeric'
                });
                const timeText = event.hours_remaining !== null ?
                    `${event.hours_remaining}h left` :
                    `${event.days_remaining}d left`;
                const formatTime = (timeStr) => {
                    if (!timeStr || timeStr === 'TBD') return 'TBD';
                    try {
                        if (timeStr.includes('T')) {
                            const time = new Date(timeStr);
                            return time.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        }
                        if (timeStr.includes(':')) {
                            const [hours, minutes] = timeStr.split(':');
                            const time = new Date();
                            time.setHours(parseInt(hours), parseInt(minutes), 0);
                            return time.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        }
                        return timeStr;
                    } catch (e) {
                        return 'TBD';
                    }
                };
                return `
                                                                                                <div class="notification-item" style="animation-delay: ${index * 0.1}s;" onclick="showEventDetails(${event.id}, 'event')">
                                                                                                    <div class="notification-item-content">
                                                                                                        <div class="notification-icon bg-primary"><i class="fas fa-calendar-alt"></i></div>
                                                                                                        <div class="notification-details">
                                                                                                            <div class="notification-title">${event.title}</div>
                                                                                                            <div class="notification-meta">
                                                                                                                <span class="meta-item"><i class="fas fa-calendar"></i>${eventDate}</span>
                                                                                                                <span class="meta-item"><i class="fas fa-clock"></i>${formatTime(event.time)}</span>
                                                                                                            </div>
                                                                                                            <div class="notification-info">
                                                                                                                <span class="info-item"><i class="fas fa-map-marker-alt"></i>${event.venue}</span>
                                                                                                                ${event.speaker ? `<span class="info-item"><i class="fas fa-user"></i>${event.speaker}</span>` : ''}
                                                                                                            </div>
                                                                                                            <div class="notification-badge"><span class="time-badge bg-primary">${timeText}</span></div>
                                                                                                        </div>
                                                                                                        <div class="notification-arrow"><i class="fas fa-chevron-right"></i></div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            `;
            }).join('');
        }

        function generateCelebrationList(celebrations) {
            if (!celebrations || celebrations.length === 0) {
                return '<div class="empty-notification-state"><i class="fas fa-birthday-cake"></i><span>No upcoming celebrations</span></div>';
            }
            return celebrations.map((celebration, index) => {
                const celebrationDate = new Date(celebration.date).toLocaleDateString('en-US', {
                    weekday: 'short',
                    month: 'short',
                    day: 'numeric'
                });
                const timeText = celebration.hours_remaining !== null ?
                    `${celebration.hours_remaining}h left` :
                    `${celebration.days_remaining}d left`;
                const formatTime = (timeStr) => {
                    if (!timeStr || timeStr === 'TBD') return 'TBD';
                    try {
                        if (timeStr.includes('T')) {
                            const time = new Date(timeStr);
                            return time.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        }
                        if (timeStr.includes(':')) {
                            const [hours, minutes] = timeStr.split(':');
                            const time = new Date();
                            time.setHours(parseInt(hours), parseInt(minutes), 0);
                            return time.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        }
                        return timeStr;
                    } catch (e) {
                        return 'TBD';
                    }
                };
                return `
                                                                                                <div class="notification-item" style="animation-delay: ${index * 0.1}s;" onclick="showEventDetails(${celebration.id}, 'celebration')">
                                                                                                    <div class="notification-item-content">
                                                                                                        <div class="notification-icon bg-warning"><i class="fas fa-birthday-cake"></i></div>
                                                                                                        <div class="notification-details">
                                                                                                            <div class="notification-title">${celebration.title}</div>
                                                                                                            <div class="notification-meta">
                                                                                                                <span class="meta-item"><i class="fas fa-user"></i>${celebration.celebrant}</span>
                                                                                                                <span class="meta-item"><i class="fas fa-calendar"></i>${celebrationDate}</span>
                                                                                                            </div>
                                                                                                            <div class="notification-info">
                                                                                                                <span class="info-item"><i class="fas fa-clock"></i>${formatTime(celebration.time)}</span>
                                                                                                                <span class="info-item"><i class="fas fa-map-marker-alt"></i>${celebration.venue}</span>
                                                                                                            </div>
                                                                                                            <div class="notification-badge"><span class="time-badge bg-warning">${timeText}</span></div>
                                                                                                        </div>
                                                                                                        <div class="notification-arrow"><i class="fas fa-chevron-right"></i></div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            `;
            }).join('');
        }

        function generateServiceList(services) {
            if (!services || services.length === 0) {
                return '<div class="empty-notification-state"><i class="fas fa-church"></i><span>No upcoming services</span></div>';
            }
            return services.map((service, index) => {
                const serviceDate = new Date(service.date).toLocaleDateString('en-US', {
                    weekday: 'short',
                    month: 'short',
                    day: 'numeric'
                });
                const timeText = service.hours_remaining !== null ?
                    `${service.hours_remaining}h left` :
                    `${service.days_remaining}d left`;
                const formatTime = (timeStr) => {
                    if (!timeStr || timeStr === 'TBD') return 'TBD';
                    try {
                        if (timeStr.includes('T')) {
                            const time = new Date(timeStr);
                            return time.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        }
                        if (timeStr.includes(':')) {
                            const [hours, minutes] = timeStr.split(':');
                            const time = new Date();
                            time.setHours(parseInt(hours), parseInt(minutes), 0);
                            return time.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        }
                        return timeStr;
                    } catch (e) {
                        return 'TBD';
                    }
                };
                return `
                                                                                                <div class="notification-item" style="animation-delay: ${index * 0.1}s;" onclick="showEventDetails(${service.id}, 'service')">
                                                                                                    <div class="notification-item-content">
                                                                                                        <div class="notification-icon bg-success"><i class="fas fa-church"></i></div>
                                                                                                        <div class="notification-details">
                                                                                                            <div class="notification-title">${service.title}</div>
                                                                                                            <div class="notification-meta">
                                                                                                                <span class="meta-item"><i class="fas fa-calendar"></i>${serviceDate}</span>
                                                                                                                <span class="meta-item"><i class="fas fa-clock"></i>${formatTime(service.time)}</span>
                                                                                                            </div>
                                                                                                            <div class="notification-info">
                                                                                                                <span class="info-item"><i class="fas fa-map-marker-alt"></i>${service.venue}</span>
                                                                                                                ${service.speaker ? `<span class="info-item"><i class="fas fa-user"></i>${service.speaker}</span>` : ''}
                                                                                                            </div>
                                                                                                            ${service.theme ? `<div class="notification-theme"><i class="fas fa-quote-left"></i>${service.theme}</div>` : ''}
                                                                                                            <div class="notification-badge"><span class="time-badge bg-success">${timeText}</span></div>
                                                                                                        </div>
                                                                                                        <div class="notification-arrow"><i class="fas fa-chevron-right"></i></div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            `;
            }).join('');
        }

        function showEventDetails(id, type) {
            let modal = document.getElementById('eventDetailsModal');
            if (!modal) {
                modal = document.createElement('div');
                modal.id = 'eventDetailsModal';
                modal.className = 'modal fade';
                modal.setAttribute('tabindex', '-1');
                modal.setAttribute('aria-labelledby', 'eventDetailsTitle');
                modal.setAttribute('aria-hidden', 'true');
                document.body.appendChild(modal);
                modal.innerHTML = `
                                                                                                <div class="modal-dialog modal-lg">
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header bg-light">
                                                                                                            <h5 class="modal-title" id="eventDetailsTitle">Event Details</h5>
                                                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                                        </div>
                                                                                                        <div class="modal-body p-4" id="eventDetailsBody">
                                                                                                            <div class="text-center">
                                                                                                                <div class="spinner-border" role="status">
                                                                                                                    <span class="visually-hidden">Loading...</span>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="modal-footer bg-light">
                                                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                                                                <i class="fas fa-times me-2"></i>Close
                                                                                                            </button>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            `;
                document.body.appendChild(modal);
            }
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
            loadEventDetails(id, type);
        }

        function loadEventDetails(id, type) {
            const modalBody = document.getElementById('eventDetailsBody');
            const modalTitle = document.getElementById('eventDetailsTitle');
            const titles = {
                'event': 'Event Details',
                'celebration': 'Celebration Details',
                'service': 'Service Details'
            };
            modalTitle.textContent = titles[type] || 'Details';
            modalBody.innerHTML = `
                                                                                            <div class="text-center py-4">
                                                                                                <div class="spinner-border text-primary" role="status">
                                                                                                    <span class="visually-hidden">Loading...</span>
                                                                                                </div>
                                                                                                <p class="mt-2 text-muted">Loading details...</p>
                                                                                            </div>
                                                                                        `;
            setTimeout(() => {
                let eventData = null;
                if (window.currentNotificationData) {
                    if (type === 'event' && window.currentNotificationData.events) {
                        eventData = window.currentNotificationData.events.find(e => e.id === id);
                    } else if (type === 'celebration' && window.currentNotificationData.celebrations) {
                        eventData = window.currentNotificationData.celebrations.find(c => c.id === id);
                    } else if (type === 'service' && window.currentNotificationData.services) {
                        eventData = window.currentNotificationData.services.find(s => s.id === id);
                    }
                }
                const formatTime = (timeStr) => {
                    if (!timeStr || timeStr === 'TBD') return 'TBD';
                    try {
                        if (timeStr.includes('T') || /\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}/.test(timeStr)) {
                            const time = new Date(timeStr);
                            return time.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        }
                        if (/^\d{2}:\d{2}/.test(timeStr)) {
                            const [hours, minutes] = timeStr.split(':');
                            const d = new Date();
                            d.setHours(parseInt(hours), parseInt(minutes), 0, 0);
                            return d.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        }
                        return timeStr;
                    } catch (e) {
                        return 'TBD';
                    }
                };
                if (eventData) {
                    const eventDate = new Date(eventData.date).toLocaleDateString('en-US', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                    let timeDisplay = 'TBD';
                    if (type === 'service') {
                        const start = eventData.start_time || eventData.time;
                        const end = eventData.end_time;
                        if (start && end) {
                            timeDisplay = `${formatTime(start)} - ${formatTime(end)}`;
                        } else if (start) {
                            timeDisplay = formatTime(start);
                        } else if (eventData.time) {
                            timeDisplay = formatTime(eventData.time);
                        }
                    } else {
                        timeDisplay = eventData.time ? formatTime(eventData.time) : 'TBD';
                    }
                    modalBody.innerHTML = `
                                                                                                    <div class="text-center mb-4">
                                                                                                        <div class="bg-${type === 'event' ? 'primary' : type === 'celebration' ? 'warning' : 'success'} text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px;">
                                                                                                            <i class="fas fa-${type === 'event' ? 'calendar-alt' : type === 'celebration' ? 'birthday-cake' : 'church'} fa-3x"></i>
                                                                                                        </div>
                                                                                                        <h3 class="text-dark mb-2">${eventData.title}</h3>
                                                                                                        <p class="text-muted">${type.charAt(0).toUpperCase() + type.slice(1)} Information</p>
                                                                                                    </div>
                                                                                                    <div class="row g-3">
                                                                                                        <div class="col-md-6">
                                                                                                            <div class="card h-100 border-0 shadow-sm">
                                                                                                                <div class="card-body text-center">
                                                                                                                    <i class="fas fa-calendar text-primary fa-2x mb-3"></i>
                                                                                                                    <h6 class="card-title">Date</h6>
                                                                                                                    <p class="card-text text-muted">${eventDate}</p>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-md-6">
                                                                                                            <div class="card h-100 border-0 shadow-sm">
                                                                                                                <div class="card-body text-center">
                                                                                                                    <i class="fas fa-clock text-success fa-2x mb-3"></i>
                                                                                                                    <h6 class="card-title">Time</h6>
                                                                                                                    <p class="card-text text-muted">${timeDisplay}</p>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-md-6">
                                                                                                            <div class="card h-100 border-0 shadow-sm">
                                                                                                                <div class="card-body text-center">
                                                                                                                    <i class="fas fa-map-marker-alt text-danger fa-2x mb-3"></i>
                                                                                                                    <h6 class="card-title">Venue</h6>
                                                                                                                    <p class="card-text text-muted">${eventData.venue}</p>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-md-6">
                                                                                                            <div class="card h-100 border-0 shadow-sm">
                                                                                                                <div class="card-body text-center">
                                                                                                                    <i class="fas fa-user text-info fa-2x mb-3"></i>
                                                                                                                    <h6 class="card-title">${type === 'celebration' ? 'Celebrant' : (type === 'service' ? 'Preacher' : 'Speaker')}</h6>
                                                                                                                    <p class="card-text text-muted">${eventData.speaker || eventData.celebrant || 'TBD'}</p>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        ${eventData.theme ? `
                                                                                                        <div class="col-12">
                                                                                                            <div class="card border-0 shadow-sm">
                                                                                                                <div class="card-body text-center">
                                                                                                                    <i class="fas fa-quote-left text-warning fa-2x mb-3"></i>
                                                                                                                    <h6 class="card-title">Theme</h6>
                                                                                                                    <p class="card-text text-muted">${eventData.theme}</p>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        ` : ''}
                                                                                                        <div class="col-12">
                                                                                                            <div class="alert alert-${type === 'event' ? 'primary' : type === 'celebration' ? 'warning' : 'success'} border-0">
                                                                                                                <div class="d-flex align-items-center">
                                                                                                                    <i class="fas fa-info-circle fa-2x me-3"></i>
                                                                                                                    <div>
                                                                                                                        <h6 class="mb-1">Time Remaining</h6>
                                                                                                                        <p class="mb-0">
                                                                                                                            ${eventData.hours_remaining !== null ?
                            `${eventData.hours_remaining} hours left` :
                            `${eventData.days_remaining} days left`}
                                                                                                                        </p>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                `;
                } else {
                    modalBody.innerHTML = `
                                                                                                    <div class="text-center py-4 text-muted">Details not found.</div>
                                                                                                `;
                }
            }, 50);
        }

        document.addEventListener('DOMContentLoaded', function () {
            loadNotifications();
            setInterval(loadNotifications, 300000);

            // Handle mobile dropdown positioning
            const notificationDropdown = document.getElementById('notificationDropdown');
            if (notificationDropdown) {
                const dropdownMenu = notificationDropdown.querySelector('.notification-dropdown');
                if (dropdownMenu) {
                    // Function to apply mobile positioning
                    function applyMobilePositioning() {
                        if (window.innerWidth <= 576) {
                            dropdownMenu.style.setProperty('position', 'fixed', 'important');
                            dropdownMenu.style.setProperty('top', '60px', 'important');
                            dropdownMenu.style.setProperty('left', '0.25rem', 'important');
                            dropdownMenu.style.setProperty('right', '0.25rem', 'important');
                            dropdownMenu.style.setProperty('width', 'calc(100vw - 0.5rem)', 'important');
                            dropdownMenu.style.setProperty('max-width', 'calc(100vw - 0.5rem)', 'important');
                            dropdownMenu.style.setProperty('margin', '0', 'important');
                            dropdownMenu.style.setProperty('transform', 'none', 'important');
                            dropdownMenu.style.setProperty('z-index', '1055', 'important');
                            dropdownMenu.style.setProperty('inset', '60px 0.25rem auto 0.25rem', 'important');
                        } else if (window.innerWidth <= 768) {
                            dropdownMenu.style.setProperty('position', 'fixed', 'important');
                            dropdownMenu.style.setProperty('top', '60px', 'important');
                            dropdownMenu.style.setProperty('left', '0.5rem', 'important');
                            dropdownMenu.style.setProperty('right', '0.5rem', 'important');
                            dropdownMenu.style.setProperty('width', 'calc(100vw - 1rem)', 'important');
                            dropdownMenu.style.setProperty('max-width', 'calc(100vw - 1rem)', 'important');
                            dropdownMenu.style.setProperty('margin', '0', 'important');
                            dropdownMenu.style.setProperty('transform', 'none', 'important');
                            dropdownMenu.style.setProperty('z-index', '1055', 'important');
                            dropdownMenu.style.setProperty('inset', '60px 0.5rem auto 0.5rem', 'important');
                        } else {
                            // Reset to default for desktop
                            dropdownMenu.style.removeProperty('position');
                            dropdownMenu.style.removeProperty('top');
                            dropdownMenu.style.removeProperty('left');
                            dropdownMenu.style.removeProperty('right');
                            dropdownMenu.style.removeProperty('width');
                            dropdownMenu.style.removeProperty('max-width');
                            dropdownMenu.style.removeProperty('margin');
                            dropdownMenu.style.removeProperty('transform');
                            dropdownMenu.style.removeProperty('inset');
                        }
                    }

                    // Apply on load
                    applyMobilePositioning();

                    // Apply on resize
                    window.addEventListener('resize', applyMobilePositioning);

                    // Apply when dropdown is shown
                    notificationDropdown.addEventListener('shown.bs.dropdown', applyMobilePositioning);
                }
            }
        });

        function updateDateTime() {
            const now = new Date();
            const dateElement = document.getElementById('currentDate');
            const timeElement = document.getElementById('currentTime');

            if (dateElement) {
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                dateElement.textContent = now.toLocaleDateString('en-US', options);
            }

            if (timeElement) {
                const hours = now.getHours().toString().padStart(2, '0');
                const minutes = now.getMinutes().toString().padStart(2, '0');
                const seconds = now.getSeconds().toString().padStart(2, '0');
                timeElement.textContent = `${hours}:${minutes}:${seconds}`;
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            updateDateTime();
            setInterval(updateDateTime, 1000);
        });

        document.addEventListener('DOMContentLoaded', function () {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const layoutSidenav = document.getElementById('layoutSidenav');

            if (sidebarToggle) {
                sidebarToggle.onclick = null;
                sidebarToggle.removeAttribute('onclick');

                if (!sidebarToggle.hasAttribute('data-layout-toggle-handler')) {
                    sidebarToggle.setAttribute('data-layout-toggle-handler', 'true');

                    sidebarToggle.addEventListener('click', function (e) {
                        e.preventDefault();
                        e.stopPropagation();

                        if (layoutSidenav) {
                            layoutSidenav.classList.toggle('sb-sidenav-toggled');
                        }
                        document.body.classList.toggle('sb-sidenav-toggled');

                        const isToggled = layoutSidenav ? layoutSidenav.classList.contains('sb-sidenav-toggled') : document.body.classList.contains('sb-sidenav-toggled');
                        localStorage.setItem('sb-sidebar-toggle', isToggled ? 'true' : 'false');

                        return false;
                    }, true);

                    const savedState = localStorage.getItem('sb-sidebar-toggle');
                    if (savedState === 'true') {
                        if (layoutSidenav) {
                            layoutSidenav.classList.add('sb-sidenav-toggled');
                        }
                        document.body.classList.add('sb-sidenav-toggled');
                    }
                }
            }

            // On mobile, close sidebar when navigation links are clicked
            // On mobile, sb-sidenav-toggled means OPEN, so we REMOVE it to close
            function closeSidebarOnMobile() {
                if (window.innerWidth <= 768) {
                    if (layoutSidenav) {
                        layoutSidenav.classList.remove('sb-sidenav-toggled');
                    }
                    document.body.classList.remove('sb-sidenav-toggled');
                    localStorage.setItem('sb-sidebar-toggle', 'false');
                }
            }

            // Close sidebar when navigation links are clicked on mobile
            var sidebarLinks = document.querySelectorAll('#sidenavAccordion .nav-link[href]');
            for (var i = 0; i < sidebarLinks.length; i++) {
                var link = sidebarLinks[i];
                var href = link.getAttribute('href');

                // Skip collapse toggles and empty links
                if (href === '#' || link.hasAttribute('data-bs-toggle')) {
                    continue;
                }

                (function (currentLink) {
                    currentLink.addEventListener('click', function (e) {
                        if (window.innerWidth <= 768) {
                            // Close sidebar immediately but allow link to work
                            closeSidebarOnMobile();

                            // Close multiple times to ensure it stays closed
                            setTimeout(closeSidebarOnMobile, 10);
                            setTimeout(closeSidebarOnMobile, 50);
                            setTimeout(closeSidebarOnMobile, 100);
                            setTimeout(closeSidebarOnMobile, 200);

                            // Don't prevent default - allow navigation to happen
                        }
                    }, false); // Use bubble phase so link navigation works
                })(link);
            }
        });

        window.showEventDetails = showEventDetails;
        window.loadNotifications = loadNotifications;
    </script>
@endsection