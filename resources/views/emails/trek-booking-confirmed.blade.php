<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.5; color: #222;">
    <h2>Booking Confirmed</h2>

    <p>Namaste {{ $booking->user->name ?? 'Traveler' }},</p>

    <p>Your trek booking has been confirmed.</p>

    <p>
        <strong>Booking Reference:</strong> {{ $booking->booking_reference }}<br>
        <strong>Trek:</strong> {{ $booking->departure->trek->title ?? 'N/A' }}<br>
        <strong>Departure Date:</strong> {{ optional($booking->departure->start_date)->format('M d, Y') ?? 'N/A' }}<br>
        <strong>Total Passengers:</strong> {{ $booking->total_passengers }}<br>
        <strong>Total Amount:</strong> NPR {{ number_format((float) $booking->total_price, 2) }}
    </p>

    @if(!empty($supportNumber))
        <p>
            For more details, contact us on WhatsApp:<br>
            <a href="https://wa.me/{{ $supportNumber }}" target="_blank" rel="noopener">+{{ $supportNumber }}</a>
        </p>
    @else
        <p>
            For more details, please contact our support team.
        </p>
    @endif

    <p>Thank you for choosing TrekAdvisor.</p>
</body>
</html>
