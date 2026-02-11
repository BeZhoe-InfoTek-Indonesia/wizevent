<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body>
    <h1>Order Confirmation</h1>
    <p>Dear {{ $user->name }},</p>
    <p>Thank you for your order! Your order #{{ $order->order_number }} has been placed successfully.</p>
    <p>Please upload your payment proof to complete the process.</p>
    <p><a href="{{ route('orders.confirmation', $order->order_number) }}">Upload Payment Proof</a></p>
    <p>Order Summary:</p>
    <ul>
        @foreach($order->orderItems as $item)
            <li>{{ $item->ticketType->name }} x {{ $item->quantity }} - {{ Number::currency($item->total_price, 'IDR') }}</li>
        @endforeach
    </ul>
    <p>Total: {{ Number::currency($order->total_amount, 'IDR') }}</p>
</body>
</html>
