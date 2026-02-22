<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: false }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #{{ $ticket->ticket_number }} - {{ $ticket->order->event->title }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }

        body {
            font-family: 'Outfit', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .ticket-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.15);
            position: relative;
            overflow: hidden;
            width: 100%;
            max-width: 880px;
        }

        .dark .ticket-card {
            background: #1e1e1e;
            box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.6);
            color: #e5e7eb;
        }

        /* Banner Styling */
        .banner-container {
            height: 180px;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 0 2rem;
        }

        .banner-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
        }

        .banner-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0.8) 70%, #ffffff 100%);
            z-index: 1;
        }

        .dark .banner-overlay {
            background: linear-gradient(to bottom, rgba(0,0,0,0.4) 0%, rgba(30,30,30,0.8) 70%, #1e1e1e 100%);
        }

        .banner-content {
            position: relative;
            z-index: 2;
        }

        .event-title {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1.1;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: -0.02em;
        }

        .dark .event-title {
            color: #ffffff;
        }

        .event-title span.highlight {
            color: #ef4444;
        }

        .vip-badge {
            background: #ef4444;
            color: white;
            padding: 0.35rem 1.75rem;
            border-radius: 9999px;
            font-weight: 700;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            box-shadow: 0 10px 20px -5px rgba(239, 68, 68, 0.3);
            margin-top: 0.75rem;
            display: inline-block;
        }

        /* Divider Styling */
        .ticket-divider {
            position: relative;
            height: 2px;
            border-top: 2px dashed #f3f4f6;
            margin: 0 2.5rem;
        }

        .dark .ticket-divider {
            border-top: 2px dashed #2d2d2d;
        }

        .ticket-divider::before,
        .ticket-divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 32px;
            height: 32px;
            background: #f1f5f9; /* Outer bg color */
            border-radius: 50%;
            transform: translateY(-50%);
            z-index: 10;
        }

        .dark .ticket-divider::before,
        .dark .ticket-divider::after {
            background: #000;
        }

        .ticket-divider::before {
            left: -56px;
        }

        .ticket-divider::after {
            right: -56px;
        }

        /* QR Section */
        .qr-outer-container {
            background: #f8fafc;
            border-radius: 16px;
            padding: 1rem;
        }

        .dark .qr-outer-container {
            background: #252525;
        }

        .qr-inner-frame {
            background: white;
            padding: 1rem;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Utils */
        .label-text {
            color: #94a3b8;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            margin-bottom: 0.5rem;
            display: block;
        }

        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .ticket-card { box-shadow: none !important; border: 1px solid #e5e7eb; border-radius: 0; max-width: 100%; height: auto; }
            .ticket-divider::before, .ticket-divider::after { display: none; }
        }
    </style>
</head>
<body class="bg-slate-100 dark:bg-black min-h-screen py-16 px-4 transition-colors duration-300 flex flex-col items-center">

    <div class="max-w-4xl w-full flex flex-col items-center gap-10">
        
        <!-- Ticket Card -->
        <main class="ticket-card">
            
            <!-- Banner Section -->
            <div class="banner-container">
                <div class="banner-bg">
                    @if($ticket->order->event->banner && $ticket->order->event->banner->url)
                        <img src="{{ $ticket->order->event->banner->url }}" class="w-full h-full object-cover">
                    @else
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuD-OYyPDtX0yy-3iUHuCdgkU62XxS7udOnfhIsGCMTqtIJitHif0WbBfqpRVzTRqe4fVXdBIGlycsnclr5bMrzNAIRKYne9vUnLAnp54jN6XkskJ0_4DhYjpOWwkzeaVZ7yOP5wC0MoET49iZFVovQyyf73t16m1NRz735NODG-sfcASlVOp0RdQfuJu15NySohbC7CcH-vHB12QcZwnMmkXG6gposLZq7IErrVZXgY-RF7229ODQLJMWCtzoYkf8h0wg5oNMoXYlnA" class="w-full h-full object-cover">
                    @endif
                </div>
                <div class="banner-overlay"></div>
                
                <div class="banner-content">
                    <h1 class="event-title">
                        @php
                            $fullTitle = $ticket->order->event->title;
                            // Check for known keywords to highlight
                            $highlighted = 'DETOX';
                            if (str_contains(strtoupper($fullTitle), $highlighted)) {
                                $parts = explode($highlighted, strtoupper($fullTitle));
                                echo $parts[0] . '<span class="highlight">' . $highlighted . '</span>' . ($parts[1] ?? '');
                            } else {
                                echo $fullTitle;
                            }
                        @endphp
                    </h1>
                    <div class="vip-badge">
                        {{ $ticket->orderItem->ticketType->name }}
                    </div>
                </div>
            </div>

            <!-- Top Divider -->
            <div class="ticket-divider"></div>

            <!-- Main Body -->
            <div class="px-10 py-6">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                    
                    <!-- Details (Left) -->
                    <div class="md:col-span-7 flex flex-col gap-8">
                        <div class="flex flex-col md:flex-row gap-8">
                            <!-- Date -->
                            <div class="flex-1">
                                <span class="label-text">Date & Time</span>
                                <div class="flex gap-3">
                                    <div class="mt-1">
                                        <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800 dark:text-gray-100 text-base leading-tight">
                                            {{ $ticket->order->event->event_date?->format('l, d F Y') }}
                                        </p>
                                        <p class="text-gray-400 text-[0.7rem] font-medium mt-0.5">
                                            {{ $ticket->order->event->event_date?->format('H:i') }} Local Time
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- Venue -->
                            <div class="flex-1">
                                <span class="label-text">Venue</span>
                                <div class="flex gap-3">
                                    <div class="mt-1 text-red-500">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        @php
                                            $location = $ticket->order->event->location ?? 'To be announced';
                                            $locParts = explode(',', $location);
                                            $mainLoc = trim($locParts[0] ?? $location);
                                            $subLoc = trim($locParts[1] ?? ($locParts[2] ?? ''));
                                        @endphp
                                        <p class="font-bold text-gray-800 dark:text-gray-100 text-base leading-tight">{{ $mainLoc }}</p>
                                        <p class="text-gray-400 text-[0.7rem] font-medium mt-0.5">{{ $subLoc }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ticket Number -->
                        <div>
                            <span class="label-text">Ticket Number</span>
                            <p class="text-xl font-bold tracking-widest text-gray-900 dark:text-white uppercase">
                                {{ $ticket->ticket_number }}
                            </p>
                        </div>
                    </div>

                    <!-- Scan (Right) -->
                    <div class="md:col-span-5 flex flex-col items-center justify-center">
                        <div class="qr-outer-container">
                            <div class="qr-inner-frame">
                                {!! $qrCode !!}
                            </div>
                        </div>
                        <div class="mt-6 flex flex-col items-center">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h2M4 8h16M4 16h16M4 20h4a2 2 0 002-2V4a2 2 0 00-2-2H4a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-[0.65rem] font-bold text-gray-800 dark:text-gray-300 uppercase tracking-[0.2em]">Scan for Entry</span>
                            </div>
                            <span class="text-[0.6rem] text-gray-400 uppercase tracking-widest">Keep this code private</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Divider -->
            <div class="ticket-divider"></div>

            <!-- Footer -->
            <div class="px-10 py-6 flex flex-col items-center text-center gap-5">
                <p class="text-[0.6rem] text-gray-400 italic max-w-sm leading-relaxed">
                    This ticket is valid for one person only. Please present this ticket at the entrance. Duplication or unauthorized resale is prohibited.
                </p>

                <div>
                    <span class="label-text !mb-1">Order Reference</span>
                    <div class="bg-gray-100 dark:bg-zinc-800 px-5 py-1 rounded-full">
                        <span class="text-[0.6rem] font-mono font-bold text-gray-600 dark:text-gray-400 uppercase">
                            {{ $ticket->order->order_number }}
                        </span>
                    </div>
                </div>
            </div>
        </main>

        <!-- Buttons -->
        <div class="flex items-center gap-4 no-print">
            {{-- <button onclick="window.print()" class="bg-white dark:bg-zinc-900 px-8 py-3.5 rounded-2xl flex items-center gap-2 border border-gray-200 dark:border-zinc-800 shadow-xl shadow-gray-200/50 dark:shadow-none hover:-translate-y-0.5 transition-all">
                <svg class="w-4 h-4 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span class="text-sm font-bold text-gray-900 dark:text-white">Print Ticket</span>
            </button> --}}
             <a href="{{ route('dashboard') }}" class="text-sm font-bold text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                &larr; Back to Dashboard
            </a>
            <a href="{{ route('tickets.download', $ticket) }}" target="_blank" class="bg-red-500 px-8 py-3.5 rounded-2xl flex items-center gap-2 shadow-xl shadow-red-500/30 hover:bg-red-600 hover:-translate-y-0.5 transition-all">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                <span class="text-sm font-bold text-white">Download PDF</span>
            </a>
        </div>
    </div>

    <!-- Scripts -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
