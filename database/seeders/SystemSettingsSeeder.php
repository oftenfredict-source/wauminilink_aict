<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = config('settings.defaults');
        
        foreach ($defaults as $key => $config) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $config['value'],
                    'type' => $config['type'],
                    'category' => $config['category'],
                    'group' => $config['group'],
                    'description' => $config['description'],
                    'is_editable' => true,
                    'is_public' => false,
                    'validation_rules' => $config['validation_rules'] ?? null,
                    'options' => $config['options'] ?? null,
                    'sort_order' => 0
                ]
            );
        }
        
        $this->command->info('System settings seeded successfully.');
    }
}