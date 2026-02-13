<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Event Cancelled</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f5f5f5; padding: 40px;">
    <div style="max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h2 style="color: #dc2626; margin: 0 0 20px 0;">Event Cancelled</h2>
        <p>Dear {{ $user->name }},</p>
        <p>We're sorry to inform you that the event <strong>{{ $event->title }}</strong> has been cancelled.</p>
        <p><strong>Cancellation Reason:</strong></p>
        <p>{{ $reason }}</p>
        <p>Your order for this event has been refunded. If you have any questions, please contact our support team.</p>
        <div style="margin-top: 30px;">
            <a href="{{ route('orders.confirmation', $event->orders->first()?->order_number ?? '') }}" style="display: inline-block; padding: 12px 24px; background-color: #666; color: white; text-decoration: none; border-radius: 4px;">View Order Details</a>
        </div>
    </div>
</body>
</html>
