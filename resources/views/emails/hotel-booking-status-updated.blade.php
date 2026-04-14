<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking Status Updated</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.5; color: #222;">
    <h2>Hotel Booking Status Updated</h2>

    <p>Namaste {{ $booking->user->name ?? 'Traveler' }},</p>

    <p>Your hotel booking status has been updated.</p>

    <p>
        <strong>Booking Reference:</strong> {{ $booking->booking_reference }}<br>
        <strong>Hotel:</strong> {{ $booking->hotelRoom?->hotel?->name ?? 'N/A' }}<br>
        <strong>Previous Status:</strong> {{ ucwords(str_replace('_', ' ', $oldStatus)) }}<br>
        <strong>New Status:</strong> {{ ucwords(str_replace('_', ' ', $newStatus)) }}
    </p>

    <p>You can view full booking details from your account dashboard.</p>

    <p>Thank you for choosing TrekAdvisor.</p>
</body>
</html>
