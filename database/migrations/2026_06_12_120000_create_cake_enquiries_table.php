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
        Schema::create('cake_enquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cake_id')
                  ->nullable()
                  ->constrained('cakes')
                  ->onDelete('set null');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->date('preferred_date')->nullable();
            $table->text('message');
            $table->string('status')->default('unread'); // unread, read
            $table->timestamps();
        });

        $parent = Permission::firstOrCreate(
            ['name' => 'cake_enquiries', 'guard_name' => 'web'],
            ['parent_id' => null]
        );

        $permissions = [
            'view_cake_enquiries',
            'delete_cake_enquiries'
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
        Schema::dropIfExists('cake_enquiries');

        Permission::whereIn('name', [
            'view_cake_enquiries',
            'delete_cake_enquiries'
        ])->delete();

        Permission::where('name', 'cake_enquiries')->delete();
    }
};
