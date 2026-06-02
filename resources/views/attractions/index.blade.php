@extends('layouts.app')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Attractions & Adventures</h6>
        <div class="d-flex align-items-center gap-2">
            <span class="bg-primary-50 text-primary-600 px-20 py-8 rounded fw-medium text-sm">
                {{ $attractions->total() }} Total Entries
            </span>
            @can('create_attractions')
                <a href="{{ route('attractions.create') }}" class="btn btn-sm btn-primary-600 d-inline-flex align-items-center gap-2">
                    <i class="ri-add-line"></i>
                    Add Attraction/Adventure
                </a>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-24" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card basic-data-table">
        <div class="card-body">
            <form method="GET" action="{{ route('attractions.index') }}" class="attractions-toolbar-wrap mb-20">
                <div class="d-flex flex-wrap align-items-end gap-3">
                    <div class="d-flex flex-wrap align-items-end gap-3">
                        <div class="attractions-filter-group">
                            <label for="attractionsSearch" class="attractions-filter-label">Keyword</label>
                            <input type="text" id="attractionsSearch" name="search" value="{{ request('search') }}"
                                class="form-control form-control-sm attractions-filter-input"
                                placeholder="Search title">
                        </div>

                        <div class="attractions-filter-group">
                            <label for="attractionsBranchFilter" class="attractions-filter-label">Branch</label>
                            <select id="attractionsBranchFilter" name="branch_id"
                                class="form-control form-control-sm attractions-filter-select">
                                <option value="">All Branches</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="attractions-filter-group">
                            <label for="attractionsTypeFilter" class="attractions-filter-label">Type</label>
                            <select id="attractionsTypeFilter" name="type"
                                class="form-control form-control-sm attractions-filter-select">
                                <option value="">All Types</option>
                                <option value="attraction" {{ request('type') === 'attraction' ? 'selected' : '' }}>Attraction</option>
                                <option value="adventure" {{ request('type') === 'adventure' ? 'selected' : '' }}>Adventure</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('attractions.index') }}"
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
                <table class="table bordered-table mb-0 mx-0" id="dataTable">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center" style="width: 80px;">Sl No</th>
                            <th scope="col" style="width: 100px;">Image</th>
                            <th scope="col">Title</th>
                            <th scope="col">Branch</th>
                            <th scope="col" class="text-center" style="width: 130px;">Type</th>
                            <th scope="col" class="text-center" style="width: 100px;">Sort Order</th>
                            @can('edit_attractions')
                                <th scope="col" class="text-center" style="width: 120px;">Status</th>
                            @endcan
                            @if(auth()->user()?->can('edit_attractions') || auth()->user()?->can('delete_attractions'))
                                <th scope="col" class="text-center" style="width: 120px;">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attractions as $attraction)
                            <tr>
                                <td class="text-center">
                                    {{ $attractions->firstItem() + $loop->index }}
                                </td>
                                <td>
                                    <div class="attraction-thumbnail-wrap">
                                        @if ($attraction->image)
                                            <img src="{{ asset($attraction->image) }}" alt="{{ $attraction->title }}">
                                        @else
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light text-muted">
                                                <i class="ri-image-line text-xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $attraction->title }}</div>
                                    @if($attraction->description)
                                        <div class="text-secondary-light small text-truncate" style="max-width: 250px;">
                                            {{ strip_tags($attraction->description) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @forelse($attraction->branches as $branch)
                                            <span class="badge bg-primary-50 text-primary-600 px-8 py-4 rounded text-xs">
                                                {{ $branch->title }}
                                            </span>
                                        @empty
                                            <span class="text-muted small">No Branch</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if ($attraction->type === 'attraction')
                                        <span class="badge bg-info-200 text-info-600 px-12 py-6 rounded fw-semibold text-xs text-uppercase">
                                            Attraction
                                        </span>
                                    @else
                                        <span class="badge bg-purple text-purple-600 px-12 py-6 rounded fw-semibold text-xs text-uppercase">
                                            Adventure
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center fw-medium text-secondary-light">
                                    {{ $attraction->sort_order }}
                                </td>
                                @can('edit_attractions')
                                    <td class="text-center">
                                        <label class="attraction-status-switch mb-0">
                                            <input type="checkbox" class="attraction-status-switch__input"
                                                data-id="{{ $attraction->id }}"
                                                {{ $attraction->status ? 'checked' : '' }}>
                                            <span class="attraction-status-switch__slider"></span>
                                        </label>
                                    </td>
                                @endcan
                                @if(auth()->user()?->can('edit_attractions') || auth()->user()?->can('delete_attractions'))
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            @can('edit_attractions')
                                                <a href="{{ route('attractions.edit', $attraction) }}"
                                                    class="action-btn-circle bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                                    title="Edit">
                                                    <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                                </a>
                                            @endcan
                                            @can('delete_attractions')
                                                <form action="{{ route('attractions.destroy', $attraction) }}" method="POST"
                                                    class="attraction-delete-form m-0 p-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="action-btn-circle remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                                        data-confirm-title="Delete Entry"
                                                        data-confirm-message="Are you sure you want to delete this attraction/adventure? This action cannot be undone."
                                                        title="Delete">
                                                        <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()?->can('edit_attractions') || auth()->user()?->can('delete_attractions') ? 8 : 6 }}" class="text-center py-32">
                                    <div class="text-secondary-light">
                                        <i class="ri-water-flash-line text-3xl d-block mb-8"></i>
                                        No attractions or adventures created yet.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($attractions->hasPages())
                <div class="attractions-pagination-wrap mt-24">
                    <div class="attractions-pagination-summary text-secondary-light">
                        Showing {{ $attractions->firstItem() }} to {{ $attractions->lastItem() }} of {{ $attractions->total() }} entries
                    </div>
                    <div class="attractions-pagination">
                        {{ $attractions->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Confirm delete dialog using global confirm modal helper
            document.querySelectorAll('.attraction-delete-form button[type="submit"]').forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const form = button.closest('form');

                    window.openAppConfirm({
                        title: button.dataset.confirmTitle || 'Delete Entry',
                        message: button.dataset.confirmMessage || 'Are you sure you want to delete this?',
                        buttonText: 'Yes, Delete',
                        buttonClass: 'btn btn-sm btn-danger',
                        onConfirm: function() {
                            form.submit();
                        }
                    });
                });
            });

            // Inline AJAX status toggler
            document.querySelectorAll('.attraction-status-switch__input').forEach(function(toggle) {
                toggle.addEventListener('change', function(event) {
                    const checkbox = event.target;
                    const attractionId = checkbox.dataset.id;
                    const isChecked = checkbox.checked;
                    const nextStatus = isChecked ? 1 : 0;
                    const nextStateText = isChecked ? 'activate' : 'deactivate';

                    // Temporarily revert the visually checked state until confirmed
                    checkbox.checked = !isChecked;

                    window.openAppConfirm({
                        title: 'Update Status',
                        message: `Are you sure you want to ${nextStateText} this attraction/adventure?`,
                        buttonText: 'Yes, Update',
                        buttonClass: 'btn btn-sm btn-primary-600',
                        onConfirm: function() {
                            // Perform AJAX request
                            fetch(`/admin/attractions/${attractionId}/status`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ status: nextStatus })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Visual checkbox is checked successfully
                                    checkbox.checked = isChecked;
                                    if (window.appToast) {
                                        window.appToast('success', data.message || 'Status updated successfully.');
                                    }
                                } else {
                                    // Revert
                                    checkbox.checked = !isChecked;
                                    if (window.appToast) {
                                        window.appToast('error', data.message || 'Failed to update status.');
                                    }
                                }
                            })
                            .catch(error => {
                                checkbox.checked = !isChecked;
                                console.error('Error updating status:', error);
                                if (window.appToast) {
                                    window.appToast('error', 'An error occurred while updating status.');
                                }
                            });
                        },
                        onCancel: function() {
                            // Leave visually checkbox as is
                            checkbox.checked = !isChecked;
                        }
                    });
                });
            });
        });
    </script>
