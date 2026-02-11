<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-lg mx-auto" 
     x-data="{ 
        scanner: null,
        initScanner() {
            this.scanner = new Html5QrcodeScanner('reader', { 
                fps: 10, 
                qrbox: {width: 250, height: 250},
                showTorchButtonIfSupported: true
            }, false);
            this.scanner.render((decodedText) => {
                $wire.scan(decodedText);
            });
        }
     }"
     x-init="initScanner()">
    
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2 font-outfit">Ticket Check-in</h2>
        <p class="text-gray-600 text-sm">Scan the attendee's QR code or enter the ticket number manually.</p>
    </div>

    <!-- Scanner Feed -->
    <div wire:ignore shadow-xl rounded-2xl overflow-hidden border-4 border-white>
        <div id="reader"></div>
    </div>

    <!-- Manual Entry -->
    <div class="mt-8 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <label for="manual_ticket" class="block text-sm font-medium text-gray-700 mb-2">Manual Ticket Number</label>
        <div class="flex gap-2">
            <input type="text" id="manual_ticket" wire:model="manualTicketNumber" 
                   class="flex-1 rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                   placeholder="TKT-XXXX-XXXX">
            <button wire:click="checkManual" 
                    class="bg-gray-900 hover:bg-black text-white px-6 py-2 rounded-xl text-sm font-bold transition duration-200">
                Check
            </button>
        </div>
    </div>

    <!-- Feedback Overlay -->
    @if($status)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/80 backdrop-blur-sm" 
             @click.self="$wire.resetFeedback()">
            <div class="bg-white rounded-[2rem] shadow-2xl overflow-hidden max-w-sm w-full transform transition-all animate-in zoom-in duration-300">
                @if($status === 'success')
                    <div class="bg-green-500 p-10 flex justify-center">
                        <div class="bg-white/20 p-4 rounded-full animate-pulse">
                            <svg class="h-16 w-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="p-8 text-center">
                        <h3 class="text-3xl font-black text-gray-900 mb-2">SUCCESS</h3>
                        <p class="text-green-600 font-bold text-lg mb-6">{{ $message }}</p>
                        
                        @if($scannedTicket)
                            <div class="bg-gray-50 rounded-2xl p-6 text-left border border-gray-100 mb-8">
                                <div class="mb-4">
                                    <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-1">Attendee</p>
                                    <p class="text-xl font-bold text-gray-900">{{ $scannedTicket->order->user?->name ?? 'Guest' }}</p>
                                </div>
                                
                                <div class="mb-4">
                                    <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-1">Ticket Type</p>
                                    <p class="text-sm font-bold text-gray-700">{{ $scannedTicket->ticketType->name }}</p>
                                </div>

                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-1">Ticket Number</p>
                                    <p class="text-xs font-mono text-gray-500">{{ $scannedTicket->ticket_number }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="bg-red-500 p-10 flex justify-center">
                        <div class="bg-white/20 p-4 rounded-full">
                            <svg class="h-16 w-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="p-8 text-center">
                        <h3 class="text-3xl font-black text-gray-900 mb-2">FAILED</h3>
                        <p class="text-red-600 font-bold text-lg mb-8">{{ $message }}</p>
                    </div>
                @endif
                
                <div class="p-6 bg-gray-50 border-t border-gray-100">
                    <button @click="$wire.resetFeedback()" 
                            class="w-full bg-gray-900 hover:bg-black text-white py-4 rounded-2xl text-lg font-black transition-all active:scale-95">
                        CONTINUE
                    </button>
                </div>
            </div>
        </div>
    @endif

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <style>
        #reader { border: none !important; }
        #reader__dashboard_section_csr button {
            @apply bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-bold mt-2;
        }
        #reader__scan_region { background: #f9fafb; }
        .animate-bounce-short {
            animation: bounce 0.5s ease-in-out 1;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
    </style>
</div>
