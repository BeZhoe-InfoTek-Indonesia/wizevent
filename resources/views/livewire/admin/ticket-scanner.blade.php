<div class="scanner-container" 
     x-data="{ 
        html5QrCode: null,
        isScanning: false,
        async startScanner() {
            if (this.isScanning) return;
            
            this.html5QrCode = new Html5Qrcode('reader');
            const config = { fps: 20, qrbox: { width: 280, height: 280 } };
            
            try {
                await this.html5QrCode.start(
                    { facingMode: 'environment' }, 
                    config, 
                    (decodedText) => {
                        this.stopScanner();
                        $wire.scan(decodedText);
                    }
                );
                this.isScanning = true;
            } catch (err) {
                console.error('Error starting scanner:', err);
                alert('Could not access camera. Please ensure you are on HTTPS and have given permission.');
            }
        },
        async stopScanner() {
            if (!this.isScanning) return;
            await this.html5QrCode.stop();
            this.isScanning = false;
        }
     }"
     x-init="startScanner()">
    
    <div class="scanner-header">
        <div class="scanner-title-group">
            <h2 class="scanner-main-title">{{ __('scanner.scanner_title') }}</h2>
            <p class="scanner-subtitle">{{ __('scanner.scanner_description') }}</p>
        </div>
        <div class="scanner-actions">
             <button @click="isScanning ? stopScanner() : startScanner()" 
                    class="scanner-btn"
                    :class="isScanning ? 'btn-danger' : 'btn-primary'">
                <template x-if="isScanning">
                    <span class="btn-content">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
                        {{ __('scanner.stop_scanning') }}
                    </span>
                </template>
                <template x-if="!isScanning">
                    <span class="btn-content">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ __('scanner.start_scanning') }}
                    </span>
                </template>
            </button>
        </div>
    </div>

    <!-- Scanner Area -->
    <div class="viewfinder-wrapper">
        <div class="viewfinder-glow"></div>
        <div class="viewfinder-frame">
            <div id="reader" wire:ignore></div>
            
            <template x-if="isScanning">
                <div class="viewfinder-overlay">
                    <div class="corner corner-tl"></div>
                    <div class="corner corner-tr"></div>
                    <div class="corner corner-bl"></div>
                    <div class="corner corner-br"></div>
                    <div class="scan-target"></div>
                    <div class="scan-line"></div>
                </div>
            </template>

            <div wire:loading wire:target="scan" class="processing-overlay">
                <div class="spinner"></div>
                <p class="processing-text">{{ __('scanner.scanning_active') }}</p>
            </div>
        </div>
    </div>

    <!-- Manual Entry -->
    <div class="manual-entry-card">
        <div class="card-header">
            <div class="icon-box">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <div class="text-box">
                <h3>{{ __('scanner.manual_entry') }}</h3>
                <p>{{ __('scanner.enter_ticket_number_error') }}</p>
            </div>
        </div>
        
        <div class="input-row">
            <input type="text" 
                   wire:model="manualTicketNumber" 
                   class="manual-input"
                   placeholder="{{ __('scanner.enter_ticket_number') }}">
            <button wire:click="checkManual" wire:loading.attr="disabled" class="check-btn">
                <span wire:loading.remove wire:target="checkManual">{{ __('scanner.check_manual') }}</span>
                <span wire:loading wire:target="checkManual" class="spinner-small"></span>
            </button>
        </div>
    </div>

    <!-- Results Overlay -->
    @if($status)
        <div class="result-backdrop" 
             x-data 
             x-init="() => { if (typeof stopScanner === 'function') stopScanner(); }"
             @keyup.escape.window="$wire.resetFeedback().then(() => startScanner())">
            
            <div class="result-modal {{ $status }}">
                <div class="result-header-graphic">
                    @if($status === 'success')
                        <div class="icon-circle">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
                        </div>
                    @else
                        <div class="icon-circle">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                    @endif
                </div>

                <div class="result-body">
                    <span class="status-badge">{{ $status === 'success' ? __('scanner.status_success') : __('scanner.status_error') }}</span>
                    <h3 class="result-title">{{ $status === 'success' ? __('scanner.check_in_successful') : __('scanner.status_error') }}</h3>
                    
                    @if($status === 'error')
                        <p class="error-message">{{ $message }}</p>
                    @endif

                    @if($scannedTicket && $status === 'success')
                        <div class="ticket-details">
                            <div class="detail-item full">
                                <label>{{ __('scanner.holder_name') }}</label>
                                <strong>{{ $scannedTicket->holder_name ?? $scannedTicket->order->user?->name ?? 'Guest' }}</strong>
                            </div>
                            <div class="detail-row">
                                <div class="detail-item">
                                    <label>{{ __('scanner.ticket_type') }}</label>
                                    <strong>{{ $scannedTicket->ticketType->name }}</strong>
                                </div>
                                <div class="detail-item">
                                    <label>{{ __('scanner.event') }}</label>
                                    <strong class="truncate">{{ $scannedTicket->orderItem->order->event->title ?? 'N/A' }}</strong>
                                </div>
                            </div>
                            <div class="detail-item footer">
                                <label>{{ __('scanner.ticket_number') }}</label>
                                <span class="ticket-num">{{ $scannedTicket->ticket_number }}</span>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="result-footer">
                    <button @click="$wire.resetFeedback().then(() => { startScanner() })" class="continue-btn">
                        {{ strtoupper(__('scanner.continue')) }}
                    </button>
                    <p class="esc-hint">{{ __('scanner.press_esc') }}</p>
                </div>
            </div>
        </div>
    @endif

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <style>
        :root {
            --scanner-primary: #6366f1;
            --scanner-primary-dark: #4f46e5;
            --scanner-success: #10b981;
            --scanner-danger: #f43f5e;
            --scanner-bg: #ffffff;
            --scanner-text: #111827;
            --scanner-muted: #6b7280;
            --scanner-border: #e5e7eb;
        }

        .dark :root {
            --scanner-bg: #111827;
            --scanner-text: #f9fafb;
            --scanner-muted: #9ca3af;
            --scanner-border: #374151;
        }

        .scanner-container {
            font-family: 'Outfit', sans-serif;
            max-width: 42rem;
            margin: 0 auto;
            color: var(--scanner-text);
        }

        .scanner-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            gap: 1.5rem;
        }

        .scanner-main-title {
            font-size: 2.25rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: -0.025em;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .scanner-subtitle {
            color: var(--scanner-muted);
            font-weight: 500;
        }

        .scanner-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }

        .btn-primary { background: var(--scanner-primary); color: white; }
        .btn-primary:hover { background: var(--scanner-primary-dark); }
        .btn-danger { background: #fee2e2; color: var(--scanner-danger); }
        .dark .btn-danger { background: rgba(244, 63, 94, 0.1); }

        .btn-content { display: flex; align-items: center; gap: 0.5rem; }

        .viewfinder-wrapper {
            position: relative;
            margin-bottom: 3rem;
        }

        .viewfinder-glow {
            position: absolute;
            inset: -1rem;
            background: linear-gradient(to right, var(--scanner-primary), #a855f7, #ec4899);
            border-radius: 3rem;
            filter: blur(24px);
            opacity: 0.15;
        }

        .viewfinder-frame {
            position: relative;
            background: black;
            border-radius: 2.5rem;
            aspect-ratio: 1/1;
            overflow: hidden;
            border: 12px solid var(--scanner-bg);
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
        }

        #reader { width: 100%; height: 100%; }
        #reader video { object-fit: cover !important; }

        .viewfinder-overlay {
            position: absolute;
            inset: 0;
            padding: 3rem;
        }

        .corner {
            position: absolute;
            width: 3rem;
            height: 3rem;
            border-color: var(--scanner-primary);
            border-width: 0;
        }
        .corner-tl { top: 3rem; left: 3rem; border-top-width: 6px; border-left-width: 6px; border-top-left-radius: 1rem; }
        .corner-tr { top: 3rem; right: 3rem; border-top-width: 6px; border-right-width: 6px; border-top-right-radius: 1rem; }
        .corner-bl { bottom: 3rem; left: 3rem; border-bottom-width: 6px; border-left-width: 6px; border-bottom-left-radius: 1rem; }
        .corner-br { bottom: 3rem; right: 3rem; border-bottom-width: 6px; border-right-width: 6px; border-bottom-right-radius: 1rem; }

        .scan-target {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60%;
            height: 60%;
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 2rem;
            background: rgba(99, 102, 241, 0.05);
            animation: pulse 2s infinite;
        }

        .scan-line {
            position: absolute;
            top: 15%;
            left: 15%;
            right: 15%;
            height: 3px;
            background: linear-gradient(to right, transparent, var(--scanner-primary), transparent);
            box-shadow: 0 0 15px var(--scanner-primary);
            animation: scan 3s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }

        .processing-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(4px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .spinner {
            width: 3rem;
            height: 3rem;
            border: 4px solid rgba(255,255,255,0.2);
            border-top-color: var(--scanner-primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 1rem;
        }

        .processing-text {
            color: white;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.75rem;
        }

        .manual-entry-card {
            background: var(--scanner-bg);
            border: 1px solid var(--scanner-border);
            border-radius: 2.5rem;
            padding: 2rem;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }

        .card-header {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            align-items: center;
        }

        .icon-box {
            padding: 1rem;
            background: #f3f4f6;
            border-radius: 1.25rem;
            color: var(--scanner-primary);
        }
        .dark .icon-box { background: rgba(255,255,255,0.05); }

        .text-box h3 { font-size: 1.25rem; font-weight: 800; text-transform: uppercase; margin: 0; }
        .text-box p { font-size: 0.875rem; color: var(--scanner-muted); margin: 0.25rem 0 0; }

        .input-row { display: flex; gap: 1rem; }

        .manual-input {
            flex: 1;
            padding: 1.25rem 1.5rem;
            background: #f9fafb;
            border: 2px solid transparent;
            border-radius: 1.25rem;
            font-family: monospace;
            font-size: 1.125rem;
            color: var(--scanner-text);
            transition: all 0.2s;
        }
        .dark .manual-input { background: rgba(0,0,0,0.2); }
        .manual-input:focus { border-color: var(--scanner-primary); outline: none; background: white; }
        .dark .manual-input:focus { background: black; }

        .check-btn {
            padding: 0 2rem;
            background: var(--scanner-text);
            color: var(--scanner-bg);
            border-radius: 1.25rem;
            font-weight: 900;
            text-transform: uppercase;
            border: none;
            cursor: pointer;
            transition: transform 0.1s;
        }
        .check-btn:active { transform: scale(0.95); }

        /* Result Overlay */
        .result-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.8);
            backdrop-filter: blur(8px);
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .result-modal {
            background: var(--scanner-bg);
            width: 100%;
            max-width: 28rem;
            border-radius: 3rem;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
            animation: modalIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .result-header-graphic {
            padding: 4rem 2rem;
            display: flex;
            justify-content: center;
            position: relative;
        }
        .success .result-header-graphic { background: linear-gradient(135deg, #10b981, #059669); }
        .error .result-header-graphic { background: linear-gradient(135deg, #f43f5e, #e11d48); }

        .icon-circle {
            background: white;
            padding: 1.5rem;
            border-radius: 50%;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.2);
            color: var(--scanner-success);
        }
        .error .icon-circle { color: var(--scanner-danger); }

        .result-body { padding: 2.5rem 2.5rem 0; text-align: center; }

        .status-badge {
            display: inline-block;
            padding: 0.5rem 1.25rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 1rem;
        }
        .success .status-badge { background: #d1fae5; color: #065f46; }
        .error .status-badge { background: #fee2e2; color: #991b1b; }

        .result-title { font-size: 2.5rem; font-weight: 900; letter-spacing: -0.05em; line-height: 1; margin: 0 0 1rem; }

        .ticket-details {
            text-align: left;
            background: var(--scanner-border);
            padding: 2rem;
            border-radius: 2rem;
            margin-top: 2rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            opacity: 0.8;
        }

        .dark .ticket-details { background: rgba(255,255,255,0.05); }

        .detail-item label { display: block; font-size: 0.625rem; font-weight: 900; text-transform: uppercase; color: var(--scanner-muted); letter-spacing: 0.1em; margin-bottom: 0.25rem; }
        .detail-item strong { display: block; font-size: 1.125rem; font-weight: 800; }
        .detail-item.full strong { font-size: 1.5rem; }

        .detail-row { display: grid; grid-cols: 2; gap: 1rem; }
        .detail-row > div { flex: 1; }

        .ticket-num { font-family: monospace; font-weight: 700; color: var(--scanner-primary); }

        .result-footer { padding: 2.5rem; }
        .continue-btn {
            width: 100%;
            padding: 1.5rem;
            background: var(--scanner-text);
            color: var(--scanner-bg);
            border-radius: 1.75rem;
            font-size: 1.25rem;
            font-weight: 900;
            text-transform: uppercase;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        .continue-btn:hover { transform: scale(1.02); }

        .esc-hint { font-size: 0.625rem; font-weight: 800; text-transform: uppercase; color: var(--scanner-muted); margin-top: 1.5rem; letter-spacing: 0.2em; opacity: 0.5; }

        @keyframes scan {
            0% { top: 15%; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 85%; opacity: 0; }
        }

        @keyframes pulse {
            0% { transform: translate(-50%, -50%) scale(1); opacity: 0.5; }
            50% { transform: translate(-50%, -50%) scale(1.05); opacity: 0.8; }
            100% { transform: translate(-50%, -50%) scale(1); opacity: 0.5; }
        }

        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.9) translateY(20px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        .truncate { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        @font-face {
            font-family: 'Outfit';
            src: url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;900&display=swap');
        }
    </style>
</div>
