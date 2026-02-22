@php
    $url = $getRecord()?->url;
@endphp

<div class="group relative flex flex-col items-center">
    <div class="w-full aspect-square p-2.5 rounded-3xl bg-[#FDEEE5] border border-[#FBE0D1] shadow-sm transition-all duration-300 hover:shadow-xl hover:shadow-[#F5A87B]/20 hover:-translate-y-1">
        <div class="relative w-full h-full overflow-hidden rounded-[1.25rem] bg-white group-hover:ring-2 group-hover:ring-[#F5A87B] transition-all">
            @if($url)
                <img 
                    src="{{ $url }}" 
                    alt="Payment Proof" 
                    class="object-cover w-full h-full transition-transform duration-700 ease-out group-hover:scale-110"
                >
                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 bg-black/10 backdrop-blur-[2px]">
                    <a 
                        href="{{ $url }}" 
                        target="_blank" 
                        class="px-5 py-2.5 text-xs font-bold tracking-widest uppercase text-white bg-black/70 backdrop-blur-md rounded-xl border border-white/30 shadow-2xl scale-90 group-hover:scale-100 transition-all hover:bg-black/90 active:scale-95"
                    >
                        View Proof
                    </a>
                </div>
            @else
                <div class="w-full h-full flex flex-col items-center justify-center text-[#F5A87B]/40">
                    <x-heroicon-m-photo class="w-12 h-12 mb-2" />
                    <span class="text-[10px] font-bold uppercase tracking-wider">No Image</span>
                </div>
            @endif
        </div>
    </div>
</div>


