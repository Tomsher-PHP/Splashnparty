<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    private function authorizeRolePermission(string $permission): void
    {
        abort_unless(auth()->user()?->can($permission), 403);
    }

    public function index()
    {
        $this->authorizeRolePermission('roles.view');

        $query = Role::withCount('permissions')->latest();

        if ($search = request('search')) {
            $query->where(function ($roleQuery) use ($search) {
                $roleQuery->where('name', 'like', '%'.$search.'%')
                    ->orWhere('guard_name', 'like', '%'.$search.'%');
            });
        }

        if ($status = request('status')) {
            if (in_array($status, ['active', 'inactive'], true)) {
                $query->where('is_active', $status === 'active');
            }
        }

        $roles = $query->paginate(10)->withQueryString();

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $this->authorizeRolePermission('roles.create');

        $permissions = Permission::whereNull('parent_id')->with('children')->get();

        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $this->authorizeRolePermission('roles.create');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')],
            'is_active' => ['required', 'boolean'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::exists('permissions', 'name')],
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
            'is_active' => $validated['is_active'],
        ]);

        if (! empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()->route('roles.index')->with('success', 'Role created');
    }

    public function edit(Role $role)
    {
        $this->authorizeRolePermission('roles.edit');

        $permissions = Permission::whereNull('parent_id')->with('children')->get();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $this->authorizeRolePermission('roles.edit');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($role->id)],
            'is_active' => ['required', 'boolean'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::exists('permissions', 'name')],
        ]);

        $role->update([
            'name' => $validated['name'],
            'is_active' => $validated['is_active'],
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('roles.index')->with('success', 'Role updated');
    }

    public function updateStatus(Request $request, Role $role)
    {
        $this->authorizeRolePermission('roles.edit');

        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $role->update([
            'is_active' => $validated['is_active'],
        ]);

        return redirect()->route('roles.index', $request->only(['search', 'status', 'page']))
            ->with('success', 'Role status updated');
    }

    public function destroy(Role $role)
    {
        $this->authorizeRolePermission('roles.delete');

        $role->delete();

        return back()->with('success', 'Deleted');
    }
}
