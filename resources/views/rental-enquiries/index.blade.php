@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-24 gap-3">
    <div>
        <h6 class="mb-4 fw-bold text-neutral-900 dark:text-white">Rental Enquiries</h6>
        <p class="mb-0 text-xs text-secondary-light dark:text-neutral-400">View and manage customer enquiries captured from the rental forms.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="bg-primary-50 dark:bg-primary-950 text-primary-600 dark:text-primary-400 px-16 py-8 rounded-10 fw-semibold text-xs border border-primary-100 border-opacity-30">
            {{ $totalCount }} Total Enquiries
        </span>
        @if($unreadCount > 0)
            <span class="bg-danger-50 dark:bg-danger-950 text-danger-600 dark:text-danger-400 px-16 py-8 rounded-10 fw-semibold text-xs border border-danger-100 border-opacity-30">
                {{ $unreadCount }} Unread
            </span>
        @endif
    </div>
</div>

<!-- Filters Panel Card -->
<div class="card mb-24 border-0 shadow-sm" style="opacity: 0; transition: opacity 0.15s ease;" id="enquiryFiltersCard">
    <div class="card-body p-20">
        <form method="GET" action="{{ route('rental-enquiries.index') }}">
            <div class="row align-items-center g-3">
                <div class="col-md-3">
                    <div class="position-relative">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search Name, Email, Phone, Message..." 
                               value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <select name="rental_id" class="form-select select2">
                        <option value="">All Rental Items</option>
                        @foreach($rentalItems as $item)
                            <option value="{{ $item->id }}" {{ request('rental_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
                        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <input type="text" name="date_range" class="form-control flatpickr-range bg-white" 
                           placeholder="Select Date Range" value="{{ request('date_range') }}" readonly>
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-primary-600 btn-sm d-inline-flex align-items-center gap-2 flex-grow-1 justify-content-center">
                        <i class="ri-search-line"></i> Filter
                    </button>
                    <a href="{{ route('rental-enquiries.index') }}" class="btn btn-outline-secondary btn-sm flex-grow-1 text-center justify-content-center">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Enquiries Table Card -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-24">
        <div class="overflow-x-auto">
            <table class="table bordered-table mb-0 align-middle">
                <thead>
                    <tr class="text-neutral-700 dark:text-neutral-300">
                        <th style="width: 60px;">#</th>
                        <th>Sender details</th>
                        <th>Rental Item Interest</th>
                        <th style="width: 180px;">Received Date</th>
                        <th class="text-center" style="width: 120px;">Status</th>
                        @if (auth()->user()?->can('delete_rental_enquiries'))
                            <th class="text-center" style="width: 100px;">Actions</th>
                        @else
                            <th class="text-center" style="width: 60px;">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($enquiries as $enquiry)
                        <tr class="position-relative">
                            <td> {{ $enquiries->firstItem() + $loop->index }} </td>
                            <td>
                                <div>
                                    <h6 class="text-sm mb-2 fw-semibold text-neutral-900 dark:text-white">{{ $enquiry->name }}</h6>
                                    <span class="text-2xs text-secondary-light dark:text-neutral-400 d-block mb-1">{{ $enquiry->email }}</span>
                                    <span class="text-3xs text-secondary-light dark:text-neutral-500 d-block">{{ $enquiry->phone }}</span>
                                </div>
                            </td>
                            <td>
                                @if($enquiry->rentalItem)
                                    <span class="badge bg-primary-50 dark:bg-primary-950 text-primary-600 dark:text-primary-400 text-2xs px-10 py-6 rounded fw-bold border border-primary-100 border-opacity-20 d-inline-block">
                                        {{ $enquiry->rentalItem->title }}
                                    </span>
                                    @if($enquiry->rentalItem->price)
                                        <span class="text-3xs text-secondary-light dark:text-neutral-500 d-block mt-4">Price: AED {{ number_format($enquiry->rentalItem->price, 2) }}</span>
                                    @endif
                                @else
                                    <span class="badge bg-neutral-100 text-neutral-600 text-2xs px-10 py-6 rounded fw-bold d-inline-block">
                                        General
                                    </span>
                                @endif
                            </td>
                            <td class="text-xs fw-medium text-secondary-light dark:text-neutral-400">
                                {{ date('d M Y, h:i A', strtotime($enquiry->created_at)) ?? 'N/A' }}
                            </td>
                            <td class="text-center">
                                @if($enquiry->status === 'unread')
                                    <span class="bg-danger-focus text-danger-main px-12 py-4 rounded-pill fw-bold text-3xs border border-danger-100 border-opacity-30 d-inline-flex align-items-center gap-1">
                                        <span class="online-indicator w-6-px h-6-px bg-danger-main rounded-circle d-block pulse-dot"></span>
                                        UNREAD
                                    </span>
                                @else
                                    <span class="bg-neutral-100 text-neutral-600 px-12 py-4 rounded-pill fw-bold text-3xs d-inline-flex align-items-center">
                                        READ
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <a href="{{ route('rental-enquiries.show', $enquiry) }}"
                                       class="bg-primary-focus text-primary-600 bg-hover-primary-200 fw-semibold w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle border-0 text-decoration-none"
                                       title="View Details">
                                        <i class="ri-eye-line text-sm"></i>
                                    </a>

                                    @can('delete_rental_enquiries')
                                        <form action="{{ route('rental-enquiries.destroy', $enquiry) }}"
                                              method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="item-icon-btn remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-semibold w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle border-0"
                                                    data-confirm-title="Delete Rental Enquiry"
                                                    data-confirm-message="Are you sure you want to permanently delete this rental enquiry?"
                                                    title="Delete Enquiry">
                                                <iconify-icon icon="fluent:delete-24-regular" class="item-icon text-sm"></iconify-icon>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-48 text-secondary-light dark:text-neutral-500 text-sm">
                                <iconify-icon icon="solar:letter-opened-bold-duotone" class="text-5xl text-neutral-300 dark:text-neutral-700 d-block mb-12 mx-auto"></iconify-icon>
                                No rental enquiries matched your search parameters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($enquiries->hasPages())
            <div class="mt-24 pt-20 border-top border-neutral-100 dark:border-neutral-800 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="text-muted text-xs">
                    Showing {{ $enquiries->firstItem() }} to {{ $enquiries->lastItem() }}
                    of {{ $enquiries->total() }} enquiries
                </div>

                <div>
                    {{ $enquiries->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

<style>
    .pulse-dot {
        animation: simple-pulse 2s infinite;
    }
    @keyframes simple-pulse {
        0% {
            transform: scale(0.9);
            opacity: 0.7;
        }
        70% {
            transform: scale(1.1);
            opacity: 1;
        }
        100% {
            transform: scale(0.9);
            opacity: 0.7;
        }
    }

    /* Select2 overrides to match template's form controls */
    .select2-container--default .select2-selection--single {
        background-color: var(--white) !important;
        border: 1px solid var(--input-form-light) !important;
        border-radius: 8px !important;
        height: 2.75rem !important; /* matches template form-control/form-select height */
        padding: 0.25rem 1.25rem !important;
        display: flex !important;
        align-items: center !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: var(--text-primary-light) !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        font-size: 0.875rem !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100% !important;
        top: 0 !important;
        right: 1.25rem !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .select2-container--open .select2-dropdown {
        background-color: var(--white) !important;
        border-color: var(--input-form-light) !important;
        border-radius: 8px !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
        z-index: 1050 !important;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field {
        background-color: var(--bg-color) !important;
        border: 1px solid var(--input-form-light) !important;
        color: var(--text-primary-light) !important;
        border-radius: 6px !important;
        padding: 6px 12px !important;
    }

    .select2-container--default .select2-results__option {
        color: var(--text-primary-light) !important;
        padding: 8px 16px !important;
        font-size: 0.875rem !important;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: var(--primary-600) !important;
        color: #ffffff !important;
    }

    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: var(--neutral-100) !important;
        color: var(--text-primary-light) !important;
    }
</style>

@section('script')
<script src="{{ asset('assets/js/flatpickr.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize Flatpickr Date Range Picker
        flatpickr(".flatpickr-range", {
            mode: "range",
            dateFormat: "Y-m-d",
            allowInput: false,
        });

        // Initialize Select2
        $('.select2').select2({
            width: '100%'
        });

        const filtersCard = document.getElementById('enquiryFiltersCard');
        if (filtersCard) {
            filtersCard.style.opacity = '1';
        }

        document.querySelectorAll('.delete-form').forEach(function(form) {
            const button = form.querySelector('button[type="submit"]');
            button.addEventListener('click', function(event) {
                event.preventDefault();

                window.openAppConfirm({
                    title: button.dataset.confirmTitle || 'Delete Enquiry',
                    message: button.dataset.confirmMessage || 'Are you sure you want to continue?',
                    buttonText: 'Yes, Delete',
                    buttonClass: 'btn btn-sm btn-danger',
                    onConfirm: function() {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection
