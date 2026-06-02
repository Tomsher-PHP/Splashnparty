@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-24 gap-3">
    <div>
        <h6 class="mb-4 fw-bold text-neutral-900 dark:text-white">Contact Enquiries</h6>
        <p class="mb-0 text-xs text-secondary-light dark:text-neutral-400">View and manage customer submissions captured from the website form.</p>
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
        <form method="GET" action="{{ route('contact-enquiries.index') }}">
            <div class="row align-items-center g-3">
                <div class="col-md-5">
                    <div class="position-relative">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search Name, Email, Subject or Category..." 
                               value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
                        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
                    </select>
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button class="btn btn-primary-600 btn-sm d-inline-flex align-items-center gap-2 flex-grow-1 justify-content-center">
                        <i class="ri-search-line"></i> Filter
                    </button>
                    <a href="{{ route('contact-enquiries.index') }}" class="btn btn-outline-secondary btn-sm flex-grow-1 text-center justify-content-center">
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
                        <th style="width: 140px;">Category</th>
                        <th>Sender details</th>
                        <th>Subject</th>
                        <th style="width: 140px;">Preferred Date</th>
                        <th style="width: 140px;">Received Date</th>
                        <th class="text-center" style="width: 120px;">Status</th>
                        @if (auth()->user()?->can('delete_contact_enquiries'))
                            <th class="text-end" style="width: 100px;">Actions</th>
                        @else
                            <th class="text-end" style="width: 60px;">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($enquiries as $enquiry)
                        <tr class="position-relative">
                            <td> {{ $enquiries->firstItem() + $loop->index }} </td>
                            <td>
                                <span class="badge bg-primary-50 dark:bg-primary-950 text-primary-600 dark:text-primary-400 text-2xs px-10 py-6 rounded fw-bold border border-primary-100 border-opacity-20">
                                    {{ strtoupper($enquiry->about) }}
                                </span>
                            </td>
                            <td>
                                <div>
                                    <h6 class="text-sm mb-2 fw-semibold text-neutral-900 dark:text-white">{{ $enquiry->full_name }}</h6>
                                    <span class="text-2xs text-secondary-light dark:text-neutral-400 d-block mb-1">{{ $enquiry->email }}</span>
                                    <span class="text-3xs text-secondary-light dark:text-neutral-500 d-block">{{ $enquiry->phone }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold text-neutral-800 dark:text-neutral-200 text-xs">{{ Str::limit($enquiry->subject, 50) }}</span>
                            </td>
                            <td class="text-xs fw-medium text-secondary-light dark:text-neutral-300">
                                {{ $enquiry->preferred_date ? date('d M Y', strtotime($enquiry->preferred_date)) : 'Not Specified' }}
                            </td>
                            <td class="text-xs fw-medium text-secondary-light dark:text-neutral-400">
                                {{ $enquiry->created_at?->format('d M Y, h:i A') ?? 'N/A' }}
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
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    <a href="{{ route('contact-enquiries.show', $enquiry) }}"
                                       class="bg-primary-focus text-primary-600 bg-hover-primary-200 fw-semibold w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle border-0 text-decoration-none"
                                       title="View Details">
                                        <i class="ri-eye-line text-sm"></i>
                                    </a>

                                    @can('delete_contact_enquiries')
                                        <form action="{{ route('contact-enquiries.destroy', $enquiry) }}"
                                              method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="item-icon-btn remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-semibold w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle border-0"
                                                    data-confirm-title="Delete Contact Enquiry"
                                                    data-confirm-message="Are you sure you want to permanently delete this customer enquiry?"
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
                                No contact enquiries matched your search parameters.
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
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Prevent Flash of Unstyled Filters Panel by fading it in
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
