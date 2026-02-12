<div class="bg-[#F8FAFC] min-h-screen font-sans pb-20 md:pb-32">
    <style>
        @keyframes shimmer {
            100% { transform: translateX(100%); }
        }
    </style>
    {{-- Full Screen Loading Overlay --}}
    <div wire:loading.flex wire:target="confirmBooking" class="fixed inset-0 z-[10000] bg-[#F1F5F9]/90 backdrop-blur-sm flex items-center justify-center transition-opacity">
        <div class="flex flex-col items-center w-full max-w-md mx-4">
            
            {{-- Main Card --}}
            <div class="w-full bg-white rounded-2xl shadow-2xl overflow-hidden relative">
                {{-- Top Progress Bar --}}
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gray-100">
                    <div class="h-full bg-[#1A8DFF] animate-[loading_2s_ease-in-out_infinite] w-1/3 rounded-r-full"></div>
                </div>

                <div class="p-8 md:p-10 flex flex-col items-center text-center">
                    {{-- Spinner with Icon --}}
                    <div class="relative w-24 h-24 mb-6">
                        <div class="absolute inset-0 border-4 border-[#F1F5F9] rounded-full"></div>
                        <div class="absolute inset-0 border-4 border-[#1A8DFF] rounded-full border-t-transparent animate-spin"></div>
                        <div class="absolute inset-0 flex items-center justify-center text-[#1A8DFF]">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>

                    <h3 class="text-2xl font-black text-[#1E293B] mb-2 tracking-tight">Preparing your booking...</h3>
                    <p class="text-[#64748B] text-[15px] font-medium leading-relaxed mb-8 max-w-[280px]">
                        Please stay on this page while we finalize your details.
                    </p>

                    {{-- Progress Steps --}}
                    <div class="w-full space-y-4 text-left pl-4 border-t border-dashed border-gray-100 pt-6">
                        {{-- Step 1 --}}
                        <div class="flex items-center gap-4">
                            <svg class="w-6 h-6 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-[14px] font-bold text-[#1E293B]">Checking availability</span>
                        </div>

                        {{-- Step 2 --}}
                        <div class="flex items-center gap-4">
                            <div class="w-6 h-6 flex items-center justify-center">
                                <div class="w-2 h-2 bg-[#1A8DFF] rounded-full animate-ping"></div>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="text-[14px] font-bold text-[#1E293B]">Securing your selection</span>
                                <span class="flex gap-0.5">
                                    <span class="w-1 h-1 bg-[#1E293B] rounded-full animate-bounce"></span>
                                    <span class="w-1 h-1 bg-[#1E293B] rounded-full animate-bounce [animation-delay:0.1s]"></span>
                                    <span class="w-1 h-1 bg-[#1E293B] rounded-full animate-bounce [animation-delay:0.2s]"></span>
                                </span>
                            </div>
                        </div>

                        {{-- Step 3 --}}
                        <div class="flex items-center gap-4 opacity-50">
                            <svg class="w-6 h-6 text-[#94A3B8] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-[14px] font-medium text-[#64748B]">Generating Order ID</span>
                        </div>
                    </div>
                </div>

                {{-- Footer System Status --}}
                <div class="bg-[#F8FAFC] py-3 text-center border-t border-[#F1F5F9]">
                    <p class="text-[11px] font-bold text-[#94A3B8] tracking-widest uppercase">System Status: Operational</p>
                </div>
            </div>

            {{-- Support Link --}}
            <div class="mt-6 text-center">
                <p class="text-[#64748B] text-[14px] font-medium">
                    Having trouble? <a href="#" class="text-[#1A8DFF] font-bold hover:underline">Contact support</a>
                </p>
            </div>
        </div>
    </div>
    {{-- Modern Stepper & Breadcrumbs --}}
    <div class="bg-white border-b border-[#F1F5F9]">
        <div class="container mx-auto px-4 max-w-6xl">
            {{-- Stepper Container --}}
            <div class="py-8 md:py-12">
                <div class="flex items-center justify-center max-w-3xl mx-auto">
                    {{-- Step 1: Select --}}
                    <div class="flex flex-col items-center gap-4 group">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-[#1A8DFF] shadow-lg shadow-blue-500/20 flex items-center justify-center text-white ring-4 ring-blue-50 transition-all">
                            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-[10px] md:text-[11px] font-black tracking-[0.15em] text-[#1A8DFF] uppercase">1. Select</span>
                    </div>

                    {{-- Progress Line 1 --}}
                    <div class="flex-1 h-0.5 bg-gradient-to-r from-[#1A8DFF] to-[#1A8DFF] mx-4 md:mx-8 -mt-9 md:-mt-11"></div>

                    {{-- Step 2: Review --}}
                    <div class="flex flex-col items-center gap-4 group">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-[#1A8DFF] shadow-lg shadow-blue-500/20 flex items-center justify-center text-white ring-4 ring-blue-50 transition-all">
                            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-[10px] md:text-[11px] font-black tracking-[0.15em] text-[#1A8DFF] uppercase">2. Review</span>
                    </div>

                    {{-- Progress Line 2 --}}
                    <div class="flex-1 h-0.5 bg-gradient-to-r from-[#1A8DFF] to-[#1A8DFF] mx-4 md:mx-8 -mt-9 md:-mt-11"></div>

                    {{-- Step 3: Payment (Current) --}}
                    <div class="flex flex-col items-center gap-4 group">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-[#1A8DFF] shadow-lg shadow-blue-500/20 flex items-center justify-center text-white font-black text-[15px] md:text-[16px] ring-4 ring-blue-50">
                            3
                        </div>
                        <span class="text-[10px] md:text-[11px] font-black tracking-[0.15em] text-[#1E293B] uppercase">3. Payment</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Breadcrumbs Sub-header --}}
        <div class="bg-[#F8FAFC] border-t border-[#F1F5F9] py-4">
            <div class="container mx-auto px-4 max-w-6xl">
                <nav class="flex items-center gap-3 text-[10px] md:text-[11px] font-black tracking-[0.2em] text-[#94A3B8] uppercase">
                    <a href="/" class="hover:text-[#1A8DFF] transition-colors">Home</a>
                    <span class="text-gray-300">/</span>
                    <a href="#" class="hover:text-[#1A8DFF] transition-colors">Concerts</a>
                    <span class="text-gray-300">/</span>
                    <span class="text-[#1E293B]">Tickets</span>
                </nav>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 max-w-6xl py-6 md:py-8">
        {{-- Page Header --}}
        <div class="mb-6 md:mb-8">
            <h1 class="text-[20px] md:text-[24px] font-black text-[#1E293B] mb-2">Visitor Details</h1>
            <p class="text-[13px] md:text-[14px] text-[#64748B] font-medium font-sans">Make sure to fill in the visitor details correctly for a smooth experience.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            {{-- Left Column (8 cols) --}}
            <div class="lg:col-span-8 space-y-6">
                {{-- Ticket Details Card --}}
                <div class="bg-white rounded-[20px] md:rounded-[24px] border border-[#E2E8F0] shadow-sm overflow-hidden">
                    <div class="p-4 md:p-6 border-b border-[#F1F5F9] border-dashed flex items-center justify-between bg-white">
                        <h3 class="text-[15px] md:text-[16px] font-black text-[#1E293B]">Ticket 1 (Pax)</h3>
                        <div class="flex items-center gap-2 md:gap-4">
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] md:text-[13px] font-bold text-[#64748B] text-right leading-tight max-w-[80px] md:max-w-none">Same as contact details</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model.live="sameAsContact" id="sameAsContact" class="sr-only peer">
                                    <div class="w-9 h-5 md:w-11 md:h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 md:after:h-5 md:after:w-5 after:transition-all peer-checked:bg-[#1A8DFF]"></div>
                                </label>
                            </div>
                            <svg class="w-5 h-5 text-[#1E293B] hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"></path></svg>
                        </div>
                    </div>

                    <div class="p-5 md:p-8 space-y-5 md:space-y-7">
                        {{-- Title Radios --}}
                        <div class="flex gap-4 md:gap-8">
                            @foreach(['Mr.', 'Mrs.', 'Ms.'] as $title)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="radio" wire:model="visitorTitle" value="{{ $title }}" class="sr-only peer">
                                    <div class="w-5 h-5 rounded-full border-2 border-[#E2E8F0] peer-checked:border-[#1A8DFF] peer-checked:border-[6px] transition-all bg-white"></div>
                                    <span class="text-[15px] font-bold text-[#64748B] peer-checked:text-[#1E293B]">{{ $title }}</span>
                                </label>
                            @endforeach
                        </div>

                        {{-- Name Input --}}
                        <div class="relative">
                            <input type="text" wire:model.live="visitorName" {{ $sameAsContact ? 'readonly' : '' }} class="w-full px-4 md:px-5 py-3 md:py-4 rounded-[14px] md:rounded-[16px] border border-[#E2E8F0] text-[15px] font-medium text-[#1E293B] placeholder-[#94A3B8] focus:outline-none focus:ring-2 focus:ring-[#1A8DFF]/10 focus:border-[#1A8DFF] transition-all {{ $sameAsContact ? 'bg-[#F8FAFC]' : 'bg-white' }}" placeholder="Full name">
                            @error('visitorName') <p class="text-red-500 text-xs mt-1 ml-2 font-medium">{{ $message }}</p> @enderror
                        </div>

                        {{-- Phone Input Cluster --}}
                        <div class="relative">
                            <div class="w-full px-4 md:px-5 py-3 md:py-4 rounded-[14px] md:rounded-[16px] border border-[#E2E8F0] flex items-center bg-white focus-within:ring-2 focus-within:ring-[#1A8DFF]/10 focus-within:border-[#1A8DFF] transition-all">
                                <div class="flex items-center gap-2 mr-3 opacity-80 select-none border-r border-[#F1F5F9] pr-3">
                                    <img src="https://flagcdn.com/w20/id.png" class="w-5 h-auto rounded-sm md:w-5 shadow-sm" alt="ID">
                                    <span class="text-[15px] font-medium text-[#64748B]">+62</span>
                                </div>
                                <input
                                            type="text"
                                            wire:model="visitorPhone"
                                            x-data
                                            x-init="$el.addEventListener('input', function(e) {
                                                let value = e.target.value.replace(/[^0-9]/g, '');
                                                value = value.substring(0, 12);
                                                if (value.length > 8) {
                                                    value = value.substring(0, 4) + '-' + value.substring(4, 8) + '-' + value.substring(8);
                                                } else if (value.length > 4) {
                                                    value = value.substring(0, 4) + '-' + value.substring(4);
                                                }
                                                e.target.value = value;
                                                $wire.set('visitorPhone', value);
                                            })"
                                            class="flex-1 w-full text-[15px] font-medium text-[#1E293B] focus:outline-none placeholder-[#94A3B8] bg-transparent"
                                            placeholder="812-3456-7890"
                                        >
                            </div>
                            @error('visitorPhone') <p class="text-red-500 text-xs mt-1 ml-2 font-medium">{{ $message }}</p> @enderror
                        </div>

                        {{-- Email Input --}}
                        <div class="relative">
                            <input type="email" wire:model.live="visitorEmail" {{ $sameAsContact ? 'readonly' : '' }} class="w-full px-5 py-4 rounded-[16px] border border-[#E2E8F0] text-[15px] font-medium text-[#1E293B] placeholder-[#94A3B8] focus:outline-none focus:ring-2 focus:ring-[#1A8DFF]/10 focus:border-[#1A8DFF] transition-all {{ $sameAsContact ? 'bg-[#F8FAFC]' : 'bg-white' }}" placeholder="Email">
                            @error('visitorEmail') <p class="text-red-500 text-xs mt-1 ml-2 font-medium">{{ $message }}</p> @enderror
                        </div>

                        {{-- Identity Card Input --}}
                        <div class="relative">
                            <input
                                type="text"
                                wire:model="visitorIdentityCard"
                                maxlength="16"
                                x-data
                                x-init="$el.addEventListener('input', function(e) {
                                    let value = e.target.value.replace(/[^0-9]/g, '');
                                    e.target.value = value;
                                    $wire.set('visitorIdentityCard', value);
                                })"
                                class="w-full px-5 py-4 rounded-[16px] border border-[#E2E8F0] text-[15px] font-medium text-[#1E293B] placeholder-[#94A3B8] focus:outline-none focus:ring-2 focus:ring-[#1A8DFF]/10 focus:border-[#1A8DFF] transition-all bg-white"
                                placeholder="Identity Card Number (16 digits)"
                            >
                            @error('visitorIdentityCard') <p class="text-red-500 text-xs mt-1 ml-2 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Total Payment Section (Compact & Relocated) --}}
                <div class="mt-6">
                    <div class="bg-white rounded-[20px] shadow-sm border border-[#E2E8F0] overflow-hidden">
                        {{-- Gradient Header --}}
                        <div class="bg-gradient-to-r from-[#00DDA3] to-[#7150FF] px-5 py-3 flex items-center gap-3">
                            <div class="w-5 h-5 bg-white/20 rounded-full flex items-center justify-center border border-white/20">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-[13px] font-bold text-white uppercase tracking-tight">You one more step to booking</span>
                        </div>

                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-[15px] font-bold text-[#64748B]">Total Payment</span>
                                <div class="flex items-center gap-2 group cursor-pointer">
                                    <span class="text-[18px] font-black text-[#1E293B]">IDR {{ number_format($order->total_amount) }}</span>
                                    <svg class="w-4 h-4 text-[#94A3B8] transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>

                            <div class="pt-5 border-t border-dashed border-[#E2E8F0] flex justify-end">
                                <button
                                    wire:click="confirmBooking"
                                    class="px-10 py-3 bg-[#0081FF] hover:bg-[#0071E3] text-white font-black text-[14px] rounded-[10px] transition-all shadow-md active:scale-[0.98] uppercase tracking-tight"
                                >
                                    Continue to payment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column (4 cols) - Event Summary --}}
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-[24px] border border-[#E2E8F0] shadow-sm overflow-hidden sticky top-8">
                    <div class="p-6">
                        <div class="flex gap-4">
                            @if($order->event->banner)
                                <img src="{{ $order->event->banner->url }}" class="w-[70px] h-[70px] object-cover rounded-[16px] shadow-sm" alt="Event">
                            @else
                                <div class="w-[70px] h-[70px] bg-[#F8FAFC] rounded-[16px] flex items-center justify-center text-gray-300 border border-[#E2E8F0]">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0 flex flex-col justify-center">
                                <div class="flex items-start justify-between gap-2">
                                    <h3 class="text-[14px] font-black text-[#1E293B] leading-tight line-clamp-2 uppercase tracking-tight">{{ $order->event->title }}</h3>
                                    <a href="#" class="text-[13px] font-bold text-[#1A8DFF] hover:underline whitespace-nowrap">Details</a>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-[#F1F5F9] border-dashed space-y-6">
                            @foreach($order->orderItems as $item)
                                <div class="space-y-1">
                                    <p class="text-[14px] font-black text-[#1E293B] uppercase tracking-wide">{{ $item->ticketType->name }}</p>
                                    <p class="text-[13px] text-[#64748B] font-bold">{{ $item->quantity }} Ticket • Pax 1</p>
                                </div>
                            @endforeach

                            <div class="space-y-1">
                                <p class="text-[13px] font-bold text-[#64748B]">Selected Date</p>
                                <p class="text-[14px] font-black text-[#1E293B]">{{ $order->event->event_date?->format('D, d M Y') }}</p>
                            </div>

                            {{-- Policy Info --}}
                            <div class="space-y-4 pt-2">
                                <div class="flex items-center gap-3.5 text-[#64748B]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    <span class="text-[13px] font-bold">Refund not allowed</span>
                                </div>
                                <div class="flex items-center gap-3.5 text-[#64748B]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    <span class="text-[13px] font-bold">Instant Confirmation</span>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex items-center gap-3.5 text-[#1E293B]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        <span class="text-[13px] font-black">Standing</span>
                                    </div>
                                    <p class="text-[12px] text-[#64748B] ml-[29px] font-medium leading-relaxed">All tickets are for standing area only.</p>
                                </div>
                                <div class="flex items-center gap-3.5 text-[#64748B]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="text-[13px] font-bold">Valid on the selected date</span>
                                </div>
                            </div>
                        </div>

                        {{-- Card Total --}}
                        <div class="mt-6 pt-6 border-t border-[#F1F5F9] border-dashed">
                            <div class="flex items-center justify-between group cursor-pointer">
                                <span class="text-[13px] font-bold text-[#64748B] uppercase tracking-wider">Total Payment</span>
                                <div class="flex items-center gap-2.5">
                                    <span class="text-[20px] font-black text-[#1E293B] tracking-tight">IDR {{ number_format($order->total_amount) }}</span>
                                    <div class="w-6 h-6 rounded-full bg-[#F8FAFC] flex items-center justify-center group-hover:bg-[#F1F5F9] transition-colors">
                                        <svg class="w-3.5 h-3.5 text-[#94A3B8] group-hover:text-[#1E293B] transition-transform duration-300 group-hover:translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
    </div>
</div>
</div>
