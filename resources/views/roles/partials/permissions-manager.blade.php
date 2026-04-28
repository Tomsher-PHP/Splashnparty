@php
    $selectedPermissions = $selectedPermissions ?? [];
    $totalPermissions = $permissions->sum(fn($permission) => $permission->children->count());

    $formatPermissionLabel = function ($permission) {
        $labelSource = $permission->title ?: $permission->name;

        return ucwords(str_replace(['.', '_'], [' ', ' '], $labelSource));
    };
@endphp

<div class="permission-manager" data-permission-manager>
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <label class="mb-1 form-label fw-semibold text-md">{{ $title ?? 'Assign Permissions' }}</label>
            <p class="mb-0 text-secondary-light small">
                {{ $description ?? '' }}
            </p>
        </div>

        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center gap-2 px-3 py-2 bg-light rounded border">
                <span class="text-secondary small">Selected:</span>
                <strong data-selected-count>{{ count($selectedPermissions) }}</strong>
                <span class="text-secondary small">/ {{ $totalPermissions }}</span>
            </div>
            <button type="button" class="btn btn-outline-secondary btn-sm" data-clear-all>Clear All</button>
        </div>
    </div>

    <div class="mb-4">
        <div class="input-group">
            <span class="input-group-text bg-white"><i class="ri-search-line text-secondary"></i></span>
            <input type="text" class="form-control form-control-sm border-start-0 ps-0"
                placeholder="Search permission groups or items..." data-permission-search>
        </div>
    </div>

    <div class="table-responsive border rounded">
        <table class="table table-hover table-sm mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 20%; min-width: 150px;" class="px-3 py-2 text-secondary fw-semibold">Module</th>
                    <th class="px-3 py-2 text-secondary fw-semibold">Permissions</th>
                    <th style="width: 100px;" class="text-center px-3 py-2 text-secondary fw-semibold">All</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($permissions as $permission)
                    @php
                        $children = $permission->children;
                        $selectedInGroup = $children
                            ->filter(fn($child) => in_array($child->name, $selectedPermissions, true))
                            ->count();
                    @endphp
                    <tr data-permission-group class="permission-group-row">
                        <td class="px-3 py-3 bg-light border-end">
                            <div class="fw-semibold text-capitalize text-dark" data-group-name>
                                {{ $formatPermissionLabel($permission) }}</div>
                            {{-- <div class="text-secondary small mt-1">
                                <span data-group-selected>{{ $selectedInGroup }}</span> / {{ $children->count() }}
                                selected
                            </div> --}}
                        </td>
                        <td class="px-3 py-3">
                            <div class="row g-3" data-group-list>
                                @forelse ($children as $childPermission)
                                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3" data-permission-item>
                                        <div class="form-check style-check mb-0 d-flex align-items-start">
                                            <input type="checkbox" name="permissions[]"
                                                value="{{ $childPermission->name }}"
                                                class="form-check-input permission-check-input permission-item__checkbox cursor-pointer flex-shrink-0 mt-1"
                                                id="perm-{{ str_replace(['.', ' '], '-', $childPermission->name) }}"
                                                {{ in_array($childPermission->name, $selectedPermissions, true) ? 'checked' : '' }}>
                                            <label
                                                class="form-check-label cursor-pointer user-select-none text-dark mt-1"
                                                for="perm-{{ str_replace(['.', ' '], '-', $childPermission->name) }}"
                                                data-permission-label>
                                                {{ $formatPermissionLabel($childPermission) }}
                                            </label>
                                            <span
                                                class="permission-item__slug d-none">{{ $childPermission->name }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-secondary small">No child permissions found.</div>
                                @endforelse
                            </div>
                        </td>
                        <td class="text-center px-3 py-3 bg-light border-start">
                            <div class="form-check style-check d-inline-flex align-items-center justify-content-center">
                                <input type="checkbox"
                                    class="form-check-input permission-check-input cursor-pointer m-0" data-group-master
                                    title="Select all in group">
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">No permissions found. Seed permissions
                            first to assign them here.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
