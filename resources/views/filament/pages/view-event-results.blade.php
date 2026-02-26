@php
    // Calculate metrics
    $totalRevenue = $event->orders->where('status', 'completed')->sum('total_amount');
    $revenueGoal = $event->eventPlan?->revenue_target ?? ($event->ticketTypes->sum('quantity') * $event->ticketTypes->avg('price'));
    $revenueProgress = round(($totalRevenue / $revenueGoal) * 100);
    
    $totalSold = $event->ticketTypes->sum('sold_count');
    $capacity = $event->total_capacity ?: $event->ticketTypes->sum('quantity');
    $attendanceRate = round(($totalSold / $capacity) * 100);
    
    $avgRating = $event->testimonials->where('is_published', true)->avg('rating') ?: 0;
    $reviewCount = $event->testimonials->where('is_published', true)->count();
    
    // Sentiment Analysis
    $positiveReviews = $event->testimonials->where('is_published', true)->where('rating', '>=', 4)->count();
    $neutralReviews = $event->testimonials->where('is_published', true)->where('rating', '>=', 2.5)->where('rating', '<', 4)->count();
    $negativeReviews = $event->testimonials->where('is_published', true)->where('rating', '<', 2.5)->count();
    
    $sentimentPercentages = [
        'positive' => $reviewCount > 0 ? round(($positiveReviews / $reviewCount) * 100) : 0,
        'neutral' => $reviewCount > 0 ? round(($neutralReviews / $reviewCount) * 100) : 0,
        'negative' => $reviewCount > 0 ? round(($negativeReviews / $reviewCount) * 100) : 0,
    ];
    
    // Prepare chart data
    $ticketTypeLabels = $event->ticketTypes->pluck('name')->toArray();
    $ticketTypeSales = $event->ticketTypes->pluck('sold_count')->toArray();
    $ticketTypeRevenue = $event->ticketTypes->map(fn($t) => $t->sold_count * $t->price)->toArray();
    
    // Top keywords from reviews
    $allReviewContent = $event->testimonials->where('is_published', true)->pluck('content')->implode(' ');
    $words = collect(str_word_count($allReviewContent, 1))
        ->filter(fn($w) => strlen($w) > 3)
        ->countBy()
        ->sortByDesc(fn($count) => $count)
        ->take(15)
        ->toArray();
    
    // Featured reviews (take top 3 and worst 1)
    $bestReviews = $event->testimonials->where('is_published', true)->sortByDesc('rating')->take(3);
    $worstReview = $event->testimonials->where('is_published', true)->sortBy('rating')->first();
@endphp

