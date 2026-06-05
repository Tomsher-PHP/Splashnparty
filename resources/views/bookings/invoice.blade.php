
<!DOCTYPE html>
<html>
    <head>
        <title>Invoice</title>
        <style>
            body {
                font-family: Verdana, Geneva, Tahoma, sans-serif;
                margin: 0;
                /* padding: 20px; */
            }

            .card {
                width: 100%;
            }

            .card-header {
                width: 100%;
                height: 50px;
                border-bottom: 1px solid #ccc;
                padding-bottom: 20px;
                margin-bottom: 20px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .card-header h1 {
                /* margin: 0;
                width: 50%;
                float: right;
                text-align: right; */
            }

            .invoice-info {
                width: 100%;
                display: flex;
                justify-content: space-between;
                margin-bottom: 55px;
                height: 170px;
            }

            .invoice-info-left {
                text-align: left;
                width: 50%;
                float: left;
            }

            .invoice-info-right {
                width: 50%;
                text-align: right;
                float: right;

            }

            .invoice-address {
                width: 100%;
                margin-bottom: 20px;
                display: flex;
                justify-content: space-between;
                margin-bottom: 20px;
                height: 250px;
            }

            .invoice-address p {
                margin: 5px 0;
                color: #666;
                line-height: 1.5;
            }

            .invoice-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;

            }

            .invoice-table th,
            .invoice-table td {
                border: 1px solid #ccc;
                padding: 8px;
                text-align: left;
            }

            .invoice-total {
                text-align: right;
            }

            .invoice-logo {
                /* width: 50%; */
                /* max-width: 150px; */
                margin-right: 20px;
                float: left;
                text-align: left;
            }

            .invoice-table .theader {
                background: #f5f6fa;

            }

            th {
                padding: 10px 15px;
                line-height: 1.55em;
            }

            td {
                padding: 10px 15px;
                line-height: 1.55em;
            }
        </style>
    </head>

    <body>
        <div class="card">
            <div class="card-header">
                <h1 class="mb-0">
                    Invoice - {{ $booking->booking_reference }}
                </h1>
            </div>

            <div class="card-body">

                <div class="invoice-info">

                    <div class="invoice-info-left">
                        <strong>Customer Details</strong>
                        <p class="mb-1">
                            <strong>Name:</strong>
                            {{ $booking->contact_name }}
                        </p>

                        <p class="mb-1">
                            <strong>Email:</strong>
                            {{ $booking->email }}
                        </p>

                        <p class="mb-1">
                            <strong>Phone:</strong>
                            {{ $booking->phone }}
                        </p>

                        <p class="mb-1">
                            <strong>Address:</strong>
                            {{ $booking->address }}
                        </p>

                        <p class="mb-1">
                            <strong>Emirate:</strong>
                            {{ $booking->emirate }}
                        </p>
                    </div>

                    <div class="invoice-info-right">
                        <strong>Booking Details</strong>
                        <p class="mb-1">
                            <strong>Reference:</strong>
                            {{ $booking->booking_reference }}
                        </p>
                        <p class="mb-1">
                            <strong>Booking Date:</strong>
                            {{ $booking->booking_date }}
                        </p>
                        <p class="mb-1">
                            <strong>Package:</strong>
                            {{ optional($booking->package)->title }}
                        </p>
                        <p class="mb-1">
                            <strong>Branch:</strong>
                            {{ optional($booking->branch)->title }}
                        </p>
                    </div>
                </div>

                <table class="invoice-table">

                    <thead>
                        <tr>
                            <th>Description</th>
                            <th width="150">Value</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <td>Adult Count</td>
                            <td>{{ $booking->adult_count }}</td>
                        </tr>

                        <tr>
                            <td>Child Count</td>
                            <td>{{ $booking->child_count }}</td>
                        </tr>

                        <tr>
                            <td>Food Type</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $booking->food_type)) }}</td>
                        </tr>

                        <tr>
                            <td>Subtotal</td>
                            <td>AED {{ number_format($booking->subtotal, 2) }}</td>
                        </tr>

                        <tr>
                            <td>VAT</td>
                            <td>AED {{ number_format($booking->vat, 2) }}</td>
                        </tr>

                        <tr>
                            <td>
                                <strong>Total Amount</strong>
                            </td>
                            <td>
                                <strong>
                                    AED {{ number_format($booking->total_amount, 2) }}
                                </strong>
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>
    </body>
</html>

