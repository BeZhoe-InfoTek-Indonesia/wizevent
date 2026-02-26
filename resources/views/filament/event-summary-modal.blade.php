@php
    $totalRevenue = $event->orders->where('status', 'completed')->sum('total_amount');
    $totalSold = $event->ticketTypes->sum('sold_count');
    $capacity = $event->total_capacity ?: 1;
    $attendanceRate = round(($totalSold / $capacity) * 100);
    $avgRating = $event->testimonials->where('is_published', true)->avg('rating') ?: 0;
    $reviewCount = $event->testimonials->where('is_published', true)->count();
    
    // Sentiment Logic
    $positiveReviews = $event->testimonials->where('is_published', true)->where('rating', '>=', 4)->count();
    $sentimentRate = $reviewCount > 0 ? round(($positiveReviews / $reviewCount) * 100) : 0;
    
    $featuredReview = $event->testimonials->where('is_published', true)->where('is_featured', true)->first() 
        ?? $event->testimonials->where('is_published', true)->sortByDesc('created_at')->first();
@endphp

<div class="neon-summary-root">
    <style>
        .neon-summary-root {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: #1f2937;
            padding: 1.5rem;
            line-height: 1.5;
        }
        .dark .neon-summary-root {
            background-color: #030712;
            color: #f3f4f6;
        }

        /* Grid System */
        .ns-grid {
            display: grid;
            gap: 1.5rem;
        }
        .ns-grid-2 { grid-template-columns: repeat(1, 1fr); }
        .ns-grid-4 { grid-template-columns: repeat(1, 1fr); }
        
        @media (min-width: 640px) {
            .ns-grid-2 { grid-template-columns: repeat(2, 1fr); }
            .ns-grid-4 { grid-template-columns: repeat(2, 1fr); }
        }
        @media (min-width: 1024px) {
            .ns-grid-4 { grid-template-columns: repeat(4, 1fr); }
        }

        /* Cards */
        .ns-card {
            background: white;
            border-radius: 1.25rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #f3f4f6;
            transition: transform 0.2s;
        }
        .dark .ns-card {
            background: #111827;
            border-color: #1f2937;
            box-shadow: none;
        }
        .ns-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }

        /* Typography */
        .ns-title { font-size: 1.5rem; font-weight: 800; letter-spacing: -0.025em; margin: 0; }
        .ns-subtitle { font-size: 0.875rem; color: #6b7280; font-weight: 500; }
        .ns-label { font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #9ca3af; }
        .ns-value { font-size: 1.875rem; font-weight: 900; line-height: 1; margin-top: 0.5rem; }
        
        /* Layout Utils */
        .ns-flex { display: flex; align-items: center; }
        .ns-between { justify-content: space-between; }
        .ns-gap-2 { gap: 0.5rem; }
        .ns-gap-4 { gap: 1rem; }
        .ns-mb-4 { margin-bottom: 1rem; }
        .ns-mt-auto { margin-top: auto; }

        /* Components */
        .ns-icon-box {
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
        }
        .ns-badge {
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
        }
        
        /* Table */
        .ns-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
        .ns-table th { text-align: left; padding-bottom: 1rem; font-size: 0.65rem; color: #9ca3af; text-transform: uppercase; }
        .ns-table td { padding: 0.75rem 0; border-bottom: 1px solid #f3f4f6; font-weight: 600; }
        .dark .ns-table td { border-bottom-color: #1f2937; }
        
        /* Specific Colors */
        .text-red { color: #dc2626; }
        .bg-red-light { background: #fef2f2; }
        .dark .bg-red-light { background: rgba(220, 38, 38, 0.1); }
        .text-emerald { color: #059669; }
        .bg-emerald-light { background: #ecfdf5; }
        .dark .bg-emerald-light { background: rgba(5, 150, 105, 0.1); }
        
        /* SVG Reset */
        .neon-summary-root svg { display: inline-block; vertical-align: middle; }
    </style>

    {{-- Header --}}
    <div class="ns-flex ns-between ns-mb-4">
        <div class="ns-flex ns-gap-4">
            <div class="ns-icon-box bg-red-light" style="width: 3.5rem; height: 3.5rem; border-radius: 1rem;">
                <!-- Explicit width/height to prevent huge icon -->
                <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor" style="color: #dc2626; width: 28px; height: 28px;">
                    <path d="M13 2.05c.5.03 1.03.18 1.54.46.96.53 1.63 1.48 1.83 2.57.17.93-.08 1.88-.66 2.65-.54.72-1.35 1.15-2.26 1.25-.09.01-.18.01-.27.01h-.3l2.84 6.63c.27.63.15 1.37-.32 1.88-.47.51-1.18.67-1.78.38-.21-.1-.39-.24-.55-.42l-7.7-10.43c-.47-.64-.53-1.48-.14-2.19.38-.71 1.12-1.15 1.93-1.15.22 0 .44.03.66.1.72.21 1.28.78 1.48 1.5.17.62.03 1.29-.38 1.81-.38.48-.96.76-1.57.76-.06 0-.12 0-.18-.01l2.35 5.25c.08.18.3.26.49.17.18-.08.26-.3.17-.49l-2.84-6.63c-.11-.26-.06-.56.12-.79.18-.23.49-.3.74-.18zM19.5 2h-4c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h4c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 18h-4V4h4v16z"/>
                </svg>
            </div>
            <div>
                <div class="ns-flex ns-gap-2">
                    <h2 class="ns-title">{{ $event->title }}</h2>
                    <span class="ns-badge bg-emerald-light text-emerald">{{ $event->status }}</span>
                </div>
                <p class="ns-subtitle" style="margin-top: 0.25rem;">
                    ID: {{ $event->slug }} • {{ $event->event_date->format('M d, Y') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="ns-grid ns-grid-4 ns-mb-4">
        {{-- Revenue --}}
        <div class="ns-card">
            <div class="ns-flex ns-between" style="margin-bottom: 0.5rem;">
                <span class="ns-label">TOTAL REVENUE</span>
                <div class="ns-icon-box bg-red-light" style="width: 2rem; height: 2rem;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #dc2626; width: 16px; height: 16px;">
                        <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="ns-value text-red" style="font-size: 1.5rem;">
                <span style="font-size: 1rem; color: #fca5a5;">Rp</span> {{ number_format($totalRevenue, 0, ',', '.') }}
            </div>
            <div class="ns-flex ns-gap-2 text-emerald" style="font-size: 0.75rem; font-weight: 700; margin-top: 0.5rem; text-transform: uppercase;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" style="width: 12px; height: 12px;"><path d="M13 7h8m0 0v8m0-8l-9 9-4-4-6 6"/></svg>
                +12.5% VS GOAL
            </div>
        </div>

        {{-- Attendance --}}
        <div class="ns-card">
            <div class="ns-flex ns-between" style="margin-bottom: 0.5rem;">
                <span class="ns-label">ATTENDANCE</span>
                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" style="color: #d1d5db; width: 20px; height: 20px;"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
            </div>
            <div class="ns-flex ns-between" style="align-items: flex-end;">
                <div>
                    <div class="ns-value">{{ number_format($totalSold) }}</div>
                    <div class="ns-label" style="margin-top: 0.25rem;">/ {{ number_format($capacity) }} CAP.</div>
                </div>
                <!-- Simple Circle Chart -->
                <div style="position: relative; width: 3.5rem; height: 3.5rem;">
                    <svg width="56" height="56" viewBox="0 0 36 36" style="transform: rotate(-90deg); width: 56px; height: 56px;">
                        <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#e5e7eb" stroke-width="3" />
                        <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#dc2626" stroke-width="3" stroke-dasharray="{{ $attendanceRate }}, 100" />
                    </svg>
                    <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 800;">{{ $attendanceRate }}%</div>
                </div>
            </div>
        </div>

        {{-- Rating --}}
        <div class="ns-card">
            <div class="ns-flex ns-between" style="margin-bottom: 0.5rem;">
                <span class="ns-label">EVENT RATING</span>
                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" style="color: #fbbf24; width: 20px; height: 20px;"><path d="M9.049 2.927c.3-.921 1.603-.921 1.603-.921s1.303 0 1.603.921l1.723 5.273h5.545c.969 0 1.371 1.24.588 1.81l-4.487 3.26 1.723 5.273c.271.827-.745 1.584-1.533 1.01l-4.487-3.26-4.487 3.26c-.788.574-1.804-.183-1.533-1.01l1.723-5.273-4.487-3.26c-.783-.57-.381-1.81.588-1.81h5.545l1.723-5.273z"/></svg>
            </div>
            <div class="ns-flex" style="align-items: baseline; gap: 0.25rem;">
                <div class="ns-value">{{ number_format($avgRating, 1) }}</div>
                <span class="ns-label" style="font-size: 0.875rem; color: #d1d5db;">/ 5</span>
            </div>
            <div class="ns-label" style="display: flex; align-items: center; gap: 0.25rem; margin-top: 0.5rem;">
                <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20" style="width: 12px; height: 12px;"><path d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z"/></svg>
                FROM {{ $reviewCount }} REVIEWS
            </div>
        </div>

        {{-- Peak Hour --}}
        <div class="ns-card">
            <div class="ns-flex ns-between" style="margin-bottom: 0.5rem;">
                <span class="ns-label">PEAK HOUR</span>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #3b82f6; width: 20px; height: 20px;"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="ns-value">9:45 <span style="font-size: 1.25rem; color: #9ca3af; font-weight: 700;">PM</span></div>
            <div class="ns-label" style="margin-top: 0.5rem;">MAX GATE ACTIVITY</div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="ns-grid ns-grid-2 ns-mb-4">
        {{-- Sales Breakdown --}}
        <div class="ns-card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column;">
            <div class="ns-flex ns-between" style="padding: 1.5rem; border-bottom: 1px solid #f3f4f6;">
                <div class="ns-flex ns-gap-2">
                    <div style="width: 0.375rem; height: 1.5rem; background: #dc2626; border-radius: 9999px;"></div>
                    <h3 style="font-size: 1.125rem; font-weight: 800;">Sales Breakdown</h3>
                </div>
                <a href="#" style="font-size: 0.65rem; font-weight: 800; color: #dc2626; text-transform: uppercase; letter-spacing: 0.05em; text-decoration: none; border-bottom: 2px solid rgba(220,38,38,0.2);">SEE FULL TABLE</a>
            </div>
            <div style="padding: 1.5rem;">
                <table class="ns-table">
                    <thead>
                        <tr>
                            <th>Ticket Category</th>
                            <th style="text-align: center;">Qty</th>
                            <th style="text-align: right;">Revenue</th>
                            <th style="text-align: right;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($event->ticketTypes as $ticket)
                            @php
                                $tCap = $ticket->quantity ?: 1;
                                $tPerc = ($ticket->sold_count / $tCap) * 100;
                            @endphp
                            <tr>
                                <td>{{ $ticket->name }}</td>
                                <td style="text-align: center; color: #6b7280;">{{ number_format($ticket->sold_count) }}</td>
                                <td style="text-align: right;">Rp {{ number_format($ticket->sold_count * $ticket->price, 0, ',', '.') }}</td>
                                <td style="text-align: right;">
                                    @if($tPerc >= 100)
                                        <span class="ns-badge" style="background: #f3f4f6; color: #4b5563;">SOLD OUT</span>
                                    @else
                                        <span class="ns-badge bg-red-light text-red">{{ round($tPerc) }}% SOLD</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #f3f4f6;">
                    <div class="ns-flex ns-between ns-label" style="margin-bottom: 0.5rem;">
                        <span>SALES TARGET PROGRESSION</span>
                        <span style="color: #111827;">{{ round(($totalSold / $capacity) * 100, 1) }}%</span>
                    </div>
                    <div style="width: 100%; height: 0.625rem; background: #f3f4f6; border-radius: 9999px; overflow: hidden;">
                        <div style="height: 100%; background: linear-gradient(to right, #ef4444, #dc2626); width: {{ ($totalSold / $capacity) * 100 }}%;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Attendee Response --}}
        <div class="ns-card" style="display: flex; flex-direction: column;">
            <div class="ns-flex ns-between ns-mb-4">
                <div class="ns-flex ns-gap-2">
                    <div class="ns-icon-box" style="background: linear-gradient(135deg, #ef4444, #b91c1c); color: white; width: 2rem; height: 2rem; border-radius: 0.5rem;">
                         <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" style="width: 16px; height: 16px;"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-7.536 5.879a1 1 0 001.415 0 3 3 0 014.242 0 1 1 0 001.415-1.415 5 5 0 00-7.072 0 1 1 0 000 1.415z"/></svg>
                    </div>
                    <h3 style="font-size: 1.125rem; font-weight: 800;">Attendee Response</h3>
                </div>
            </div>

            <div style="background: #f9fafb; border-radius: 1rem; padding: 1.5rem; margin-bottom: 1.5rem;">
                <div class="ns-flex ns-between" style="margin-bottom: 1rem;">
                    <div>
                        <div style="font-size: 2.25rem; font-weight: 900; line-height: 1;">{{ $sentimentRate }}%</div>
                        <div class="ns-label text-emerald">POSITIVE SENTIMENT</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 2.25rem; font-weight: 900; line-height: 1; color: #d1d5db;">{{ 100 - $sentimentRate }}%</div>
                        <div class="ns-label">NEGATIVE/NEUTRAL</div>
                    </div>
                </div>
                <div style="width: 100%; height: 0.75rem; background: #e5e7eb; border-radius: 9999px; overflow: hidden;">
                    <div style="height: 100%; background: #10b981; width: {{ $sentimentRate }}%;"></div>
                </div>
            </div>

            <div class="ns-mt-auto">
                <span class="ns-label" style="display: block; margin-bottom: 1rem;">FEATURED REVIEW</span>
                <div style="background: rgba(254, 242, 242, 0.5); border: 1px solid #fee2e2; border-radius: 1rem; padding: 1.5rem; position: relative;">
                    <svg style="position: absolute; top: 1rem; right: 1rem; color: #fecaca; width: 32px; height: 32px;" width="32" height="32" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H15.017C14.4647 8 14.017 8.44772 14.017 9V11C14.017 11.5523 13.5693 12 13.017 12H12.017V5H22.017V15C22.017 18.3137 19.3307 21 16.017 21H14.017ZM5.0166 21L5.0166 18C5.0166 16.8954 5.91203 16 7.0166 16H10.0166C10.5689 16 11.0166 15.5523 11.0166 15V9C11.0166 8.44772 10.5689 8 10.0166 8H6.0166C5.46432 8 5.0166 8.44772 5.0166 9V11C5.0166 11.5523 4.56889 12 4.0166 12H3.0166V5H13.0166V15C13.0166 18.3137 10.3303 21 7.0166 21H5.0166Z" /></svg>
                    <p style="font-style: italic; color: #4b5563; font-size: 0.875rem; margin-bottom: 1rem; position: relative; z-index: 1;">
                        "{{ $featuredReview?->content ?? "The audiovisual production at the main stage was absolutely world-class. The VIP entry process was seamless." }}"
                    </p>
                    <div class="ns-flex ns-gap-4">
                        <div style="width: 2.5rem; height: 2.5rem; background: #e5e7eb; border-radius: 9999px; overflow: hidden; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #6b7280; font-size: 0.75rem;">
                            @if($featuredReview?->user?->avatar)
                                <img src="{{ $featuredReview->user->avatar }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                {{ substr($featuredReview?->user?->name ?? 'M', 0, 1) }}
                            @endif
                        </div>
                        <div>
                            <div style="font-weight: 800; font-size: 0.875rem; color: #111827;">{{ $featuredReview?->user?->name ?? 'Marcus Sterling' }}</div>
                            <div class="ns-label" style="font-size: 0.6rem;">VIP TICKET HOLDER • VERIFIED</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer Actions --}}
    <div class="ns-grid ns-grid-4">
        {{-- Report --}}
        <div class="ns-card ns-flex ns-gap-4" style="cursor: pointer; padding: 1rem;">
             <div class="ns-icon-box" style="background: #f3f4f6; color: #4b5563; width: 3rem; height: 3rem;">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 24px; height: 24px;"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
             </div>
             <div>
                 <div style="font-weight: 800; font-size: 0.875rem; color: #111827;">View Full Report</div>
                 <div class="ns-label">PDF • XLSX • CSV</div>
             </div>
        </div>

        {{-- Export --}}
        <div class="ns-card ns-flex ns-gap-4" style="cursor: pointer; padding: 1rem;">
             <div class="ns-icon-box bg-red-light text-red" style="width: 3rem; height: 3rem;">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 24px; height: 24px;"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
             </div>
             <div>
                 <div style="font-weight: 800; font-size: 0.875rem; color: #111827;">Export Attendees</div>
                 <div class="ns-label">{{ number_format($totalSold) }} RECORDS</div>
             </div>
        </div>

        {{-- View Results --}}
        <a href="{{ \App\Filament\Resources\EventResource::getUrl('results', ['record' => $event]) }}" class="ns-card ns-flex ns-gap-4" style="cursor: pointer; padding: 1rem; text-decoration: none; color: inherit;">
             <div class="ns-icon-box" style="background: #dcfce7; color: #15803d; width: 3rem; height: 3rem;">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 24px; height: 24px;"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
             </div>
             <div>
                 <div style="font-weight: 800; font-size: 0.875rem; color: #111827;">{{ __('event.actions.view_results') }}</div>
                 <div class="ns-label">DETAILED ANALYTICS</div>
             </div>
        </a>

        {{-- Contact --}}
        <div class="ns-card ns-flex ns-gap-4" style="cursor: pointer; padding: 1rem;">
             <div class="ns-icon-box" style="background: #f3f4f6; color: #4b5563; width: 3rem; height: 3rem;">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20" style="width: 24px; height: 24px;"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
             </div>
             <div>
                 <div style="font-weight: 800; font-size: 0.875rem; color: #111827;">Contact Organizer</div>
                 <div class="ns-label">{{ strtoupper($event->organizers->first()?->name ?? 'Sarah Jenkins') }}</div>
             </div>
        </div>

        {{-- Archive --}}
        <button style="background: #dc2626; color: white; border: none; border-radius: 1rem; padding: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 10px 15px -3px rgba(220, 38, 38, 0.3); transition: all 0.2s;">
            <span style="font-weight: 900; text-transform: uppercase; letter-spacing: -0.025em; font-size: 0.875rem;">Archive Event</span>
            <div style="width: 2rem; height: 2rem; background: rgba(255,255,255,0.2); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                 <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" style="width: 16px; height: 16px;"><path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4zm12 6H4l1 10a2 2 0 002 2h6a2 2 0 002-2l1-10zM10 11a1 1 0 00-1 1v3a1 1 0 102 0v-3a1 1 0 00-1-1z"/></svg>
            </div>
        </button>
    </div>
</div>
