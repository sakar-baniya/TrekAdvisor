<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #334155; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px; }
        .header { border-bottom: 2px solid #f1f5f9; padding-bottom: 15px; margin-bottom: 20px; }
        .label { font-weight: bold; color: #64748b; font-size: 0.875rem; text-transform: uppercase; }
        .value { margin-bottom: 15px; font-size: 1rem; }
        .message-box { background-color: #f8fafc; padding: 15px; border-radius: 6px; border: 1px solid #f1f5f9; white-space: pre-wrap; }
        .footer { margin-top: 30px; font-size: 0.75rem; color: #94a3b8; border-top: 1px solid #f1f5f9; pt: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="margin:0; color: #0f172a;">New Inquiry Received</h2>
            <p style="margin: 5px 0 0; font-size: 0.875rem; color: #64748b;">You have received a new message through the TrekAdvisor contact form.</p>
        </div>

        <div class="label">Sender Name</div>
        <div class="value">{{ $contactMessage->name }}</div>

        <div class="label">Email Address</div>
        <div class="value">{{ $contactMessage->email }}</div>

        <div class="label">Subject</div>
        <div class="value">{{ $contactMessage->subject }}</div>

        <div class="label">Message</div>
        <div class="message-box">{{ $contactMessage->message }}</div>

        <div class="footer">
            <p><strong>Note:</strong> You can reply directly to this email to respond to the customer.</p>
            <p>This message has also been saved in your <a href="{{ route('admin.contact-messages.show', $contactMessage) }}">Admin Dashboard</a>.</p>
        </div>
    </div>
</body>
</html>