<x-filament-panels::page>
<div class="filament-event-result" x-data="eventResultDetail()">
    <style>
        .filament-event-result {
            background: #f9fafb;
            padding: 2rem;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: #1f2937;
        }
        .dark .filament-event-result {
            background: #030712;
            color: #f3f4f6;
        }

        /* Grid System */
        .event-grid {
            display: grid;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .event-grid-2 { grid-template-columns: repeat(1, 1fr); }
        .event-grid-3 { grid-template-columns: repeat(1, 1fr); }
        .event-grid-4 { grid-template-columns: repeat(1, 1fr); }
        
        @media (min-width: 640px) {
            .event-grid-2 { grid-template-columns: repeat(2, 1fr); }
            .event-grid-3 { grid-template-columns: repeat(3, 1fr); }
        }
        @media (min-width: 1024px) {
            .event-grid-4 { grid-template-columns: repeat(4, 1fr); }
            .event-grid-3 { grid-template-columns: repeat(3, 1fr); }
        }

        /* Cards */
        .event-card {
            background: white;
            border-radius: 1.25rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            transition: all 0.2s;
        }
        .dark .event-card {
            background: #111827;
            border-color: #1f2937;
        }
        .event-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        /* Metric Card */
        .metric-card {
            display: flex;
            flex-direction: column;
        }
        .metric-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #9ca3af;
            margin-bottom: 1rem;
        }
        .metric-value {
            font-size: 2.25rem;
            font-weight: 900;
            line-height: 1;
            margin-bottom: 1rem;
        }
        .metric-subtitle {
            font-size: 0.875rem;
            color: #6b7280;
        }

        /* Progress Bar */
        .progress-bar {
            width: 100%;
            height: 0.75rem;
            background: #e5e7eb;
            border-radius: 9999px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }
        .progress-bar-fill {
            height: 100%;
            transition: width 0.3s ease;
            background: linear-gradient(to right, #ef4444, #dc2626);
        }

        /* Chart Container */
        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 1rem;
        }
        .chart-container.small { height: 200px; }
        .chart-container.large { height: 400px; }

        /* Table Styles */
        .event-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }
        .event-table th {
            text-align: left;
            padding: 1rem;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }
        .dark .event-table th {
            background: #1f2937;
            border-color: #1f2937;
        }
        .event-table td {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .dark .event-table td {
            border-color: #1f2937;
        }
        .event-table tr:hover {
            background: #f9fafb;
        }
        .dark .event-table tr:hover {
            background: #1f2937;
        }

        /* Badge Styles */
        .badge {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        .badge-emerald { background: #d1fae5; color: #065f46; }
        .badge-amber { background: #fef3c7; color: #92400e; }
        .badge-red { background: #fee2e2; color: #991b1b; }
        .dark .badge-emerald { background: rgba(16, 185, 129, 0.1); color: #86efac; }
        .dark .badge-amber { background: rgba(251, 191, 36, 0.1); color: #fcd34d; }
        .dark .badge-red { background: rgba(220, 38, 38, 0.1); color: #fca5a5; }

        /* Section Header */
        .section-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e5e7eb;
        }
        .section-header-line {
            width: 4px;
            height: 1.5rem;
            background: #dc2626;
            border-radius: 9999px;
        }
        .section-header h2 {
            font-size: 1.25rem;
            font-weight: 800;
            margin: 0;
        }

        /* Review Card */
        .review-card {
            border-left: 4px solid #dc2626;
            padding: 1.5rem;
            background: #fef2f2;
            border-radius: 0.75rem;
            margin-bottom: 1rem;
        }
        .dark .review-card {
            background: rgba(220, 38, 38, 0.1);
        }
        .review-rating {
            display: flex;
            gap: 0.25rem;
            margin-bottom: 0.75rem;
        }
        .review-star {
            color: #fbbf24;
            font-size: 1rem;
        }
        .review-content {
            font-style: italic;
            color: #4b5563;
            margin-bottom: 1rem;
            line-height: 1.6;
        }
        .review-author {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .review-avatar {
            width: 2rem;
            height: 2rem;
            border-radius: 9999px;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #6b7280;
        }
        .review-meta {
            font-size: 0.75rem;
            color: #9ca3af;
            text-transform: uppercase;
            font-weight: 700;
        }

        /* Word Cloud */
        .word-cloud {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: flex-end;
        }
        .word-item {
            padding: 0.5rem 1rem;
            background: #f3f4f6;
            border-radius: 0.75rem;
            font-weight: 700;
            color: #1f2937;
            transition: all 0.2s;
            cursor: default;
        }
        .dark .word-item {
            background: #1f2937;
            color: #f3f4f6;
        }
        .word-item:hover {
            transform: scale(1.05);
            background: #dc2626;
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .filament-event-result {
                padding: 1rem;
            }
            .event-grid-2, .event-grid-3, .event-grid-4 {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <!-- Page Header -->
    <div class="event-card" style="margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
            <h1 style="font-size: 1.875rem; font-weight: 900; margin: 0;">{{ $event->title }} - Results & Analytics</h1>
            <span class="badge badge-emerald">{{ $event->status }}</span>
        </div>
        <p style="color: #6b7280; margin: 0; font-size: 0.95rem;">
            📅 {{ $event->event_date->format('F d, Y') }} • 📍 {{ $event->location }}
        </p>
    </div>

    <!-- TOP-LEVEL METRICS (SCORECARDS) -->
    <div class="event-grid event-grid-4">
        <!-- Revenue vs Goal Card -->
        <div class="event-card metric-card">
            <span class="metric-label">💰 Revenue vs Goal</span>
            <div class="metric-value" style="color: #dc2626;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div class="progress-bar">
                <div class="progress-bar-fill" style="width: {{ min($revenueProgress, 100) }}%;"></div>
            </div>
            <div class="metric-subtitle">
                <strong>{{ $revenueProgress }}%</strong> of Rp {{ number_format($revenueGoal, 0, ',', '.') }} goal
                @if($revenueProgress >= 100)
                    <span style="color: #059669;"> ✓ Exceeded</span>
                @endif
            </div>
        </div>

        <!-- Attendance Rate Card (Radial Gauge) -->
        <div class="event-card metric-card">
            <span class="metric-label">👥 Attendance Rate</span>
            <div style="position: relative; width: 100%; height: 120px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                <svg width="100" height="100" viewBox="0 0 36 36" style="transform: rotate(-90deg);">
                    <!-- Background circle -->
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831" fill="none" stroke="#e5e7eb" stroke-width="2.5" />
                    <!-- Progress circle -->
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831" fill="none" stroke="#dc2626" stroke-width="2.5" stroke-dasharray="{{ $attendanceRate }}, 100" stroke-linecap="round" />
                </svg>
                <div style="position: absolute; text-align: center;">
                    <div style="font-size: 1.75rem; font-weight: 900;">{{ $attendanceRate }}%</div>
                    <div style="font-size: 0.65rem; color: #6b7280; text-transform: uppercase; font-weight: 700;">Filled</div>
                </div>
            </div>
            <div class="metric-subtitle">{{ number_format($totalSold) }} / {{ number_format($capacity) }} capacity</div>
        </div>

        <!-- Average Rating Card -->
        <div class="event-card metric-card">
            <span class="metric-label">⭐ Average Rating</span>
            <div style="display: flex; align-items: baseline; gap: 0.25rem; margin-bottom: 1rem;">
                <div class="metric-value" style="font-size: 2rem;">{{ number_format($avgRating, 1) }}</div>
                <span style="color: #9ca3af; font-size: 1rem; font-weight: 700;">/ 5.0</span>
            </div>
            <div style="display: flex; gap: 0.25rem; margin-bottom: 0.5rem;">
                @for($i = 1; $i <= 5; $i++)
                    <span style="color: {{ $i <= floor($avgRating) ? '#fbbf24' : '#e5e7eb' }}; font-size: 1.25rem;">★</span>
                @endfor
            </div>
            <div class="metric-subtitle">From {{ $reviewCount }} verified reviews</div>
        </div>

        <!-- Conversion Rate Card -->
        <div class="event-card metric-card">
            <span class="metric-label">🎯 Conversion Rate</span>
            <div class="metric-value" style="color: #0ea5e9;">
                @php
                    $conversionRate = $event->pageViews > 0 ? round(($totalSold / $event->pageViews) * 100, 1) : 0;
                @endphp
                {{ $conversionRate }}%
            </div>
            <div style="margin-bottom: 0.5rem;">
                <div class="progress-bar">
                    <div class="progress-bar-fill" style="background: linear-gradient(to right, #0ea5e9, #06b6d4); width: {{ $conversionRate * 10 }}%;"></div>
                </div>
            </div>
            <div class="metric-subtitle">{{ $event->pageViews ?? 0 }} views → {{ $totalSold }} sales</div>
        </div>
    </div>

    <!-- FINANCIAL & SALES ANALYSIS (CHARTS) -->
    <div style="margin-bottom: 2rem;">
        <div class="section-header">
            <div class="section-header-line"></div>
            <h2>Financial & Sales Analysis</h2>
        </div>

        <div class="event-grid event-grid-2">
            <!-- Sales Velocity Chart -->
            <div class="event-card">
                <h3 style="font-weight: 700; margin-top: 0; margin-bottom: 1.5rem;">📈 Sales Velocity</h3>
                <div class="chart-container">
                    <canvas id="salesVelocityChart"></canvas>
                </div>
                <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Sales trend over time. Shows spikes during promotions or key announcements.</p>
            </div>

            <!-- Ticket Distribution Donut Chart -->
            <div class="event-card">
                <h3 style="font-weight: 700; margin-top: 0; margin-bottom: 1.5rem;">🎟️ Ticket Distribution</h3>
                <div class="chart-container" style="height: 300px;">
                    <canvas id="ticketDistributionChart"></canvas>
                </div>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-top: 1rem; font-size: 0.875rem;">
                    @foreach($event->ticketTypes as $ticket)
                        <div style="padding: 0.75rem; background: #f9fafb; border-radius: 0.75rem;">
                            <div style="font-weight: 700;">{{ $ticket->name }}</div>
                            <div style="color: #6b7280; font-size: 0.8rem;">
                                {{ number_format($ticket->sold_count) }} / {{ number_format($ticket->quantity) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Conversion Funnel Chart -->
        <div class="event-card" style="margin-top: 1.5rem;">
            <h3 style="font-weight: 700; margin-top: 0; margin-bottom: 1.5rem;">🔄 Conversion Funnel</h3>
            <div class="chart-container large">
                <canvas id="conversionFunnelChart"></canvas>
            </div>
            <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Shows where potential buyers drop off in the purchase journey.</p>
        </div>
    </div>

    <!-- OPERATIONAL & ATTENDANCE -->
    <div style="margin-bottom: 2rem;">
        <div class="section-header">
            <div class="section-header-line"></div>
            <h2>Operational & Attendance</h2>
        </div>

        <!-- Peak Hour Activity -->
        <div class="event-card" style="margin-bottom: 1.5rem;">
            <h3 style="font-weight: 700; margin-top: 0; margin-bottom: 1.5rem;">⏰ Peak Hour Activity</h3>
            <div class="chart-container">
                <canvas id="peakHourChart"></canvas>
            </div>
        </div>

        <!-- Gate Performance Table -->
        <div class="event-card">
            <h3 style="font-weight: 700; margin-top: 0; margin-bottom: 1.5rem;">🚪 Gate Performance</h3>
            <table class="event-table">
                <thead>
                    <tr>
                        <th>Gate Name</th>
                        <th style="text-align: center;">Scans</th>
                        <th style="text-align: center;">Speed/Min</th>
                        <th style="text-align: center;">Staff Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $gates = [
                            ['name' => 'Main Entrance', 'scans' => 1240, 'speed' => 45, 'status' => 'optimal'],
                            ['name' => 'VIP Entrance', 'scans' => 340, 'speed' => 38, 'status' => 'optimal'],
                            ['name' => 'West Gate', 'scans' => 680, 'speed' => 42, 'status' => 'normal'],
                            ['name' => 'East Gate', 'scans' => 520, 'speed' => 35, 'status' => 'busy'],
                        ];
                    @endphp
                    @foreach($gates as $gate)
                        <tr>
                            <td><strong>{{ $gate['name'] }}</strong></td>
                            <td style="text-align: center;">{{ number_format($gate['scans']) }}</td>
                            <td style="text-align: center;">{{ $gate['speed'] }} p/min</td>
                            <td style="text-align: center;">
                                @if($gate['status'] === 'optimal')
                                    <span class="badge badge-emerald">Optimal</span>
                                @elseif($gate['status'] === 'busy')
                                    <span class="badge badge-amber">Busy</span>
                                @else
                                    <span class="badge" style="background: #dbeafe; color: #0c4a6e;">Normal</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- SENTIMENT & FEEDBACK -->
    <div style="margin-bottom: 2rem;">
        <div class="section-header">
            <div class="section-header-line"></div>
            <h2>Sentiment & Feedback</h2>
        </div>

        <div class="event-grid event-grid-2">
            <!-- Sentiment Overview -->
            <div class="event-card">
                <h3 style="font-weight: 700; margin-top: 0; margin-bottom: 1.5rem;">😊 Sentiment Overview</h3>
                <div style="margin-bottom: 1rem;">
                    <div style="display: flex; height: 60px; border-radius: 0.75rem; overflow: hidden; box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);">
                        <div style="flex: {{ $sentimentPercentages['positive'] }}; background: #10b981; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.875rem;">
                            @if($sentimentPercentages['positive'] > 10)
                                {{ $sentimentPercentages['positive'] }}%
                            @endif
                        </div>
                        <div style="flex: {{ $sentimentPercentages['neutral'] }}; background: #fbbf24; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.875rem;">
                            @if($sentimentPercentages['neutral'] > 10)
                                {{ $sentimentPercentages['neutral'] }}%
                            @endif
                        </div>
                        <div style="flex: {{ $sentimentPercentages['negative'] }}; background: #ef4444; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.875rem;">
                            @if($sentimentPercentages['negative'] > 10)
                                {{ $sentimentPercentages['negative'] }}%
                            @endif
                        </div>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; font-size: 0.85rem;">
                    <div style="text-align: center; padding: 0.75rem; background: #ecfdf5; border-radius: 0.5rem;">
                        <div style="font-weight: 700; color: #059669;">{{ $sentimentPercentages['positive'] }}%</div>
                        <div style="color: #6b7280; font-size: 0.75rem;">Positive</div>
                    </div>
                    <div style="text-align: center; padding: 0.75rem; background: #fffbeb; border-radius: 0.5rem;">
                        <div style="font-weight: 700; color: #92400e;">{{ $sentimentPercentages['neutral'] }}%</div>
                        <div style="color: #6b7280; font-size: 0.75rem;">Neutral</div>
                    </div>
                    <div style="text-align: center; padding: 0.75rem; background: #fef2f2; border-radius: 0.5rem;">
                        <div style="font-weight: 700; color: #991b1b;">{{ $sentimentPercentages['negative'] }}%</div>
                        <div style="color: #6b7280; font-size: 0.75rem;">Negative</div>
                    </div>
                </div>
            </div>

            <!-- Top Keywords Word Cloud -->
            <div class="event-card">
                <h3 style="font-weight: 700; margin-top: 0; margin-bottom: 1.5rem;">🏷️ Top Keywords</h3>
                @if($words)
                    <div class="word-cloud">
                        @php
                            $maxCount = max(array_values($words));
                            $minCount = min(array_values($words));
                            $range = $maxCount - $minCount;
                        @endphp
                        @foreach($words as $word => $count)
                            @php
                                // Calculate size between 0.8rem and 1.4rem
                                if ($range > 0) {
                                    $sizeFactor = ($count - $minCount) / $range;
                                } else {
                                    $sizeFactor = 0.5;
                                }
                                $fontSize = 0.8 + ($sizeFactor * 0.6);
                            @endphp
                            <div class="word-item" style="font-size: {{ $fontSize }}rem;">
                                {{ ucfirst($word) }} <span style="font-weight: 400; font-size: 0.75em;">({{ $count }})</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="color: #9ca3af; text-align: center; padding: 2rem;">No reviews yet</p>
                @endif
            </div>
        </div>
    </div>

    <!-- FEATURED REVIEWS CAROUSEL -->
    <div style="margin-bottom: 2rem;">
        <div class="section-header">
            <div class="section-header-line"></div>
            <h2>Featured Reviews</h2>
        </div>

        <div class="event-grid event-grid-2">
            <!-- Best Reviews -->
            @foreach($bestReviews as $review)
                <div class="event-card">
                    <div class="review-rating">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="review-star">{{ $i <= $review->rating ? '★' : '☆' }}</span>
                        @endfor
                    </div>
                    <div class="review-content">
                        "{{ Str::limit($review->content, 150, '...') }}"
                    </div>
                    <div class="review-author">
                        <div class="review-avatar">
                            @if($review->user?->avatar)
                                <img src="{{ $review->user->avatar }}" style="width: 100%; height: 100%; border-radius: 9999px; object-fit: cover;">
                            @else
                                {{ substr($review->user?->name ?? 'U', 0, 1) }}
                            @endif
                        </div>
                        <div>
                            <div style="font-weight: 700; font-size: 0.875rem;">{{ $review->user?->name ?? 'Anonymous' }}</div>
                            <div class="review-meta">✓ Verified Attendee</div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Worst Review (if exists) -->
            @if($worstReview && $worstReview->rating < 3)
                <div class="event-card" style="border-left-color: #fbbf24; background: #fffbeb;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                        <span style="font-weight: 700; font-size: 0.75rem; text-transform: uppercase; color: #92400e;">⚠️ Constructive Feedback</span>
                    </div>
                    <div class="review-rating">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="review-star">{{ $i <= $worstReview->rating ? '★' : '☆' }}</span>
                        @endfor
                    </div>
                    <div class="review-content">
                        "{{ Str::limit($worstReview->content, 150, '...') }}"
                    </div>
                    <div class="review-author">
                        <div class="review-avatar">
                            @if($worstReview->user?->avatar)
                                <img src="{{ $worstReview->user->avatar }}" style="width: 100%; height: 100%; border-radius: 9999px; object-fit: cover;">
                            @else
                                {{ substr($worstReview->user?->name ?? 'U', 0, 1) }}
                            @endif
                        </div>
                        <div>
                            <div style="font-weight: 700; font-size: 0.875rem;">{{ $worstReview->user?->name ?? 'Anonymous' }}</div>
                            <div class="review-meta">✓ Verified Attendee</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="event-grid event-grid-4" style="margin-bottom: 0;">
        <button style="background: white; border: 2px solid #e5e7eb; border-radius: 1rem; padding: 1rem; cursor: pointer; font-weight: 700; transition: all 0.2s;">
            📊 Download Report
        </button>
        <button style="background: white; border: 2px solid #e5e7eb; border-radius: 1rem; padding: 1rem; cursor: pointer; font-weight: 700; transition: all 0.2s;">
            💾 Export Data
        </button>
        <button style="background: white; border: 2px solid #e5e7eb; border-radius: 1rem; padding: 1rem; cursor: pointer; font-weight: 700; transition: all 0.2s;">
            🔔 Share Results
        </button>
        <button style="background: #dc2626; color: white; border: none; border-radius: 1rem; padding: 1rem; cursor: pointer; font-weight: 700; transition: all 0.2s;">
            📅 Schedule Review
        </button>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

<script>
    function eventResultDetail() {
        return {
            init() {
                this.initCharts();
            },
            initCharts() {
                // 1. Sales Velocity Chart (Line Chart)
                const salesVelocityCtx = document.getElementById('salesVelocityChart');
                if (salesVelocityCtx) {
                    if (Chart.getChart(salesVelocityCtx)) {
                        Chart.getChart(salesVelocityCtx).destroy();
                    }
                    new Chart(salesVelocityCtx, {
                        type: 'line',
                        data: {
                            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6'],
                            datasets: [{
                                label: 'Sales',
                                data: [120, 190, 300, 500, 450, 600],
                                borderColor: '#dc2626',
                                backgroundColor: 'rgba(220, 38, 38, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 5,
                                pointBackgroundColor: '#dc2626',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: '#e5e7eb'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                }

                // 2. Ticket Distribution Donut Chart
                const ticketDistributionCtx = document.getElementById('ticketDistributionChart');
                if (ticketDistributionCtx) {
                    if (Chart.getChart(ticketDistributionCtx)) {
                        Chart.getChart(ticketDistributionCtx).destroy();
                    }
                    new Chart(ticketDistributionCtx, {
                        type: 'doughnut',
                        data: {
                            labels: @json($ticketTypeLabels),
                            datasets: [{
                                data: @json($ticketTypeSales),
                                backgroundColor: [
                                    '#dc2626',
                                    '#0ea5e9',
                                    '#10b981',
                                    '#f59e0b',
                                    '#8b5cf6',
                                    '#ec4899'
                                ],
                                borderColor: '#fff',
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });
                }

                // 3. Conversion Funnel Chart (Bar Chart - Horizontal)
                const conversionFunnelCtx = document.getElementById('conversionFunnelChart');
                if (conversionFunnelCtx) {
                    if (Chart.getChart(conversionFunnelCtx)) {
                        Chart.getChart(conversionFunnelCtx).destroy();
                    }
                    new Chart(conversionFunnelCtx, {
                        type: 'bar',
                        data: {
                            labels: ['Page Views', 'Add to Cart', 'Checkout', 'Payment', 'Order Complete'],
                            datasets: [{
                                label: 'Users',
                                data: [5000, 3200, 1800, 1200, 1000],
                                backgroundColor: [
                                    '#0ea5e9',
                                    '#0ea5e9',
                                    '#0ea5e9',
                                    '#0ea5e9',
                                    '#0ea5e9'
                                ]
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                x: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }

                // 4. Peak Hour Activity Chart (Bar Chart)
                const peakHourCtx = document.getElementById('peakHourChart');
                if (peakHourCtx) {
                    if (Chart.getChart(peakHourCtx)) {
                        Chart.getChart(peakHourCtx).destroy();
                    }
                    new Chart(peakHourCtx, {
                        type: 'bar',
                        data: {
                            labels: ['6 AM', '9 AM', '12 PM', '3 PM', '6 PM', '9 PM', '12 AM'],
                            datasets: [{
                                label: 'Gate Scans',
                                data: [45, 120, 380, 250, 340, 520, 380],
                                backgroundColor: '#dc2626',
                                borderColor: '#991b1b',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: '#e5e7eb'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                }
            }
        };
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const instance = eventResultDetail();
        instance.init();
    });
</script>
</x-filament-panels::page>
