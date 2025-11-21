<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Child extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'full_name',
        'gender',
        'date_of_birth',
        'parent_name',
        'parent_phone',
        'parent_relationship',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get parent/guardian name (from member or non-member parent)
     */
    public function getParentName()
    {
        if ($this->member) {
            return $this->member->full_name;
        }
        return $this->parent_name ?? null;
    }

    /**
     * Get parent/guardian phone (from member or non-member parent)
     */
    public function getParentPhone()
    {
        if ($this->member) {
            return $this->member->phone_number;
        }
        return $this->parent_phone ?? null;
    }

    /**
     * Get parent/guardian relationship
     */
    public function getParentRelationship()
    {
        if ($this->member) {
            return 'Church Member';
        }
        return $this->parent_relationship ?? null;
    }

    /**
     * Check if parent is a church member
     */
    public function hasMemberParent()
    {
        return !is_null($this->member_id);
    }

    /**
     * Get attendance records for this child
     */
    public function attendances()
    {
        return $this->hasMany(ServiceAttendance::class);
    }

    /**
     * Get Sunday service attendance records
     */
    public function sundayServiceAttendances()
    {
        return $this->hasMany(ServiceAttendance::class)->sundayServices();
    }

    /**
     * Get special event attendance records
     */
    public function specialEventAttendances()
    {
        return $this->hasMany(ServiceAttendance::class)->specialEvents();
    }

    /**
     * Calculate the child's age
     * 
     * @param Carbon|null $referenceDate Reference date for age calculation (defaults to today)
     * @return int
     */
    public function getAge($referenceDate = null)
    {
        if (!$this->date_of_birth) {
            return 0;
        }

        $reference = $referenceDate ?? Carbon::now();
        // Use diffInYears which returns integer, but ensure it's cast to int for safety
        return (int) $this->date_of_birth->diffInYears($reference);
    }

    /**
     * Get the child's age group
     * 
     * @return string|null 'infant' (< 3), 'sunday_school' (3-12), 'teenager' (13-17), or null if 18+
     */
    public function getAgeGroup()
    {
        $age = $this->getAge();
        
        if ($age < 3) {
            return 'infant';
        } elseif ($age >= 3 && $age <= 12) {
            return 'sunday_school';
        } elseif ($age >= 13 && $age <= 17) {
            return 'teenager';
        }
        
        return null; // 18 or older
    }

    /**
     * Determine which service type this child should attend based on age
     * 
     * @return string|null 'children_service' for ages 3-12, 'sunday_service' for ages 13-17, null for others
     */
    public function getRecommendedServiceType()
    {
        $ageGroup = $this->getAgeGroup();
        
        switch ($ageGroup) {
            case 'sunday_school':
                return 'children_service'; // Sunday School
            case 'teenager':
                return 'sunday_service'; // Main adult service
            default:
                return null; // Infants (< 3) or adults (18+) - not typically recorded
        }
    }

    /**
     * Check if this child should attend Sunday School (ages 3-12)
     * 
     * @return bool
     */
    public function shouldAttendSundaySchool()
    {
        return $this->getAgeGroup() === 'sunday_school';
    }

    /**
     * Check if this child should attend main service (ages 13-17)
     * 
     * @return bool
     */
    public function shouldAttendMainService()
    {
        return $this->getAgeGroup() === 'teenager';
    }

    /**
     * Check if this child is part of children's ministry (ages 3-17)
     * 
     * @return bool
     */
    public function isChildrenMinistryMember()
    {
        $ageGroup = $this->getAgeGroup();
        return in_array($ageGroup, ['sunday_school', 'teenager']);
    }

    /**
     * Check if this child should be recorded in attendance (ages 3-17)
     * 
     * @return bool
     */
    public function shouldRecordAttendance()
    {
        $ageGroup = $this->getAgeGroup();
        return in_array($ageGroup, ['sunday_school', 'teenager']);
    }
}



