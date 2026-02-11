<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Approved - {{ config('app.name') }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f6f9fc;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f6f9fc;
            padding-bottom: 40px;
        }
        .main {
            background-color: #ffffff;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-spacing: 0;
            color: #1a1f36;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
        }
        .header-image {
            width: 100%;
            height: 240px;
            object-fit: cover;
            display: block;
        }
        .content {
            padding: 40px 30px;
            text-align: center;
        }
        .badge {
            display: inline-block;
            background-color: #e6fcf5;
            color: #0ca678;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .success-icon {
            margin: 0 auto 20px;
            width: 64px;
            height: 64px;
            background-color: #e6fcf5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        h1 {
            font-size: 32px;
            font-weight: 800;
            margin: 0 0 10px;
            color: #1a1f36;
        }
        .subtext {
            color: #4f566b;
            font-size: 16px;
            line-height: 24px;
            margin-bottom: 30px;
        }
        .message-box {
            text-align: left;
            margin-bottom: 30px;
        }
        .message-box p {
            margin: 0 0 16px;
            font-size: 15px;
            line-height: 24px;
            color: #4f566b;
        }
        .button-group {
            margin: 30px 0;
            display: flex;
            gap: 16px;
            justify-content: center;
        }
        .btn {
            display: inline-block;
            padding: 14px 24px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            text-align: center;
        }
        .btn-primary {
            background-color: #4CAF50;
            color: #ffffff !important;
        }
        .btn-secondary {
            background-color: #f0f4f8;
            color: #2b3a4a !important;
        }
        .ticket-alert {
            background-color: #f8fafc;
            border-radius: 12px;
            padding: 24px;
            text-align: left;
            display: flex;
            gap: 16px;
            margin-top: 30px;
        }
        .alert-icon {
            width: 24px;
            height: 24px;
            flex-shrink: 0;
        }
        .alert-content h3 {
            margin: 0 0 4px;
            font-size: 16px;
            font-weight: 700;
            color: #1a1f36;
        }
        .alert-content p {
            margin: 0;
            font-size: 14px;
            line-height: 20px;
            color: #4f566b;
        }
        .quote {
            font-style: italic;
            color: #697386;
            margin: 40px 0;
            font-size: 16px;
        }
        .social-links {
            margin-bottom: 20px;
        }
        .social-links a {
            display: inline-block;
            margin: 0 8px;
            text-decoration: none;
        }
        .footer-links {
            margin-bottom: 30px;
        }
        .footer-links a {
            color: #697386;
            margin: 0 10px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
        }
        .legal {
            font-size: 12px;
            color: #a3acb9;
            line-height: 18px;
        }
        .legal a {
            color: #a3acb9;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <center class="wrapper">
        <table class="main" width="100%">
            <!-- Hero Image -->
            <tr>
                <td>
                    <img src="{{ $event->banner_image ?? 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=1200&q=80' }}" alt="{{ $event->title }}" class="header-image">
                    <div style="position: relative;">
                        <div style="position: absolute; bottom: 20px; left: 30px; text-align: left;">
                            <span class="badge" style="background-color: #4CAF50; color: white; margin-bottom: 8px;">Order Confirmed</span>
                            <h2 style="color: white; margin: 0; font-size: 28px; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">{{ $event->title }}</h2>
                        </div>
                    </div>
                </td>
            </tr>

            <!-- Main Content -->
            <tr>
                <td class="content">
                    <div class="success-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 6L9 17L4 12" stroke="#4CAF50" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>

                    <h1>Payment Approved!</h1>
                    <p class="subtext">Your transaction was successful and your spot is reserved.</p>

                    <div class="message-box">
                        <p>Dear {{ $user->name }},</p>
                        <p>Great news! Your payment for order <strong>#{{ $order->order_number }}</strong> has been verified and approved. We've processed your request and everything is ready to go.</p>
                    </div>

                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td align="center">
                                <table border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td align="center" style="padding-right: 15px;">
                                            <a href="{{ route('orders.invoice', $order) }}" class="btn btn-primary">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="vertical-align: middle; margin-right: 4px;">
                                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4m4-5l5 5 5-5m-5 5V3"/>
                                                </svg>
                                                Download Invoice
                                            </a>
                                        </td>
                                        <td align="center">
                                            <a href="{{ route('orders.status', $order->order_number) }}" class="btn btn-secondary">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="vertical-align: middle; margin-right: 4px;">
                                                    <path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5zm0 9h14"/>
                                                </svg>
                                                View My Tickets
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>

                    <div class="ticket-alert">
                        <div class="alert-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#4CAF50" stroke-width="2">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0"/>
                            </svg>
                        </div>
                        <div class="alert-content">
                            <h3>Your tickets are ready!</h3>
                            <p>You can find your tickets by clicking the "View My Tickets" button above. Make sure to have them ready on your phone when you arrive!</p>
                        </div>
                    </div>

                    <p class="quote">"Thank you for your purchase! We can't wait to see you there."</p>

                    <div class="social-links">
                        <a href="#"><img src="https://img.icons8.com/ios-filled/32/697386/facebook-new.png" width="24" height="24" alt="FB"></a>
                        <a href="#"><img src="https://img.icons8.com/ios-filled/32/697386/twitter.png" width="24" height="24" alt="TW"></a>
                        <a href="#"><img src="https://img.icons8.com/ios-filled/32/697386/instagram-new.png" width="24" height="24" alt="IG"></a>
                    </div>

                    <div class="footer-links">
                        <a href="#">FAQs</a>
                        <a href="#">Customer Support</a>
                        <a href="#">Privacy Policy</a>
                        <a href="#">Terms of Service</a>
                    </div>

                    <div class="legal">
                        <p>You received this email because you made a purchase on our platform.<br>
                        If you didn't make this purchase, please <a href="#">contact our support team</a> immediately.<br>
                        © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                    </div>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>
