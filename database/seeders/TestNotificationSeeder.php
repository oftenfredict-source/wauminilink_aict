<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SpecialEvent;
use App\Models\Celebration;
use App\Models\SundayService;
use Carbon\Carbon;

class TestNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test special event for tomorrow
        SpecialEvent::create([
            'title' => 'Test Special Event',
            'description' => 'This is a test event for notifications',
            'event_date' => Carbon::tomorrow()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'venue' => 'Main Hall',
            'speaker' => 'Test Speaker'
        ]);

        // Create a test celebration for next week
        Celebration::create([
            'title' => 'Test Birthday Celebration',
            'type' => 'Birthday',
            'celebrant_name' => 'Test Celebrant',
            'celebration_date' => Carbon::now()->addWeek()->toDateString(),
            'start_time' => '14:00:00',
            'end_time' => '16:00:00',
            'venue' => 'Fellowship Hall'
        ]);

        // Create a test Sunday service for next Sunday
        $nextSunday = Carbon::now()->next(Carbon::SUNDAY);
        SundayService::create([
            'service_date' => $nextSunday->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '11:00:00',
            'venue' => 'Main Sanctuary',
            'preacher' => 'Pastor Test',
            'theme' => 'Test Theme'
        ]);

        $this->command->info('Test notification data created successfully!');
    }
}