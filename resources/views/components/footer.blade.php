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
                    <li class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-full bg-red-50 text-red-500 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-500">123 Event Street, New York, NY 10001</span>
                    </li>
                     <li class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-red-50 text-red-500 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-500">+1 (555) 123-4567</span>
                    </li>
                     <li class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-red-50 text-red-500 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-500">support@{{ strtolower(str_replace(' ', '', config('app.name'))) }}.com</span>
                    </li>
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
