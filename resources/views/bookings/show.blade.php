@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Booking Details</h6>
        <div class="d-flex align-items-center gap-2">
            {{-- <label>Payment Status: </label>

            {{ ucfirst($booking->payment_status) }} --}}
            {{-- <form method="POST"
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
            </form> --}}

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
                <th width="30%">Booking Ref</th>
                <td>{{ $booking->booking_reference }}</td>
            </tr>
            <tr>
                <th>Branch</th>
                <td>{{ $booking->branch->title ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Package</th>
                <td>{{ $booking->package->title ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Customer Name</th>
                <td>{{ $booking->contact_name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $booking->email ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Phone</th>
                <td>{{ $booking->phone }}</td>
            </tr>
            <tr>
                <th>Emirate</th>
                <td>{{ $booking->emirate ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Address</th>
                <td>{{ $booking->address ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Booking Date</th>
                <td>{{ $booking->booking_date ? $booking->booking_date->format('Y-m-d') : 'N/A' }}</td>
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
                <th>Food Type</th>
                <td>{{ $booking->food_type ? ucfirst(str_replace('_', ' ', $booking->food_type)) : 'N/A' }}</td>
            </tr>
            @if($booking->food_type === 'with_food')
            <tr>
                <th>Food Preference</th>
                <td>{{ $booking->food_preference ? ucfirst(str_replace(['_', '-'], ' ', $booking->food_preference)) : 'N/A' }}</td>
            </tr>
            @endif
            <tr>
                <th>Subtotal</th>
                <td>{{ number_format($booking->subtotal, 2) }}</td>
            </tr>
            <tr>
                <th>VAT</th>
                <td>{{ number_format($booking->vat, 2) }}</td>
            </tr>
            <tr>
                <th>Total Amount</th>
                <td>{{ number_format($booking->total_amount, 2) }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ ucfirst($booking->status) }}</td>
            </tr>
            <tr>
                <th>Payment Status</th>
                <td style="font-size:16px !important;"> 
                    @if ($booking->payment_status === 'paid')
                        <span class="badge bg-success ">Paid</span>
                    @elseif ($booking->payment_status === 'unpaid')
                        <span class="badge bg-danger">Unpaid</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Remarks</th>
                <td>{{ $booking->remarks ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection