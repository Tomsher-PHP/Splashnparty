@extends('layouts.app')

@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Bookings</h6>
    <div class="d-flex align-items-center gap-2">
        <span class="bg-primary-50 text-primary-600 px-20 py-8 rounded fw-medium text-sm">{{ $bookings->total() }} Total
            Bookings</span>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" action="{{ route('bookings.index') }}">
            <div class="d-flex flex-wrap align-items-end gap-3">
                <div>
                    <input type="text"
                        class="form-control form-control-sm"
                        placeholder="Reference No"
                        name="booking_reference"
                        value="{{ request('booking_reference') }}">
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-primary-600">
                        <i class="ri-search-line"></i> Filter
                    </button>

                    <a href="{{ route('bookings.index') }}"
                        class="btn btn-sm btn-outline-secondary">
                        Reset
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
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Date</th>
                        <th>Adults</th>
                        <th>Children</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($bookings as $key => $booking)

                    <tr>
                        <td>
                            {{ $key + 1 }}
                        </td>
                        <td>{{ $booking->booking_reference }}</td>
                        <td>{{ $booking->contact_name }}</td>
                        <td>{{ $booking->phone }}</td>
                        <td>{{ $booking->booking_date }}</td>
                        <td>{{ $booking->adult_count }}</td>
                        <td>{{ $booking->child_count }}</td>
                        <td>{{ $booking->total_amount }}</td>
                        <td>{{ ucfirst($booking->status) }}</td>

                        <td>
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                @can('view_bookings')
                                <a href="{{ route('bookings.show',$booking->id) }}"
                                class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle">
                                    <iconify-icon icon="mdi:eye-outline"
                                            class="menu-icon">
                                        </iconify-icon>
                                </a>
                                @endcan

                                @can('generate_invoice')
                                    <a href="{{ route('bookings.invoice', $booking->id) }}"
                                    class="bg-info-focus text-info-600 bg-hover-info-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle">
                                        <iconify-icon icon="mdi:download-outline"
                                            class="menu-icon">
                                        </iconify-icon>
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>

                    @endforeach
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
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</div>

@endsection