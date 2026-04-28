@extends('layouts.app')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-4">Edit Role</h6>
            <p class="mb-0 text-secondary-light">Update the role name and its mapped permissions.</p>
        </div>
        <a href="{{ route('roles.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2">
            <i class="ri-arrow-left-line"></i>
            Back to Roles
        </a>
    </div>

    <form action="{{ route('roles.update', $role) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row gy-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row gy-4 align-items-end">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Role Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="name" name="name" value="{{ old('name', $role->name) }}"
                                    class="form-control form-control-sm @error('name') is-invalid @enderror"
                                    placeholder="Enter role name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="is_active" class="form-label fw-semibold">Status <span
                                        class="text-danger">*</span></label>
                                <select id="is_active" name="is_active"
                                    class="form-control form-control-sm @error('is_active') is-invalid @enderror">
                                    <option value="1"
                                        {{ old('is_active', (string) $role->is_active) === '1' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="0"
                                        {{ old('is_active', (string) $role->is_active) === '0' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <div class="border radius-8 px-20 py-16 bg-base">
                                    <span class="text-secondary-light d-block mb-4">Currently assigned permissions</span>
                                    <h6 class="mb-0">{{ count($rolePermissions) }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @include('roles.partials.permissions-manager', [
                            'permissions' => $permissions,
                            'selectedPermissions' => old('permissions', $rolePermissions),
                            'title' => 'Assign Permissions',
                            'description' => '',
                        ])
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <button type="submit" class="btn btn-sm btn-primary-600 d-inline-flex align-items-center gap-2">
                        <i class="ri-save-line"></i>
                        Update Role
                    </button>
                    <a href="{{ route('roles.index') }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('style')
    <style>
        .cursor-pointer {
            cursor: pointer;
        }

        tr.is-filtered-out {
            display: none !important;
        }

        [data-permission-item].is-hidden {
            display: none !important;
        }

        .permission-manager .permission-check-input {
            width: 18px;
            height: 18px;
            border: 1px solid var(--text-secondary-light) !important;
            background-color: transparent;
            background-image: none;
        }

        .permission-manager .permission-check-input:checked,
        .permission-manager .permission-check-input:indeterminate {
            border-color: var(--primary-600) !important;
        }

        .permission-manager .permission-check-input:indeterminate::before {
            background-color: var(--primary-600);
            transform: translate(-50%, -50%) scale(1);
            visibility: visible;
            opacity: 1;
        }

        .permission-manager .permission-check-input:indeterminate::after {
            content: "-";
            font-family: inherit;
            font-size: 16px;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            visibility: visible;
            opacity: 1;
        }

        .form-control::placeholder {
            font-size: 13px !important;
            opacity: 0.7 !important;
        }

        .form-control::-webkit-input-placeholder {
            font-size: 13px !important;
            opacity: 0.7 !important;
        }

        .form-control::-moz-placeholder {
            font-size: 13px !important;
            opacity: 0.7 !important;
        }
    </style>
@endsection

@section('script')
    <script>
        document.querySelectorAll('[data-permission-manager]').forEach(function(manager) {
            const selectedCountElement = manager.querySelector('[data-selected-count]');
            const groups = Array.from(manager.querySelectorAll('[data-permission-group]'));
            const searchInput = manager.querySelector('[data-permission-search]');
            const clearAllButton = manager.querySelector('[data-clear-all]');

            function updateCounts() {
                const allCheckboxes = manager.querySelectorAll('.permission-item__checkbox');
                const selectedCount = Array.from(allCheckboxes).filter(function(checkbox) {
                    return checkbox.checked;
                }).length;

                if (selectedCountElement) {
                    selectedCountElement.textContent = selectedCount;
                }

                groups.forEach(function(group) {
                    const groupCheckboxes = Array.from(group.querySelectorAll(
                        '.permission-item__checkbox'));
                    const checkedCount = groupCheckboxes.filter(function(checkbox) {
                        return checkbox.checked;
                    }).length;
                    const master = group.querySelector('[data-group-master]');
                    // const selectedLabel = group.querySelector('[data-group-selected]');

                    // if (selectedLabel) {
                    //     selectedLabel.textContent = checkedCount;
                    // }

                    if (master) {
                        master.checked = groupCheckboxes.length > 0 && checkedCount === groupCheckboxes
                            .length;
                        master.indeterminate = checkedCount > 0 && checkedCount < groupCheckboxes.length;
                    }
                });
            }

            groups.forEach(function(group) {
                const master = group.querySelector('[data-group-master]');
                const checkboxes = Array.from(group.querySelectorAll('.permission-item__checkbox'));

                if (master) {
                    master.addEventListener('change', function() {
                        checkboxes.forEach(function(checkbox) {
                            const item = checkbox.closest('[data-permission-item]');
                            if (item && item.classList.contains('is-hidden')) {
                                return;
                            }

                            checkbox.checked = master.checked;
                        });

                        updateCounts();
                    });
                }

                checkboxes.forEach(function(checkbox) {
                    checkbox.addEventListener('change', updateCounts);
                });
            });

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const term = searchInput.value.trim().toLowerCase();

                    groups.forEach(function(group) {
                        const groupName = (group.querySelector('[data-group-name]')?.textContent ||
                            '').toLowerCase();
                        const items = Array.from(group.querySelectorAll('[data-permission-item]'));
                        let visibleItems = 0;

                        items.forEach(function(item) {
                            const label = (item.querySelector('[data-permission-label]')
                                ?.textContent || '').toLowerCase();
                            const slug = (item.querySelector('.permission-item__slug')
                                ?.textContent || '').toLowerCase();
                            const matches = !term || groupName.includes(term) || label
                                .includes(term) || slug.includes(term);

                            item.classList.toggle('is-hidden', !matches);
                            if (matches) {
                                visibleItems++;
                            }
                        });

                        const isVisible = visibleItems > 0 || (!term && items.length > 0) || (
                            term && groupName.includes(term));
                        group.classList.toggle('is-filtered-out', !isVisible);
                    });
                });
            }

            if (clearAllButton) {
                clearAllButton.addEventListener('click', function() {
                    manager.querySelectorAll('.permission-item__checkbox').forEach(function(checkbox) {
                        checkbox.checked = false;
                    });

                    updateCounts();
                });
            }

            updateCounts();
        });
    </script>
@endsection
