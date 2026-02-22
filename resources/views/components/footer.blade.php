@php
    $contactSetting = \App\Models\Setting::where('key', 'contact_information')->first();
    $contactComponents = $contactSetting ? $contactSetting->components->pluck('value', 'name') : [];
@endphp

{{-- Footer --}}

<footer class="bg-white pt-20 pb-10 mt-20 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8 mb-16">

            <!-- Brand Column -->
            <div class="space-y-6">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    <div class="transform group-hover:scale-110 transition-transform duration-300">
                        <img src="{{ asset('images/logo-difan.png') }}" alt="{{ config('app.name') }}" class="h-10 w-auto">
                    </div>
                </a>
                <p class="text-gray-500 leading-relaxed text-sm">
                    Your premier destination for booking tickets to the hottest concerts, festivals, and events worldwide.
                </p>

                <!-- Socials -->
                <div class="flex gap-3">
                    @foreach(['FB', 'TW', 'IG'] as $social)
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-600 hover:bg-red-500 hover:text-white transition-all duration-300">
                            {{ $social }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Company -->
            <div class="lg:pl-8">
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider mb-6">Company</h3>
                <ul class="space-y-4 text-sm font-medium text-gray-500">
                    <li><a href="#" class="hover:text-red-500 transition-colors">About Us</a></li>
                    <li><a href="#" class="hover:text-red-500 transition-colors">Careers</a></li>
                    <li><a href="#" class="hover:text-red-500 transition-colors">Blog</a></li>
                    <li><a href="#" class="hover:text-red-500 transition-colors">Partners</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider mb-6">Support</h3>
                <ul class="space-y-4 text-sm font-medium text-gray-500">
                    <li><a href="#" class="hover:text-red-500 transition-colors">Help Center</a></li>
                    <li><a href="#" class="hover:text-red-500 transition-colors">Terms of Service</a></li>
                    <li><a href="#" class="hover:text-red-500 transition-colors">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-red-500 transition-colors">Contact Us</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider mb-6">Contact</h3>
                <ul class="space-y-4">
                    @if($contactComponents->has('Office Address'))
                    <li class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-full bg-red-50 text-red-500 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-500">{{ $contactComponents->get('Office Address') }}</span>
                    </li>
                    @endif

                    @if($contactComponents->has('Phone Number'))
                    <li class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-red-50 text-red-500 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-500">{{ $contactComponents->get('Phone Number') }}</span>
                    </li>
                    @endif

                    @if($contactComponents->has('Email Address'))
                    <li class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-red-50 text-red-500 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-500">{{ $contactComponents->get('Email Address') }}</span>
                    </li>
                    @endif

                    @if($contactComponents->has('WhatsApp Number'))
                    <li class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-green-50 text-green-500 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-500">{{ $contactComponents->get('WhatsApp Number') }}</span>
                    </li>
                    @endif

                    @if($contactComponents->has('Business Hours'))
                    <li class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-500">{{ $contactComponents->get('Business Hours') }}</span>
                    </li>
                    @endif
                </ul>
            </div>
        </div>

        <!-- Checkpoint -->
        <div class="pt-8 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-sm font-medium text-gray-400">&copy; {{ date('Y') }} {{ config('app.name') }} Inc. All rights reserved.</p>

            <button class="flex items-center gap-2 px-4 py-2 rounded-full bg-gray-100 hover:bg-gray-200 transition-colors text-sm font-bold text-gray-600">
                <span>English (US)</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
        </div>
    </div>
</footer>
