<?php

namespace Tests\Feature;

use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TestimonialTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create the testimonial permissions
        $parent = Permission::firstOrCreate(['name' => 'testimonials', 'guard_name' => 'web']);
        foreach (['view_testimonials', 'create_testimonials', 'edit_testimonials', 'delete_testimonials'] as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web', 'parent_id' => $parent->id]);
        }

        // Create a user and role
        $this->admin = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $role->givePermissionTo(['view_testimonials', 'create_testimonials', 'edit_testimonials', 'delete_testimonials']);
        $this->admin->assignRole($role);
    }

    public function test_unauthenticated_user_cannot_access_testimonials()
    {
        $response = $this->get(route('testimonials.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_view_testimonials_list()
    {
        Testimonial::create([
            'name' => 'John Doe',
            'title' => 'CEO',
            'star_rating' => 5,
            'description' => 'Excellent service!',
            'sort_order' => 1,
            'status' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('testimonials.index'));
        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertSee('Excellent service!');
    }

    public function test_admin_can_create_testimonial()
    {
        $response = $this->actingAs($this->admin)->post(route('testimonials.store'), [
            'name' => 'Jane Smith',
            'title' => 'Manager',
            'star_rating' => 4,
            'description' => 'Very satisfied with the party splash.',
            'sort_order' => 2,
            'status' => 1,
        ]);

        $response->assertRedirect(route('testimonials.index'));
        $this->assertDatabaseHas('testimonials', [
            'name' => 'Jane Smith',
            'star_rating' => 4,
        ]);
    }

    public function test_create_validation_star_rating_range()
    {
        // Test star rating under 1
        $response = $this->actingAs($this->admin)->post(route('testimonials.store'), [
            'name' => 'Jane Smith',
            'star_rating' => 0,
            'description' => 'Very satisfied.',
            'sort_order' => 2,
            'status' => 1,
        ]);
        $response->assertSessionHasErrors(['star_rating']);

        // Test star rating over 5
        $response2 = $this->actingAs($this->admin)->post(route('testimonials.store'), [
            'name' => 'Jane Smith',
            'star_rating' => 6,
            'description' => 'Very satisfied.',
            'sort_order' => 2,
            'status' => 1,
        ]);
        $response2->assertSessionHasErrors(['star_rating']);
    }

    public function test_admin_can_update_testimonial()
    {
        $testimonial = Testimonial::create([
            'name' => 'Old Name',
            'title' => 'Old Title',
            'star_rating' => 3,
            'description' => 'Old Description',
            'sort_order' => 5,
            'status' => true,
        ]);

        $response = $this->actingAs($this->admin)->put(route('testimonials.update', $testimonial), [
            'name' => 'New Name',
            'title' => 'New Title',
            'star_rating' => 5,
            'description' => 'New Description',
            'sort_order' => 1,
            'status' => 0,
        ]);

        $response->assertRedirect(route('testimonials.index'));
        $this->assertDatabaseHas('testimonials', [
            'id' => $testimonial->id,
            'name' => 'New Name',
            'status' => false,
            'star_rating' => 5,
        ]);
    }

    public function test_admin_can_toggle_status()
    {
        $testimonial = Testimonial::create([
            'name' => 'John Doe',
            'star_rating' => 5,
            'description' => 'Awesome!',
            'sort_order' => 1,
            'status' => true,
        ]);

        $response = $this->actingAs($this->admin)->patch(route('testimonials.update-status', $testimonial), [
            'status' => 0,
        ]);

        $response->assertRedirect(route('testimonials.index'));
        $this->assertDatabaseHas('testimonials', [
            'id' => $testimonial->id,
            'status' => false,
        ]);
    }

    public function test_admin_can_delete_testimonial()
    {
        $testimonial = Testimonial::create([
            'name' => 'To Be Deleted',
            'star_rating' => 2,
            'description' => 'Bad experience.',
            'sort_order' => 10,
            'status' => true,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('testimonials.destroy', $testimonial));
        $response->assertRedirect();
        $this->assertDatabaseMissing('testimonials', [
            'id' => $testimonial->id,
        ]);
    }
}
