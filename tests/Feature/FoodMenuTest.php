<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\FoodMenu;
use App\Models\FoodMenuCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class FoodMenuTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Branch $branch1;
    private Branch $branch2;
    private FoodMenuCategory $category1;
    private FoodMenuCategory $category2;

    protected function setUp(): void
    {
        parent::setUp();

        // Create the food menu permissions
        $parent = Permission::firstOrCreate(['name' => 'food_menus', 'guard_name' => 'web']);
        foreach (['view_food_menus', 'create_food_menus', 'edit_food_menus', 'delete_food_menus'] as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web', 'parent_id' => $parent->id]);
        }

        // Create admin user and role
        $this->admin = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $role->givePermissionTo(['view_food_menus', 'create_food_menus', 'edit_food_menus', 'delete_food_menus']);
        $this->admin->assignRole($role);

        // Create dummy branches
        $this->branch1 = Branch::create(['title' => 'Al Barsha Branch', 'status' => 1]);
        $this->branch2 = Branch::create(['title' => 'Jumeirah Branch', 'status' => 1]);

        // Create dummy categories
        $this->category1 = FoodMenuCategory::create(['title' => 'Starters', 'status' => 1]);
        $this->category2 = FoodMenuCategory::create(['title' => 'Main Course', 'status' => 1]);
    }

    public function test_admin_can_view_food_menus_list_without_filters()
    {
        FoodMenu::create([
            'title' => 'Spring Rolls',
            'branch_ids' => [$this->branch1->id],
            'type' => 'adult',
            'food_type' => 'veg',
            'price' => '25.00',
            'status' => 1,
            'food_menu_category_id' => $this->category1->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('food-menus.index'));
        $response->assertStatus(200);
        $response->assertSee('Spring Rolls');
    }

    public function test_admin_can_filter_food_menus_by_category()
    {
        // Menu 1 in Category 1
        $menu1 = FoodMenu::create([
            'title' => 'Spring Rolls',
            'branch_ids' => [$this->branch1->id],
            'type' => 'adult',
            'food_type' => 'veg',
            'price' => '25.00',
            'status' => 1,
            'food_menu_category_id' => $this->category1->id,
        ]);

        // Menu 2 in Category 2
        $menu2 = FoodMenu::create([
            'title' => 'Grilled Chicken',
            'branch_ids' => [$this->branch1->id],
            'type' => 'adult',
            'food_type' => 'non-veg',
            'price' => '45.00',
            'status' => 1,
            'food_menu_category_id' => $this->category2->id,
        ]);

        // Filter by Category 1 (Starters)
        $response1 = $this->actingAs($this->admin)->get(route('food-menus.index', ['category' => $this->category1->id]));
        $response1->assertStatus(200);
        $response1->assertSee('Spring Rolls');
        $response1->assertDontSee('Grilled Chicken');

        // Filter by Category 2 (Main Course)
        $response2 = $this->actingAs($this->admin)->get(route('food-menus.index', ['category' => $this->category2->id]));
        $response2->assertStatus(200);
        $response2->assertSee('Grilled Chicken');
        $response2->assertDontSee('Spring Rolls');
    }

    public function test_admin_can_filter_food_menus_by_location_branch()
    {
        // Menu 1 in Branch 1 only
        $menu1 = FoodMenu::create([
            'title' => 'Spring Rolls',
            'branch_ids' => [$this->branch1->id],
            'type' => 'adult',
            'food_type' => 'veg',
            'price' => '25.00',
            'status' => 1,
            'food_menu_category_id' => $this->category1->id,
        ]);

        // Menu 2 in Branch 2 only
        $menu2 = FoodMenu::create([
            'title' => 'Jumeirah Burger',
            'branch_ids' => [$this->branch2->id],
            'type' => 'adult',
            'food_type' => 'non-veg',
            'price' => '35.00',
            'status' => 1,
            'food_menu_category_id' => $this->category2->id,
        ]);

        // Filter by Location 1 (Al Barsha Branch)
        $response1 = $this->actingAs($this->admin)->get(route('food-menus.index', ['branch' => $this->branch1->id]));
        $response1->assertStatus(200);
        $response1->assertSee('Spring Rolls');
        $response1->assertDontSee('Jumeirah Burger');

        // Filter by Location 2 (Jumeirah Branch)
        $response2 = $this->actingAs($this->admin)->get(route('food-menus.index', ['branch' => $this->branch2->id]));
        $response2->assertStatus(200);
        $response2->assertSee('Jumeirah Burger');
        $response2->assertDontSee('Spring Rolls');
    }
}
