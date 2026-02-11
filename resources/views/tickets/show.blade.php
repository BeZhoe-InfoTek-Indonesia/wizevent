<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #{{ $ticket->ticket_number }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print {
                display: none;
            }
            body {
                background: white;
            }
            .ticket-container {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6 flex justify-between items-center no-print">
            <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center">
                <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Dashboard
            </a>
            <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print Ticket
            </button>
        </div>

        <div class="ticket-container bg-white rounded-lg shadow-xl overflow-hidden">
            <!-- Event Banner -->
            <div class="h-48 bg-indigo-600 relative overflow-hidden">
                @if($ticket->order->event->banner_url)
                    <img src="{{ $ticket->order->event->banner_url }}" alt="{{ $ticket->order->event->title }}" class="w-full h-full object-cover opacity-50">
                @else
                    <div class="w-full h-full flex items-center justify-center text-white opacity-20">
                        <svg class="h-24 w-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                    </div>
                @endif
                <div class="absolute inset-0 flex items-center justify-center">
                    <h1 class="text-3xl font-bold text-white text-center px-4 drop-shadow-md">{{ $ticket->order->event->title }}</h1>
                </div>
            </div>

            <div class="p-8">
                <div class="flex flex-col md:flex-row justify-between">
                    <!-- Ticket Details -->
                    <div class="flex-1 pr-0 md:pr-8 mb-6 md:mb-0">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Attendee</h3>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $ticket->order->user->name }}</p>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Date & Time</h3>
                                <p class="mt-1 text-lg font-semibold text-gray-900">
                                    {{ $ticket->order->event->start_date?->format('F j, Y') }}<br>
                                    <span class="text-base font-normal text-gray-600">{{ $ticket->order->event->start_time?->format('H:i') }}</span>
                                </p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Venue</h3>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $ticket->order->event->location ?? 'To be announced' }}</p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Ticket Type</h3>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $ticket->orderItem->ticketType->name }}</p>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100">
                             <div class="flex items-center text-gray-500 text-sm">
                                <svg class="h-5 w-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Order #{{ $ticket->order->order_number }} • Confirmed</span>
                            </div>
                            <div class="mt-2 text-xs text-gray-400">
                                Ticket ID: {{ $ticket->ticket_number }}
                            </div>
                        </div>
                    </div>

                    <!-- QR Code Section -->
                    <div class="flex flex-col items-center justify-center pl-0 md:pl-8 border-l-0 md:border-l border-dashed border-gray-300">
                        <div class="bg-white p-2 rounded-lg border border-gray-200 shadow-sm mb-3">
                            {{ $qrCode }}
                        </div>
                        <p class="text-xs text-center text-gray-500 max-w-[150px]">Present this QR code at the entrance for scanning.</p>
                        
                        <div class="mt-4 px-3 py-1 bg-gray-100 rounded-full text-xs font-mono text-gray-600">
                            {{ $ticket->status }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ticket Footer -->
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-100 flex justify-between items-center text-xs text-gray-500">
                <span>{{ config('app.name') }}</span>
                <span>Generated on {{ now()->format('d M Y H:i') }}</span>
            </div>
        </div>
    </div>
</body>
</html>
