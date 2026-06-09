@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-24 gap-3">
    <div>
        <h6 class="mb-4 fw-bold text-neutral-900 dark:text-white">Newsletter Subscribers</h6>
        <p class="mb-0 text-xs text-secondary-light dark:text-neutral-400">View and manage email addresses subscribed to the newsletter.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="bg-primary-50 dark:bg-primary-950 text-primary-600 dark:text-primary-400 px-16 py-8 rounded-10 fw-semibold text-xs border border-primary-100 border-opacity-30">
            {{ $totalCount }} Total Subscribers
        </span>
        @can('view_newsletter_subscriptions')
            <a href="{{ route('newsletter-subscriptions.export') }}" class="btn btn-success-600 btn-sm d-inline-flex align-items-center gap-2">
                <i class="ri-file-download-line text-lg"></i> Export to CSV
            </a>
        @endcan
    </div>
</div>

<!-- Filters Panel Card -->
<div class="card mb-24 border-0 shadow-sm" style="opacity: 0; transition: opacity 0.15s ease;" id="newsletterFiltersCard">
    <div class="card-body p-20">
        <form method="GET" action="{{ route('newsletter-subscriptions.index') }}">
            <div class="row align-items-center g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control form-control-sm" 
                           placeholder="Search by Email..." 
                           value="{{ request('search') }}">
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-primary-600 btn-sm d-inline-flex align-items-center gap-2 flex-grow-1 justify-content-center">
                        <i class="ri-search-line"></i> Filter
                    </button>
                    <a href="{{ route('newsletter-subscriptions.index') }}" class="btn btn-outline-secondary btn-sm flex-grow-1 text-center justify-content-center">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Subscribers Table Card -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-24">
        <div class="overflow-x-auto">
            <table class="table bordered-table mb-0 align-middle">
                <thead>
                    <tr class="text-neutral-700 dark:text-neutral-300">
                        <th style="width: 60px;">#</th>
                        <th>Email Address</th>
                        <th style="width: 200px;">Subscribed At</th>
                        @can('delete_newsletter_subscriptions')
                            <th class="text-end" style="width: 100px;">Actions</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $sub)
                        <tr>
                            <td>{{ $subscriptions->firstItem() + $loop->index }}</td>
                            <td class="text-neutral-900 dark:text-white fw-medium text-sm">
                                {{ $sub->email }}
                            </td>
                            <td class="text-xs fw-medium text-secondary-light dark:text-neutral-400">
                                {{ $sub->created_at?->format('d M Y, h:i A') ?? 'N/A' }}
                            </td>
                            @can('delete_newsletter_subscriptions')
                                <td>
                                    <div class="d-flex justify-content-end align-items-center">
                                        <form action="{{ route('newsletter-subscriptions.destroy', $sub->id) }}"
                                              method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="item-icon-btn remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-semibold w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle border-0"
                                                    data-confirm-title="Delete Subscriber"
                                                    data-confirm-message="Are you sure you want to permanently delete this email subscription?"
                                                    title="Delete Subscriber">
                                                <iconify-icon icon="fluent:delete-24-regular" class="item-icon text-sm"></iconify-icon>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endcan
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()?->can('delete_newsletter_subscriptions') ? 4 : 3 }}" class="text-center py-48 text-secondary-light dark:text-neutral-500 text-sm">
                                <iconify-icon icon="solar:letter-opened-bold-duotone" class="text-5xl text-neutral-300 dark:text-neutral-700 d-block mb-12 mx-auto"></iconify-icon>
                                No subscribers matched your search parameters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subscriptions->hasPages())
            <div class="mt-24 pt-20 border-top border-neutral-100 dark:border-neutral-800 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="text-muted text-xs">
                    Showing {{ $subscriptions->firstItem() }} to {{ $subscriptions->lastItem() }}
                    of {{ $subscriptions->total() }} subscribers
                </div>

                <div>
                    {{ $subscriptions->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Prevent Flash of Unstyled Filters Panel by fading it in
        const filtersCard = document.getElementById('newsletterFiltersCard');
        if (filtersCard) {
            filtersCard.style.opacity = '1';
        }

        document.querySelectorAll('.delete-form').forEach(function(form) {
            const button = form.querySelector('button[type="submit"]');
            button.addEventListener('click', function(event) {
                event.preventDefault();

                window.openAppConfirm({
                    title: button.dataset.confirmTitle || 'Delete Subscriber',
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
