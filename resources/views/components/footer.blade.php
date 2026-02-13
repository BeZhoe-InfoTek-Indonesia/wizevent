{{-- Footer --}}
<footer class="bg-white border-t border-gray-100 pt-16 pb-8 mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <!-- Column 1: Custom Logo & Contact -->
            <div class="col-span-1 lg:col-span-1">
                <div class="mb-6">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <x-application-logo class="h-8 w-auto fill-current text-blue-600" />
                        <span class="text-xl font-bold text-gray-900 tracking-tight">{{ config('app.name') }}</span>
                    </a>
                </div>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <div class="bg-gray-100 p-2 rounded-full text-gray-600 mt-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">WhatsApp</div>
                            <div class="text-gray-900 font-medium">+62 858 1150 0888</div>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Column 2: Company & Products -->
            <div class="col-span-1 lg:col-span-1 grid grid-cols-2 gap-8">
                <div>
                    <h3 class="font-bold text-gray-900 mb-4">Company</h3>
                    <ul class="space-y-3 text-sm text-gray-600">
                        <li><a href="#" class="hover:text-blue-600">Blog</a></li>
                        <li><a href="#" class="hover:text-blue-600">Newsroom</a></li>
                        <li><a href="#" class="hover:text-blue-600">Careers</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 mb-4">Products</h3>
                    <ul class="space-y-3 text-sm text-gray-600">
                        <li><a href="#" class="hover:text-blue-600">Flights</a></li>
                        <li><a href="#" class="hover:text-blue-600">Hotels</a></li>
                        <li><a href="#" class="hover:text-blue-600">Events</a></li>
                    </ul>
                </div>
            </div>

            <!-- Column 3: Support -->
            <div class="col-span-1 lg:col-span-1">
                <h3 class="font-bold text-gray-900 mb-4">Support</h3>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li><a href="#" class="hover:text-blue-600">Help Center</a></li>
                    <li><a href="#" class="hover:text-blue-600">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-blue-600">Terms & Conditions</a></li>
                </ul>
            </div>

            <!-- Column 4: App Download -->
            <div class="col-span-1 lg:col-span-1">
                    <h3 class="font-bold text-gray-900 mb-4">Cheaper on the app</h3>
                <div class="space-y-3">
                    <a href="#" class="block bg-black text-white px-4 py-2 rounded-lg flex items-center gap-3 w-48 hover:opacity-90 transition">
                        <div class="text-left">
                            <div class="text-[0.6rem] leading-none uppercase">Download on the</div>
                            <div class="text-lg font-bold leading-none">App Store</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="border-t border-dashed border-gray-200 mt-12 pt-8 text-center sm:text-left text-xs text-gray-500">
            &copy; 2011-{{ date('Y') }} PT. Global Tiket Network. All Rights Reserved.
        </div>
    </div>
</footer>
