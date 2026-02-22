<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Ticket #{{ $ticket->ticket_number }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 40px 20px;
            background-color: #f4f5f7;
            -webkit-font-smoothing: antialiased;
        }
        .ticket-card {
            width: 100%;
            max-width: 480px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            position: relative;
        }
        
        /* BANNER SECTION */
        .banner-section {
            width: 100%;
            height: 220px;
            background-color: #fdf6e9; /* Light cream fallback */
            position: relative;
            overflow: hidden;
        }
        .banner-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .banner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(253, 246, 233, 0.9) 0%, rgba(253, 246, 233, 0.2) 60%, rgba(253, 246, 233, 0) 100%);
        }
        .banner-content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            padding: 30px;
            z-index: 10;
        }
        .banner-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #ffffff;
            color: #d35400;
            padding: 6px 16px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            letter-spacing: 0.5px;
        }
        .banner-badge span {
            margin-right: 4px;
            color: #e67e22;
        }
        .banner-text-artistic {
            max-width: 70%;
            margin-top: 10px;
        }
        /* Using safe fonts for PDF */
        .banner-title-serif {
            font-family: 'Georgia', 'Times New Roman', serif;
            font-size: 28px;
            color: #2c3e50;
            margin: 0;
            line-height: 1.2;
        }
        .banner-subtitle-serif {
            font-family: 'Georgia', 'Times New Roman', serif;
            font-style: italic;
            font-size: 20px;
            color: #34495e;
            margin: 5px 0 10px 0;
        }
        .banner-desc-small {
            font-size: 9px;
            color: #7f8c8d;
            line-height: 1.4;
            max-width: 220px;
        }

        /* MAIN CONTENT SECTION */
        .content-section {
            padding: 35px 35px 10px 35px;
            position: relative;
        }
        .label-live {
            color: #e67e22;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
            display: block;
        }
        .event-title {
            font-size: 34px;
            font-weight: 800;
            color: #0d1b2a;
            margin: 0 0 6px 0;
            line-height: 1.1;
        }
        .event-subtitle {
            font-size: 15px;
            color: #5d6d7e;
            margin: 0;
            font-weight: 500;
        }

        /* GRID DATA */
        .info-grid {
            width: 100%;
            margin-top: 35px;
            border-collapse: collapse;
        }
        .info-grid td {
            padding-bottom: 30px;
            vertical-align: top;
        }
        .info-label {
            font-size: 10px;
            color: #94a3b8;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 1.2px;
            margin-bottom: 8px;
            display: block;
        }
        .info-value {
            font-size: 18px;
            color: #0d1b2a;
            font-weight: 700;
        }
        .info-subvalue {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
            display: block;
        }

        /* CUTOUTS - Ticket aesthetic */
        .cutout-wrapper {
            position: relative;
            margin: 15px 0;
            height: 2px;
        }
        .dot-line {
            border-top: 2px dashed #f1f5f9;
            width: 100%;
            margin-top: 1px;
        }
        .cutout {
            position: absolute;
            width: 30px;
            height: 30px;
            background-color: #f4f5f7; /* Match body background */
            border-radius: 50%;
            top: -15px;
            z-index: 20;
        }
        .cutout-left { left: -15px; }
        .cutout-right { right: -15px; }

        /* FOOTER DETAILS */
        .footer-details {
            padding: 20px 35px 40px 35px;
        }
        .user-info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .user-name {
            font-size: 16px;
            font-weight: 700;
            color: #0d1b2a;
        }
        .order-id {
            font-size: 16px;
            font-weight: 700;
            color: #94a3b8;
        }

        /* QR SECTION */
        .qr-section {
            text-align: center;
            padding: 30px 0 50px 0;
            background-color: #ffffff;
        }
        .qr-image {
            display: inline-block;
            margin-bottom: 20px;
        }
        .qr-security {
            font-size: 10px;
            color: #cbd5e1;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-family: monospace;
            font-weight: bold;
        }

        /* Utility */
        .w-half { width: 50%; }
        
    </style>
</head>
<body>

<div class="ticket-card">
    
    <!-- Top Image Area -->
    <div class="banner-section">
        @if(isset($bannerBase64) && $bannerBase64)
            <img src="{{ $bannerBase64 }}" class="banner-image">
            <div class="banner-overlay"></div>
        @elseif($ticket->ticketType->event->banner && $ticket->ticketType->event->banner->url)
            <img src="{{ $ticket->ticketType->event->banner->url }}" class="banner-image">
            <div class="banner-overlay"></div>
        @else
            <!-- Placeholder design elements -->
            <div class="banner-content">
                <div class="banner-text-artistic">
                    <h2 class="banner-title-serif">Mimal Ipsade</h2>
                    <h3 class="banner-subtitle-serif">L'artre wore</h3>
                    <p class="banner-desc-small">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                        Vivamus lacinia odio vitae vestibulum vestibulum.
                    </p>
                </div>
            </div>
        @endif
        
        <!-- Badge -->
        <div class="banner-badge">
            <span>★</span> {{ strtoupper($ticket->ticketType->name) }}
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-section">
        <span class="label-live">LIVE EVENT</span>
        <h1 class="event-title">{{ $ticket->ticketType->event->title }}</h1>
        @if($ticket->ticketType->event->description_short && $ticket->ticketType->event->description_short !== $ticket->ticketType->event->title)
            <p class="event-subtitle">{{ $ticket->ticketType->event->description_short }}</p>
        @endif

        <table class="info-grid">
            <tr>
                <td class="w-half">
                    <span class="info-label">DATE</span>
                    <div class="info-value">{{ $ticket->ticketType->event->event_date->format('M d, Y') }}</div>
                </td>
                <td class="w-half">
                    <span class="info-label">TIME</span>
                    <div class="info-value">{{ $ticket->ticketType->event->event_date->format('h:i A') }}</div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="info-label">VENUE</span>
                    <div class="info-value">{{ $ticket->ticketType->event->venue_name }}</div>
                    <div class="info-subvalue">{{ $ticket->ticketType->event->location }}</div>
                </td>
            </tr>
            <tr>
                <td class="w-half">
                    <span class="info-label">SECTION</span>
                    <div class="info-value">{{ $ticket->section_name ?? 'Floor A' }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Cutout Separator -->
    <div class="cutout-wrapper">
        <div class="cutout cutout-left"></div>
        <div class="cutout cutout-right"></div>
        <div class="dot-line"></div>
    </div>

    <!-- Footer Identity -->
    <div class="footer-details">
        <table class="user-info-table">
            <tr>
                <td>
                    <span class="info-label">TICKET HOLDER</span>
                    <div class="user-name">{{ $ticket->orderItem->order->user->name ?? 'John Visitor' }}</div>
                </td>
                <td style="text-align: right;">
                    <span class="info-label">ORDER ID</span>
                    <div class="order-id">#{{ $ticket->orderItem->order->order_number ?? '8901' }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- QR Code Section -->
    <div class="qr-section">
        <div class="qr-image">
            <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code" width="160" height="160"/>
        </div>
        <div class="qr-security">
            SEC:{{ implode('-', str_split($ticket->ticket_number, 4)) }}
        </div>
    </div>

</div>

</body>
</html>
