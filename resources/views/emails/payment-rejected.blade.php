<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Rejected</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f5f5f5; padding: 40px;">
    <div style="max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h2 style="color: #dc2626; margin: 0 0 20px 0;">Payment Rejected</h2>
        <p>Dear {{ $user->name }},</p>
        <p>We're sorry to inform you that your payment for order <strong>#{{ $order->order_number }}</strong> has been rejected.</p>
        <p><strong>Reason:</strong> {{ $rejectionReason }}</p>
        <p>Please re-upload a valid proof of payment to complete your order.</p>
        <div style="margin-top: 30px;">
            <a href="{{ route('orders.confirmation', $order->order_number) }}" style="display: inline-block; padding: 12px 24px; background-color: #1A8DFF; color: white; text-decoration: none; border-radius: 4px;">Re-upload Payment Proof</a>
        </div>
    </div>
</body>
</html>
