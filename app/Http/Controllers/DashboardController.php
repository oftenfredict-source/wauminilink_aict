<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Child;
use App\Models\SpecialEvent;
use App\Models\Celebration;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        \Log::info('DashboardController@index called');
        
        // Get basic member counts
        $registeredMembers = Member::count();
        
        // Get active events count (events that are upcoming or today)
        $activeEvents = SpecialEvent::where('event_date', '>=', now()->toDateString())->count();
        
        // Get upcoming celebrations count (celebrations that are upcoming or today)
        $upcomingCelebrations = Celebration::where('celebration_date', '>=', now()->toDateString())->count();
        
        // Calculate family-inclusive demographics
        $familyDemographics = $this->calculateFamilyDemographics();
        
        return view('dashboard', compact(
            'registeredMembers',
            'activeEvents', 
            'upcomingCelebrations'
        ) + $familyDemographics);
    }
    
    private function calculateFamilyDemographics()
    {
        // Get registered members demographics
        $maleMembers = Member::where('gender', 'Male')->count();
        $femaleMembers = Member::where('gender', 'Female')->count();
        
        // Count spouses (from spouse fields in members table)
        $maleSpouses = Member::whereNotNull('spouse_full_name')
            ->where('spouse_full_name', '!=', '')
            ->where('gender', 'Male')
            ->count();
        $femaleSpouses = Member::whereNotNull('spouse_full_name')
            ->where('spouse_full_name', '!=', '')
            ->where('gender', 'Female')
            ->count();
        
        // Count children from children table
        $maleChildren = Child::where('gender', 'Male')->count();
        $femaleChildren = Child::where('gender', 'Female')->count();
        
        // Calculate total family members
        $totalMembers = $maleMembers + $femaleMembers + $maleSpouses + $femaleSpouses + $maleChildren + $femaleChildren;
        
        // Calculate gender totals including family
        $totalMaleMembers = $maleMembers + $maleSpouses + $maleChildren;
        $totalFemaleMembers = $femaleMembers + $femaleSpouses + $femaleChildren;
        
        // Calculate age groups including family
        $adultMembers = Member::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) >= 18')->count();
        $adultSpouses = Member::whereNotNull('spouse_date_of_birth')
            ->whereRaw('TIMESTAMPDIFF(YEAR, spouse_date_of_birth, CURDATE()) >= 18')
            ->count();
        $totalAdults = $adultMembers + $adultSpouses;
        
        $childMembers = Member::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) < 18')->count();
        $childSpouses = Member::whereNotNull('spouse_date_of_birth')
            ->whereRaw('TIMESTAMPDIFF(YEAR, spouse_date_of_birth, CURDATE()) < 18')
            ->count();
        $totalChildren = $childMembers + $childSpouses + Child::count();
        
        return [
            'totalMembers' => $totalMembers,
            'maleMembers' => $totalMaleMembers,
            'femaleMembers' => $totalFemaleMembers,
            'totalChildren' => $totalChildren,
            'adultMembers' => $totalAdults,
            'registeredMembers' => $registeredMembers ?? Member::count(),
            'familyBreakdown' => [
                'registered_males' => $maleMembers,
                'registered_females' => $femaleMembers,
                'spouse_males' => $maleSpouses,
                'spouse_females' => $femaleSpouses,
                'child_males' => $maleChildren,
                'child_females' => $femaleChildren,
            ]
        ];
    }
}
