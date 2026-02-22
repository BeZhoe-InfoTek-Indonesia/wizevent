<div class="bg-[#F8FAFC] min-h-screen font-sans text-slate-900 pb-24">
    {{-- 1. Banner Section --}}
    <div class="relative w-full h-[200px] md:h-[300px] bg-slate-900 overflow-hidden">
        @if($event->banner)
            <img src="{{ Storage::url($event->banner->file_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-[2px]"></div>
        @else
            <div class="w-full h-full bg-gradient-to-br from-blue-900 via-slate-800 to-black"></div>
        @endif
        
        {{-- Overlay Content --}}
        <div class="absolute inset-0 flex flex-col items-center justify-center text-center text-white p-6">
            <h1 class="text-3xl md:text-5xl font-black mb-4 tracking-tighter uppercase max-w-4xl leading-none">
                {{ $event->title }}
            </h1>
            <div class="flex flex-wrap justify-center items-center gap-4 text-xs md:text-sm font-bold uppercase tracking-widest text-red-400">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                    {{ $event->location }} ({{ $event->venue_name ?? 'JIS' }})
                </span>
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                    {{ $event->event_date->format('d F Y') }}
                </span>
            </div>
        </div>
    </div>

    {{-- 2. Stepper --}}
    <div class="bg-white border-b border-slate-100 py-6 mb-8 shadow-sm">
        <div class="container mx-auto px-4 max-w-5xl">
            <div class="flex items-center justify-center">
                <div class="flex items-center w-full max-w-3xl">
                    <!-- Step 1: Select -->
                    <div class="flex flex-col items-center flex-1 relative">
                        <div class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center text-white mb-2 z-10 shadow-lg shadow-red-600/30 ring-4 ring-red-50">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="hidden sm:block text-[10px] font-black uppercase tracking-widest text-red-600">1. Select</span>
                        <span class="sm:hidden text-[8px] font-black uppercase tracking-widest text-red-600">Select</span>
                    </div>
                    <!-- Line -->
                    <div class="flex-1 h-0.5 bg-red-600 mx-2 -mt-6 rounded-full opacity-50"></div>
                    <!-- Step 2: Review -->
                    <div class="flex flex-col items-center flex-1 relative">
                        <div class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center text-white mb-2 z-10 shadow-lg shadow-red-600/30">
                            <span class="text-base font-black italic">2</span>
                        </div>
                        <span class="hidden sm:block text-[10px] font-black uppercase tracking-widest text-slate-800">2. Review</span>
                        <span class="sm:hidden text-[8px] font-black uppercase tracking-widest text-slate-800">Review</span>
                    </div>
                    <!-- Line -->
                    <div class="flex-1 h-0.5 bg-slate-100 mx-2 -mt-6 rounded-full"></div>
                    <!-- Step 3: Payment -->
                    <div class="flex flex-col items-center flex-1 relative opacity-30">
                        <div class="w-10 h-10 rounded-full bg-white border-2 border-slate-200 flex items-center justify-center text-slate-400 mb-2 z-10">
                            <span class="text-base font-black italic">3</span>
                        </div>
                        <span class="hidden sm:block text-[10px] font-black uppercase tracking-widest text-slate-400">3. Payment</span>
                        <span class="sm:hidden text-[8px] font-black uppercase tracking-widest text-slate-400">Pay</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 max-w-5xl mt-8">
        {{-- Breadcrumb --}}
        <nav class="flex text-slate-400 font-bold text-[9px] uppercase tracking-[0.2em] space-x-3 mb-6" aria-label="Breadcrumb">
            <a href="/" class="hover:text-red-600 transition-colors">Home</a>
            <span>/</span>
            <a href="#" class="hover:text-red-600 transition-colors">Concerts</a>
            <span>/</span>
            <span class="text-slate-600">Tickets</span>
        </nav>

        <div class="flex flex-col lg:flex-row gap-8 items-start">
            
            {{-- Left Column: Ticket Cards --}}
            <div class="flex-1 w-full space-y-6">
                @foreach($event->ticketTypes as $ticketType)
                    <div class="group bg-white rounded-2xl border border-slate-200 overflow-visible hover:border-red-400 transition-all duration-300 shadow-sm hover:shadow-lg relative">
                        
                        {{-- Ticket Notches --}}
                        <div class="absolute -left-3 bottom-20 w-6 h-6 bg-[#F8FAFC] border border-slate-200 rounded-full z-10"></div>
                        <div class="absolute -right-3 bottom-20 w-6 h-6 bg-[#F8FAFC] border border-slate-200 rounded-full z-10"></div>
                        
                        {{-- Loading Overlay --}}
                        <div wire:loading.flex wire:target="incrementQuantity({{ $ticketType->id }}), decrementQuantity({{ $ticketType->id }})" class="absolute inset-0 bg-white/60 backdrop-blur-[1px] z-20 items-center justify-center rounded-2xl">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-8 h-8 border-3 border-red-600/20 border-t-red-600 rounded-full animate-spin"></div>
                                <span class="text-[10px] font-black text-red-600 uppercase tracking-widest">Pricing...</span>
                            </div>
                        </div>
                        
                        {{-- Content --}}
                        <div class="p-5 md:p-8">
                            <div class="flex justify-between items-start mb-4">
                                <h4 class="text-xl md:text-2xl font-black text-slate-900 group-hover:text-red-600 transition-colors uppercase tracking-tight">
                                    {{ $ticketType->name }}
                                </h4>
                                <span class="px-3 py-1 bg-green-50 text-green-600 text-[9px] font-black uppercase tracking-[0.1em] rounded-full ring-1 ring-green-100">
                                    Available
                                </span>
                            </div>

                            <p class="text-slate-400 text-xs font-medium mb-6">
                                {{ Str::limit($ticketType->description ?? 'General Admission area with standard access.', 120) }}
                            </p>
                            
                            <ul class="space-y-4 mb-8">
                                <li class="flex items-center gap-4">
                                    <div class="w-7 h-2 rounded-lg bg-red-50 flex items-center justify-center text-red-600 shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                    </div>
                                    <span class="text-xs md:text-sm font-bold text-slate-600">1 ticket for {{ $event->title }}</span>
                                </li>
                                <li class="flex items-center gap-4">
                                    <div class="w-7 h-2 rounded-lg bg-green-50 flex items-center justify-center text-green-600 shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                    </div>
                                    <span class="text-xs md:text-sm font-bold text-slate-600">Contribution to concert waste recycling included</span>
                                </li>
                                <li class="flex items-center gap-4">
                                    <div class="w-7 h-2 rounded-lg bg-yellow-50 flex items-center justify-center text-yellow-600 shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <span class="text-xs md:text-sm font-bold text-slate-600">Excludes 10% Entert. Tax & 5% Admin Fee</span>
                                </li>
                            </ul>

                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-red-50/50 rounded-xl border border-red-100">
                                <span class="animate-pulse w-1.5 h-1.5 rounded-full bg-red-600"></span>
                                <span class="text-[10px] font-black text-red-600 uppercase tracking-widest">
                                    Sales Ends {{ $ticketType->sales_end_at ? $ticketType->sales_end_at->format('d M • H:i') : $event->event_date->format('d M') }}
                                </span>
                            </div>
                        </div>

                        {{-- Perforation --}}
                        <div class="px-10">
                            <div class="border-t-[1.5px] border-dashed border-slate-100"></div>
                        </div>

                        {{-- Foot --}}
                        <div class="p-5 md:p-8 flex flex-row justify-between items-center gap-2">
                            <div>
                                <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest block mb-1">Price</span>
                                <div class="text-2xl font-black text-slate-900 leading-none">
                                    Rp. {{ number_format($ticketType->price, 0, ',', '.') }}
                                </div>
                            </div>

                            <div class="flex items-center bg-white border border-slate-100 rounded-xl p-1 gap-2 sm:gap-4 shadow-sm">
                                <button 
                                    wire:click="decrementQuantity({{ $ticketType->id }})"
                                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-50 text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-all disabled:opacity-30"
                                    @if(($quantities[$ticketType->id] ?? 0) <= 0) disabled @endif
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"></path></svg>
                                </button>
                                
                                <span class="text-xl font-black text-slate-900 w-6 text-center tabular-nums">
                                    {{ $quantities[$ticketType->id] ?? 0 }}
                                </span>

                                <button 
                                    wire:click="incrementQuantity({{ $ticketType->id }})"
                                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-red-600 text-white hover:bg-red-700 shadow-md shadow-red-600/20 transition-all disabled:opacity-30"
                                    @if(($quantities[$ticketType->id] ?? 0) >= ($ticketType->max_purchase ?: 99)) disabled @endif
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Right Column: Summary --}}
            <div class="w-full lg:w-[360px] shrink-0 sticky top-10">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-200/30 p-8">
                    <h3 class="text-xl font-black text-slate-900 mb-8 uppercase tracking-tight">Order Summary</h3>
                    
                    @if(collect($quantities)->sum() > 0)
                        <div class="space-y-8">
                            {{-- Items List --}}
                            <div class="space-y-4">
                                @foreach($event->ticketTypes as $ticketType)
                                    @if(($quantities[$ticketType->id] ?? 0) > 0)
                                        <div class="flex items-center gap-4 p-3 bg-slate-50/50 rounded-xl border border-slate-100">
                                            <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center text-red-600 shadow-inner">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24 text-red-600">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex justify-between items-start">
                                                    <h5 class="text-sm font-black text-slate-900 uppercase tracking-tight truncate">{{ $ticketType->name }}</h5>
                                                </div>
                                                <p class="text-[9px] font-black text-slate-400 mt-0.5 uppercase tracking-widest leading-none">
                                                    {{ $quantities[$ticketType->id] }} × Rp{{ number_format($ticketType->price, 0, ',', '.') }}
                                                </p>
                                            </div>
                                            <div class="text-sm font-black text-slate-900 shrink-0">
                                                Rp{{ number_format(($ticketType->price * ($quantities[$ticketType->id] ?? 0)), 0, ',', '.') }}
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            {{-- Totals --}}
                            <div class="pt-6 border-t-2 border-dashed border-slate-50 space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Subtotal</span>
                                    <span class="text-sm font-black text-slate-600">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center border-b border-slate-50 pb-4">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tax & Fees</span>
                                    <span class="text-sm font-black text-slate-600">Rp{{ number_format($taxAmount + $platformFeeAmount, 0, ',', '.') }}</span>
                                </div>
                                
                                <div class="pt-4 space-y-5">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-[9px] font-black text-slate-300 uppercase tracking-[0.3em] block">Total Amount</span>
                                        <div class="text-3xl font-black text-slate-900 tracking-tighter tabular-nums leading-none">
                                            Rp.{{ number_format($totalAmount, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    
                                    <button 
                                        wire:click="proceedToCheckout"
                                        class="w-full py-4 bg-gradient-to-br from-red-500 to-red-700 hover:from-red-600 hover:to-red-800 text-white font-black text-base uppercase tracking-widest rounded-2xl shadow-[0_10px_20px_-5px_rgba(220,38,38,0.4)] transition-all transform hover:-translate-y-0.5 active:scale-[0.98] active:shadow-inner flex items-center justify-center gap-3 group border border-red-400/30"
                                    >
                                        <span>Order Now</span>
                                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Empty State --}}
                        <div class="text-center py-16 flex flex-col items-center justify-center">
                            <div class="w-24 h-24 bg-slate-50 rounded-[32px] flex items-center justify-center mb-6 shadow-inner group">
                                <svg class="w-12 h-12 text-slate-200 group-hover:text-red-100 transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                </svg>
                            </div>
                            <h4 class="font-black text-slate-900 uppercase tracking-tight text-lg mb-2">Cart empty</h4>
                            <p class="text-[10px] font-bold text-slate-400 max-w-[160px] leading-relaxed uppercase tracking-widest">Select tickets to proceed</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
