<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f3f4f6;
            color: #374151;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .header {
            background-color: #fff1f2;
            padding: 24px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ffe4e6;
        }
        .logo {
            color: #be123c;
            font-weight: 800;
            font-size: 20px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .logo-text {
            color: #be123c;
        }
        .order-id {
            color: #9ca3af;
            font-size: 14px;
            float: right;
        }
        .content-layout {
            display: table;
            width: 100%;
            padding: 32px;
            box-sizing: border-box;
        }
        .left-column {
            display: table-cell;
            vertical-align: top;
            padding-right: 20px;
        }
        .right-column {
            display: table-cell;
            vertical-align: top;
            width: 380px;
            min-width: 300px;
        }
        .success-header-table {
            width: 100%;
            margin-bottom: 24px;
        }
        .success-icon-cell {
            width: 48px;
            vertical-align: top;
            padding-right: 16px;
        }
        .success-icon {
            width: 48px;
            height: 48px;
            background-color: #d1fae5;
            color: #059669;
            border-radius: 50%;
            text-align: center;
            line-height: 48px;
            font-size: 24px;
            display: block;
        }
        .success-title {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 4px 0;
        }
        .verification-status {
            color: #d97706;
            font-weight: 500;
            font-size: 14px;
        }
        .greeting {
            color: #4b5563;
            margin-bottom: 32px;
        }
        .section-title {
            color: #9ca3af;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
            border-top: 1px solid #e5e7eb;
            padding-top: 24px;
        }
        .steps-container {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px;
        }
        .step-table {
            width: 100%;
            margin-bottom: 24px;
        }
        .step-table:last-child {
            margin-bottom: 0;
        }
        .step-number {
            font-weight: 600;
            color: #6b7280;
            width: 24px;
            vertical-align: top;
        }
        .step-content h4 {
            margin: 0 0 4px 0;
            color: #111827;
            font-size: 16px;
            font-weight: 600;
        }
        .step-content p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }

        /* Order Summary Card */
        .summary-card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 24px;
        }
        .event-details {
            padding: 20px;
        }
        .event-header-table {
            width: 100%;
            margin-bottom: 16px;
        }
        .event-image {
            width: 64px;
            height: 64px;
            background-color: #e5e7eb;
            border-radius: 8px;
            object-fit: cover;
            display: block;
        }
        .event-info h3 {
            margin: 0 0 4px 0;
            font-size: 16px;
            color: #111827;
        }
        .event-info p {
            margin: 0;
            font-size: 13px;
            color: #6b7280;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
        }
        .summary-label {
            color: #6b7280;
        }
        .summary-value {
            color: #111827;
            font-weight: 500;
            text-align: right;
        }
        .summary-table {
            width: 100%;
            margin-bottom: 12px;
            font-size: 14px;
        }
        .text-right {
            text-align: right;
        }
        .total-row {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
        }
        .total-label {
            font-weight: 700;
            color: #111827;
        }
        .total-value {
            font-size: 20px;
            font-weight: 700;
            color: #dc2626;
        }

        /* Bank Details */
        .bank-card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }
        .bank-header {
            color: #9ca3af;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 16px;
        }
        .bank-details-row {
            margin-bottom: 16px;
            border-bottom: 1px dashed #e5e7eb;
            padding-bottom: 16px;
        }
        .bank-details-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 0;
        }
        .cta-button {
            display: block;
            width: 100%;
            background-color: #dc2626;
            color: white !important;
            text-align: center;
            padding: 14px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 16px;
            box-sizing: border-box;
        }
        .back-link {
            display: block;
            text-align: center;
            color: #6b7280 !important;
            text-decoration: none;
            font-size: 14px;
        }

        /* Responsive override for mobile */
        @media only screen and (max-width: 768px) {
            .content-layout {
                display: block;
            }
            .left-column, .right-column {
                display: block;
                width: 100%;
                padding-right: 0;
            }
            .right-column {
                margin-top: 32px;
            }
            .header {
                display: block;
                text-align: center;
            }
            .logo {
                justify-content: center;
                margin-bottom: 10px;
            }
            .order-id {
                float: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <span style="font-size: 24px; margin-right: 8px;">🎟️</span> <span class="logo-text">{{ strtoupper(config('app.name')) }}</span>
            </div>
            <div class="order-id">
                Order #{{ $order->order_number }}
            </div>
        </div>

        <div class="content-layout">
            <!-- Left Column -->
            <div class="left-column">
                <table class="success-header-table" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td class="success-icon-cell">
                            <span class="success-icon">✓</span>
                        </td>
                        <td>
                            <h1 class="success-title">Order Successful!</h1>
                            <div class="verification-status">Verification in progress</div>
                        </td>
                    </tr>
                </table>

                <div class="greeting">
                    Hi <strong>{{ $user->name }}</strong>, your order <span style="background-color: #fef2f2; color: #dc2626; padding: 2px 6px; border-radius: 4px; font-size: 0.9em;">#{{ $order->order_number }}</span> has been placed.
                    <br>To finalize your booking, please complete the payment process outlined below.
                </div>

                <div class="section-title">NEXT STEPS</div>

                <div class="steps-container">
                    <table class="step-table" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td class="step-number">1.</td>
                            <td class="step-content">
                                <h4>Transfer the total amount</h4>
                                <p>Send the payment to the bank account listed in the Payment Details section.</p>
                            </td>
                        </tr>
                    </table>
                    <table class="step-table" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td class="step-number">2.</td>
                            <td class="step-content">
                                <h4>Upload your proof of payment</h4>
                                <p>Take a screenshot or photo of the receipt and upload it using the button on the right.</p>
                            </td>
                        </tr>
                    </table>
                    <table class="step-table" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td class="step-number">3.</td>
                            <td class="step-content">
                                <h4>Wait for admin approval</h4>
                                <p>We will verify your payment within 24 hours and send your tickets via email.</p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Right Column -->
            <div class="right-column">
                <div class="section-title" style="margin-top: 0; border-top: none; padding-top: 0;">ORDER SUMMARY</div>
                
                <div class="summary-card">
                    <div class="event-details">
                        <table class="event-header-table" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td width="72" valign="top">
                                    <img src="{{ $event->banner?->url ?? 'https://via.placeholder.com/64' }}" alt="Event" class="event-image">
                                </td>
                                <td valign="top">
                                    <div class="event-info">
                                        <h3>{{ $event->title }}</h3>
                                        <p>📍 {{ $event->venue_name ?? $event->location }}</p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        
                        <table class="summary-table" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td class="summary-label">Date</td>
                                <td class="summary-value text-right">{{ $event->event_date->format('M d, Y • h:i A') }}</td>
                            </tr>
                        </table>
                        
                        <table class="summary-table" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td class="summary-label" valign="top">Tickets</td>
                                <td class="summary-value text-right">
                                    @foreach($order->orderItems as $item)
                                        <div style="margin-bottom: 4px;">{{ $item->ticketType->name }} (x{{ $item->quantity }})</div>
                                    @endforeach
                                </td>
                            </tr>
                        </table>

                        <table class="summary-table total-row" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td class="total-label">Total To Pay</td>
                                <td class="total-value text-right">{{ Number::currency($order->total_amount ?? 0, 'IDR') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="bank-card">
                    <div class="bank-header">
                        <span>🏛️</span> BANK TRANSFER DETAILS
                    </div>
                    
                    @if($event->paymentBanks && $event->paymentBanks->count() > 0)
                        @foreach($event->paymentBanks as $bank)
                        <div class="bank-details-row">
                            <table class="summary-table" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="summary-label">Bank Name</td>
                                    <td class="bank-value text-right" style="font-weight: 600; color: #111827;">{{ $bank->bank_name }}</td>
                                </tr>
                                <tr>
                                    <td class="summary-label">Account No.</td>
                                    <td class="bank-value text-right" style="font-weight: 600; color: #111827;">{{ $bank->account_number }}</td>
                                </tr>
                                <tr>
                                    <td class="summary-label">Account Name</td>
                                    <td class="bank-value text-right" style="font-weight: 600; color: #111827;">{{ $bank->account_holder }}</td>
                                </tr>
                            </table>
                        </div>
                        @endforeach
                    @else
                        {{-- Fallback default bank if none assigned --}}
                        <div class="bank-details-row">
                            <table class="summary-table" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="summary-label">Bank Name</td>
                                    <td class="bank-value text-right" style="font-weight: 600; color: #111827;">National Trust Bank</td>
                                </tr>
                                <tr>
                                    <td class="summary-label">Account No.</td>
                                    <td class="bank-value text-right" style="font-weight: 600; color: #111827;">123-456-7890</td>
                                </tr>
                                <tr>
                                    <td class="summary-label">Account Name</td>
                                    <td class="bank-value text-right" style="font-weight: 600; color: #111827;">{{ config('app.name') }} Official</td>
                                </tr>
                            </table>
                        </div>
                    @endif
                </div>

                <a href="{{ route('profile.orders.show', $order->uuid) }}" class="cta-button">
                    Upload Payment Proof
                </a>
                
                {{-- Assuming orders.show route exists and takes order object or ID --}}
                <a href="{{ route('events.index') }}" class="back-link">
                    ← Search Event
                </a>
            </div>
        </div>
    </div>
</body>
</html>

