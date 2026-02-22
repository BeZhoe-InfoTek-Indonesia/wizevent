<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice \#{{ $order->order_number }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 13px;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 40px;
        }
        .header {
            width: 100%;
            margin-bottom: 40px;
            position: relative;
        }
        .logo-section {
            float: left;
            width: 50%;
        }
        .logo {
            font-size: 32px;
            font-weight: 900;
            color: #1a1f36;
            letter-spacing: -1px;
            margin: 0;
            line-height: 1;
        }
        .logo span {
            color: #1a1f36;
        }
        .tagline {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #718096;
            margin-top: 5px;
            font-weight: bold;
        }
        .tagline:before {
            content: "● ";
            color: #1a1f36;
        }
        .invoice-title-wrapper {
            float: right;
            width: 45%;
            text-align: right;
            position: relative;
        }
        .watermark-invoice {
            position: absolute;
            top: -20px;
            right: 0;
            font-size: 60px;
            font-weight: 900;
            color: #f1f4f8;
            z-index: -1;
            text-transform: uppercase;
        }
        .invoice-label {
            font-size: 24px;
            font-weight: 800;
            color: #1a1f36;
            margin-bottom: 10px;
        }
        .invoice-meta {
            font-size: 13px;
            color: #4a5568;
        }
        .invoice-meta strong {
            color: #1a1f36;
        }
        .divider {
            clear: both;
            border-bottom: 1px solid #edf2f7;
            padding-top: 20px;
            margin-bottom: 30px;
        }
        .info-grid {
            width: 100%;
            margin-bottom: 40px;
        }
        .info-grid td {
            width: 50%;
            vertical-align: top;
        }
        .section-header {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #a0aec0;
            font-weight: 800;
            margin-bottom: 15px;
            display: block;
        }
        .section-header i {
            margin-right: 5px;
        }
        .customer-name {
            font-size: 18px;
            font-weight: 800;
            color: #1a1f36;
            margin: 0 0 5px 0;
        }
        .customer-info {
            color: #718096;
            font-size: 13px;
        }
        .event-grid {
            width: 100%;
        }
        .event-item-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #a0aec0;
            font-weight: 800;
            margin-bottom: 3px;
        }
        .event-item-value {
            font-size: 14px;
            font-weight: 800;
            color: #2d3748;
            margin-bottom: 12px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            text-align: left;
            padding: 12px 15px;
            background-color: #f8fafc;
            color: #718096;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 800;
            border-bottom: 1px solid #edf2f7;
            border-top: 1px solid #edf2f7;
        }
        .items-table td {
            padding: 20px 15px;
            border-bottom: 1px solid #f7fafc;
            vertical-align: middle;
        }
        .item-desc {
            font-size: 15px;
            font-weight: 800;
            color: #1a1f36;
            margin-bottom: 4px;
        }
        .item-subdesc {
            font-size: 11px;
            color: #a0aec0;
            font-style: italic;
        }
        .qty-cell {
            font-size: 14px;
            font-weight: 700;
            color: #4a5568;
            text-align: center;
        }
        .price-cell {
            font-size: 14px;
            color: #718096;
            text-align: right;
        }
        .total-cell {
            font-size: 15px;
            font-weight: 800;
            color: #1a1f36;
            text-align: right;
        }
        .summary-wrapper {
            float: right;
            width: 40%;
            margin-top: 20px;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 8px 0;
            font-size: 14px;
        }
        .summary-label {
            color: #718096;
            text-align: left;
        }
        .summary-value {
            color: #1a1f36;
            text-align: right;
            padding-left: 10px;
        }
        .summary-total-row {
            border-top: 2px solid #1a1f36;
            margin-top: 10px;
        }
        .summary-total-row td {
            padding-top: 15px;
            font-size: 18px;
            font-weight: 800;
            color: #1a1f36;
        }
        .paid-stamp {
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            border: 8px solid #c6f6d5;
            color: #c6f6d5;
            font-size: 120px;
            font-weight: 900;
            padding: 10px 40px;
            border-radius: 20px;
            opacity: 0.4;
            z-index: 10;
            pointer-events: none;
            text-transform: uppercase;
            letter-spacing: 15px;
        }
        .footer {
            clear: both;
            margin-top: 100px;
            text-align: center;
            border-top: 1px solid #edf2f7;
            padding-top: 30px;
        }
        .footer-text {
            color: #a0aec0;
            font-size: 13px;
            font-style: italic;
            margin-bottom: 20px;
        }
        .footer-icons {
            color: #cbd5e0;
            font-size: 16px;
        }
        .clear {
            clear: both;
        }
        .pending-stamp {
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            border: 8px solid #ecc94b;
            color: #ecc94b;
            font-size: 100px;
            font-weight: 900;
            padding: 10px 40px;
            border-radius: 20px;
            opacity: 0.4;
            z-index: 10;
            pointer-events: none;
            text-transform: uppercase;
            letter-spacing: 15px;
        }
    </style>