@endsection

@section('style')
    <style>
        /* Responsive Table Fit Override */
        #dataTable.bordered-table {
            min-width: 0 !important;
            width: 100% !important;
            table-layout: auto !important;
        }

        #dataTable.bordered-table th,
        #dataTable.bordered-table td {
            white-space: normal !important;
            word-break: break-word !important;
            overflow-wrap: break-word !important;
        }

        .attractions-filter-group {
            min-width: 180px;
        }

        .attractions-filter-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary-light);
            margin-bottom: 6px;
            display: block;
        }

        .attractions-filter-input,
        .attractions-filter-select {
            height: 42px;
            border-radius: 8px;
            border: 1px solid var(--input-form-light);
            background: #fff;
        }

        .attraction-thumbnail-wrap {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            overflow: hidden;
            background: #f1f5f9;
            border: 1px solid rgba(226, 232, 240, 0.8);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .attraction-thumbnail-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .attraction-thumbnail-wrap:hover img {
            transform: scale(1.12);
        }

        .action-btn-circle {
            border: 0;
            transition: all 0.2s ease;
        }

        .attraction-status-switch {
            position: relative;
            display: inline-block;
            width: 34px;
            height: 20px;
        }

        .attraction-status-switch__input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .attraction-status-switch__slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background-color: #dbe3ef;
            transition: .2s ease;
            border-radius: 999px;
        }

        .attraction-status-switch__slider:before {
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

        .attraction-status-switch__input:checked+.attraction-status-switch__slider {
            background-color: #22c55e;
        }

        .attraction-status-switch__input:checked+.attraction-status-switch__slider:before {
            transform: translateX(14px);
        }

        .attractions-pagination-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            padding-top: 16px;
            border-top: 1px solid var(--input-form-light);
        }

        .attractions-pagination-summary {
            font-size: 14px;
            font-weight: 500;
        }

        .attractions-pagination {
            margin-left: auto;
        }

        @media (max-width: 767px) {
            .attractions-filter-group,
            .attractions-pagination-wrap,
            .attractions-pagination {
                width: 100%;
                min-width: 100%;
            }

            .attractions-pagination-summary {
                text-align: center;
                width: 100%;
            }

            .attractions-pagination .pagination {
                justify-content: center;
            }
        }
    </style>
@endsection
