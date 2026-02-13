<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Loved Event Update</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f5f5f5; padding: 40px;">
    <div style="max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h2 style="color: #ec4899; margin: 0 0 20px 0;">Loved Event Update</h2>
        <p>Dear {{ $user->name }},</p>
        <p>The event <strong>{{ $event->title }}</strong> that you saved to your favorites has been updated.</p>
        <p><strong>What's New:</strong></p>
        <p>{{ $changes }}</p>
        <p>Don't miss out! You can still purchase tickets if available.</p>
        <div style="margin-top: 30px;">
            <a href="{{ route('events.show', $event->slug) }}" style="display: inline-block; padding: 12px 24px; background-color: #1A8DFF; color: white; text-decoration: none; border-radius: 4px;">View Event Details</a>
        </div>
    </div>
</body>
</html>
