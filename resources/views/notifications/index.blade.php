@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-24 gap-3">
    <div>
        <h6 class="mb-4 fw-bold text-neutral-900 dark:text-white">Notifications</h6>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="bg-primary-50 dark:bg-primary-950 text-primary-600 dark:text-primary-400 px-16 py-8 rounded-10 fw-semibold text-xs border border-primary-100 border-opacity-30">
            {{ $totalCount }} Total Alerts
        </span>
        @if($unreadCount > 0)
            <span class="bg-danger-50 dark:bg-danger-950 text-danger-600 dark:text-danger-400 px-16 py-8 rounded-10 fw-semibold text-xs border border-danger-100 border-opacity-30">
                {{ $unreadCount }} Unread
            </span>
        @endif
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Bulk Actions Bar -->
<div id="bulkActionsBar" class="d-none mb-16 p-16 bg-danger-50 dark:bg-danger-950 text-danger-600 dark:text-danger-400 rounded-8 d-flex align-items-center justify-content-between">
    <div>
        <span id="selectedCount" class="fw-semibold">0</span> item(s) selected
    </div>
    <button type="button" id="bulkDeleteBtn" class="btn btn-danger-600 btn-sm d-flex align-items-center gap-1">
        <iconify-icon icon="fluent:delete-24-regular"></iconify-icon> Delete Selected
    </button>
</div>

