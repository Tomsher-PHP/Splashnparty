@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Booking Details</h6>
        <div class="d-flex align-items-center gap-2">
            <label>Payment Status: </label>
            <form method="POST"
                action="{{ route('bookings.payment-status', $booking->id) }}">
                @csrf
                <select name="payment_status"
                        class="form-control form-control-sm"
                        onchange="this.form.submit()">
                    <option value="paid"
                        {{ $booking->payment_status == 'paid' ? 'selected' : '' }}>
                        Paid
                    </option>
                    <option value="unpaid"
                        {{ $booking->payment_status == 'unpaid' ? 'selected' : '' }}>
                        UnPaid
                    </option>
                </select>
            </form>

            @can('generate_invoice')
                <a href="{{ route('bookings.invoice', $booking->id) }}"
                class="btn btn-sm btn-primary">
                    <i class="fas fa-file-download"></i>
                    Download Invoice
                </a>
            @endcan
        </div>
    </div>

    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th>Booking Ref</th>
                <td>{{ $booking->booking_reference }}</td>
            </tr>
            <tr>
                <th>Customer</th>
                <td>{{ $booking->contact_name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $booking->email }}</td>
            </tr>
            <tr>
                <th>Phone</th>
                <td>{{ $booking->phone }}</td>
            </tr>
            <tr>
                <th>Booking Date</th>
                <td>{{ $booking->booking_date }}</td>
            </tr>
            <tr>
                <th>Adults</th>
                <td>{{ $booking->adult_count }}</td>
            </tr>
            <tr>
                <th>Children</th>
                <td>{{ $booking->child_count }}</td>
            </tr>
            <tr>
                <th>Total Amount</th>
                <td>{{ $booking->total_amount }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ ucfirst($booking->status) }}</td>
            </tr>
            <tr>
                <th>Payment Status</th>
                <td>{{ ucfirst($booking->payment_status) }}</td>
            </tr>
            <tr>
                <th>Remarks</th>
                <td>{{ $booking->remarks }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection