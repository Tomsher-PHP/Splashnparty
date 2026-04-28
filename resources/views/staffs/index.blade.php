@extends('layouts.app')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Staffs</h6>
        <div class="d-flex align-items-center gap-2">
            <span class="bg-primary-50 text-primary-600 px-20 py-8 rounded fw-medium text-sm">{{ $staffs->total() }} Total
                Staffs</span>
            @can('create_staff')
                <a href="{{ route('staffs.create') }}" class="btn btn-sm btn-primary-600 d-inline-flex align-items-center gap-2">
                    <i class="ri-add-line"></i>
                    Add Staff
                </a>
            @endcan
        </div>
    </div>

    <div class="card basic-data-table">
        <div class="card-body">
            <form method="GET" action="{{ route('staffs.index') }}" class="staffs-toolbar-wrap mb-20">
                <div class="d-flex flex-wrap align-items-end gap-3">
                    <div class="d-flex flex-wrap align-items-end gap-3">
                        <div class="staffs-filter-group">
                            <label for="staffsSearch" class="staffs-filter-label">Keyword</label>
                            <input type="text" id="staffsSearch" name="search" value="{{ request('search') }}"
                                class="form-control form-control-sm staffs-filter-input staffs-toolbar-search"
                                placeholder="Search staff">
                        </div>

                        <div class="staffs-filter-group">
                            <label for="staffsRoleFilter" class="staffs-filter-label">Role</label>
                            <select id="staffsRoleFilter" name="role"
                                class="form-control form-control-sm staffs-filter-select staffs-toolbar-role">
                                <option value="">All</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ request('role') === $role->name ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="staffs-filter-group">
                            <label for="staffsStatusFilter" class="staffs-filter-label">Status</label>
                            <select id="staffsStatusFilter" name="status"
                                class="form-control form-control-sm staffs-filter-select staffs-toolbar-status">
                                <option value="">All</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('staffs.index') }}"
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
                <table class="table bordered-table mb-0 mx-0" id="dataTable" data-page-length="10">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">Sl No</th>
                            <th scope="col">Staff</th>
                            <th scope="col" class="text-center">Phone</th>
                            <th scope="col" class="text-center">Role</th>
                            @can('edit_staff')
                                <th scope="col" class="text-center">Status</th>
                            @endcan
                            <th scope="col" class="text-center">Created Date</th>
                            @can('edit_staff')
                                <th scope="col" class="text-center">Action</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody id="staffsTableBody">
                        @forelse ($staffs as $staff)
                            <tr>
                                <td class="text-center">
                                    {{ $staffs->firstItem() + $loop->index }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-12">
                                        <div class="staff-avatar">
                                            @if ($staff->image)
                                                <img src="{{ asset('storage/' . $staff->image) }}"
                                                    alt="{{ $staff->name }}">
                                            @else
                                                <span>{{ strtoupper(substr($staff->name, 0, 1)) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-dark">{{ $staff->name }}</div>
                                            <div class="text-secondary-light small">{{ $staff->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">{{ $staff->phone ?: '-' }}</td>
                                <td class="text-center">
                                    <span class="bg-primary-50 text-primary-600 px-12 py-6 rounded fw-medium text-sm">
                                        {{ $staff->roles->first()?->name ?? 'No role' }}
                                    </span>
                                </td>
                                @can('edit_staff')
                                    <td class="text-center">
                                        <form action="{{ route('staffs.update-status', $staff) }}" method="POST"
                                            class="staff-status-form d-inline-flex align-items-center gap-12">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="is_active" value="{{ $staff->is_active ? 0 : 1 }}">
                                            @if (request('search'))
                                                <input type="hidden" name="search" value="{{ request('search') }}">
                                            @endif
                                            @if (request('role'))
                                                <input type="hidden" name="role" value="{{ request('role') }}">
                                            @endif
                                            @if (request('status'))
                                                <input type="hidden" name="status" value="{{ request('status') }}">
                                            @endif
                                            @if (request('page'))
                                                <input type="hidden" name="page" value="{{ request('page') }}">
                                            @endif

                                            <label class="staff-status-switch mb-0">
                                                <input type="checkbox" class="staff-status-switch__input"
                                                    {{ $staff->is_active ? 'checked' : '' }}>
                                                <span class="staff-status-switch__slider"></span>
                                            </label>
                                        </form>
                                    </td>
                                @endcan
                                <td class="text-center">
                                    {{ optional($staff->created_at)->format('d M Y') }}
                                </td>
                                @can('edit_staff')
                                    <td class="text-center">
                                        <div class="staffs-action-buttons">
                                            <a href="{{ route('staffs.edit', $staff) }}"
                                                class="staffs-icon-btn bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                                title="Edit">
                                                <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                            </a>
                                            @can('delete_staff')
                                                <form action="{{ route('staffs.destroy', $staff) }}" method="POST"
                                                    class="staff-delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="staffs-icon-btn remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                                        data-confirm-title="Delete Staff"
                                                        data-confirm-message="Are you sure you want to delete this staff?"
                                                        title="Delete">
                                                        <iconify-icon icon="fluent:delete-24-regular"
                                                            class="menu-icon"></iconify-icon>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                @endcan
                            </tr>
                        @empty
                            <tr id="staffsEmptyState">
                                <td colspan="{{ auth()->user()?->can('edit_staff') ? 7 : 5 }}" class="text-center py-32">
                                    <div class="text-secondary-light">
                                        <i class="ri-user-line text-3xl d-block mb-8"></i>
                                        No staffs created yet.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($staffs->hasPages())
                <div class="staffs-pagination-wrap mt-24">
                    <div class="staffs-pagination-summary text-secondary-light">
                        Showing {{ $staffs->firstItem() }} to {{ $staffs->lastItem() }} of {{ $staffs->total() }} staffs
                    </div>
                    <div class="staffs-pagination">
                        {{ $staffs->onEachSide(1)->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.querySelectorAll('.staff-status-switch__input').forEach(function(toggle) {
            toggle.addEventListener('change', function(event) {
                const form = event.target.closest('.staff-status-form');
                const nextState = event.target.checked ? 'activate' : 'inactivate';

                window.openAppConfirm({
                    title: 'Update Staff Status',
                    message: `Are you sure you want to ${nextState} this staff?`,
                    buttonText: 'Yes, Update',
                    buttonClass: 'btn btn-sm btn-primary-600',
                    onConfirm: function() {
                        form.submit();
                    }
                });

                event.target.checked = !event.target.checked;
            });
        });

        document.querySelectorAll('.staff-delete-form button[type="submit"]').forEach(function(button) {
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
        .staffs-filter-group {
            min-width: 180px;
        }

        .staffs-filter-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary-light);
            margin-bottom: 6px;
            display: block;
        }

        .staffs-filter-input,
        .staffs-filter-select {
            height: 42px;
            border-radius: 8px;
            border: 1px solid var(--input-form-light);
            background: #fff;
        }

        .staffs-toolbar-search {
            min-width: 220px;
        }

        .staffs-toolbar-role {
            min-width: 170px;
        }

        .staffs-toolbar-status {
            min-width: 150px;
        }

        .staff-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            background: var(--primary-50);
            color: var(--primary-600);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            flex-shrink: 0;
        }

        .staff-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .staffs-action-buttons {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .staffs-icon-btn {
            border: 0;
        }

        .staff-status-switch {
            position: relative;
            display: inline-block;
            width: 34px;
            height: 20px;
        }

        .staff-status-switch__input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .staff-status-switch__slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background-color: #dbe3ef;
            transition: .2s ease;
            border-radius: 999px;
        }

        .staff-status-switch__slider:before {
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

        .staff-status-switch__input:checked+.staff-status-switch__slider {
            background-color: #22c55e;
        }

        .staff-status-switch__input:checked+.staff-status-switch__slider:before {
            transform: translateX(14px);
        }

        .staffs-pagination-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            padding-top: 16px;
            border-top: 1px solid var(--input-form-light);
        }

        .staffs-pagination-summary {
            font-size: 14px;
            font-weight: 500;
        }

        .staffs-pagination {
            margin-left: auto;
        }

        .staffs-pagination .pagination {
            margin-bottom: 0;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .staffs-pagination .page-item {
            margin: 0;
        }

        .staffs-pagination .page-link {
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

        .staffs-pagination .page-item.active .page-link {
            background: var(--primary-600);
            border-color: var(--primary-600);
            color: #fff;
        }

        .staffs-pagination .page-item.disabled .page-link {
            color: var(--text-secondary-light);
            background: #f8fafc;
            border-color: var(--input-form-light);
        }

        .staffs-pagination .page-link:hover {
            background: var(--primary-50);
            border-color: var(--primary-200);
            color: var(--primary-700);
        }

        @media (max-width: 767px) {

            .staffs-filter-group,
            .staffs-toolbar-search,
            .staffs-toolbar-role,
            .staffs-toolbar-status,
            .staffs-pagination-wrap,
            .staffs-pagination {
                width: 100%;
                min-width: 100%;
            }

            .staffs-pagination-summary {
                text-align: center;
                width: 100%;
            }

            .staffs-pagination .pagination {
                justify-content: center;
            }
        }
    </style>
@endsection
