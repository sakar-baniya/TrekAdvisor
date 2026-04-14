<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trek Booking Receipt</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.5; color: #222;">
    <h2>Trek Payment Receipt</h2>

    <p>Namaste {{ $booking->user->name ?? 'Traveler' }},</p>

    <p>Your payment has been received. Here is your receipt summary.</p>

    <p>
        <strong>Booking Reference:</strong> {{ $booking->booking_reference }}<br>
        <strong>Trek:</strong> {{ $booking->departure->trek->title ?? 'N/A' }}<br>
        <strong>Departure Date:</strong> {{ optional($booking->departure->start_date)->format('M d, Y') ?? 'N/A' }}<br>
        <strong>Total Passengers:</strong> {{ $booking->total_passengers }}<br>
        <strong>Amount:</strong> {{ $payment?->currency ?? 'NPR' }} {{ number_format((float) $booking->total_price, 2) }}<br>
        <strong>Payment Status:</strong> {{ strtoupper((string) ($payment?->status ?? 'success')) }}<br>
        <strong>Transaction ID:</strong> {{ $payment?->transaction_id ?? 'N/A' }}
    </p>

    <p>Thank you for choosing TrekAdvisor.</p>
</body>
</html>
