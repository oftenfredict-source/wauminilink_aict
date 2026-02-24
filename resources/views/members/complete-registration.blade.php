@extends('layouts.index')

@section('styles')
    {{-- Reuse the same styles from add-members --}}
    <style>
        .card-header.bg-gradient-primary {
            background: linear-gradient(45deg, #940000, #ff4d4d) !important;
        }

        .bg-gradient-primary {
            background: linear-gradient(90deg, #7a0000 0%, #940000 100%) !important;
        }

        .btn-primary {
            background-color: #940000 !important;
            border-color: #940000 !important;
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
        }

        .wizard-step .step-label {
            color: #7a0000;
            font-weight: 500;
        }

        .wizard-step.active .step-label {
            color: #940000;
            font-weight: 700;
        }

        .wizard-step.completed .step-label {
            color: #198754;
            font-weight: 700;
        }

        .next-step {
            background: linear-gradient(90deg, #7a0000 0%, #940000 100%) !important;
            border: none !important;
            color: #fff !important;
        }

        .animated.fadeIn {
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        .prefilled-note {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 10px 14px;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        /* Locked / pre-filled fields look slightly different */
        .field-locked {
            background-color: #f8f9fa !important;
            cursor: not-allowed;
        }
    </style>
@endsection

@section('content')
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Registration Completed!',
                    html: `<div style='font-size:1.1em'>{{ addslashes(session('success')) }}</div>`,
                    confirmButtonColor: '#940000',
                });
            });
        </script>
    @endif

    <div class="container-fluid px-2 px-md-5 py-4 animated fadeIn">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden main-form-card">

            {{-- Header --}}
            <div
                class="card-header bg-gradient-primary d-flex flex-column flex-md-row justify-content-between align-items-center py-4 px-4 border-0">
                <span class="fs-5 fw-bold text-white d-flex align-items-center">
                    <i class="fas fa-clipboard-check me-2"></i>
                    Complete Registration &mdash;
                    <span class="ms-2 text-warning">{{ $member->full_name }}</span>
                    <small class="ms-3 opacity-75">#{{ $member->member_id }}</small>
                </span>
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('members.view', ['tab' => 'permanent']) }}"
                        class="btn btn-outline-light btn-sm shadow-sm">
                        <i class="fas fa-list me-1"></i> All Members
                    </a>
                </div>
            </div>

            <div class="card-body bg-light px-4 py-4">

                <div class="prefilled-note">
                    <i class="fas fa-info-circle me-2 text-warning"></i>
                    <strong>Pre-filled information</strong> from parent registration is locked.
                    Please complete <strong>Profession</strong>, <strong>Phone Number</strong>,
                    <strong>Residence</strong>, and set the correct <strong>Member Type</strong>.
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger rounded-3 shadow-sm">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="completeRegForm" method="POST" action="{{ route('members.update', $member->id) }}"
                    enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="_complete_registration" value="1">

                    {{-- ── Wizard Steps ── --}}
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

                    {{-- ════════════════════════════════════════════════════════
                    STEP 1 – Personal Information
                    ════════════════════════════════════════════════════════ --}}
                    <div id="step1">
                        <div class="row g-4 mb-3">
                            {{-- Membership Type --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="membership_type" class="form-label small fw-bold text-muted ms-1">Membership
                                        Type</label>
                                    <select name="membership_type" id="membership_type" class="form-select select2"
                                        required>
                                        <option value="permanent" {{ $member->membership_type === 'permanent' ? 'selected' : '' }}>Permanent</option>
                                        <option value="temporary" {{ $member->membership_type === 'temporary' ? 'selected' : '' }}>Temporary</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Member Type --}}
                            <div class="col-md-4" id="memberTypeWrapper">
                                <div class="form-group">
                                    <label for="member_type" class="form-label small fw-bold text-muted ms-1">Member
                                        Type</label>
                                    <select name="member_type" id="member_type" class="form-select select2" required>
                                        <option value=""></option>
                                        <option value="independent" {{ ($member->member_type ?? 'independent') === 'independent' ? 'selected' : '' }}>Independent Person</option>
                                        <option value="father" {{ $member->member_type === 'father' ? 'selected' : '' }}>
                                            Father</option>
                                        <option value="mother" {{ $member->member_type === 'mother' ? 'selected' : '' }}>
                                            Mother</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Envelope Number (locked) --}}
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control field-locked" name="envelope_number"
                                        id="envelope_number" value="{{ old('envelope_number', $member->envelope_number) }}"
                                        readonly>
                                    <label for="envelope_number">Envelope Number</label>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            {{-- Full Name (locked) --}}
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control field-locked" name="full_name" id="full_name"
                                        value="{{ old('full_name', $member->full_name) }}" required readonly>
                                    <label for="full_name">Full Name</label>
                                </div>
                            </div>

                            {{-- Gender (locked) --}}
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <select class="form-select field-locked" id="gender_display" disabled>
                                        <option value="male" {{ $member->gender === 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ $member->gender === 'female' ? 'selected' : '' }}>Female
                                        </option>
                                    </select>
                                    <label for="gender_display">Gender</label>
                                </div>
                                <input type="hidden" name="gender" value="{{ $member->gender }}">
                            </div>

                            {{-- Date of Birth (locked) --}}
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="date" class="form-control field-locked" name="date_of_birth"
                                        id="date_of_birth"
                                        value="{{ old('date_of_birth', $member->date_of_birth ? \Carbon\Carbon::parse($member->date_of_birth)->format('Y-m-d') : '') }}"
                                        required readonly>
                                    <label for="date_of_birth">Date of Birth</label>
                                </div>
                            </div>

                            {{-- Education Level --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="education_level" class="form-label small fw-bold text-muted ms-1">Education
                                        Level</label>
                                    <select class="form-select select2" name="education_level" id="education_level">
                                        <option value=""></option>
                                        <option value="primary" {{ old('education_level', $member->education_level) === 'primary' ? 'selected' : '' }}>Primary</option>
                                        <option value="secondary" {{ old('education_level', $member->education_level) === 'secondary' ? 'selected' : '' }}>Secondary</option>
                                        <option value="high_level" {{ old('education_level', $member->education_level) === 'high_level' ? 'selected' : '' }}>High Level
                                        </option>
                                        <option value="certificate" {{ old('education_level', $member->education_level) === 'certificate' ? 'selected' : '' }}>Certificate
                                        </option>
                                        <option value="diploma" {{ old('education_level', $member->education_level) === 'diploma' ? 'selected' : '' }}>Diploma</option>
                                        <option value="bachelor_degree" {{ old('education_level', $member->education_level) === 'bachelor_degree' ? 'selected' : '' }}>Bachelor
                                            Degree</option>
                                        <option value="masters" {{ old('education_level', $member->education_level) === 'masters' ? 'selected' : '' }}>Masters</option>
                                        <option value="phd" {{ old('education_level', $member->education_level) === 'phd' ? 'selected' : '' }}>PhD</option>
                                        <option value="professor" {{ old('education_level', $member->education_level) === 'professor' ? 'selected' : '' }}>Professor</option>
                                        <option value="not_studied" {{ old('education_level', $member->education_level) === 'not_studied' ? 'selected' : '' }}>Not Studied
                                        </option>
                                    </select>
                                </div>
                            </div>

                            {{-- Profession (required, highlight if placeholder) --}}
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="text"
                                        class="form-control {{ str_starts_with($member->profession ?? '', 'N/A (') ? 'border-warning' : '' }}"
                                        name="profession" id="profession" required placeholder="e.g. Teacher"
                                        value="{{ old('profession', str_starts_with($member->profession ?? '', 'N/A (') ? '' : $member->profession) }}">
                                    <label for="profession">Profession</label>
                                </div>
                            </div>

                            {{-- NIDA --}}
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="nida_number" id="nida_number"
                                        minlength="20" maxlength="20"
                                        value="{{ old('nida_number', $member->nida_number) }}">
                                    <label for="nida_number">NIDA Number (optional)</label>
                                </div>
                            </div>
                        </div>

                        {{-- Passport Picture --}}
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
                                    <div id="imagePreview" class="border rounded p-3" style="display:none;">
                                        <img id="previewImg" src="" alt="Preview" class="img-thumbnail"
                                            style="max-width:150px;max-height:150px;">
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="removeImagePreview()">
                                                <i class="fas fa-trash me-1"></i>Remove
                                            </button>
                                        </div>
                                    </div>
                                    @if($member->profile_picture)
                                        <div class="border rounded p-3">
                                            <img src="{{ asset('storage/' . $member->profile_picture) }}" class="img-thumbnail"
                                                style="max-width:150px;max-height:150px;" alt="Current photo">
                                            <p class="small text-muted mt-1">Current photo</p>
                                        </div>
                                    @else
                                        <div id="noImagePlaceholder" class="border rounded p-4 text-muted">
                                            <i class="fas fa-image fa-3x mb-2"></i>
                                            <p class="mb-0">No image selected</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-lg px-4 shadow-sm next-step"
                                id="nextStep1">Next <i class="fas fa-arrow-right ms-1"></i></button>
                        </div>
                    </div>

                    {{-- ════════════════════════════════════════════════════════
                    STEP 2 – Other Info (Phone, Email, Origin, Tribe)
                    ════════════════════════════════════════════════════════ --}}
                    <div id="step2" style="display:none;">
                        <div class="row g-4 mb-4">
                            {{-- Phone --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="phone_number" class="form-label small fw-bold text-muted ms-1">Phone
                                        Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text">+255</span>
                                        <input type="text" class="form-control" name="phone_number" id="phone_number"
                                            placeholder="744000000" required
                                            value="{{ old('phone_number', $member->phone_number) }}">
                                    </div>
                                </div>
                                <small class="text-muted ms-1">Enter phone number without +255 (e.g., 712345678)</small>
                            </div>

                            {{-- Email --}}
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="email" class="form-control" name="email" id="email"
                                        value="{{ old('email', $member->email) }}">
                                    <label for="email">Email (optional)</label>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            {{-- Region (origin) --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="region" class="form-label small fw-bold text-muted ms-1">Region
                                        (Origin)</label>
                                    <select class="form-select select2" id="region" name="region" required>
                                        @if($member->region)
                                            <option value="{{ $member->region }}" selected>{{ $member->region }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            {{-- District --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="district" class="form-label small fw-bold text-muted ms-1">District
                                        (Origin)</label>
                                    <select class="form-select select2" id="district" name="district" required>
                                        @if($member->district)
                                            <option value="{{ $member->district }}" selected>{{ $member->district }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            {{-- Ward --}}
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="ward" id="ward" required
                                        value="{{ old('ward', $member->ward) }}">
                                    <label for="ward">Ward (Origin)</label>
                                </div>
                            </div>

                            {{-- Street --}}
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="street" id="street" required
                                        value="{{ old('street', $member->street) }}">
                                    <label for="street">Street (Origin)</label>
                                </div>
                            </div>

                            {{-- P.O. Box --}}
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="address" id="address"
                                        value="{{ old('address', $member->address) }}">
                                    <label for="address">P.O. Box (optional)</label>
                                </div>
                            </div>

                            {{-- Tribe --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tribe" class="form-label small fw-bold text-muted ms-1">Tribe</label>
                                    <select class="form-select select2" id="tribe" name="tribe" required>
                                        @if($member->tribe)
                                            <option value="{{ $member->tribe }}" selected>{{ $member->tribe }}</option>
                                        @endif
                                        <option value="Other" {{ $member->tribe === 'Other' ? 'selected' : '' }}>Other
                                        </option>
                                    </select>
                                </div>
                            </div>

                            {{-- Other Tribe --}}
                            <div class="col-md-3" id="otherTribeWrapper"
                                style="{{ ($member->tribe === 'Other' && $member->other_tribe) ? '' : 'display:none;' }}">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="other_tribe" id="other_tribe"
                                        value="{{ old('other_tribe', $member->other_tribe) }}">
                                    <label for="other_tribe">Other Tribe</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary btn-lg px-4 prev-step">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </button>
                            <button type="button" class="btn btn-primary btn-lg px-4 next-step">
                                Next <i class="fas fa-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>

                    {{-- ════════════════════════════════════════════════════════
                    STEP 3 – Current Residence
                    ════════════════════════════════════════════════════════ --}}
                    <div id="step3" style="display:none;">
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="residence_region" class="form-label small fw-bold text-muted ms-1">Region
                                        (Residence)</label>
                                    <select name="residence_region" id="residence_region" class="form-select select2"
                                        required>
                                        @if($member->residence_region)
                                            <option value="{{ $member->residence_region }}" selected>
                                                {{ $member->residence_region }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="residence_district"
                                        class="form-label small fw-bold text-muted ms-1">District (Residence)</label>
                                    <select name="residence_district" id="residence_district" class="form-select select2"
                                        required>
                                        @if($member->residence_district)
                                            <option value="{{ $member->residence_district }}" selected>
                                                {{ $member->residence_district }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="residence_ward" id="residence_ward"
                                        required value="{{ old('residence_ward', $member->residence_ward) }}">
                                    <label for="residence_ward">Ward (Residence)</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="residence_street" id="residence_street"
                                        required value="{{ old('residence_street', $member->residence_street) }}">
                                    <label for="residence_street">Street (Residence)</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="residence_road" id="residence_road"
                                        value="{{ old('residence_road', $member->residence_road) }}">
                                    <label for="residence_road">Road Name (optional)</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="residence_house_number"
                                        id="residence_house_number"
                                        value="{{ old('residence_house_number', $member->residence_house_number) }}">
                                    <label for="residence_house_number">House Number (optional)</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary btn-lg px-4 prev-step">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </button>
                            <button type="button" class="btn btn-primary btn-lg px-4 next-step">
                                Next <i class="fas fa-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>

                    {{-- ════════════════════════════════════════════════════════
                    STEP 4 – Family Information
                    ════════════════════════════════════════════════════════ --}}
                    <div id="step4" style="display:none;">

                        {{-- Marital / Spouse section (father or mother) --}}
                        <div id="maritalStatusSection" class="border rounded-3 p-4 mb-4 bg-white shadow-sm"
                            style="{{ in_array($member->member_type, ['father', 'mother']) ? '' : 'display:none;' }}">
                            <h6 class="mb-3 fw-bold" style="color:#940000;">
                                <i class="fas fa-heart me-2"></i>Marital Status
                            </h6>
                            <div class="row g-4 mb-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="marital_status" class="form-label small fw-bold text-muted ms-1">Marital
                                            Status</label>
                                        <select class="form-select select2" name="marital_status" id="marital_status">
                                            <option value=""></option>
                                            <option value="married" {{ old('marital_status', $member->marital_status) === 'married' ? 'selected' : '' }}>Married</option>
                                            <option value="divorced" {{ old('marital_status', $member->marital_status) === 'divorced' ? 'selected' : '' }}>Divorced</option>
                                            <option value="widowed" {{ old('marital_status', $member->marital_status) === 'widowed' ? 'selected' : '' }}>Widowed</option>
                                            <option value="separated" {{ old('marital_status', $member->marital_status) === 'separated' ? 'selected' : '' }}>Separated
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Spouse fields --}}
                            <div id="spouseInfoFields"
                                style="{{ $member->marital_status === 'married' ? '' : 'display:none;' }}">
                                <h6 class="mb-3 fw-bold" style="color:#940000;">
                                    <i class="fas fa-user me-2"></i>Spouse Information
                                </h6>
                                <div class="row g-4 mb-3">
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="spouse_full_name"
                                                id="spouse_full_name"
                                                value="{{ old('spouse_full_name', $member->spouse_full_name) }}">
                                            <label for="spouse_full_name">Full Name</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="date" class="form-control" name="spouse_date_of_birth"
                                                id="spouse_date_of_birth"
                                                value="{{ old('spouse_date_of_birth', $member->spouse_date_of_birth ? \Carbon\Carbon::parse($member->spouse_date_of_birth)->format('Y-m-d') : '') }}">
                                            <label for="spouse_date_of_birth">Date of Birth</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="spouse_gender" class="form-label small fw-bold text-muted ms-1">Spouse Gender</label>
                                            <select class="form-select select2" name="spouse_gender" id="spouse_gender">
                                                <option value=""></option>
                                                <option value="male" {{ old('spouse_gender', $member->spouseMember->gender ?? '') === 'male' ? 'selected' : '' }}>Male</option>
                                                <option value="female" {{ old('spouse_gender', $member->spouseMember->gender ?? '') === 'female' ? 'selected' : '' }}>Female</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="spouse_education_level"
                                                class="form-label small fw-bold text-muted ms-1">Education Level</label>
                                            <select class="form-select select2" name="spouse_education_level"
                                                id="spouse_education_level">
                                                <option value=""></option>
                                                @foreach(['primary', 'secondary', 'high_level', 'certificate', 'diploma', 'bachelor_degree', 'masters', 'phd', 'professor', 'not_studied'] as $lvl)
                                                    <option value="{{ $lvl }}" {{ old('spouse_education_level', $member->spouse_education_level) === $lvl ? 'selected' : '' }}>
                                                        {{ ucwords(str_replace('_', ' ', $lvl)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="spouse_profession"
                                                id="spouse_profession"
                                                value="{{ old('spouse_profession', $member->spouse_profession) }}">
                                            <label for="spouse_profession">Profession</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="spouse_nida_number"
                                                id="spouse_nida_number" minlength="20" maxlength="20"
                                                value="{{ old('spouse_nida_number', $member->spouse_nida_number) }}">
                                            <label for="spouse_nida_number">NIDA Number (optional)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="email" class="form-control" name="spouse_email" id="spouse_email"
                                                value="{{ old('spouse_email', $member->spouse_email) }}">
                                            <label for="spouse_email">Email (optional)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="spouse_tribe" class="form-label small fw-bold text-muted ms-1">Spouse Tribe</label>
                                            <select class="form-select select2" name="spouse_tribe" id="spouse_tribe">
                                                <option value=""></option>
                                                @foreach(['Sukuma', 'Nyamwezi', 'Chagga', 'Haya', 'Ha', 'Gogo', 'Hehe', 'Makonde', 'Zaramo', 'Nyakyusa', 'Other'] as $tribe)
                                                    <option value="{{ $tribe }}" {{ old('spouse_tribe', $member->spouse_tribe) === $tribe ? 'selected' : '' }}>
                                                        {{ $tribe }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4" id="spouseOtherTribeWrapper" style="display: {{ old('spouse_tribe', $member->spouse_tribe) === 'Other' ? 'block' : 'none' }};">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="spouse_other_tribe" id="spouse_other_tribe" 
                                                value="{{ old('spouse_other_tribe', $member->spouse_other_tribe) }}">
                                            <label for="spouse_other_tribe">Specify Other Tribe</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="spouse_phone_number"
                                                class="form-label small fw-bold text-muted ms-1">Spouse Phone Number</label>
                                            <div class="input-group">
                                                <span class="input-group-text">+255</span>
                                                <input type="text" class="form-control" name="spouse_phone_number"
                                                    id="spouse_phone_number" placeholder="744000000"
                                                    value="{{ old('spouse_phone_number', $member->spouse_phone_number) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="spouse_church_member"
                                                class="form-label small fw-bold text-muted ms-1">Is spouse a church
                                                member?</label>
                                            <select class="form-select select2" name="spouse_church_member"
                                                id="spouse_church_member">
                                                <option value=""></option>
                                                <option value="yes" {{ old('spouse_church_member', $member->spouse_church_member) === 'yes' ? 'selected' : '' }}>Yes, church
                                                    member</option>
                                                <option value="no" {{ old('spouse_church_member', $member->spouse_church_member) === 'no' ? 'selected' : '' }}>No, not a
                                                    church member</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="spouseEnvelopeWrapper" style="display: {{ old('spouse_church_member', $member->spouse_church_member) === 'yes' ? 'block' : 'none' }};">
                                            <label for="spouse_envelope_number" class="form-label small fw-bold text-muted ms-1">Spouse Envelope Number</label>
                                            <input type="text" class="form-control" name="spouse_envelope_number" id="spouse_envelope_number" 
                                                value="{{ old('spouse_envelope_number', $member->spouseMember->envelope_number ?? ($member->spouse_church_member === 'yes' ? $member->spouse_member_id : '')) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                     </div>

                    {{-- Family Members / Dependents Section --}}
                    <div id="childrenSection" class="border rounded-3 p-4 mb-4 bg-white shadow-sm">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="mb-0 fw-bold" style="color:#940000;">
                                    <i class="fas fa-users-cog me-2"></i>Family Members / Dependents
                                </h6>
                                <small class="text-muted">Including children, parents, or other relatives</small>
                            </div>
                            <div class="mb-2">
                                <button type="button" class="btn btn-outline-danger btn-sm" id="addChildBtn">
                                    <i class="fas fa-plus me-1"></i>Add Member
                                </button>
                            </div>
                            <div id="childrenContainer">
                                @php $childIdx = 0; @endphp
                                @foreach($member->children as $child)
                                    <div class="row g-3 mb-2 align-items-end child-row" data-index="{{ $childIdx }}">
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold text-muted">Full Name</label>
                                            <input type="text" class="form-control child-fullname" 
                                                   name="children[{{ $childIdx }}][full_name]" 
                                                   value="{{ $child->full_name }}" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small fw-bold text-muted">Relationship</label>
                                            <select class="form-select child-relationship" 
                                                    name="children[{{ $childIdx }}][relationship]" required>
                                                @foreach(['Son/Daughter', 'Father', 'Mother', 'Brother', 'Sister', 'Grandparent', 'Relative', 'Other'] as $rel)
                                                    <option value="{{ $rel }}" {{ ($child->relationship ?? 'Son/Daughter') === $rel ? 'selected' : '' }}>{{ $rel }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small fw-bold text-muted">Gender</label>
                                            <select class="form-select child-gender" 
                                                    name="children[{{ $childIdx }}][gender]" required>
                                                <option value="male" {{ $child->gender === 'male' ? 'selected' : '' }}>Male</option>
                                                <option value="female" {{ $child->gender === 'female' ? 'selected' : '' }}>Female</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small fw-bold text-muted">Date of Birth</label>
                                            <div class="input-group">
                                                <input type="date" class="form-control child-dob" 
                                                       name="children[{{ $childIdx }}][date_of_birth]" 
                                                       value="{{ $child->date_of_birth->format('Y-m-d') }}" required>
                                                <span class="input-group-text child-age bg-light" style="min-width:40px; font-size:0.7rem;">{{ $child->date_of_birth->age }}y</span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small fw-bold text-muted">Church Member</label>
                                            <select class="form-select child-church-member" 
                                                    name="children[{{ $childIdx }}][is_church_member]" 
                                                    onchange="toggleChildEnvelope(this, {{ $childIdx }})">
                                                <option value="no" {{ $child->is_church_member === 'no' ? 'selected' : '' }}>No</option>
                                                <option value="yes" {{ $child->is_church_member === 'yes' ? 'selected' : '' }}>Yes</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2" id="childEnvelopeWrapper_{{ $childIdx }}" 
                                             style="{{ $child->is_church_member === 'yes' ? '' : 'display:none;' }}">
                                            <label class="form-label small fw-bold text-muted">Envelope #</label>
                                            <input type="text" class="form-control child-envelope" 
                                                   name="children[{{ $childIdx }}][envelope_number]" 
                                                   value="{{ $child->envelope_number }}">
                                        </div>
                                        <div class="col-md-1 text-end">
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-child-btn" 
                                                    title="Remove Member"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </div>
                                    @php $childIdx++; @endphp
                                @endforeach
                            </div>
                        </div>

                        {{-- Guardian section --}}
                        <div id="guardianSection" class="border rounded-3 p-4 mb-4 bg-white shadow-sm"
                             style="{{ $member->member_type === 'independent' ? '' : 'display:none;' }}">
                            <h6 class="mb-3 fw-bold" style="color:#940000;">
                                <i class="fas fa-user-shield me-2"></i>Guardian / Responsible Person (Optional)
                            </h6>
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control"
                                               name="guardian_name" id="guardian_name"
                                               value="{{ old('guardian_name', $member->guardian_name) }}">
                                        <label for="guardian_name">Guardian Name</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="guardian_phone" class="form-label small fw-bold text-muted ms-1">Guardian Phone</label>
                                        <div class="input-group">
                                            <span class="input-group-text">+255</span>
                                            <input type="text" class="form-control"
                                                   name="guardian_phone" id="guardian_phone"
                                                   placeholder="744000000"
                                                   value="{{ old('guardian_phone', $member->guardian_phone) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="guardian_relationship" class="form-label small fw-bold text-muted ms-1">Relationship</label>
                                        <select class="form-select select2"
                                                name="guardian_relationship" id="guardian_relationship">
                                            <option value=""></option>
                                            @foreach(['Parent', 'Relative', 'Neighbor', 'Friend', 'Other'] as $rel)
                                                <option value="{{ $rel }}"
                                                    {{ old('guardian_relationship', $member->guardian_relationship) === $rel ? 'selected' : '' }}>
                                                    {{ $rel }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary btn-lg px-4 prev-step">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </button>
                            <button type="button" class="btn btn-primary btn-lg px-4 next-step">
                                Next <i class="fas fa-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>

                    {{-- ════════════════════════════════════════════════════════
                         STEP 5 – Summary
                    ════════════════════════════════════════════════════════ --}}
                    <div id="step5" style="display:none;">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-gradient-primary text-white fw-bold">
                                <i class="fas fa-check-circle me-2"></i>Review Before Saving
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-sm table-borderless">
                                            <tr><th>Name</th><td id="sum_name">—</td></tr>
                                            <tr><th>Gender</th><td id="sum_gender">—</td></tr>
                                            <tr><th>Date of Birth</th><td id="sum_dob">—</td></tr>
                                            <tr><th>Envelope No.</th><td id="sum_env">—</td></tr>
                                            <tr><th>Membership Type</th><td id="sum_mem_type">—</td></tr>
                                            <tr><th>Member Type</th><td id="sum_member_type">—</td></tr>
                                            <tr><th>Profession</th><td id="sum_prof">—</td></tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-sm table-borderless">
                                            <tr><th>Phone</th><td id="sum_phone">—</td></tr>
                                            <tr><th>Email</th><td id="sum_email">—</td></tr>
                                            <tr><th>Region (Origin)</th><td id="sum_region">—</td></tr>
                                            <tr><th>District (Origin)</th><td id="sum_district">—</td></tr>
                                            <tr><th>Ward</th><td id="sum_ward">—</td></tr>
                                            <tr><th>Residence Region</th><td id="sum_res_region">—</td></tr>
                                            <tr><th>Residence District</th><td id="sum_res_district">—</td></tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary btn-lg px-4 prev-step">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </button>
                            <button type="submit" class="btn btn-success btn-lg px-5 shadow">
                                <i class="fas fa-save me-2"></i>Complete Registration
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        let currentStep = 1;
        const totalSteps = 5;

        // ── Location data ────────────────────────────────────────────
        const savedRegion      = @json(old('region', $member->region));
        const savedDistrict    = @json(old('district', $member->district));
        const savedResRegion   = @json(old('residence_region', $member->residence_region));
        const savedResDistrict = @json(old('residence_district', $member->residence_district));
        const savedTribe       = @json(old('tribe', $member->tribe));

        const regionEl          = document.getElementById('region');
        const districtEl        = document.getElementById('district');
        const residenceRegionEl = document.getElementById('residence_region');
        const residenceDistrictEl = document.getElementById('residence_district');
        const tribeEl           = document.getElementById('tribe');
        const otherTribeWrapper = document.getElementById('otherTribeWrapper');

        function makeOpts(list, selected, labelKey) {
            return '<option value=""></option>' +
                list.map(r => `<option value="${r[labelKey]}" ${r[labelKey] === selected ? 'selected' : ''}>${r[labelKey]}</option>`).join('');
        }

        fetch('/data/tanzania-locations.json').then(r => r.json()).then(data => {
            const regions = data.regions || [];
            regionEl.innerHTML        = makeOpts(regions, savedRegion,    'name');
            residenceRegionEl.innerHTML = makeOpts(regions, savedResRegion, 'name');

            function loadDistricts(regionName, distElm, selDist) {
                const found = regions.find(r => r.name === regionName);
                distElm.innerHTML = '<option value=""></option>' +
                    (found ? found.districts : []).map(d =>
                        `<option value="${d.name}" ${d.name === selDist ? 'selected' : ''}>${d.name}</option>`
                    ).join('');
                if (typeof $ !== 'undefined') $(distElm).trigger('change.select2');
            }

            if (savedRegion)    loadDistricts(savedRegion,    districtEl,         savedDistrict);
            if (savedResRegion) loadDistricts(savedResRegion, residenceDistrictEl, savedResDistrict);

            regionEl.addEventListener('change',          () => loadDistricts(regionEl.value,          districtEl, ''));
            residenceRegionEl.addEventListener('change', () => loadDistricts(residenceRegionEl.value, residenceDistrictEl, ''));

            initSelect2();
        }).catch(() => initSelect2());

        fetch('/data/tribes.json').then(r => r.json()).then(data => {
            const tribes = data.tribes || data || [];
            tribeEl.innerHTML = '<option value=""></option>' +
                tribes.map(t => `<option value="${t}" ${t === savedTribe ? 'selected' : ''}>${t}</option>`).join('') +
                `<option value="Other" ${'Other' === savedTribe ? 'selected' : ''}>Other</option>`;
            if (typeof $ !== 'undefined') $(tribeEl).trigger('change.select2');
            tribeEl.addEventListener('change', () => {
                otherTribeWrapper.style.display = tribeEl.value === 'Other' ? '' : 'none';
            });
        });

        function initSelect2() {
            if (typeof $ !== 'undefined' && typeof $.fn.select2 !== 'undefined') {
                $('.select2:not([disabled])').each(function() {
                    $(this).select2({ width: '100%' });
                });
            }
        }

        // ── Member Type → show/hide sections ────────────────────────
        const memberTypeEl         = document.getElementById('member_type');
        const maritalStatusSection = document.getElementById('maritalStatusSection');
        const guardianSection      = document.getElementById('guardianSection');
        const maritalStatusEl      = document.getElementById('marital_status');
        const spouseInfoFields     = document.getElementById('spouseInfoFields');

        // Spouse church member toggle
        const spouseChurchMemberEl = document.getElementById('spouse_church_member');
        const spouseEnvelopeWrapper = document.getElementById('spouseEnvelopeWrapper');
        const spouseEnvelopeInput = document.getElementById('spouse_envelope_number');

        if (spouseChurchMemberEl) {
            $(spouseChurchMemberEl).on('change', function() {
                if (this.value === 'yes') {
                    if (spouseEnvelopeWrapper) spouseEnvelopeWrapper.style.display = 'block';
                    if (spouseEnvelopeInput) spouseEnvelopeInput.setAttribute('required', 'required');
                } else {
                    if (spouseEnvelopeWrapper) spouseEnvelopeWrapper.style.display = 'none';
                    if (spouseEnvelopeInput) {
                        spouseEnvelopeInput.removeAttribute('required');
                        spouseEnvelopeInput.value = '';
                    }
                }
            });
        }

        // Spouse tribe toggle
        const spouseTribeEl = document.getElementById('spouse_tribe');
        const spouseOtherTribeWrapper = document.getElementById('spouseOtherTribeWrapper');
        if (spouseTribeEl) {
            $(spouseTribeEl).on('change', function() {
                if (spouseOtherTribeWrapper) {
                    spouseOtherTribeWrapper.style.display = this.value === 'Other' ? 'block' : 'none';
                }
            });
        }

        function updateFamilySections() {
            const val = memberTypeEl.value;
            const isParent = val === 'father' || val === 'mother';
            const isIndependent = val === 'independent';
            
            // Marital status visible for parents, and optionally for independent if we allow them to be married
            // But usually church logic: if married -> father/mother. 
            // However, let's keep it visible for independent too if they want to fill it.
            maritalStatusSection.style.display = (isParent || isIndependent) ? '' : 'none';
            
            // Children/Family section visible for everyone except maybe some specific types
            const childrenSection = document.getElementById('childrenSection');
            if (childrenSection) {
                childrenSection.style.display = (isParent || isIndependent) ? '' : 'none';
            }
            
            guardianSection.style.display = (isParent) ? 'none' : '';
        }

        memberTypeEl.addEventListener('change', updateFamilySections);
        if (typeof $ !== 'undefined') {
            $(memberTypeEl).on('select2:select', updateFamilySections);
        }
        updateFamilySections();

        maritalStatusEl.addEventListener('change', function () {
            spouseInfoFields.style.display = this.value === 'married' ? '' : 'none';
        });
        if (typeof $ !== 'undefined') {
            $(maritalStatusEl).on('select2:select', function () {
                spouseInfoFields.style.display = this.value === 'married' ? '' : 'none';
            });
        }

        // ── Wizard navigation ────────────────────────────────────────
        function showStep(step) {
            for (let i = 1; i <= totalSteps; i++) {
                const el = document.getElementById('step' + i);
                if (el) el.style.display = i === step ? '' : 'none';
            }
            document.querySelectorAll('.wizard-step').forEach(ws => {
                const s = parseInt(ws.dataset.step);
                const circle = ws.querySelector('.step-circle');
                ws.classList.toggle('active', s === step);
                ws.classList.toggle('completed', s < step);
                if (s < step) {
                    circle.innerHTML = '<i class="fas fa-check"></i>';
                    circle.className = 'step-circle bg-success text-white shadow';
                } else if (s === step) {
                    circle.innerHTML = s;
                    circle.className = 'step-circle bg-primary text-white shadow';
                } else {
                    circle.innerHTML = s;
                    circle.className = 'step-circle bg-secondary text-white shadow';
                }
            });
            currentStep = step;
            if (step === 5) populateSummary();
        }

        function validateStep(step) {
            let valid = true;
            document.querySelectorAll('#step' + step + ' [required]').forEach(el => {
                if (!el.disabled && !el.value.trim()) {
                    el.classList.add('is-invalid');
                    valid = false;
                } else {
                    el.classList.remove('is-invalid');
                }
            });
            if (!valid && typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'warning', title: 'Required Fields', text: 'Please fill in all required fields before proceeding.', confirmButtonColor: '#940000' });
            }
            return valid;
        }

        document.querySelectorAll('.next-step').forEach(btn => {
            btn.addEventListener('click', () => { if (validateStep(currentStep)) showStep(currentStep + 1); });
        });
        document.querySelectorAll('.prev-step').forEach(btn => {
            btn.addEventListener('click', () => showStep(currentStep - 1));
        });

        // ── Summary populator ────────────────────────────────────────
        function populateSummary() {
            const v = id => { const el = document.getElementById(id); return el ? el.value : ''; };
            const t = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val || '—'; };
            t('sum_name',        v('full_name'));
            t('sum_gender',      v('gender_display') || document.querySelector('[name="gender"]')?.value);
            t('sum_dob',         v('date_of_birth'));
            t('sum_env',         v('envelope_number'));
            t('sum_mem_type',    v('membership_type'));
            t('sum_member_type', v('member_type'));
            t('sum_prof',        v('profession'));
            t('sum_phone',       v('phone_number'));
            t('sum_email',       v('email'));
            t('sum_region',      v('region'));
            t('sum_district',    v('district'));
            t('sum_ward',        v('ward'));
            t('sum_res_region',  v('residence_region'));
            t('sum_res_district',v('residence_district'));
        }

        // ── Image preview ────────────────────────────────────────────
        window.handleImagePreview = function(input) {
            const preview = document.getElementById('imagePreview');
            const img     = document.getElementById('previewImg');
            const ph      = document.getElementById('noImagePlaceholder');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    img.src = e.target.result;
                    if (preview) preview.style.display = '';
                    if (ph) ph.style.display = 'none';
                };
                reader.readAsDataURL(input.files[0]);
            }
        };
        window.removeImagePreview = function() {
            const input   = document.getElementById('profile_picture');
            const preview = document.getElementById('imagePreview');
            const ph      = document.getElementById('noImagePlaceholder');
            if (input)   input.value = '';
            if (preview) preview.style.display = 'none';
            if (ph)      ph.style.display = '';
        };

        // ── Family Members Management (Dynamic rows) ────────────────
        let nextChildIdx = {{ $member->children->count() }};
        const childrenContainer = document.getElementById('childrenContainer');
        const addChildBtn = document.getElementById('addChildBtn');

        function addChild() {
            const idx = nextChildIdx;
            const row = document.createElement('div');
            row.className = 'row g-3 mb-2 align-items-end child-row';
            row.innerHTML = `
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Full Name</label>
                    <input type="text" class="form-control child-fullname" name="children[${idx}][full_name]" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">Relationship</label>
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
                    <label class="form-label small fw-bold text-muted">Gender</label>
                    <select class="form-select child-gender" name="children[${idx}][gender]" required>
                        <option value=""></option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">Date of Birth</label>
                    <div class="input-group">
                        <input type="date" class="form-control child-dob" name="children[${idx}][date_of_birth]" required>
                        <span class="input-group-text child-age bg-light" style="min-width:40px; display:none; font-size:0.7rem;"></span>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">Church Member</label>
                    <select class="form-select child-church-member" name="children[${idx}][is_church_member]" onchange="toggleChildEnvelope(this, ${idx})">
                        <option value="no">No</option>
                        <option value="yes">Yes</option>
                    </select>
                </div>
                <div class="col-md-2" id="childEnvelopeWrapper_${idx}" style="display:none;">
                    <label class="form-label small fw-bold text-muted">Envelope #</label>
                    <input type="text" class="form-control child-envelope" name="children[${idx}][envelope_number]">
                </div>
                <div class="col-md-1 text-end">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-child-btn" title="Remove Member"><i class="fas fa-trash"></i></button>
                </div>
            `;
            childrenContainer.appendChild(row);
            
            // Add validation/age listeners
            const dobInput = row.querySelector('.child-dob');
            dobInput.addEventListener('change', function() {
                updateAgeDisplay(this);
                const churchMemberSelect = row.querySelector('.child-church-member');
                toggleChildEnvelope(churchMemberSelect, idx);
            });

            row.querySelector('.remove-child-btn').addEventListener('click', function() {
                row.remove();
                // We don't decrement nextChildIdx to avoid ID collision
                reindexChildren();
            });

            nextChildIdx++;
        }

        function updateAgeDisplay(input) {
            const row = input.closest('.child-row');
            const ageSpan = row.querySelector('.child-age');
            const dob = new Date(input.value);
            if (input.value && dob < new Date()) {
                const today = new Date();
                let age = today.getFullYear() - dob.getFullYear();
                const m = today.getMonth() - dob.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
                ageSpan.textContent = age + 'y';
                ageSpan.style.display = '';
            } else {
                ageSpan.style.display = 'none';
            }
        }

        function reindexChildren() {
            const rows = childrenContainer.querySelectorAll('.child-row');
            rows.forEach((row, i) => {
                // We keep the original element IDs and variable idx for internal JS logic (toggleChildEnvelope)
                // but we update the NAME attributes for correct PHP array mapping on submit.
                row.querySelector('.child-fullname').setAttribute('name', `children[${i}][full_name]`);
                row.querySelector('.child-relationship').setAttribute('name', `children[${i}][relationship]`);
                row.querySelector('.child-gender').setAttribute('name', `children[${i}][gender]`);
                row.querySelector('.child-dob').setAttribute('name', `children[${i}][date_of_birth]`);
                row.querySelector('.child-church-member').setAttribute('name', `children[${i}][is_church_member]`);
                row.querySelector('.child-envelope').setAttribute('name', `children[${i}][envelope_number]`);
            });
        }

        window.toggleChildEnvelope = function(select, idx) {
            const wrapper = document.getElementById(`childEnvelopeWrapper_${idx}`);
            if (!wrapper) return;
            
            const row = select.closest('.child-row');
            const dobInput = row.querySelector('.child-dob');
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
        };

        if (addChildBtn) addChildBtn.addEventListener('click', addChild);

        // Add remove listener to existing rows
        childrenContainer.querySelectorAll('.remove-child-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const row = this.closest('.child-row');
                row.remove();
                reindexChildren();
            });
        });

        // Add age calculation to existing rows
        childrenContainer.querySelectorAll('.child-dob').forEach((input, idx) => {
            input.addEventListener('change', function() {
                updateAgeDisplay(this);
                const row = this.closest('.child-row');
                const churchMemberSelect = row.querySelector('.child-church-member');
                // For existing rows, idx matches the data-index
                const rowIdx = row.dataset.index || idx;
                toggleChildEnvelope(churchMemberSelect, rowIdx);
            });
        });

        showStep(1);
    });
    </script>
@endsection