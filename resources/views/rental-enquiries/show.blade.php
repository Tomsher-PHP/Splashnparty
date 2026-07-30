@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-24 gap-3">
    <div>
        <h6 class="mb-4 fw-bold text-neutral-900 dark:text-white">Rental Enquiry Details</h6>
        <p class="mb-0 text-xs text-secondary-light dark:text-neutral-400">View detailed payload and customer messages captured from the rental forms.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('rental-enquiries.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-2">
            <i class="ri-arrow-left-line"></i> Back to Enquiries
        </a>
        @can('delete_rental_enquiries')
            <form action="{{ route('rental-enquiries.destroy', $rentalEnquiry) }}" method="POST" class="delete-form d-inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm d-inline-flex align-items-center gap-2 border-0"
                        data-confirm-title="Delete Rental Enquiry"
                        data-confirm-message="Are you sure you want to permanently delete this rental enquiry?">
                    <i class="ri-delete-bin-line"></i> Delete
                </button>
            </form>
        @endcan
    </div>
</div>

<div class="row gy-4">
    <!-- Message Content Column -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-28">
                <div class="mb-24 pb-20 border-bottom border-neutral-100 dark:border-neutral-800">
                    <span class="badge bg-primary-50 dark:bg-primary-950 text-primary-600 dark:text-primary-400 text-3xs px-12 py-6 rounded fw-bold border border-primary-100 border-opacity-20 mb-12">
                        RENTAL ENQUIRY
                    </span>
                    @if($rentalEnquiry->rentalItem)
                        <h6 class="text-xl fw-bold text-neutral-900 dark:text-white mb-8">
                            Enquiry for: {{ $rentalEnquiry->rentalItem->title }} 
                            @can('edit_rental_items')
                                <a href="{{ route('rental-items.edit', $rentalEnquiry->rentalItem->id) }}" class="text-sm fw-medium text-primary-600 dark:text-primary-400 text-decoration-none ms-8" target="_blank" title="View Rental Item Details">
                                    <i class="ri-external-link-line"></i> [Edit Rental Item]
                                </a>
                            @endcan
                        </h6>
                    @else
                        <h6 class="text-xl fw-bold text-neutral-900 dark:text-white mb-8">General Rental Enquiry</h6>
                    @endif
                    <div class="d-flex align-items-center gap-2 text-xs text-secondary-light dark:text-neutral-400">
                        <iconify-icon icon="solar:clock-circle-bold" class="text-sm"></iconify-icon>
                        <span>Received on {{ $rentalEnquiry->created_at?->format('l, d M Y - h:i A') ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="message-display-box p-24 bg-neutral-50 dark:bg-neutral-900 bg-opacity-40 rounded-16 border border-neutral-100 border-opacity-50 dark:border-neutral-800 dark:border-opacity-50">
                    <p style="white-space: pre-wrap;" class="text-neutral-800 dark:text-neutral-200 mb-0">{{ $rentalEnquiry->message }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Profile Sidebar Column -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-28 d-flex flex-column justify-content-between">
                <div>
                    <!-- Visual Initial Avatar Block -->
                    <div class="text-center mb-24 pb-24 border-bottom border-neutral-100 dark:border-neutral-800">
                        <div class="w-50-px h-50-px bg-primary-100 dark:bg-primary-950 text-primary-600 dark:text-primary-400 rounded-circle d-flex justify-content-center align-items-center font-bold text-xl mx-auto mb-16 shadow-sm border border-primary-200 border-opacity-35">
                            {{ strtoupper(substr($rentalEnquiry->name ?? 'U', 0, 1)) }}
                        </div>
                        <h6 class="fw-bold text-neutral-900 dark:text-white mb-4">{{ $rentalEnquiry->name }}</h6>
                        <span class="text-xs text-secondary-light dark:text-neutral-400">Rental Enquirer</span>
                    </div>

                    <!-- Details Grid -->
                    <h6 class="text-lg fw-bold text-secondary-light dark:text-neutral-500 uppercase tracking-widest mb-16">Contact Credentials</h6>
                    
                    <div class="d-flex flex-column gap-20 mb-28">
                        <div>
                            <span class="text-2xs text-secondary-light dark:text-neutral-400 d-block mb-4">EMAIL ADDRESS</span>
                            <a href="mailto:{{ $rentalEnquiry->email }}" class="text-sm fw-semibold text-primary-600 dark:text-primary-400 text-decoration-none d-inline-flex align-items-center gap-2">
                                <i class="ri-mail-line text-md"></i>
                                {{ $rentalEnquiry->email }}
                            </a>
                        </div>

                        <div>
                            <span class="text-2xs text-secondary-light dark:text-neutral-400 d-block mb-4">PHONE NUMBER</span>
                            <a href="tel:{{ $rentalEnquiry->phone }}" class="text-sm fw-semibold text-primary-600 dark:text-primary-400 text-decoration-none d-inline-flex align-items-center gap-2">
                                <i class="ri-phone-line text-md"></i>
                                {{ $rentalEnquiry->phone }}
                            </a>
                        </div>

                        @if($rentalEnquiry->rentalItem)
                            <div>
                                <span class="text-2xs text-secondary-light dark:text-neutral-400 d-block mb-4">RENTAL ITEM OF INTEREST</span>
                                <span class="text-sm fw-semibold text-neutral-800 dark:text-neutral-200 d-inline-flex align-items-center gap-2">
                                    <i class="ri-building-4-line text-md text-primary-600"></i>
                                    {{ $rentalEnquiry->rentalItem->title }}
                                </span>
                                @if($rentalEnquiry->rentalItem->price)
                                    <span class="text-xs text-secondary-light dark:text-neutral-500 d-block mt-4">Price: AED {{ number_format($rentalEnquiry->rentalItem->price, 2) }}</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.querySelector('.delete-form');
        if (form) {
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
        }
    });
</script>
@endsection
