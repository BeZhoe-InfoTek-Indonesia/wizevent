<div class="py-12 bg-gray-50/50 min-h-screen">
    <div class="container mx-auto px-4">
        {{-- Back Button --}}
        <div class="mb-8">
            <a href="{{ route('events.show', $event->slug) }}" class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-[#ff4747] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Event
            </a>
        </div>

        @if($this->approvedTestimonials->isNotEmpty())
            @php
                $testimonials = $this->approvedTestimonials;
                $totalReviews = $testimonials->count();
                $averageRating = $totalReviews > 0 ? $testimonials->avg('rating') : 0;
                
                $starCounts = [
                    5 => $testimonials->where('rating', 5)->count(),
                    4 => $testimonials->where('rating', 4)->count(),
                    3 => $testimonials->where('rating', 3)->count(),
                    2 => $testimonials->where('rating', 2)->count(),
                    1 => $testimonials->where('rating', 1)->count(),
                ];
            @endphp

            <div x-data="{ ratingFilter: 'all', sortBy: 'newest' }">
                <div class="mb-12">
                    <div class="flex items-center gap-4 mb-2">
                        <div class="w-1.5 h-8 bg-[#ff4747] rounded-full"></div>
                        <h2 class="text-3xl font-black text-gray-900 tracking-tight">What People Say</h2>
                    </div>
                    <p class="text-gray-500 font-medium ml-5.5">Real feedback from {{ $totalReviews }} attendees</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                    {{-- Left Column: Summary & Filters --}}
                    <div class="lg:col-span-4 space-y-8">
                        {{-- Rating Summary Card --}}
                        <div class="bg-white rounded-[24px] p-8 border border-gray-100 shadow-[0_20px_40px_rgba(0,0,0,0.04)] sticky top-8">
                            <div class="flex items-end gap-4 mb-8">
                                <span class="text-7xl font-black text-gray-900 tracking-tighter leading-none">{{ number_format($averageRating, 1) }}</span>
                                <div class="pb-2 space-y-1">
                                    <div class="flex items-center gap-1">
                                        @for($i=1; $i<=5; $i++)
                                            <svg class="w-5 h-5 {{ $i <= round($averageRating) ? 'text-[#ff4747]' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest pl-0.5">OUT OF 5</p>
                                </div>
                            </div>

                            {{-- Progress Bars --}}
                            <div class="space-y-3 mb-8">
                                @foreach([5,4,3,2,1] as $star)
                                    @php 
                                        $count = $starCounts[$star];
                                        $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                                    @endphp
                                    <div class="flex items-center gap-4 text-sm group cursor-pointer" @click="ratingFilter = ratingFilter === {{ $star }} ? 'all' : {{ $star }}">
                                        <span class="font-bold text-gray-900 w-3 text-right group-hover:text-[#ff4747] transition-colors">{{ $star }}</span>
                                        <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-[#ff4747] rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <span class="font-medium text-gray-400 w-8 text-right group-hover:text-gray-900 transition-colors">{{ round($percentage) }}%</span>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Write Review CTA --}}
                            @if($this->canSubmitTestimonial)
                                <a href="{{ route('events.review', ['slug' => $event->slug]) }}" 
                                   class="flex w-full items-center justify-center gap-2 px-6 py-4 bg-[#ff4747] text-white rounded-xl font-bold hover:bg-[#ff3333] hover:shadow-[0_10px_20px_rgba(255,71,71,0.3)] hover:-translate-y-0.5 transition-all active:scale-95">
                                    Write a Review
                                </a>
                            @endif
                            
                            {{-- Filter Panel --}}
                            <div class="mt-8 pt-8 border-t border-gray-100">
                                <h3 class="font-bold text-gray-900 mb-4">Filter Reviews</h3>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 block">Sort By</label>
                                        <div class="relative">
                                            <select x-model="sortBy" class="w-full appearance-none bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-[#ff4747] focus:border-[#ff4747] block w-full p-2.5 font-bold cursor-pointer">
                                                <option value="newest">Newest First</option>
                                                <option value="oldest">Oldest First</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 block">Rating Range</label>
                                        <div class="flex flex-wrap gap-2">
                                            <button @click="ratingFilter = 'all'" 
                                                    :class="ratingFilter === 'all' ? 'bg-[#ff4747] text-white border-[#ff4747]' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-300'"
                                                    class="px-4 py-2 text-xs font-bold rounded-xl border transition-all">
                                                All
                                            </button>
                                            @foreach([5,4,3] as $star)
                                            <button @click="ratingFilter = {{ $star }}" 
                                                    :class="ratingFilter === {{ $star }} ? 'bg-[#ff4747] text-white border-[#ff4747]' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-300'"
                                                    class="px-4 py-2 text-xs font-bold rounded-xl border transition-all">
                                                {{ $star }} Stars
                                            </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Reviews List --}}
                    <div class="lg:col-span-8 space-y-4" :class="{'flex flex-col-reverse': sortBy === 'oldest'}">
                        @foreach($testimonials as $testimonial)
                            <div x-show="ratingFilter === 'all' || ratingFilter === {{ $testimonial->rating }}"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 translate-y-4"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="bg-white rounded-[24px] p-8 border border-gray-100 hover:border-gray-200 transition-all duration-300 relative group">
                                
                                @if($testimonial->is_featured)
                                    <div class="absolute -top-3 -right-3 z-10">
                                        <span class="px-3 py-1 bg-[#ff4747] text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg shadow-red-500/30">
                                            Featured Review
                                        </span>
                                    </div>
                                @endif

                                <div class="flex items-start gap-4 sm:gap-6">
                                    {{-- Avatar --}}
                                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg sm:text-xl shrink-0 shadow-lg shadow-blue-500/20">
                                        {{ strtoupper(substr($testimonial->user->name, 0, 1)) }}
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <h4 class="font-bold text-gray-900 text-lg leading-tight">{{ $testimonial->user->name }}</h4>
                                                    <span class="px-2 py-0.5 bg-green-50 text-green-600 text-[10px] font-bold uppercase tracking-widest rounded-md border border-green-100">Verified</span>
                                                </div>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <div class="flex gap-0.5">
                                                        @for($i=1; $i<=5; $i++)
                                                            <svg class="w-3.5 h-3.5 {{ $i <= $testimonial->rating ? 'text-[#ff4747]' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                            </svg>
                                                        @endfor
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-xs text-gray-400 font-medium whitespace-nowrap">
                                                {{ $testimonial->created_at->diffForHumans() }}
                                            </span>
                                        </div>

                                        <div class="text-gray-600 leading-relaxed mb-6 text-sm sm:text-base">
                                            "{{ $testimonial->content }}"
                                        </div>

                                        <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                                            <span class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">Was this helpful?</span>
                                            <div class="flex items-center gap-4">
                                                <button wire:click="voteOnTestimonial({{ $testimonial->id }}, true)"
                                                        class="group flex items-center gap-1.5 text-xs font-medium text-gray-400 hover:text-gray-700 transition-colors">
                                                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                                    </svg>
                                                    {{ $testimonial->helpful_votes_count }}
                                                </button>
                                                <button wire:click="voteOnTestimonial({{ $testimonial->id }}, false)"
                                                        class="group flex items-center gap-1.5 text-xs font-medium text-gray-400 hover:text-gray-700 transition-colors">
                                                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform mt-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M18 9.5a1.5 1.5 0 11-3 0v-6a1.5 1.5 0 013 0v6zM14 9.667v-5.43a2 2 0 00-1.105-1.79l-.05-.025A4 4 0 0011.055 2H5.64a2 2 0 00-1.962 1.608l-1.2 6A2 2 0 004.44 12H8v4a2 2 0 002 2 1 1 0 001-1v-.667a4 4 0 01.8-2.4l1.4-1.866a4 4 0 00.8-2.4z"/>
                                                    </svg>
                                                    {{ $testimonial->not_helpful_votes_count }}
                                                </button>
                                                
                                                <button class="text-gray-300 hover:text-red-500 transition-colors ml-2">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z" clip-rule="evenodd"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            {{-- Empty State or Just Submission for first --}}
             <div class="max-w-xl mx-auto text-center py-20">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-2">No reviews yet</h3>
                <p class="text-gray-500 mb-8">Be the first to share your experience with this event!</p>
                
                @if($this->canSubmitTestimonial)
                    <a href="{{ route('events.review', ['slug' => $event->slug]) }}" 
                       class="inline-block px-8 py-4 bg-[#ff4747] text-white rounded-xl font-bold hover:bg-[#ff3333] shadow-lg shadow-red-500/30 transition-all">
                        Write a Review
                    </a>
                @endif
             </div>
        @endif
    </div>
</div>
