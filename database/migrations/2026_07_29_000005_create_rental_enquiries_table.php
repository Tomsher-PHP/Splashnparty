<?php

use App\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rental_enquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')
                  ->nullable()
                  ->constrained('rental_items')
                  ->onDelete('set null');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->text('message');
            $table->string('status')->default('unread'); // unread, read
            $table->timestamps();
        });

        $parent = Permission::firstOrCreate(
            ['name' => 'rental_enquiries', 'guard_name' => 'web'],
            ['parent_id' => null]
        );

        $permissions = [
            'view_rental_enquiries',
            'delete_rental_enquiries'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['parent_id' => $parent->id]
            );
        }

        // Assign to all existing roles
        try {
            $roles = Role::all();
            foreach ($roles as $role) {
                $role->givePermissionTo($permissions);
            }
        } catch (\Throwable $e) {
            // Silence if Spatie tables are not loaded yet or roles do not exist
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_enquiries');

        Permission::whereIn('name', [
            'view_rental_enquiries',
            'delete_rental_enquiries'
        ])->delete();

        Permission::where('name', 'rental_enquiries')->delete();
    }
};
