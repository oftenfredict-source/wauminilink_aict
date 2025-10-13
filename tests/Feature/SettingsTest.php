<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\SystemSetting;
use App\Services\SettingsService;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_access_settings_page()
    {
        $response = $this->get('/settings');
        $response->assertStatus(200);
        $response->assertSee('System Settings');
    }

    /** @test */
    public function can_get_setting_value()
    {
        // Create a test setting
        SystemSetting::create([
            'key' => 'test_setting',
            'value' => 'test_value',
            'type' => 'string',
            'category' => 'general',
            'group' => 'basic',
            'description' => 'Test setting',
            'is_editable' => true,
            'is_public' => false,
        ]);

        $value = SettingsService::get('test_setting');
        $this->assertEquals('test_value', $value);
    }

    /** @test */
    public function can_set_setting_value()
    {
        // Create a test setting
        $setting = SystemSetting::create([
            'key' => 'test_setting',
            'value' => 'old_value',
            'type' => 'string',
            'category' => 'general',
            'group' => 'basic',
            'description' => 'Test setting',
            'is_editable' => true,
            'is_public' => false,
        ]);

        SettingsService::set('test_setting', 'new_value');
        
        $setting->refresh();
        $this->assertEquals('new_value', $setting->value);
    }

    /** @test */
    public function can_get_church_info()
    {
        $churchInfo = SettingsService::getChurchInfo();
        
        $this->assertArrayHasKey('name', $churchInfo);
        $this->assertArrayHasKey('address', $churchInfo);
        $this->assertArrayHasKey('phone', $churchInfo);
        $this->assertArrayHasKey('email', $churchInfo);
    }

    /** @test */
    public function can_get_membership_settings()
    {
        $membershipSettings = SettingsService::getMembershipSettings();
        
        $this->assertArrayHasKey('child_max_age', $membershipSettings);
        $this->assertArrayHasKey('age_reference', $membershipSettings);
        $this->assertArrayHasKey('auto_generate_member_id', $membershipSettings);
    }

    /** @test */
    public function can_get_finance_settings()
    {
        $financeSettings = SettingsService::getFinanceSettings();
        
        $this->assertArrayHasKey('enable_tithes', $financeSettings);
        $this->assertArrayHasKey('enable_offerings', $financeSettings);
        $this->assertArrayHasKey('expense_approval_threshold', $financeSettings);
    }

    /** @test */
    public function can_check_feature_enabled()
    {
        // Test with default value
        $this->assertTrue(SettingsService::isFeatureEnabled('enable_tithes'));
        $this->assertFalse(SettingsService::isFeatureEnabled('enable_sms_notifications'));
    }

    /** @test */
    public function can_format_currency()
    {
        $formatted = SettingsService::formatCurrency(150000);
        $this->assertStringContains('150,000', $formatted);
    }

    /** @test */
    public function can_update_settings_via_form()
    {
        $response = $this->post('/settings', [
            'church_name' => 'Test Church',
            'child_max_age' => 20,
        ]);

        $response->assertRedirect('/settings');
        $response->assertSessionHas('success');
        
        $this->assertEquals('Test Church', SettingsService::get('church_name'));
        $this->assertEquals(20, SettingsService::get('child_max_age'));
    }

    /** @test */
    public function can_export_settings()
    {
        $response = $this->get('/settings/export');
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
    }

    /** @test */
    public function can_reset_settings()
    {
        // Set a custom value
        SettingsService::set('church_name', 'Custom Church');
        
        // Reset settings
        $response = $this->post('/settings/reset');
        
        $response->assertRedirect('/settings');
        $response->assertSessionHas('success');
        
        // Should be back to default
        $this->assertEquals('Waumini Church', SettingsService::get('church_name'));
    }
}