<!-- Notifications Table Card -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-24">
        <div class="d-flex col-md-12 row">
            <form method="GET" class="mb-16 col-md-8" action="{{ route('admin.notifications.index') }}">
                <div class="row align-items-center g-3">
                    <div class="col-md-5">
                        <div class="position-relative">
                            <input type="text" name="search" class="form-control form-control-sm ps-40" 
                                placeholder="Search notifications..." 
                                value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <select name="status" class="form-select form-select-sm" aria-label="Default">
                            <option value="">All Statuses</option>
                            <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
                            <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex gap-2">
                        <button class="btn btn-primary-600 btn-sm d-inline-flex align-items-center gap-2 flex-grow-1 justify-content-center">
                            <i class="ri-search-line"></i> Filter
                        </button>
                        <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-secondary btn-sm flex-grow-1 text-center justify-content-center">
                            Reset
                        </a>
                    </div>
                </div>
            </form>

            <div class="d-flex justify-content-end mb-16  col-md-4">
                <form method="GET" action="{{ route('admin.notifications.index') }}" class="d-flex align-items-center gap-2">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    <span class="text-xs text-secondary-light text-nowrap">Show</span>
                    <select name="limit" class="form-select form-select-sm w-auto" onchange="this.form.submit()" style="min-width: 80px;">
                        <option value="10" {{ $limit == 10 ? 'selected' : '' }}>10</option>
                        <option value="15" {{ $limit == 15 ? 'selected' : '' }}>15</option>
                        <option value="25" {{ $limit == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $limit == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ $limit == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="text-xs text-secondary-light text-nowrap">entries</span>
                </form>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="table bordered-table mb-0 align-middle">
                <thead>
                    <tr class="text-neutral-700 dark:text-neutral-300">
                        <th style="width: 40px;">
                            <input type="checkbox" id="selectAll" class="form-check-input">
                        </th>
                        <th style="width: 60px;">#</th>
                        <th>Notification Details</th>
                        <th style="width: 200px;">Date & Time</th>
                        <th class="text-center" style="width: 120px;">Status</th>
                        <th class="text-center" style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notifications as $notification)
                        <tr class="position-relative">
                            <td>
                                <input type="checkbox" class="form-check-input select-item" value="{{ $notification->id }}">
                            </td>
                            <td> {{ $notifications->firstItem() + $loop->index }} </td>
                            <td>
                                <div>
                                    {{-- @if($notification->link)
                                        <a href="{{ $notification->link }}" class="text-neutral-900 dark:text-white hover-text-primary fw-semibold text-sm d-block mb-1">
                                            {{ $notification->title }}
                                        </a>
                                    @else --}}
                                        <h6 class="text-sm mb-1 fw-semibold text-neutral-900 dark:text-white">{{ $notification->title }}</h6>
                                    {{-- @endif --}}
                                    <span class="text-xs text-secondary-light dark:text-neutral-400 d-block">{{ $notification->message }}</span>
                                </div>
                            </td>
                            <td class="text-xs fw-medium text-secondary-light dark:text-neutral-300">
                                <span class="d-block">{{ $notification->created_at->format('d M Y, h:i A') }}</span>
                                <span class="text-3xs text-neutral-400">{{ $notification->created_at->diffForHumans() }}</span>
                            </td>
                            <td class="text-center">
                                @if(!$notification->is_read)
                                    <span class="bg-danger-focus text-danger-main px-12 py-4 rounded-pill fw-bold text-3xs border border-danger-100 border-opacity-30 d-inline-flex align-items-center gap-1">
                                        <span class="online-indicator w-6-px h-6-px bg-danger-main rounded-circle d-block pulse-dot"></span>
                                        UNREAD
                                    </span>
                                @else
                                    <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-bold text-3xs d-inline-flex align-items-center">
                                        READ
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <form action="{{ route('admin.notifications.destroy', $notification) }}"
                                          method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="item-icon-btn remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-semibold w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle border-0"
                                                data-confirm-title="Delete Notification"
                                                data-confirm-message="Are you sure you want to permanently delete this notification?"
                                                title="Delete Notification">
                                            <iconify-icon icon="fluent:delete-24-regular" class="item-icon text-sm"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-48 text-secondary-light dark:text-neutral-500 text-sm">
                                <iconify-icon icon="solar:bell-off-bold-duotone" class="text-5xl text-neutral-300 dark:text-neutral-700 d-block mb-12 mx-auto"></iconify-icon>
                                No notifications found matching your filter parameters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($notifications->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-24">
                <div class="text-xs text-secondary-light dark:text-neutral-400">
                    Showing {{ $notifications->firstItem() }} to {{ $notifications->lastItem() }} of {{ $notifications->total() }} notifications
                </div>
                <div>
                    {{ $notifications->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const selectItemCheckboxes = document.querySelectorAll('.select-item');
    const bulkActionsBar = document.getElementById('bulkActionsBar');
    const selectedCountSpan = document.getElementById('selectedCount');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

    function updateBulkActionsBar() {
        const checkedCheckboxes = document.querySelectorAll('.select-item:checked');
        const count = checkedCheckboxes.length;
        
        if (count > 0) {
            bulkActionsBar.classList.remove('d-none');
            selectedCountSpan.textContent = count;
        } else {
            bulkActionsBar.classList.add('d-none');
        }

        // Keep Select All checkbox in sync
        selectAllCheckbox.checked = (count === selectItemCheckboxes.length && selectItemCheckboxes.length > 0);
        selectAllCheckbox.indeterminate = (count > 0 && count < selectItemCheckboxes.length);
    }

    selectAllCheckbox.addEventListener('change', function() {
        selectItemCheckboxes.forEach(cb => {
            cb.checked = selectAllCheckbox.checked;
        });
        updateBulkActionsBar();
    });

    selectItemCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkActionsBar);
    });

    bulkDeleteBtn.addEventListener('click', function() {
        const checkedCheckboxes = document.querySelectorAll('.select-item:checked');
        const ids = Array.from(checkedCheckboxes).map(cb => cb.value);

        if (ids.length === 0) return;

        window.openAppConfirm({
            title: 'Delete Selected Notifications',
            message: `Are you sure you want to permanently delete the ${ids.length} selected notification(s)?`,
            buttonText: 'Delete',
            buttonClass: 'btn btn-danger btn-sm',
            onConfirm: function() {
                fetch("{{ route('admin.notifications.bulk-delete') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ ids: ids })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'An error occurred while deleting notifications.');
                    }
                })
                .catch(error => {
                    console.error('Error deleting notifications:', error);
                    alert('An error occurred. Please try again.');
                });
            }
        });
    });

    // Individual delete confirmations
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const title = this.getAttribute('data-confirm-title') || 'Confirm Delete';
                const msg = this.getAttribute('data-confirm-message') || 'Are you sure?';
                
                window.openAppConfirm({
                    title: title,
                    message: msg,
                    buttonText: 'Delete',
                    buttonClass: 'btn btn-danger btn-sm',
                    onConfirm: function() {
                        form.submit();
                    }
                });
            });
        }
    });
});
</script>
@endsection
