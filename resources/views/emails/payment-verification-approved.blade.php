<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('email.payment_successful') }} - {{ config('app.name') }}</title>
    <!--[if mso]>
    <style type="text/css">
        body, table, td, a { font-family: Arial, Helvetica, sans-serif !important; }
    </style>
    <![endif]-->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        
        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            background-color: #F8F9FC;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        table { border-collapse: collapse !important; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 1px solid #E5E9F2;
        }

        .header-logo {
            padding: 25px 0;
            text-align: center;
            background-color: #FFF9F9;
        }

        .hero-section {
            padding: 40px 30px;
            text-align: center;
        }

        .success-icon-container {
            margin-bottom: 24px;
        }

        .success-icon-circle {
            display: inline-block;
            width: 80px;
            height: 80px;
            background-color: #DEF7EC;
            border-radius: 50%;
            position: relative;
        }

        .success-icon-inner {
            display: inline-block;
            width: 56px;
            height: 56px;
            background-color: #16A34A;
            border-radius: 50%;
            margin-top: 12px;
            line-height: 56px;
            text-align: center;
        }

        .title {
            margin: 0;
            font-size: 32px;
            font-weight: 800;
            color: #111827;
            letter-spacing: -0.025em;
        }

        .subtitle {
            margin: 8px 0 0;
            font-size: 18px;
            font-weight: 500;
            color: #6B7280;
        }

        .greeting-box {
            padding: 0 40px;
            margin-bottom: 30px;
            text-align: center;
        }

        .greeting-text {
            font-size: 15px;
            line-height: 1.6;
            color: #374151;
        }

        .order-number-badge {
            background-color: #FFF1F1;
            color: #E52D27;
            padding: 2px 8px;
            border-radius: 4px;
            font-family: monospace;
            font-weight: 600;
        }

        .divider {
            border-top: 1px dashed #E5E7EB;
            margin: 30px 40px;
        }

        .summary-header {
            padding: 0 40px;
            margin-bottom: 16px;
        }

        .summary-title {
            font-size: 12px;
            font-weight: 700;
            color: #9CA3AF;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .order-card {
            margin: 0 40px 30px;
            padding: 20px;
            border: 1px solid #F3F4F6;
            border-radius: 12px;
            background-color: #ffffff;
        }

        .event-info-table td {
            padding: 8px 0;
        }

        .info-label {
            font-size: 14px;
            color: #6B7280;
            width: 100px;
        }

        .info-value {
            font-size: 14px;
            color: #111827;
            font-weight: 600;
            text-align: right;
        }

        .total-row td {
            padding-top: 16px;
            border-top: 1px solid #F3F4F6;
        }

        .total-label {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
        }

        .total-amount {
            font-size: 20px;
            font-weight: 800;
            color: #E52D27;
            text-align: right;
        }

        .actions-section {
            padding: 0 40px 40px;
            text-align: center;
        }

        .btn-primary {
            display: inline-block;
            background-color: #E52D27;
            color: #ffffff !important;
            padding: 18px 40px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(229, 45, 39, 0.3);
            margin-bottom: 20px;
        }

        .btn-link {
            display: inline-block;
            color: #6B7280 !important;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-link-icon {
            vertical-align: middle;
            margin-right: 6px;
        }

        .footer-premium {
            background-color: #0B1221;
            padding: 50px 40px;
            text-align: center;
            color: #9CA3AF;
        }

        .social-icons {
            margin-bottom: 30px;
        }

        .social-icon-circle {
            display: inline-block;
            width: 36px;
            height: 36px;
            background-color: #1F2937;
            border-radius: 50%;
            line-height: 36px;
            text-align: center;
            margin: 0 8px;
        }

        .footer-text {
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .help-center-link {
            color: #E52D27;
            text-decoration: underline;
        }

        .copyright-text {
            font-size: 11px;
            color: #4B5563;
        }

        @media screen and (max-width: 480px) {
            .container { border: none; }
            .hero-section { padding: 30px 20px; }
            .greeting-box, .divider, .summary-header, .order-card, .actions-section { padding-left: 20px; padding-right: 20px; margin-left: 20px; margin-right: 20px; }
            .title { font-size: 28px; }
        }
    </style>
</head>
<body>
    <div style="background-color: #F8F9FC; padding: 20px 0;">
        <div class="container">
            <!-- Header Logo -->
            <div class="header-logo">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center">
                            <div style="display: inline-flex; align-items: center;">
                                <div style="background-color: #E52D27; color: #ffffff; padding: 6px; border-radius: 6px; margin-right: 10px;">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="display: block;"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6v-2a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v2z"/><line x1="13" y1="3" x2="13" y2="21"/></svg>
                                </div>
                                <span style="font-size: 20px; font-weight: 800; color: #111827; letter-spacing: 1px;">{{ config('app.name') }}</span>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Hero Section -->
            <div class="hero-section">
                <div class="success-icon-container">
                    <div class="success-icon-circle">
                         <div class="success-icon-inner">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" style="margin-top: 14px;"><polyline points="20 6 9 17 4 12"></polyline></svg>
                         </div>
                    </div>
                </div>
                <h1 class="title">{{ __('email.payment_successful') }}</h1>
                <p class="subtitle">{{ __('email.all_set_for_event') }}</p>
            </div>

            <!-- Greeting -->
            <div class="greeting-box">
                <p class="greeting-text">
                    {!! __('email.order_confirmed_message', [
                        'name' => '<strong>' . e($user->name) . '</strong>', 
                        'order_number' => '<span class="order-number-badge">#' . e($order->order_number) . '</span>'
                    ]) !!}
                    <br><br>
                    {{ __('email.receipt_sent_message') }}
                </p>
            </div>

            <div class="divider"></div>

            <!-- Summary Header -->
            <div class="summary-header">
                <h3 class="summary-title">{{ __('email.order_summary') }}</h3>
            </div>

            <!-- Order Card -->
            <div class="order-card">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="64" valign="top" style="padding-right: 16px;">
                            @if($event->banner)
                                <img src="{{ $event->banner->url }}" alt="{{ $event->title }}" style="width: 64px; height: 64px; border-radius: 12px; object-fit: cover; display: block; border: 1px solid #F3F4F6;">
                            @else
                                <div style="width: 64px; height: 64px; background-color: #F8F9FC; border-radius: 12px; text-align: center; line-height: 64px; border: 1px solid #F3F4F6;">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-top: 18px;"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                                </div>
                            @endif
                        </td>
                        <td valign="top">
                            <h2 style="margin: 0 0 4px; font-size: 18px; font-weight: 700; color: #111827;">{{ $event->title }}</h2>
                            <div style="display: flex; align-items: center; color: #6B7280; font-size: 13px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 4px;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                {{ $event->venue_name ?? $event->location }}
                            </div>
                        </td>
                    </tr>
                </table>

                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="event-info-table" style="margin-top: 20px;">
                    <tr>
                        <td class="info-label">Date & Time</td>
                        <td class="info-value">{{ $event->event_date?->format('M d, Y') }} • {{ $event->event_date?->format('H:i A') }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Ticket Type</td>
                        <td class="info-value">
                            @foreach($order->orderItems as $item)
                                {{ $item->ticketType->name }} (x{{ $item->quantity }}){{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </td>
                    </tr>
                    <tr class="total-row">
                        <td class="total-label">{{ __('email.total_paid') }}</td>
                        <td class="total-amount">{{ Number::currency($order->total_amount, 'IDR') }}</td>
                    </tr>
                </table>
            </div>

            <!-- Actions -->
            <div class="actions-section">
                <a href="{{ route('profile.orders.show', $order->order_number) }}" class="btn-primary">
                    {{ __('email.view_order_details') }}
                </a>
                <br>
            </div>

            <!-- Footer -->
            <div class="footer-premium">
                <p class="footer-text" style="color: #ffffff; font-weight: 500; font-size: 15px;">
                    {{ __('email.footer_thank_you') }}
                </p>
                
                <div class="social-icons">
                    <a href="#" class="social-icon-circle">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="#9CA3AF"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                    </a>
                    <a href="#" class="social-icon-circle">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="#9CA3AF"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
                    </a>
                    <a href="#" class="social-icon-circle">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="#9CA3AF"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                    </a>
                </div>

                <div style="border-top: 1px solid #1F2937; padding-top: 30px; margin-top: 30px;">
                    <p style="margin: 0 0 10px; font-size: 14px;">
                        {{ __('email.need_help') }} <a href="#" class="help-center-link">{{ __('email.visit_help_center') }}</a>
                    </p>
                    <p class="copyright-text">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
