<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Event;
use App\Models\EventBranchDetail;
use App\Models\EventBranchFeature;
use App\Models\EventBranchGallery;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Branch $branch1;
    private Branch $branch2;

    protected function setUp(): void
    {
        parent::setUp();

        // Create the event permissions
        $parent = Permission::firstOrCreate(['name' => 'events', 'guard_name' => 'web']);
        foreach (['view_events', 'create_events', 'edit_events', 'delete_events'] as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web', 'parent_id' => $parent->id]);
        }

        // Create a user and role
        $this->admin = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $role->givePermissionTo(['view_events', 'create_events', 'edit_events', 'delete_events']);
        $this->admin->assignRole($role);

        // Create dummy branches
        $this->branch1 = Branch::create([
            'title' => 'Branch One',
            'status' => 1,
        ]);
        $this->branch2 = Branch::create([
            'title' => 'Branch Two',
            'status' => 1,
        ]);
    }

    public function test_unauthenticated_user_cannot_access_events()
    {
        $response = $this->get(route('events.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_view_events_list()
    {
        $event = Event::create([
            'title' => 'Sample Event',
            'slug' => 'sample-event',
            'status' => 1,
        ]);

        $response = $this->actingAs($this->admin)->get(route('events.index'));
        $response->assertStatus(200);
        $response->assertSee('Sample Event');
    }

    public function test_admin_can_create_event_with_branch_details()
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin)->post(route('events.store'), [
            'title' => 'New Event',
            'slug' => 'new-event',
            'heading' => 'Banner Heading Text',
            'description' => 'Event description',
            'status' => 1,
            'meta_title' => 'Event Meta Title',
            'meta_description' => 'Event Meta Description',
            'meta_keywords' => 'keyword1, keyword2',
            'og_title' => 'OG Title Test',
            'og_description' => 'OG Description Test',
            'og_image' => UploadedFile::fake()->image('seo_og.jpg'),
            'twitter_title' => 'Twitter Title Test',
            'twitter_description' => 'Twitter Description Test',
            'branch_details' => [
                [
                    'branch_id' => $this->branch1->id,
                    'title' => 'Branch Detail Title',
                    'description' => 'Branch Detail description text',
                    'image' => UploadedFile::fake()->image('branch.jpg'),
                    'middle_banner' => UploadedFile::fake()->image('banner.jpg'),
                    'middle_banner_link' => 'https://example.com',
                    'features_title' => 'Features list title',
                    'features_description' => 'Features list description',
                    'gallery_title' => 'Gallery section title',
                    'gallery_description' => 'Gallery section description',
                    'status' => 1,
                    'features' => [
                        [
                            'title' => 'Feature 1',
                            'subtitle' => 'Subtitle 1',
                            'content' => 'Content 1',
                            'icon' => UploadedFile::fake()->image('icon1.jpg'),
                            'status' => 1,
                        ]
                    ],
                    'gallery' => [
                        [
                            'title' => 'Gallery Image 1',
                            'description' => 'Desc 1',
                            'image' => UploadedFile::fake()->image('gal1.jpg'),
                            'status' => 1,
                        ]
                    ]
                ]
            ]
        ]);

        $response->assertRedirect(route('events.index'));
        $this->assertDatabaseHas('events', [
            'title' => 'New Event',
            'slug' => 'new-event',
            'heading' => 'Banner Heading Text',
            'meta_title' => 'Event Meta Title',
            'meta_description' => 'Event Meta Description',
            'meta_keywords' => 'keyword1, keyword2',
            'og_title' => 'OG Title Test',
            'og_description' => 'OG Description Test',
            'twitter_title' => 'Twitter Title Test',
            'twitter_description' => 'Twitter Description Test',
        ]);

        $event = Event::where('slug', 'new-event')->first();
        $this->assertNotNull($event->og_image);
        $this->assertStringStartsWith('storage/uploads/seo/', $event->og_image);

        $this->assertDatabaseHas('event_branch_details', [
            'branch_id' => $this->branch1->id,
            'title' => 'Branch Detail Title',
            'features_title' => 'Features list title',
            'middle_banner_link' => 'https://example.com',
        ]);

        $this->assertDatabaseHas('event_branch_features', [
            'title' => 'Feature 1',
        ]);

        $this->assertDatabaseHas('event_branch_galleries', [
            'title' => 'Gallery Image 1',
        ]);
    }

    public function test_admin_can_update_event_retaining_old_images()
    {
        Storage::fake('public');

        // First, create an event with branch details, features, galleries, etc.
        $event = Event::create([
            'title' => 'Update Test Event',
            'slug' => 'update-test-event',
            'heading' => 'Old Heading',
            'meta_title' => 'Old Meta Title',
            'og_image' => 'storage/uploads/seo/old_og.jpg',
            'status' => 1,
        ]);

        $detail = EventBranchDetail::create([
            'event_id' => $event->id,
            'branch_id' => $this->branch1->id,
            'title' => 'Old Detail Title',
            'image' => 'storage/uploads/events/branch-details/old_branch.jpg',
            'middle_banner' => 'storage/uploads/events/middle-banner/old_banner.jpg',
            'middle_banner_link' => 'https://old.com',
            'status' => 1,
        ]);

        $feature = EventBranchFeature::create([
            'event_branch_detail_id' => $detail->id,
            'title' => 'Old Feature',
            'icon' => 'storage/uploads/events/features/old_icon.jpg',
            'status' => 1,
        ]);

        $gallery = EventBranchGallery::create([
            'event_branch_detail_id' => $detail->id,
            'title' => 'Old Gallery Image',
            'image' => 'storage/uploads/events/gallery/old_gal.jpg',
            'status' => 1,
        ]);

        // Submit update request without uploading new files, but providing the old path values in hidden fields.
        // We will update meta_title and set remove_og_image = 1 to verify SEO deletion.
        $response = $this->actingAs($this->admin)->put(route('events.update', $event), [
            'title' => 'Updated Event Title',
            'slug' => 'update-test-event',
            'heading' => 'New Heading',
            'status' => 1,
            'meta_title' => 'New Meta Title',
            'remove_og_image' => 1,
            'branch_details' => [
                [
                    'branch_id' => $this->branch1->id,
                    'title' => 'Updated Detail Title',
                    'old_image' => 'storage/uploads/events/branch-details/old_branch.jpg',
                    'old_middle_banner' => 'storage/uploads/events/middle-banner/old_banner.jpg',
                    'middle_banner_link' => 'https://new.com',
                    'status' => 1,
                    'features' => [
                        [
                            'title' => 'Updated Feature',
                            'old_icon' => 'storage/uploads/events/features/old_icon.jpg',
                            'status' => 1,
                        ]
                    ],
                    'gallery' => [
                        [
                            'title' => 'Updated Gallery Image',
                            'old_image' => 'storage/uploads/events/gallery/old_gal.jpg',
                            'status' => 1,
                        ]
                    ]
                ]
            ]
        ]);

        $response->assertRedirect(route('events.index'));

        // Assert database is updated for titles and SEO fields
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Updated Event Title',
            'heading' => 'New Heading',
            'meta_title' => 'New Meta Title',
            'og_image' => null,
        ]);

        // Verify the old images were persisted successfully (not reset to null)
        $this->assertDatabaseHas('event_branch_details', [
            'event_id' => $event->id,
            'title' => 'Updated Detail Title',
            'image' => 'storage/uploads/events/branch-details/old_branch.jpg',
            'middle_banner' => 'storage/uploads/events/middle-banner/old_banner.jpg',
            'middle_banner_link' => 'https://new.com',
        ]);

        // Get the new detail id (since it uses delete and recreate strategy)
        $newDetail = EventBranchDetail::where('event_id', $event->id)->first();

        $this->assertDatabaseHas('event_branch_features', [
            'event_branch_detail_id' => $newDetail->id,
            'title' => 'Updated Feature',
            'icon' => 'storage/uploads/events/features/old_icon.jpg',
        ]);

        $this->assertDatabaseHas('event_branch_galleries', [
            'event_branch_detail_id' => $newDetail->id,
            'title' => 'Updated Gallery Image',
            'image' => 'storage/uploads/events/gallery/old_gal.jpg',
        ]);
    }

    public function test_event_api_filtering_by_branch()
    {
        $event = Event::create([
            'title' => 'API Filter Event',
            'slug' => 'api-filter-event',
            'meta_title' => 'API Meta Title',
            'meta_description' => 'API Meta Description',
            'og_image' => 'storage/uploads/seo/api_og.jpg',
            'status' => 1,
        ]);

        // Branch 1 details
        EventBranchDetail::create([
            'event_id' => $event->id,
            'branch_id' => $this->branch1->id,
            'title' => 'Branch 1 Detail Title',
            'status' => 1,
        ]);

        // Branch 2 details
        EventBranchDetail::create([
            'event_id' => $event->id,
            'branch_id' => $this->branch2->id,
            'title' => 'Branch 2 Detail Title',
            'status' => 1,
        ]);

        // Call show endpoint with slug only and assert SEO fields are present
        $response = $this->getJson('/api/event-details?slug=api-filter-event');
        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.meta_title', 'API Meta Title')
            ->assertJsonPath('data.meta_description', 'API Meta Description')
            ->assertJsonPath('data.og_image', asset('storage/uploads/seo/api_og.jpg'))
            ->assertJsonCount(2, 'data.branch_details');

        // Call show endpoint with branch_id = branch 1
        $response1 = $this->getJson('/api/event-details?slug=api-filter-event&branch_id=' . $this->branch1->id);
        $response1->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data.branch_details')
            ->assertJsonPath('data.branch_details.0.branch_id', $this->branch1->id);

        // Call show endpoint with branch_id = branch 2
        $response2 = $this->getJson('/api/event-details?slug=api-filter-event&branch_id=' . $this->branch2->id);
        $response2->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data.branch_details')
            ->assertJsonPath('data.branch_details.0.branch_id', $this->branch2->id);
    }
}
