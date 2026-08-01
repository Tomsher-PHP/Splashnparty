<?php

namespace Tests\Feature;

use App\Models\RentalCategory;
use App\Models\RentalItem;
use App\Models\RentalEnquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\RentalEnquiryMail;
use App\Mail\RentalEnquiryThankYouMail;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RentalEnquiryTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private RentalItem $rentalItem;

    protected function setUp(): void
    {
        parent::setUp();

        // Create the rental enquiry permissions
        $parent = Permission::firstOrCreate(['name' => 'rental_enquiries', 'guard_name' => 'web']);
        foreach (['view_rental_enquiries', 'delete_rental_enquiries'] as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web', 'parent_id' => $parent->id]);
        }

        // Create a user and role
        $this->admin = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $role->givePermissionTo(['view_rental_enquiries', 'delete_rental_enquiries']);
        $this->admin->assignRole($role);

        // Create rental category
        $category = RentalCategory::create([
            'title' => 'Inflatables',
            'slug' => 'inflatables',
            'status' => true,
        ]);

        // Create rental item
        $this->rentalItem = RentalItem::create([
            'rental_category_id' => $category->id,
            'title' => 'Giant Slide',
            'price' => 250.00,
            'description' => 'A very large inflatable slide.',
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

    public function test_submit_rental_enquiry_successfully()
    {
        Mail::fake();

        $response = $this->postJson('/api/rental-enquiry', [
            'rental_id' => $this->rentalItem->id,
            'name' => 'John Rental',
            'email' => 'johnrental@example.com',
            'phone' => '1234567890',
            'message' => 'I would like to rent this slide.',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Your rental enquiry has been successfully submitted.');

        $this->assertDatabaseHas('rental_enquiries', [
            'rental_id' => $this->rentalItem->id,
            'name' => 'John Rental',
            'email' => 'johnrental@example.com',
            'status' => 'unread',
        ]);

        Mail::assertQueued(RentalEnquiryMail::class);
    }

    public function test_submit_rental_enquiry_validation_errors()
    {
        Mail::fake();

        $response = $this->postJson('/api/rental-enquiry', [
            'name' => '',
            'email' => 'not-an-email',
            'phone' => '',
            'message' => '',
        ]);

        // Since validation failures in RentalApiController return HTTP 200 with success => false,
        // we assert status 200 but check success false and errors presence
        $response->assertStatus(200)
            ->assertJsonPath('success', false)
            ->assertJsonStructure(['errors' => ['name', 'email', 'phone', 'message']]);

        Mail::assertNotQueued(RentalEnquiryMail::class);
    }

    public function test_unauthenticated_user_cannot_access_admin_rental_enquiries()
    {
        $response = $this->get(route('rental-enquiries.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_view_rental_enquiries_list()
    {
        $enquiry = RentalEnquiry::create([
            'rental_id' => $this->rentalItem->id,
            'name' => 'Listing Test Customer',
            'email' => 'listing@example.com',
            'phone' => '9876543210',
            'message' => 'Listing test message',
            'status' => 'unread',
        ]);

        $response = $this->actingAs($this->admin)->get(route('rental-enquiries.index'));
        $response->assertStatus(200);
        $response->assertSee('Listing Test Customer');
    }

    public function test_admin_can_show_single_rental_enquiry()
    {
        $enquiry = RentalEnquiry::create([
            'rental_id' => $this->rentalItem->id,
            'name' => 'Detail Test Customer',
            'email' => 'detail@example.com',
            'phone' => '9876543210',
            'message' => 'Detail test message',
            'status' => 'unread',
        ]);

        $response = $this->actingAs($this->admin)->get(route('rental-enquiries.show', $enquiry));
        $response->assertStatus(200);
        $response->assertSee('Detail Test Customer');

        // Check if status was automatically marked as read after viewing
        $this->assertDatabaseHas('rental_enquiries', [
            'id' => $enquiry->id,
            'status' => 'read',
        ]);
    }

    public function test_admin_can_filter_rental_enquiries_by_date_range()
    {
        $enquiry1 = RentalEnquiry::create([
            'rental_id' => $this->rentalItem->id,
            'name' => 'Old Rental Enquiry',
            'email' => 'old@example.com',
            'phone' => '1234567890',
            'message' => 'Old message',
            'status' => 'unread',
        ]);
        $enquiry1->created_at = '2026-07-01 10:00:00';
        $enquiry1->save();

        $enquiry2 = RentalEnquiry::create([
            'rental_id' => $this->rentalItem->id,
            'name' => 'New Rental Enquiry',
            'email' => 'new@example.com',
            'phone' => '1234567890',
            'message' => 'New message',
            'status' => 'unread',
        ]);
        $enquiry2->created_at = '2026-07-15 10:00:00';
        $enquiry2->save();

        $response = $this->actingAs($this->admin)->get(route('rental-enquiries.index', [
            'date_range' => '2026-07-10 to 2026-07-20',
        ]));

        $response->assertStatus(200);
        $response->assertSee('New Rental Enquiry');
        $response->assertDontSee('Old Rental Enquiry');
    }

    public function test_admin_can_delete_rental_enquiry()
    {
        $enquiry = RentalEnquiry::create([
            'rental_id' => $this->rentalItem->id,
            'name' => 'Delete Test Customer',
            'email' => 'delete@example.com',
            'phone' => '9876543210',
            'message' => 'Delete test message',
            'status' => 'unread',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('rental-enquiries.destroy', $enquiry));
        $response->assertRedirect(route('rental-enquiries.index'));

        $this->assertDatabaseMissing('rental_enquiries', [
            'id' => $enquiry->id,
        ]);
    }

    public function test_get_active_rental_categories()
    {
        $response = $this->getJson('/api/rentals');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Rental categories found.')
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'slug',
                        'sort_order',
                        'status',
                    ]
                ],
                'page_content'
            ]);
    }

    public function test_get_rental_items_by_category_paginated()
    {
        $response = $this->getJson('/api/rental-items?category_id=' . $this->rentalItem->rental_category_id);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Rental items found.')
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'current_page',
                    'data' => [
                        '*' => [
                            'id',
                            'rental_category_id',
                            'image',
                            'title',
                            'price',
                            'description',
                            'sort_order',
                            'status',
                        ]
                    ],
                    'total',
                ]
            ]);
    }

    public function test_get_all_rental_items_without_category()
    {
        $response = $this->getJson('/api/rental-items');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Rental items found.')
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'current_page',
                    'data' => [
                        '*' => [
                            'id',
                            'rental_category_id',
                            'image',
                            'title',
                            'price',
                            'description',
                            'sort_order',
                            'status',
                        ]
                    ],
                    'total',
                ]
            ]);
    }

    public function test_get_rental_items_invalid_category_validation()
    {
        $response = $this->getJson('/api/rental-items?category_id=99999');

        $response->assertStatus(200)
            ->assertJsonPath('success', false)
            ->assertJsonStructure(['errors' => ['category_id']]);
    }
}
