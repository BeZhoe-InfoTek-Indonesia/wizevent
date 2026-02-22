<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Your Reviews</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manage and view your past event feedback</p>
        </div>
        
        <div class="w-full sm:w-auto">
            <select 
                wire:model.live="sort"
                class="w-full sm:w-48 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 text-sm focus:ring-red-500 focus:border-red-500"
            >
                <option value="latest">Sort by Date (Newest)</option>
                <option value="oldest">Sort by Date (Oldest)</option>
                <option value="rating_high">Highest Rating</option>
                <option value="rating_low">Lowest Rating</option>
            </select>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Total Reviews -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Reviews</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $this->totalReviewsCount }}</p>
            </div>
            <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-full">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                </svg>
            </div>
        </div>

        <!-- Helpful Feedback -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Helpful Feedback</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $this->totalHelpfulVotes }}</p>
            </div>
             <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-full">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Tabs/Filter -->
    <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="-mb-px flex space-x-8">
            <button 
                wire:click="setStatusFilter('all')"
                class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2 {{ $statusFilter === 'all' || $statusFilter === 'approved' ? 'border-red-500 text-red-600 dark:text-red-500' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Review List
            </button>

            <button 
                wire:click="setStatusFilter('to_review')"
                class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2 {{ $statusFilter === 'to_review' ? 'border-red-500 text-red-600 dark:text-red-500' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Waiting for Review
                @if($this->eventsToReviewCount > 0)
                    <span class="bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400 py-0.5 px-2.5 rounded-full text-xs">
                        {{ $this->eventsToReviewCount }}
                    </span>
                @endif
            </button>
        </nav>
    </div>

    <!-- Review List or Events to Review -->
    @if($statusFilter === 'to_review')
        @if($this->eventsToReview->count() > 0)
            <div class="space-y-4">
                @foreach($this->eventsToReview as $event)
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
                        <div class="flex flex-col sm:flex-row gap-6">
                            <!-- Event Image -->
                            <div class="shrink-0">
                                <img 
                                    src="{{ $event->banner?->url ?? 'https://placehold.co/100x100' }}" 
                                    alt="{{ $event->title }}" 
                                    class="w-full sm:w-32 sm:h-32 rounded-lg object-cover"
                                >
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 line-clamp-1">
                                            {{ $event->title }}
                                        </h3>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $event->event_date->format('M d, Y') }}
                                            </span>
                                            <span class="text-xs text-gray-400">•</span>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $event->location }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Actions -->
                                    <div class="flex items-center gap-2">
                                        <a 
                                            href="{{ route('events.show', $event) }}#reviews"
                                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors"
                                        >
                                            Write Review
                                        </a>
                                    </div>
                                </div>
                                
                                <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed mb-4 line-clamp-2">
                                    {{ $event->short_description ?? $event->description }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $this->eventsToReview->links() }}
            </div>
        @else
            <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl border border-dashed border-gray-200 dark:border-gray-700">
                <div class="w-16 h-16 bg-gray-50 dark:bg-gray-700/50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">No events to review</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">You're all caught up! Browse more events to attend.</p>
                <a href="{{ route('events.index') }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                    Browse Events
                </a>
            </div>
        @endif
    @elseif($this->reviews->count() > 0)
        <div class="space-y-4">
            @foreach($this->reviews as $review)
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="flex flex-col sm:flex-row gap-6">
                        <!-- Event Image -->
                        <div class="shrink-0">
                            <img 
                                src="{{ $review->event->banner?->url ?? 'https://placehold.co/100x100' }}" 
                                alt="{{ $review->event->title }}" 
                                class="w-full sm:w-32 sm:h-32 rounded-lg object-cover"
                            >
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 line-clamp-1">
                                        {{ $review->event->title }}
                                    </h3>
                                    <div class="flex items-center gap-2 mt-1">
                                        <div class="flex items-center text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-300 dark:text-gray-600' }}" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="text-sm font-medium text-red-500">
                                            {{ $review->user->name ?? 'User' }}
                                        </span>
                                        <span class="text-xs text-gray-400">•</span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $review->created_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Actions -->
                                <div class="flex items-center gap-2">
                                     <span class="px-2 py-1 text-xs font-medium rounded-full 
                                        {{ $review->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 
                                           ($review->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 
                                           'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400') }}">
                                        {{ ucfirst($review->status) }}
                                    </span>
                                    <button 
                                        wire:click="deleteReview({{ $review->id }})"
                                        wire:confirm="Are you sure you want to delete this review?"
                                        class="p-2 text-gray-400 hover:text-red-500 transition-colors rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20"
                                        title="Delete review"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed mb-4">
                                {{ $review->content }}
                            </p>
                            
                            <!-- Review Stats -->
                            @if($review->status === 'approved')
                                <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400 border-t border-gray-100 dark:border-gray-700 pt-3">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                                        </svg>
                                        {{ $review->helpful_votes_count ?? 0 }} found helpful
                                    </div>
                                    <div class="flex items-center gap-1">
                                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        128 views
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $this->reviews->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl border border-dashed border-gray-200 dark:border-gray-700">
            <div class="w-16 h-16 bg-gray-50 dark:bg-gray-700/50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">No reviews found</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6">You haven't posted any reviews yet.</p>
            <a href="{{ route('events.index') }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                Browse Events to Review
            </a>
        </div>
    @endif
</div>

<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('review-deleted', (event) => {
        // Optional: Toast notification instead of alert
        // alert(event.message); 
    });
});
</script>
