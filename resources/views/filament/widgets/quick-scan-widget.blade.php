<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex flex-col items-center justify-center py-6 text-center">
            <div class="mb-4 p-4 bg-indigo-50 dark:bg-indigo-900/30 rounded-full ring-8 ring-indigo-50/50 dark:ring-indigo-900/10">
                <svg class="w-10 h-10 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                </svg>
            </div>
            
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('scanner.quick_scan_title') }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 max-w-[200px]">
                {{ __('scanner.quick_scan_description') }}
            </p>
            
            <x-filament::button 
                tag="a" 
                href="{{ \App\Filament\Pages\ScanTickets::getUrl() }}"
                color="primary"
                icon="heroicon-m-qr-code"
                class="w-full shadow-lg shadow-primary-500/20"
            >
                {{ __('scanner.open_scanner') }}
            </x-filament::button>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
