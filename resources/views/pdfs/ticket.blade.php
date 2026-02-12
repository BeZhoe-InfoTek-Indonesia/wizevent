<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Ticket \#{{ $ticket->ticket_number }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 40px;
            background-color: #f6f9fc;
        }
        .ticket-wrapper {
            width: 100%;
            background-color: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            position: relative;
        }
        .ticket-header {
            position: relative;
            height: 250px;
            width: 100%;
            overflow: hidden;
        }
        .hero-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(0deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.2) 100%);
            display: block;
        }
        .hero-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            width: 90%;
            color: #ffffff;
        }
        .event-title {
            font-size: 32px;
            font-weight: 900;
            margin: 0 0 15px 0;
            text-transform: uppercase;
            letter-spacing: -0.5px;
            line-height: 1.1;
        }
        .event-title span {
            color: #ff4d4d;
        }
        .ticket-badge {
            display: inline-block;
            background-color: #ff4d4d;
            color: #ffffff;
            font-size: 14px;
            font-weight: 800;
            padding: 8px 30px;
            border-radius: 25px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Perforation Styling */
        .perforation {
            position: relative;
            height: 2px;
            width: 100%;
            margin: 0;
        }
        .perforation-line {
            width: 100%;
            border-top: 2px dashed #e2e8f0;
        }
        .cutout-left, .cutout-right {
            position: absolute;
            top: -15px;
            width: 30px;
            height: 30px;
            background-color: #f6f9fc;
            border-radius: 50%;
        }
        .cutout-left { left: -15px; }
        .cutout-right { right: -15px; }

        .ticket-body {
            padding: 40px;
        }
        .info-table {
            width: 100%;
        }
        .info-column {
            vertical-align: top;
            padding-right: 20px;
        }
        .qr-column {
            width: 200px;
            vertical-align: top;
            text-align: center;
        }
        .info-group {
            margin-bottom: 25px;
        }
        .group-label {
            font-size: 11px;
            font-weight: 800;
            color: #a0aec0;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 8px;
        }
        .group-value {
            font-size: 18px;
            font-weight: 700;
            color: #1a1f36;
            margin: 0;
            line-height: 1.3;
        }
        .group-value-sub {
            font-size: 14px;
            color: #718096;
            margin-top: 4px;
        }
        .ticket-number-value {
            font-size: 24px;
            font-weight: 900;
            color: #1a1f36;
            letter-spacing: 1px;
        }
        
        .qr-frame {
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 15px;
            display: inline-block;
            margin-bottom: 15px;
        }
        .scan-label {
            font-size: 12px;
            font-weight: 800;
            color: #1a1f36;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 10px;
        }
        .scan-sublabel {
            font-size: 9px;
            color: #a0aec0;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 5px;
        }

        .ticket-footer {
            padding: 30px 40px;
            background-color: #ffffff;
            border-top: 2px dashed #f1f4f8;
            text-align: center;
        }
        .footer-disclaimer {
            font-size: 11px;
            color: #718096;
            font-style: italic;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .order-ref-label {
            font-size: 10px;
            color: #a0aec0;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }
        .order-ref-badge {
            display: inline-block;
            background-color: #f1f4f8;
            color: #4a5568;
            font-size: 11px;
            font-weight: 700;
            padding: 5px 15px;
            border-radius: 12px;
        }
        .clear { clear: both; }
    </style>
</head>
<body>

<div class="ticket-wrapper">
    <!-- Header Hero Section -->
    <div class="ticket-header">
        @if($ticket->ticketType->event->banner && $ticket->ticketType->event->banner->url)
            <img src="{{ $ticket->ticketType->event->banner->url }}" alt="Event Banner" class="hero-image">
        @else
            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuD-OYyPDtX0yy-3iUHuCdgkU62XxS7udOnfhIsGCMTqtIJitHif0WbBfqpRVzTRqe4fVXdBIGlycsnclr5bMrzNAIRKYne9vUnLAnp54jN6XkskJ0_4DhYjpOWwkzeaVZ7yOP5wC0MoET49iZFVovQyyf73t16m1NRz735NODG-sfcASlVOp0RdQfuJu15NySohbC7CcH-vHB12QcZwnMmkXG6gposLZq7IErrVZXgY-RF7229ODQLJMWCtzoYkf8h0wg5oNMoXYlnA" alt="Event Banner" class="hero-image">
        @endif
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="event-title">
                {{ $ticket->ticketType->event->title }}
            </h1>
            <div class="ticket-badge">{{ $ticket->ticketType->name }}</div>
        </div>
    </div>

    <!-- Perforation Line -->
    <div class="perforation">
        <div class="cutout-left"></div>
        <div class="perforation-line"></div>
        <div class="cutout-right"></div>
    </div>

    <!-- Ticket Details Body -->
    <div class="ticket-body">
        <table class="info-table">
            <tr>
                <td class="info-column">
                    <div class="info-group">
                        <div class="group-label">DATE & TIME</div>
                        <div class="group-value">{{ $ticket->ticketType->event->event_date->format('l, d F Y') }}</div>
                        <div class="group-value-sub">{{ $ticket->ticketType->event->event_date->format('H:i') }} Local Time</div>
                    </div>

                    <div class="info-group">
                        <div class="group-label">VENUE</div>
                        <div class="group-value">{{ $ticket->ticketType->event->venue_name }}</div>
                        <div class="group-value-sub">{{ $ticket->ticketType->event->location }}</div>
                    </div>

                    <div class="info-group" style="margin-bottom: 0;">
                        <div class="group-label">TICKET NUMBER</div>
                        <div class="ticket-number-value">{{ $ticket->ticket_number }}</div>
                    </div>
                </td>
                <td class="qr-column">
                    <div class="qr-frame">
                        <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code" width="140" height="140"/>
                    </div>
                    <div class="scan-label">
                        <span style="color: #ff4d4d; vertical-align: middle;">▣</span> SCAN FOR ENTRY
                    </div>
                    <div class="scan-sublabel">Fast-track scanning optimized</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Footer Section -->
    <div class="ticket-footer">
        <p class="footer-disclaimer">
            This ticket is valid for one person only. Please present this ticket at the entrance.<br>
            Duplication or unauthorized resale is prohibited.
        </p>
        <div class="order-ref-label">Order Reference</div>
        <div class="order-ref-badge">{{ $ticket->orderItem->order->order_number }}</div>
    </div>
</div>

</body>
</html>
