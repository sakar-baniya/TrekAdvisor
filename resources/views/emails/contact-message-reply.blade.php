<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Reply</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.5; color: #222;">
    <h2>Support Reply</h2>

    <p>Namaste {{ $messageModel->name ?? 'Customer' }},</p>

    <p>Our team has replied to your message.</p>

    <p>
        <strong>Your Subject:</strong> {{ $messageModel->subject }}<br>
        <strong>Your Message:</strong><br>
        {{ $messageModel->message }}
    </p>

    <p>
        <strong>Our Reply:</strong><br>
        {{ $messageModel->staff_response }}
    </p>

    <p>Thank you for contacting TrekAdvisor.</p>
</body>
</html>
