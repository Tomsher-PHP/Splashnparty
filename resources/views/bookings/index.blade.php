@extends('layouts.app')

@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <h6 class="fw-semibold mb-0">Bookings</h6>
    <div class="d-flex align-items-center gap-2">
        <span class="bg-primary-50 text-primary-600 px-20 py-8 rounded fw-medium text-sm">{{ $bookings->total() }} Total
            Bookings</span>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white py-16">
        <form method="GET" action="{{ route('bookings.index') }}">
            <div class="row g-3 align-items-end">
                <!-- Keyword search -->
                <div class="col-md-3 col-sm-6">
                    {{-- <label class="form-label text-sm fw-medium text-secondary mb-1">Search Keyword</label> --}}
                    <input type="text"
                        class="form-control form-control-sm"
                        placeholder="Search ref, name, phone..."
                        name="keyword"
                        value="{{ request('keyword') }}">
                </div>

                <!-- Branch select -->
                <div class="col-md-3 col-sm-6">
                    {{-- <label class="form-label text-sm fw-medium text-secondary mb-1">Branch</label> --}}
                    <select name="branch_id" class="form-select form-select-sm">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->title }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Package select -->
                <div class="col-md-3 col-sm-6">
                    {{-- <label class="form-label text-sm fw-medium text-secondary mb-1">Package</label> --}}
                    <select name="package_id" class="form-select form-select-sm">
                        <option value="">All Packages</option>
                        @foreach($packages as $package)
                        <option value="{{ $package->id }}" {{ request('package_id') == $package->id ? 'selected' : '' }}>
                            {{ $package->title }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Reservation Date Range -->
                <div class="col-md-3 col-sm-6">
                    {{-- <label class="form-label text-sm fw-medium text-secondary mb-1">Reservation Date</label> --}}
                    <input type="text"
                        name="reservation_date_range"
                        class="form-control form-control-sm flatpickr-range bg-white"
                        placeholder="Select reservation date range"
                        value="{{ request('reservation_date_range') }}">
                </div>

                <!-- Booked At Date Range -->
                <div class="col-md-3 col-sm-6">
                    {{-- <label class="form-label text-sm fw-medium text-secondary mb-1">Booked At</label> --}}
                    <input type="text"
                        name="booked_date_range"
                        class="form-control form-control-sm flatpickr-range bg-white"
                        placeholder="Select booked at range"
                        value="{{ request('booked_date_range') }}">
                </div>

                <!-- Action buttons -->
                <div class=" col-md-1 col-sm-3 d-inline-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary-600 px-16 w-100" title="Filter">
                        <i class="ri-search-line me-1"></i> 
                    </button>
                    <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-outline-secondary px-16 w-100 text-center" title="Reset">
                        <i class="ri-refresh-line me-1"></i> 
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="card-body">
        <div class="overflow-x-auto">
            <table class="table bordered-table mb-0">
                <thead class="bg-light">
                    <tr>
                        <th width="60">#</th>
                        <th>Booking Ref</th>
                        <th style="max-width: 180px;">Customer</th>
                        <th style="max-width: 150px;">Branch</th>
                        <th style="max-width: 200px;">Package</th>
                        <th class="text-center">Reservation Date</th>
                        
                        <th class="text-center">Adults</th>
                        <th class="text-center">Children</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Booked At</th>
                        <th width="180" class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($bookings as $key => $booking)
                    <tr>
                        <td>
                            {{ $bookings->firstItem() + $key }}
                        </td>
                        <td>{{ $booking->booking_reference }}</td>
                        <td style="max-width: 180px; white-space: normal; word-break: break-word;">
                            <div class="fw-semibold text-dark">{{ $booking->contact_name }}</div>
                            <div class="text-xs text-secondary mt-1">
                                <span class="d-block"><i class="ri-phone-line align-middle text-muted me-1"></i>{{ $booking->phone }}</span>
                                @if($booking->email)
                                <span class="d-block text-truncate" title="{{ $booking->email }}"><i class="ri-mail-line align-middle text-muted me-1"></i>{{ $booking->email }}</span>
                                @endif
                            </div>
                        </td>
                        <td style="max-width: 150px; white-space: normal; word-break: break-word;">{{ $booking->branch?->title ?? 'N/A' }}</td>
                        <td style="max-width: 200px; white-space: normal; word-break: break-word;">{{ $booking->package?->title ?? 'N/A' }}</td>
                        <td class="text-center">{{ $booking->booking_date ? $booking->booking_date->format('Y-m-d') : 'N/A' }}</td>
                        <td class="text-center">{{ $booking->adult_count }}</td>
                        <td class="text-center">{{ $booking->child_count }}</td>
                        <td class="text-center">{{ $booking->total_amount }}</td>
                        <td class="text-center">{{ $booking->created_at ? $booking->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                                                                        
                        <td class="text-center">
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                @can('view_bookings')
                                <a href="{{ route('bookings.show', $booking->id) }}"
                                class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle">
                                    <iconify-icon icon="mdi:eye-outline" class="menu-icon"></iconify-icon>
                                </a>
                                @endcan

                                @can('generate_invoice')
                                    <a href="{{ route('bookings.invoice', $booking->id) }}"
                                    class="bg-info-focus text-info-600 bg-hover-info-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle">
                                        <iconify-icon icon="mdi:download-outline" class="menu-icon"></iconify-icon>
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center py-24 text-muted">
                            No bookings found matching filters.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <small class="text-muted">
                    Showing {{ $bookings->firstItem() }} to {{ $bookings->lastItem() }} of {{ $bookings->total() }} entries
                </small>
            </div>
            <div>
                {{ $bookings->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('assets/js/flatpickr.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        flatpickr(".flatpickr-range", {
            mode: "range",
            dateFormat: "Y-m-d",
            allowInput: false,
        });
    });
</script>
@endsection