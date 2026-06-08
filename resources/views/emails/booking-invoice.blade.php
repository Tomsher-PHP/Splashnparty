<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>

<h2>Booking Confirmation</h2>

<p>Dear {{ $booking->contact_name }},</p>

<p>Your booking has been received successfully.</p>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <td><strong>Booking Reference</strong></td>
        <td>{{ $booking->booking_reference }}</td>
    </tr>

    <tr>
        <td><strong>Booking Date</strong></td>
        <td>{{ $booking->booking_date }}</td>
    </tr>

    <tr>
        <td><strong>Adults</strong></td>
        <td>{{ $booking->adult_count }}</td>
    </tr>

    <tr>
        <td><strong>Children</strong></td>
        <td>{{ $booking->children_count }}</td>
    </tr>

    <tr>
        <td><strong>Total Amount</strong></td>
        <td>AED {{ number_format($booking->total_amount, 2) }}</td>
    </tr>
</table>

<p>Thank you.</p>

</body>
</html>