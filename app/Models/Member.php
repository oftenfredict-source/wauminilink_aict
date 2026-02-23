<?php
// File: app/Models/Member.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Member extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'member_id',
        'biometric_enroll_id',
        'envelope_number',
        'member_type',           // father, mother, independent
        'membership_type',       // permanent, temporary
        'full_name',
        'email',
        'phone_number',
        'date_of_birth',
        'gender',
        'education_level',       // primary, secondary, chuo_cha_kati, university
        'profession',
        'guardian_name',
        'guardian_phone',
        'guardian_relationship',
        'nida_number',
        'tribe',
        'other_tribe',
        'region',
        'district',
        'ward',
        'street',
        'address',
        'residence_region',
        'residence_district',
        'residence_ward',
        'residence_street',
        'residence_road',
        'residence_house_number',
        'profile_picture',
        'marital_status',
        'spouse_full_name',
        'spouse_date_of_birth',
        'spouse_education_level',
        'spouse_profession',
        'spouse_nida_number',
        'spouse_email',
        'spouse_phone_number',
        'spouse_tribe',
        'spouse_other_tribe',
        'spouse_gender',
        'spouse_church_member',
        'spouse_member_id',
        'spouse_envelope_number',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'spouse_date_of_birth' => 'date',
    ];

    public function children()
    {
        return $this->hasMany(Child::class);
    }

    // Relationship to spouse member (if spouse is also a church member)
    public function spouseMember()
    {
        return $this->belongsTo(Member::class, 'spouse_member_id');
    }

    // Reverse relationship - get the main member from spouse member
    public function mainMember()
    {
        return $this->hasOne(Member::class, 'spouse_member_id');
    }

    // Financial relationships
    public function tithes()
    {
        return $this->hasMany(Tithe::class);
    }

    public function offerings()
    {
        return $this->hasMany(Offering::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function pledges()
    {
        return $this->hasMany(Pledge::class);
    }

    public function annualFees()
    {
        return $this->hasMany(AnnualFee::class);
    }

    // Attendance relationships
    public function attendances()
    {
        return $this->hasMany(ServiceAttendance::class);
    }

    public function sundayServiceAttendances()
    {
        return $this->hasMany(ServiceAttendance::class)->sundayServices();
    }

    public function specialEventAttendances()
    {
        return $this->hasMany(ServiceAttendance::class)->specialEvents();
    }

    // Leadership relationships
    public function leadershipPositions()
    {
        return $this->hasMany(Leader::class);
    }

    public function activeLeadershipPositions()
    {
        return $this->hasMany(Leader::class)->where('is_active', true);
    }

    // Bereavement relationships
    public function bereavementContributions()
    {
        return $this->hasMany(BereavementContribution::class);
    }

    // User account relationship
    public function user()
    {
        return $this->hasOne(User::class);
    }

    /**
     * Generate a unique member ID
     * Format: AICT + YYYY + sequential number (4 digits)
     * Example: AICT-2025-0001
     */
    public static function generateMemberId()
    {
        $year = date('Y');
        $prefix = 'AICT-' . $year . '-';

        // Find the highest sequence number for the current year
        $lastMember = self::where('member_id', 'like', $prefix . '%')
            ->orderBy('member_id', 'desc')
            ->first();

        $sequence = 1;
        if ($lastMember) {
            // Extract the sequence number from the last ID (e.g., AICT-2026-0012 -> 12)
            $lastIdParts = explode('-', $lastMember->member_id);
            if (count($lastIdParts) === 3) {
                $sequence = (int) $lastIdParts[2] + 1;
            }
        }

        // Format: AICT-2025-0001
        $memberId = $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);

        // Final check for uniqueness (just in case of race conditions)
        while (self::where('member_id', $memberId)->exists()) {
            $sequence++;
            $memberId = $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
        }

        return $memberId;
    }

    /**
     * Generate a unique biometric enroll ID (2-3 digits: 10-999)
     * This ID is used to register members on the biometric device
     * 
     * @return string Unique enroll ID between 10 and 999
     */
    public static function generateBiometricEnrollId()
    {
        $maxAttempts = 1000; // Prevent infinite loop
        $attempts = 0;

        do {
            // Generate random number between 10 and 999 (2-3 digits)
            $enrollId = rand(10, 999);
            $attempts++;

            if ($attempts >= $maxAttempts) {
                // If we can't find a unique ID, try sequential search
                for ($id = 10; $id <= 999; $id++) {
                    if (!self::where('biometric_enroll_id', (string) $id)->exists()) {
                        return (string) $id;
                    }
                }
                throw new \Exception('Cannot generate unique biometric enroll ID. All IDs (10-999) are taken.');
            }

        } while (self::where('biometric_enroll_id', (string) $enrollId)->exists());

        return (string) $enrollId;
    }

    /**
     * Get the member's age
     */
    public function getAgeAttribute()
    {
        if (!$this->date_of_birth) {
            return 0;
        }
        return \Carbon\Carbon::parse($this->date_of_birth)->age;
    }

    /**
     * Boot method to auto-generate biometric enroll ID when member is created
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($member) {
            // Auto-generate biometric enroll ID if not provided
            if (empty($member->biometric_enroll_id)) {
                $member->biometric_enroll_id = self::generateBiometricEnrollId();
            }
        });
    }
}