<div x-data="{ open: false, isMobile: window.innerWidth < 768 }" x-init="isMobile = window.innerWidth < 768">
    <div class="bg-gray-50 min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <div class="flex flex items-center justify-between mb-8">
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Notifications</h1>
                <span class="text-sm font-normal text-gray-500">Manage your notifications</span>
            </div>

            {{-- Filters --}}
            <div class="flex flex items-center gap-2 overflow-x-auto pb-6 mb-6 border-b border-gray-200">
                <button 
                        wire:click="setFilter('all')"
                        :class="filter === 'all' ? 'bg-blue-500 text-white font-bold' : 'text-gray-700 hover:bg-gray-100'"
                        class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors">
                    All
                </button>
                <button 
                        wire:click="setFilter('unread')"
                        :class="filter === 'unread' ? 'bg-blue-500 text-white font-bold' : 'text-gray-700 hover:bg-gray-100'"
                        class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors">
                    Unread
                </button>
                <button 
                        wire:click="setFilter('payment')"
                        :class="filter === 'payment' ? 'bg-blue-500 text-white font-bold' : 'text-gray-700 hover:bg-gray-100'"
                        class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors">
                    Payments
                </button>
                <button 
                        wire:click="setFilter('events')"
                        :class="filter === 'events' ? 'bg-blue-500 text-white font-bold' : 'text-gray-700 hover:bg-gray-100'"
                        class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors">
                    Events
                </button>
                <button 
                        wire:click="setFilter('loved_events')"
                        :class="filter === 'loved_events' ? 'bg-blue-500 text-white font-bold' : 'text-gray-700 hover:bg-gray-100'"
                        class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors">
                    Loved Events
                </button>
                <button 
                        wire:click="setFilter('promotions')"
                        :class="filter === 'promotions' ? 'bg-blue-500 text-white font-bold' : 'text-gray-700 hover:bg-gray-100'"
                        class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors">
                    Promotions
                </button>
            </div>

            @if($this->notifications->count() > 0)
                <div class="space-y-4">
                    @foreach($this->notifications as $notification)
                        @isset($notification->data['type'])
                            @php
                                $type = $notification->data['type'] ?? 'notification';
                                $icon = $this->getNotificationIcon($type);
                                $label = $this->getNotificationTypeLabel($type);
                                $isUnread = is_null($notification->read_at);
                            @endphp
                        <div class="bg-white border border-gray-200 rounded-2xl p-4 hover:shadow-md transition-all duration-200 {{ $isUnread ? 'bg-blue-50 border-blue-200' : '' }}">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center {{ $isUnread ? 'bg-blue-100' : 'bg-gray-100' }}">
                                    <svg class="w-6 h-6 {{ $isUnread ? 'text-blue-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13v2a10 10 0 0l-10 5v10l10-0 0 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div>
                                        <p class="font-bold text-gray-900 text-sm">{{ $notification->data['title'] ?? 'Notification' }}</p>
                                        <p class="text-gray-600 text-sm line-clamp-2">{{ $notification->data['message'] ?? '' }}</p>
                                    </div>
                                    <div class="ml-auto flex items-center gap-3">
                                        <span class="text-xs text-gray-400 whitespace-nowrap">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                        <button 
                                                wire:click="dismiss('{{ $notification->id }}')"
                                                class="text-gray-400 hover:text-gray-600 hover:text-red-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6 4 6v12 0 0 0l-0 0 0z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white border border-gray-200 rounded-2xl p-12 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12v2a10 10 0 0l10 5v10l10 0 0 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No Notifications</h3>
                        <p class="text-gray-600 text-sm">You're all caught up! New notifications will appear here.</p>
                    </div>
                </div>
            @endif

            {{-- Load More Button --}}
            @if($this->notifications->hasMorePages())
                <div class="text-center mt-8">
                    {{ $this->notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
