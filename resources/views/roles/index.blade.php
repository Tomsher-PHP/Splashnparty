@extends('layouts.app')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Roles & Permissions</h6>
        <div class="d-flex align-items-center gap-2">
            <span class="bg-primary-50 text-primary-600 px-20 py-8 rounded fw-medium text-sm">{{ $roles->total() }} Total
                Roles</span>
            @can('roles.create')
                <a href="{{ route('roles.create') }}"
                    class="btn btn-sm btn-primary-600 d-inline-flex align-items-center gap-2">
                    <i class="ri-add-line"></i>
                    Add Role
                </a>
            @endcan
        </div>
    </div>

    <div class="card basic-data-table">
        <div class="card-body">
            <form method="GET" action="{{ route('roles.index') }}" class="roles-toolbar-wrap mb-20">
                <div class="d-flex flex-wrap align-items-end  gap-3">
                    <div class="d-flex flex-wrap align-items-end gap-3">
                        <div class="roles-filter-group">
                            <label for="rolesSearch" class="roles-filter-label">Keyword</label>
                            <input type="text" id="rolesSearch" name="search" value="{{ request('search') }}"
                                class="form-control form-control-sm roles-filter-input roles-toolbar-search"
                                placeholder="Search roles">
                        </div>

                        <div class="roles-filter-group">
                            <label for="rolesStatusFilter" class="roles-filter-label">Status</label>
                            <select id="rolesStatusFilter" name="status"
                                class="form-control form-control-sm roles-filter-select roles-toolbar-status">
                                <option value="">All</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('roles.index') }}"
                            class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2">
                            <i class="ri-refresh-line"></i>
                            Reset
                        </a>
                        <button type="submit" class="btn btn-sm btn-primary-600 d-inline-flex align-items-center gap-2">
                            <i class="ri-search-line"></i>
                            Filter
                        </button>
                    </div>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="table bordered-table mb-0 mx-0" id="dataTable" data-page-length='10'>
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">Sl No</th>
                            <th scope="col">Role Title</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col" class="text-center">Created Date</th>
                            @if (auth()->user()?->can('roles.edit') || auth()->user()?->can('roles.delete'))
                                <th scope="col" class="text-center">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="rolesTableBody">
                        @forelse ($roles as $role)
                            <tr>
                                <td class="text-center">
                                    {{ $roles->firstItem() + $loop->index }}
                                </td>
                                <td>
                                    {{ $role->name }}
                                </td>
                                <td class="text-center">
                                    @can('roles.edit')
                                        <form action="{{ route('roles.update-status', $role) }}" method="POST"
                                            class="role-status-form d-inline-flex align-items-center gap-12">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="is_active" value="{{ $role->is_active ? 0 : 1 }}">
                                            @if (request('search'))
                                                <input type="hidden" name="search" value="{{ request('search') }}">
                                            @endif
                                            @if (request('status'))
                                                <input type="hidden" name="status" value="{{ request('status') }}">
                                            @endif
                                            @if (request('page'))
                                                <input type="hidden" name="page" value="{{ request('page') }}">
                                            @endif

                                            <label class="role-status-switch mb-0">
                                                <input type="checkbox" class="role-status-switch__input"
                                                    {{ $role->is_active ? 'checked' : '' }}>
                                                <span class="role-status-switch__slider"></span>
                                            </label>
                                        </form>
                                    @else
                                        <span
                                            class="badge {{ $role->is_active ? 'bg-success-focus text-success-main' : 'bg-danger-focus text-danger-main' }}">
                                            {{ $role->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    @endcan
                                </td>
                                <td class="text-center">
                                    {{ optional($role->created_at)->format('d M Y') }}
                                </td>
                                @if (auth()->user()?->can('roles.edit') || auth()->user()?->can('roles.delete'))
                                    <td class="text-center">
                                        <div class="roles-action-buttons">
                                            @can('roles.edit')
                                                <a href="{{ route('roles.edit', $role) }}"
                                                    class="roles-icon-btn bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                                    title="Edit">
                                                    <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                                </a>
                                            @endcan
                                            @can('roles.delete')
                                                <form action="{{ route('roles.destroy', $role) }}" method="POST"
                                                    class="role-delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="roles-icon-btn remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                                        data-confirm-title="Delete Role"
                                                        data-confirm-message="Are you sure you want to delete this role?"
                                                        title="Delete">
                                                        <iconify-icon icon="fluent:delete-24-regular"
                                                            class="menu-icon"></iconify-icon>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr id="rolesEmptyState">
                                <td colspan="{{ auth()->user()?->can('roles.edit') || auth()->user()?->can('roles.delete') ? 5 : 4 }}"
                                    class="text-center py-32">
                                    <div class="text-secondary-light">
                                        <i class="ri-shield-user-line text-3xl d-block mb-8"></i>
                                        No roles created yet.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($roles->hasPages())
                <div class="roles-pagination-wrap mt-24">
                    <div class="roles-pagination-summary text-secondary-light">
                        Showing {{ $roles->firstItem() }} to {{ $roles->lastItem() }} of {{ $roles->total() }} roles
                    </div>
                    <div class="roles-pagination">
                        {{ $roles->onEachSide(1)->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.querySelectorAll('.role-status-switch__input').forEach(function(toggle) {
            toggle.addEventListener('change', function(event) {
                const form = event.target.closest('.role-status-form');
                const nextState = event.target.checked ? 'enable' : 'disable';

                window.openAppConfirm({
                    title: 'Update Role Status',
                    message: `Are you sure you want to ${nextState} this role?`,
                    buttonText: 'Yes, Update',
                    buttonClass: 'btn btn-sm btn-primary-600',
                    onConfirm: function() {
                        form.submit();
                    }
                });

                event.target.checked = !event.target.checked;
            });
        });

        document.querySelectorAll('.role-delete-form button[type="submit"]').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const form = button.closest('form');

                window.openAppConfirm({
                    title: button.dataset.confirmTitle || 'Delete',
                    message: button.dataset.confirmMessage || 'Are you sure you want to continue?',
                    buttonText: 'Yes, Delete',
                    buttonClass: 'btn btn-sm btn-danger',
                    onConfirm: function() {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection

@section('style')
    <style>
        .roles-toolbar-wrap {
            /* border-bottom: 1px solid var(--input-form-light);
                padding: 16px 0; */
        }

        .roles-filter-group {
            min-width: 180px;
        }

        .roles-filter-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary-light);
            margin-bottom: 6px;
            display: block;
        }

        .roles-filter-input,
        .roles-filter-select {
            height: 42px;
            border-radius: 8px;
            border: 1px solid var(--input-form-light);
            background: #fff;
        }

        .roles-toolbar-search {
            min-width: 220px;
        }

        .roles-toolbar-status {
            min-width: 150px;
        }

        .roles-action-buttons {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .roles-icon-btn {
            border: 0;
        }

        .role-status-switch {
            position: relative;
            display: inline-block;
            width: 34px;
            height: 20px;
        }

        .role-status-switch__input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .role-status-switch__slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background-color: #dbe3ef;
            transition: .2s ease;
            border-radius: 999px;
        }

        .role-status-switch__slider:before {
            position: absolute;
            content: "";
            height: 14px;
            width: 14px;
            left: 3px;
            top: 3px;
            background-color: white;
            transition: .2s ease;
            border-radius: 50%;
            box-shadow: 0 1px 3px rgba(15, 23, 42, .25);
        }

        .role-status-switch__input:checked+.role-status-switch__slider {
            background-color: #22c55e;
        }

        .role-status-switch__input:checked+.role-status-switch__slider:before {
            transform: translateX(14px);
        }

        .roles-pagination-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            padding-top: 16px;
            border-top: 1px solid var(--input-form-light);
        }

        .roles-pagination-summary {
            font-size: 14px;
            font-weight: 500;
        }

        .roles-pagination {
            margin-left: auto;
        }

        .roles-pagination .pagination {
            margin-bottom: 0;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .roles-pagination .page-item {
            margin: 0;
        }

        .roles-pagination .page-link {
            border-radius: 8px;
            border: 1px solid var(--input-form-light);
            color: var(--text-primary-light);
            padding: 6px 10px;
            min-width: 34px;
            min-height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 12px;
            line-height: 1;
            background: #fff;
            box-shadow: none;
        }

        .roles-pagination .page-item.active .page-link {
            background: var(--primary-600);
            border-color: var(--primary-600);
            color: #fff;
        }

        .roles-pagination .page-item.disabled .page-link {
            color: var(--text-secondary-light);
            background: #f8fafc;
            border-color: var(--input-form-light);
        }

        .roles-pagination .page-link:hover {
            background: var(--primary-50);
            border-color: var(--primary-200);
            color: var(--primary-700);
        }

        @media (max-width: 767px) {
            .roles-toolbar-wrap {
                padding: 14px 0;
            }

            .roles-filter-group,
            .roles-toolbar-search,
            .roles-toolbar-status {
                min-width: 100%;
            }

            .roles-pagination-wrap,
            .roles-pagination {
                width: 100%;
            }

            .roles-pagination-summary {
                text-align: center;
                width: 100%;
            }

            .roles-pagination .pagination {
                justify-content: center;
            }
        }
    </style>
@endsection
