<!DOCTYPE html>
<html>
<head>
    <title>Payment Rejected</title>
</head>
<body>
    <h1>Payment Rejected</h1>
    <p>Dear {{ $user->name }},</p>
    <p>Your payment for order #{{ $order->order_number }} was rejected.</p>
    <p>Reason: {{ $rejectionReason }}</p>
    <p>Please re-upload a valid proof of payment.</p>
    <p><a href="{{ route('orders.confirmation', $order->order_number) }}">Re-upload Payment Proof</a></p>
</body>
</html>
