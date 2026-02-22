@component('layouts.error')
    <main class="min-h-screen flex items-center justify-center bg-white dark:bg-gray-900">
        <div class="max-w-4xl w-full p-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow px-8 py-12 flex flex-col lg:flex-row items-center gap-8">
                <div class="flex-1 text-center lg:text-left">
                    <div class="text-red-600 font-semibold text-sm uppercase">Error 403</div>
                    <h1 class="mt-4 text-4xl font-extrabold text-gray-900 dark:text-white">VIPs Only</h1>
                    <h2 class="text-2xl font-bold text-red-600">Access Denied</h2>

                    <p class="mt-4 text-gray-500 dark:text-gray-400">Sorry, but it looks like you're not on the guest list for this page. This area is reserved for ticket holders with special clearance.</p>

                    <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center lg:justify-start">
                        <a href="{{ url('/') }}" class="inline-block px-4 py-2 bg-red-600 text-white rounded-md shadow">Return to Safety</a>
                        <a href="mailto:support@{{ strtolower(str_replace(' ', '', config('app.name'))) }}.com" class="inline-block px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-md">Contact Support</a>
                    </div>

                    <div class="mt-6 text-sm text-gray-400">If you believe this is an error, contact support with details and your order number.</div>
                </div>

                <div class="w-64 flex-shrink-0">
                    <div class="h-48 w-48 rounded-full bg-gradient-to-br from-red-50 to-red-100 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center">
                        <svg class="w-20 h-20 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5s-3 1.343-3 3 1.343 3 3 3z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 21v-2a4 4 0 014-4h6a4 4 0 014 4v2" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endcomponent
