<?php

namespace Tests\Feature;

use App\Models\Cake;
use App\Models\CakeEnquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\CakeEnquiry as CakeEnquiryMail;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CakeEnquiryTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Cake $cake;

    protected function setUp(): void
    {
        parent::setUp();

        // Create the cake enquiry permissions
        $parent = Permission::firstOrCreate(['name' => 'cake_enquiries', 'guard_name' => 'web']);
        foreach (['view_cake_enquiries', 'delete_cake_enquiries'] as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web', 'parent_id' => $parent->id]);
        }

        // Create a user and role
        $this->admin = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $role->givePermissionTo(['view_cake_enquiries', 'delete_cake_enquiries']);
        $this->admin->assignRole($role);

        // Create a dummy cake
        $this->cake = Cake::create([
            'title' => 'Chocolate Cake',
            'product_code' => 'CHOC-01',
            'price' => 150.00,
            'status' => true,
        ]);

        // Seed site setting for email recipient
        \App\Models\SiteSetting::create([
            'group' => 'contact',
            'key' => 'enquiry_email',
            'value' => 'admin@example.com',
            'type' => 'text',
        ]);
    }

    public function test_submit_cake_enquiry_successfully()
    {
        Mail::fake();

        $response = $this->postJson('/api/cake-enquiry', [
            'cake_id' => $this->cake->id,
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '9876543210',
            'preferred_date' => '2026-06-25',
            'message' => 'Please make it extra chocolatey!',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Your cake enquiry has been successfully submitted.');

        $this->assertDatabaseHas('cake_enquiries', [
            'cake_id' => $this->cake->id,
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'status' => 'unread',
        ]);

        Mail::assertQueued(CakeEnquiryMail::class);
    }

    public function test_submit_cake_enquiry_validation_errors()
    {
        Mail::fake();

        $response = $this->postJson('/api/cake-enquiry', [
            'name' => '',
            'email' => 'not-an-email',
            'phone' => '',
            'message' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonStructure(['errors' => ['name', 'email', 'phone', 'message']]);

        Mail::assertNotQueued(CakeEnquiryMail::class);
    }

    public function test_unauthenticated_user_cannot_access_admin_cake_enquiries()
    {
        $response = $this->get(route('cake-enquiries.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_view_cake_enquiries_list()
    {
        $enquiry = CakeEnquiry::create([
            'cake_id' => $this->cake->id,
            'name' => 'John Listing Test',
            'email' => 'johnlist@example.com',
            'phone' => '1234567890',
            'message' => 'Testing listing views',
            'status' => 'unread',
        ]);

        $response = $this->actingAs($this->admin)->get(route('cake-enquiries.index'));
        $response->assertStatus(200);
        $response->assertSee('John Listing Test');
    }

    public function test_admin_can_show_single_cake_enquiry()
    {
        $enquiry = CakeEnquiry::create([
            'cake_id' => $this->cake->id,
            'name' => 'John Detail Test',
            'email' => 'johndetail@example.com',
            'phone' => '1234567890',
            'message' => 'Testing detail view',
            'status' => 'unread',
        ]);

        $response = $this->actingAs($this->admin)->get(route('cake-enquiries.show', $enquiry));
        $response->assertStatus(200);
        $response->assertSee('John Detail Test');

        // Check if status got marked as read after viewing
        $this->assertDatabaseHas('cake_enquiries', [
            'id' => $enquiry->id,
            'status' => 'read',
        ]);
    }

    public function test_admin_can_delete_cake_enquiry()
    {
        $enquiry = CakeEnquiry::create([
            'cake_id' => $this->cake->id,
            'name' => 'Delete Test User',
            'email' => 'delete@example.com',
            'phone' => '1234567890',
            'message' => 'Testing deletion',
            'status' => 'unread',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('cake-enquiries.destroy', $enquiry));
        $response->assertRedirect(route('cake-enquiries.index'));

        $this->assertDatabaseMissing('cake_enquiries', [
            'id' => $enquiry->id,
        ]);
    }
}
