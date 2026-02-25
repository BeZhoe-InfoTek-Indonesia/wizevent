<x-filament-widgets::widget class="fi-clock-widget">
    <x-filament::section>
        <div 
            x-data="{ 
                time: '',
                date: '',
                day: '',
                updateTime() {
                    const now = new Date();
                    this.time = new Intl.DateTimeFormat('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true }).format(now);
                    this.date = new Intl.DateTimeFormat('en-US', { month: 'long', day: 'numeric', year: 'numeric' }).format(now);
                    this.day = new Intl.DateTimeFormat('en-US', { weekday: 'long' }).format(now);
                }
            }" 
            x-init="updateTime(); setInterval(() => updateTime(), 1000)"
            class="flex items-center gap-4 w-full"
        >
            <div class="rounded-full bg-primary-100 p-3 dark:bg-primary-900 ring-1 ring-primary-500/20">
                <x-filament::icon
                    icon="heroicon-o-clock"
                    class="h-8 w-8 text-primary-600 dark:text-primary-400"
                />
            </div>
            
            <div class="flex flex-col min-w-0">
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400" x-text="day + ', ' + date"></p>
                <p class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white truncate font-mono" x-text="time"></p>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