</head>
<body>

    @if($order->status == 'completed')
    <div class="paid-stamp">PAID</div>
    @elseif(in_array($order->status, ['pending', 'pending_payment', 'pending_verification', 'payment_uploaded']))
    <div class="pending-stamp">PENDING</div>
    @elseif($order->status === 'cancelled')
    <div class="pending-stamp" style="border-color: #f56565; color: #f56565;">CANCELLED</div>
    @endif

    <div class="header">
        <div class="logo-section">
            <img src="{{ public_path('images/logo-difan.png') }}" alt="DIFAN EVENT" height="40" style="margin-bottom: 5px;">
            <div class="tagline">Event Management System</div>
        </div>
        <div class="invoice-title-wrapper">
            <div class="watermark-invoice">INVOICE</div>
            <div class="invoice-label">INVOICE</div>
            <div class="invoice-meta">
                Order #: <strong>{{ $order->order_number }}</strong><br>
                Date: <strong>{{ $order->created_at->format('d M Y') }}</strong>
            </div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="divider"></div>

    <table class="info-grid">
        <tr>
            <td>
                <span class="section-header">BILL TO</span>
                <h3 class="customer-name">{{ $order->user->name }}</h3>
                <div class="customer-info">
                    {{ $order->user->email }}<br>
                    {{-- Assuming we might have address in future, but based on screenshot --}}
                    123 Street Name, City<br>
                    Province, Country 12345
                </div>
            </td>
            <td>
                <span class="section-header">EVENT DETAILS</span>
                <div class="event-grid">
                    <div class="event-item-label">EVENT</div>
                    <div class="event-item-value">{{ strtoupper($order->event->title) }}</div>
                    
                    <table width="100%">
                        <tr>
                            <td width="55%">
                                <div class="event-item-label">DATE</div>
                                <div class="event-item-value">{{ $order->event->event_date->format('d M Y, H:i') }}</div>
                            </td>
                            <td>
                                <div class="event-item-label">VENUE</div>
                                <div class="event-item-value">{{ $order->event->venue_name ?? 'Indonesia Arena' }}</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th width="50%">Description</th>
                <th width="10%" style="text-align: center;">QTY</th>
                <th width="20%" style="text-align: right;">Unit Price</th>
                <th width="20%" style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr>
                <td>
                    <div class="item-desc">{{ $item->ticketType->name }}</div>
                    <div class="item-subdesc">{{ $item->ticketType->description ?? 'Regular admission' }}</div>
                </td>
                <td class="qty-cell">{{ $item->quantity }}</td>
                <td class="price-cell">{{ number_format($item->unit_price, 2) }}</td>
                <td class="total-cell">{{ number_format($item->total_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-wrapper">
        <table class="summary-table">
            <tr>
                <td class="summary-label">Subtotal</td>
                <td class="summary-value">{{ number_format($order->subtotal, 2) }}</td>
            </tr>
            @if($order->discount_amount > 0)
            <tr>
                <td class="summary-label">Discount</td>
                <td class="summary-value">-{{ number_format($order->discount_amount, 2) }}</td>
            </tr>
            @endif
            @if($order->tax_amount > 0)
            <tr>
                <td class="summary-label">Tax (10%)</td>
                <td class="summary-value">{{ number_format($order->tax_amount, 2) }}</td>
            </tr>
            @else
            {{-- Default tax row as per screenshot --}}
            <tr>
                <td class="summary-label">Tax (10%)</td>
                <td class="summary-value">{{ number_format($order->total_amount * 0.1, 2) }}</td>
            </tr>
            @endif
            <tr class="summary-total-row">
                <td class="summary-label">TOTAL</td>
                <td class="summary-value">IDR {{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="clear"></div>

    <div class="footer">
        <p class="footer-text">Thank you for your purchase! We look forward to seeing you at the event.</p>
        <div class="footer-icons">
            {{-- Placeholder icons using text/dots/border as PDF icons are tricky --}}
            &bull; &nbsp; &bull; &nbsp; &bull;
        </div>
    </div>

</body>
</html>
