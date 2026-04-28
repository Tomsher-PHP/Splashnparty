<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    private function authorizeStaffPermission(string $permission): void
    {
        abort_unless(auth()->user()?->can($permission), 403);
    }

    public function index()
    {
        $this->authorizeStaffPermission('view_staff');

        $query = User::with('roles')->where('user_type', 'staff')->latest();

        if ($search = request('search')) {
            $query->where(function ($staffQuery) use ($search) {
                $staffQuery->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%')
                    ->orWhereHas('roles', function ($roleQuery) use ($search) {
                        $roleQuery->where('name', 'like', '%'.$search.'%');
                    });
            });
        }

        if ($role = request('role')) {
            $query->role($role);
        }

        if ($status = request('status')) {
            if (in_array($status, ['active', 'inactive'], true)) {
                $query->where('is_active', $status === 'active');
            }
        }

        $staffs = $query->paginate(15)->withQueryString();
        $roles = Role::orderBy('name')->get();

        return view('staffs.index', compact('staffs', 'roles'));
    }

    public function create()
    {
        $this->authorizeStaffPermission('create_staff');

        $roles = Role::where('is_active', true)->orderBy('name')->get();

        return view('staffs.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->authorizeStaffPermission('create_staff');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'role_id' => ['required', Rule::exists('roles', 'id')],
        ]);

        $imagePath = $request->file('image')?->store('staffs', 'public');

        $staff = User::create([
            'name' => $validated['name'],
            'user_type' => 'staff',
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => $validated['password'],
            'image' => $imagePath,
        ]);

        $staff->syncRoles([Role::findById($validated['role_id'])->name]);

        return redirect()->route('staffs.index')->with('success', 'Staff created');
    }

    public function edit(User $staff)
    {
        $this->authorizeStaffPermission('edit_staff');
        $this->ensureStaffUser($staff);

        $roles = Role::where('is_active', true)
            ->orWhereHas('users', function ($query) use ($staff) {
                $query->where('users.id', $staff->id);
            })
            ->orderBy('name')
            ->get();

        $staffRoleId = $staff->roles->first()?->id;

        return view('staffs.edit', compact('staff', 'roles', 'staffRoleId'));
    }

    public function update(Request $request, User $staff)
    {
        $this->authorizeStaffPermission('edit_staff');
        $this->ensureStaffUser($staff);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($staff->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'string', 'min:8'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'role_id' => ['required', Rule::exists('roles', 'id')],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ];

        if (! empty($validated['password'])) {
            $data['password'] = $validated['password'];
        }

        if ($request->hasFile('image')) {
            if ($staff->image) {
                Storage::disk('public')->delete($staff->image);
            }

            $data['image'] = $request->file('image')->store('staffs', 'public');
        }

        $staff->update($data);
        $staff->syncRoles([Role::findById($validated['role_id'])->name]);

        return redirect()->route('staffs.index')->with('success', 'Staff updated');
    }

    public function updateStatus(Request $request, User $staff)
    {
        $this->authorizeStaffPermission('edit_staff');
        $this->ensureStaffUser($staff);

        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $staff->update([
            'is_active' => $validated['is_active'],
        ]);

        return redirect()->route('staffs.index', $request->only(['search', 'role', 'status', 'page']))
            ->with('success', 'Staff status updated');
    }

    public function destroy(User $staff)
    {
        $this->authorizeStaffPermission('edit_staff');
        $this->ensureStaffUser($staff);

        if ($staff->image) {
            Storage::disk('public')->delete($staff->image);
        }

        $staff->delete();

        return back()->with('success', 'Staff deleted');
    }

    private function ensureStaffUser(User $staff): void
    {
        abort_unless($staff->user_type === 'staff', 404);
    }
}
