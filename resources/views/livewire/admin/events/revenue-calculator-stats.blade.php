<div class="revenue-calculator-stats">
    <style>
        .revenue-calculator-stats {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            font-family: 'Inter', sans-serif;
        }

        /* Hero Card */
        .stats-hero-card {
            background-color: white;
            border: 1px solid #e5e7eb; /* gray-200 */
            border-radius: 0.75rem;
            padding: 1.25rem;
            color: #111827;
            position: relative;
            overflow: hidden;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        
        .dark .stats-hero-card {
            background-color: #111827; /* gray-900 */
            border-color: #1f2937;
            color: white;
        }

        .hero-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280; /* gray-500 */
            font-weight: 600;
        }
        .dark .hero-label { color: #9ca3af; }

        .hero-value {
            font-size: 2rem; /* 3xl */
            font-weight: 700;
            line-height: 2.25rem;
            margin-top: 0.25rem;
            letter-spacing: -0.025em;
            color: #111827;
        }
        .dark .hero-value { color: white; }

        .hero-value.profit { color: #10b981; /* emerald-500 */ }
        .hero-value.loss { color: #f43f5e; /* rose-500 */ }

        .hero-footer {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #f3f4f6;
        }
        .dark .hero-footer { border-top-color: #1f2937; }

        .footer-metric-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            margin-bottom: 0.125rem;
        }
        .dark .footer-metric-label { color: #9ca3af; }

        .footer-metric-value {
            font-size: 1rem;
            font-weight: 600;
            color: #111827;
        }
        .dark .footer-metric-value { color: white; }

        /* List Cards */
        .stats-list-card {
            background-color: white;
            border: 1px solid #e5e7eb; /* gray-200 */
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .dark .stats-list-card {
            background-color: #111827; /* gray-900 */
            border-color: #1f2937; /* gray-800 */
        }

        .card-header {
            padding: 0.75rem 1rem;
            background-color: #f9fafb; /* gray-50 */
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .dark .card-header {
            background-color: rgba(255, 255, 255, 0.05);
            border-color: #1f2937;
        }

        .header-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 1.75rem;
            height: 1.75rem;
            border-radius: 0.375rem;
            background-color: white;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            border: 1px solid #f3f4f6;
        }
        
        .dark .header-icon {
            background-color: #1f2937;
            border-color: #374151;
        }

        .header-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: #111827; /* gray-900 */
        }

        .dark .header-title { color: white; }

        .list-group {
            display: flex;
            flex-direction: column;
        }

        .list-item {
            padding: 0.625rem 1rem;
            border-bottom: 1px solid #f3f4f6; /* gray-100 */
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.15s ease;
        }
        
        .dark .list-item {
            border-color: #1f2937;
        }

        .list-item:last-child { border-bottom: none; }
        .list-item:hover { background-color: #f9fafb; }
        .dark .list-item:hover { background-color: rgba(255, 255, 255, 0.05); }

        .item-main {
            padding: 0.75rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .item-main-content {
            display: flex;
            gap: 0.75rem;
        }

        /* Radio Indicator Styling */
        .indicator-outer {
            width: 1.25rem;
            height: 1.25rem;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 0.125rem;
            flex-shrink: 0;
        }
        
        .indicator-inner {
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 9999px;
            background-color: currentColor;
        }

        .indicator-outer.selected-emerald {
            border: 1.5px solid #10b981; /* emerald-500 */
            color: #10b981;
            background-color: #ecfdf5; /* emerald-50 */
        }
        .dark .indicator-outer.selected-emerald {
            background-color: rgba(16, 185, 129, 0.1);
        }

        .indicator-outer.selected-rose {
            border: 1.5px solid #f43f5e; /* rose-500 */
            color: #f43f5e;
            background-color: #fff1f2; /* rose-50 */
        }
        .dark .indicator-outer.selected-rose {
            background-color: rgba(244, 63, 94, 0.1);
        }

        .indicator-hollow {
            width: 0.75rem;
            height: 0.75rem;
            border: 1.5px solid #d1d5db; /* gray-300 */
            border-radius: 9999px;
            margin-left: 0.25rem; /* Optical alignment */
        }
        .dark .indicator-hollow { border-color: #4b5563; }

        .item-title {
            font-size: 0.875rem;
            font-weight: 500;
            color: #111827;
        }
        .dark .item-title { color: white; }

        .item-subtitle {
            font-size: 0.75rem;
            color: #6b7280; /* gray-500 */
            margin-top: 0.125rem;
        }
        .dark .item-subtitle { color: #9ca3af; }

        .item-value-large {
            font-size: 1.125rem;
            font-weight: 700;
            margin-top: 0.5rem;
        }
        .dark .item-value-large { color: white; }

        /* Conditional States */
        .stats-hero-card.state-success {
            background-color: #f0fdf4; /* emerald-50 */
            border-color: #bbb;
            border-color: #86efac; /* emerald-300 */
        }
        .dark .stats-hero-card.state-success {
            background-color: rgba(6, 95, 70, 0.3);
            border-color: rgba(52, 211, 153, 0.2);
        }

        .stats-hero-card.state-danger {
            background-color: #fef2f2; /* rose-50 */
            border-color: #fda4af; /* rose-300 */
        }
        .dark .stats-hero-card.state-danger {
            background-color: rgba(159, 18, 57, 0.3);
            border-color: rgba(251, 113, 133, 0.2);
        }

        /* List Cards */
        .stats-list-card {
            background-color: white;
            border: 1px solid #e5e7eb; /* gray-200 */
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .dark .stats-list-card {
            background-color: #111827; /* gray-900 */
            border-color: #1f2937; /* gray-800 */
        }

        .card-header {
            padding: 0.75rem 1rem;
            background-color: #f9fafb; /* gray-50 */
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .dark .card-header {
            background-color: rgba(255, 255, 255, 0.05);
            border-color: #1f2937;
        }

        .header-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 1.75rem;
            height: 1.75rem;
            border-radius: 0.375rem;
            background-color: white;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            border: 1px solid #f3f4f6;
        }
        
        .dark .header-icon {
            background-color: #1f2937;
            border-color: #374151;
        }

        .header-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: #111827; /* gray-900 */
        }

        .dark .header-title { color: white; }

        .list-group {
            display: flex;
            flex-direction: column;
        }

        .list-item {
            padding: 0.625rem 1rem;
            border-bottom: 1px solid #f3f4f6; /* gray-100 */
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.15s ease;
        }
        
        .dark .list-item {
            border-color: #1f2937;
        }

        .list-item:last-child { border-bottom: none; }
        .list-item:hover { background-color: #f9fafb; }
        .dark .list-item:hover { background-color: rgba(255, 255, 255, 0.05); }

        .item-main {
            padding: 0.75rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .item-main-content {
            display: flex;
            gap: 0.75rem;
        }

        /* Radio Indicator Styling */
        .indicator-outer {
            width: 1.25rem;
            height: 1.25rem;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 0.125rem;
            flex-shrink: 0;
        }
        
        .indicator-inner {
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 9999px;
            background-color: currentColor;
        }

        .indicator-outer.selected-emerald {
            border: 1.5px solid #10b981; /* emerald-500 */
            color: #10b981;
            background-color: #ecfdf5; /* emerald-50 */
        }
        .dark .indicator-outer.selected-emerald {
            background-color: rgba(16, 185, 129, 0.1);
        }

        .indicator-outer.selected-rose {
            border: 1.5px solid #f43f5e; /* rose-500 */
            color: #f43f5e;
            background-color: #fff1f2; /* rose-50 */
        }
        .dark .indicator-outer.selected-rose {
            background-color: rgba(244, 63, 94, 0.1);
        }

        .indicator-hollow {
            width: 0.75rem;
            height: 0.75rem;
            border: 1.5px solid #d1d5db; /* gray-300 */
            border-radius: 9999px;
            margin-left: 0.25rem; /* Optical alignment */
        }
        .dark .indicator-hollow { border-color: #4b5563; }

        .item-title {
            font-size: 0.875rem;
            font-weight: 500;
            color: #111827;
        }
        .dark .item-title { color: white; }

        .item-subtitle {
            font-size: 0.75rem;
            color: #6b7280; /* gray-500 */
            margin-top: 0.125rem;
        }
        .dark .item-subtitle { color: #9ca3af; }

        .item-value-large {
            font-size: 1.125rem;
            font-weight: 700;
            margin-top: 0.5rem;
        }
        .dark .item-value-large { color: white; }

        .item-value-small {
            font-size: 0.75rem;
            font-weight: 600;
            color: #111827;
        }
        .dark .item-value-small { color: white; }

        .text-emerald-500 { color: #10b981; }
        .text-rose-500 { color: #f43f5e; }
        .text-emerald-400 { color: #34d399; }
        .text-rose-400 { color: #fb7185; }
        .text-white { color: white; }
    </style>

    @php
        $metrics = $this->metrics;
        $isProfitable = $metrics['net_profit'] >= 0;
        $margin = $metrics['gross_revenue'] > 0 ? ($metrics['net_profit'] / $metrics['gross_revenue']) * 100 : 0;
    @endphp

    <!-- Net Profit Card -->
    <div class="stats-hero-card {{ $isProfitable ? 'state-success' : 'state-danger' }}">
        <div class="hero-label">Estimated Net Profit</div>
        <div class="hero-value {{ $isProfitable ? 'profit' : 'loss' }}">
             Rp {{ number_format($metrics['net_profit'], 0, ',', '.') }}
        </div>
        
        <div class="hero-footer">
            <div>
                <div class="footer-metric-label">Margin</div>
                <div class="footer-metric-value {{ $margin >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                     {{ number_format($margin, 1) }}%
                </div>
            </div>
            <div style="text-align: right;">
                <div class="footer-metric-label">Break-Even</div>
                <div class="footer-metric-value">
                     {{ number_format($metrics['break_even_tickets']) }} <span style="font-size: 0.75em; font-weight: 400; color: #9ca3af;">Tix</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue List -->
    <div class="stats-list-card">
        <div class="card-header">
             <div class="header-icon">
                <x-filament::icon icon="heroicon-m-banknotes" class="w-4 h-4 text-emerald-500" />
            </div>
            <div class="header-title">Revenue Analysis</div>
        </div>
        <div class="list-group">
            <!-- Main Item -->
            <div class="list-item item-main">
                <div class="item-main-content">
                    <div class="indicator-outer selected-emerald">
                        <div class="indicator-inner"></div>
                    </div>
                    <div>
                        <div class="item-title">Total Revenue</div>
                        <div class="item-subtitle">Gross income from tickets & merch</div>
                    </div>
                </div>
                <div class="item-value-large text-emerald-500">
                    Rp {{ number_format($metrics['gross_revenue'], 0, ',', '.') }}
                </div>
            </div>

            <!-- Sub Items -->
            <div class="list-item">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div class="indicator-hollow"></div>
                    <span class="item-subtitle" style="font-weight: 500;">Ticket Sales</span>
                </div>
                <div class="item-value-small">Rp {{ number_format($metrics['ticket_revenue'], 0, ',', '.') }}</div>
            </div>
            <div class="list-item">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                     <div class="indicator-hollow"></div>
                    <span class="item-subtitle" style="font-weight: 500;">Merchandise</span>
                </div>
                 <div class="item-value-small">Rp {{ number_format($metrics['merch_revenue'], 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <!-- Expenses List -->
    <div class="stats-list-card">
        <div class="card-header">
             <div class="header-icon">
                <x-filament::icon icon="heroicon-m-calculator" class="w-4 h-4 text-rose-500" />
            </div>
            <div class="header-title">Expense Breakdown</div>
        </div>
        <div class="list-group">
            <!-- Main Item -->
            <div class="list-item item-main">
                <div class="item-main-content">
                    <div class="indicator-outer selected-rose">
                        <div class="indicator-inner"></div>
                    </div>
                    <div>
                        <div class="item-title">Total Expenses</div>
                        <div class="item-subtitle">Includes taxes & platform fees</div>
                    </div>
                </div>
                <div class="item-value-large text-rose-500">
                    Rp {{ number_format($metrics['total_expenses'], 0, ',', '.') }}
                </div>
            </div>

            <!-- Sub Items -->
            <div class="list-item">
                 <div style="display: flex; align-items: center; gap: 0.75rem;">
                     <div class="indicator-hollow"></div>
                    <span class="item-subtitle" style="font-weight: 500;">Fixed Production</span>
                </div>
                <div class="item-value-small">Rp {{ number_format($metrics['fixed_expenses'], 0, ',', '.') }}</div>
            </div>
            <div class="list-item">
                 <div style="display: flex; align-items: center; gap: 0.75rem;">
                     <div class="indicator-hollow"></div>
                    <span class="item-subtitle" style="font-weight: 500;">Tax & Fees</span>
                </div>
                 <div class="item-value-small">Rp {{ number_format($metrics['tax_amount'] + $metrics['fee_amount'], 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>
