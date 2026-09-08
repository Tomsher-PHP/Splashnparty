<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GeneralSettingTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $parent = Permission::firstOrCreate(['name' => 'general_settings', 'guard_name' => 'web']);
        foreach (['view_general_settings', 'edit_general_settings'] as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web', 'parent_id' => $parent->id]);
        }

        $this->admin = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $role->givePermissionTo(['view_general_settings', 'edit_general_settings']);
        $this->admin->assignRole($role);
    }

    public function test_unauthenticated_user_cannot_access_general_settings()
    {
        $response = $this->get(route('general-settings.edit'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_view_report_notification_email_settings()
    {
        $response = $this->actingAs($this->admin)->get(route('general-settings.edit'));

        $response->assertStatus(200);
        $response->assertSee('Report Notification Email Settings');
        $response->assertSee('report_notification_email');
        $response->assertSee('report_cc_emails');
    }

    public function test_admin_can_update_report_notification_email_settings()
    {
        $payload = [
            'site_name' => 'Splash n Party',
            'report_notification_email' => 'reports@splashnparty.com',
            'report_cc_emails' => 'management@splashnparty.com, audit@splashnparty.com',
        ];

        $response = $this->actingAs($this->admin)->put(route('general-settings.update'), $payload);

        $response->assertRedirect(route('general-settings.edit'));
        $response->assertSessionHas('success', 'General settings updated');

        $this->assertDatabaseHas('site_settings', [
            'key' => 'report_notification_email',
            'value' => 'reports@splashnparty.com',
        ]);

        $this->assertDatabaseHas('site_settings', [
            'key' => 'report_cc_emails',
            'value' => 'management@splashnparty.com, audit@splashnparty.com',
        ]);

        $ccEmails = SiteSetting::getCcEmailsByKey('report_cc_emails');
        $this->assertEquals(['management@splashnparty.com', 'audit@splashnparty.com'], $ccEmails);
    }

    public function test_public_settings_api_does_not_expose_report_emails()
    {
        SiteSetting::create([
            'group' => 'report_email_settings',
            'key' => 'report_notification_email',
            'value' => 'secret_reports@splashnparty.com',
            'type' => 'email',
        ]);

        SiteSetting::create([
            'group' => 'report_email_settings',
            'key' => 'report_cc_emails',
            'value' => 'secret_cc@splashnparty.com',
            'type' => 'text',
        ]);

        $response = $this->getJson('/api/settings');

        $response->assertStatus(200);
        $response->assertJsonMissing(['report_notification_email' => 'secret_reports@splashnparty.com']);
        $response->assertJsonMissing(['report_cc_emails' => 'secret_cc@splashnparty.com']);
    }
}
