<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Models\Ticket;
use Intervention\Image\ImageManager;
use Spatie\Browsershot\Browsershot;

class TicketService
{
    /**
     * Generate tickets for a given order item.
     * 
     * @param OrderItem $orderItem
     * @return void
     */
    public function generateTickets(OrderItem $orderItem): void
    {
        for ($i = 0; $i < $orderItem->quantity; $i++) {
            $ticketNumber = (new Ticket)->generateTicketNumber($orderItem->order_id);

            $ticket = Ticket::create([
                'order_item_id' => $orderItem->id,
                'ticket_type_id' => $orderItem->ticket_type_id,
                'ticket_number' => $ticketNumber,
                'holder_name' => null,
                'status' => 'active',
                'qr_code_content' => null,
            ]);

            $this->generateQrCode($ticket);
        }

        activity()
            ->performedOn($orderItem)
            ->log("Generated {$orderItem->quantity} tickets");
    }

    /**
     * Generate and save a QR code for a ticket.
     * 
     * @param Ticket $ticket
     * @return string
     */
    public function generateQrCode(Ticket $ticket): string
    {
        $payload = json_encode([
            'ticket_id' => $ticket->id,
            'ticket_number' => $ticket->ticket_number,
            'event_id' => $ticket->ticketType->event_id,
        ]);

        $encrypted = encrypt($payload);

        $ticket->update(['qr_code_content' => $encrypted]);

        return $encrypted;
    }

    /**
     * Validate an encrypted QR code payload.
     * 
     * @param string $encryptedPayload
     * @throws \Exception
     * @return Ticket
     */
    public function validateQrCode(string $encryptedPayload): Ticket
    {
        try {
            $decrypted = decrypt($encryptedPayload);
            $payload = json_decode($decrypted, true);

            if (! isset($payload['ticket_id'])) {
                throw new \Exception(__('scanner.ticket_not_found'));
            }

            $ticket = Ticket::with(['orderItem.order.event', 'ticketType'])->find($payload['ticket_id']);

            if (! $ticket) {
                throw new \Exception(__('scanner.ticket_not_found'));
            }

            if ($ticket->ticket_number !== $payload['ticket_number']) {
                throw new \Exception(__('scanner.ticket_not_found'));
            }

            if ($ticket->status === 'used') {
                $time = $ticket->checked_in_at ? $ticket->checked_in_at->setTimezone(config('app.timezone'))->format('H:i') : '--:--';
                throw new \Exception(__('scanner.ticket_already_used', ['time' => $time]));
            }

            if ($ticket->status === 'cancelled' || $ticket->status === 'cancelled') {
                throw new \Exception(__('scanner.ticket_cancelled_error'));
            }

            if (! $ticket->canBeUsed()) {
                throw new \Exception(__('scanner.ticket_invalid_error'));
            }

            return $ticket;
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            throw new \Exception(__('scanner.ticket_invalid_error'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Mark a ticket as used/checked-in.
     * 
     * @param Ticket $ticket
     * @throws \Illuminate\Validation\ValidationException
     * @return Ticket
     */
    public function markTicketAsUsed(Ticket $ticket): Ticket
    {
        if ($ticket->status === 'used') {
            $time = $ticket->checked_in_at ? $ticket->checked_in_at->setTimezone(config('app.timezone'))->format('H:i') : '--:--';
            throw \Illuminate\Validation\ValidationException::withMessages(['ticket' => __('scanner.ticket_already_used', ['time' => $time])]);
        }

        if (! $ticket->canBeUsed()) {
            throw \Illuminate\Validation\ValidationException::withMessages(['ticket' => __('scanner.ticket_invalid_error')]);
        }

        $ticket->markAsUsed();

        activity()
            ->performedOn($ticket)
            ->causedBy(auth()->user())
            ->log('Ticket checked in');

        return $ticket->refresh();
    }

    /**
     * Cancel a ticket.
     * 
     * @param Ticket $ticket
     * @return Ticket
     */
    public function cancelTicket(Ticket $ticket): Ticket
    {
        $ticket->cancel();

        activity()
            ->performedOn($ticket)
            ->causedBy(auth()->user())
            ->log('Ticket cancelled');

        return $ticket->refresh();
    }

    /**
     * Generate a PDF file for the ticket.
     * 
     * @param Ticket $ticket
     * @param string $driver
     * @return string
     */
    public function generateTicketPdf(Ticket $ticket, string $driver = 'dompdf'): string
    {
        if ($driver === 'browsershot') {
            return $this->generateTicketPdfBrowsershot($ticket);
        }

        $ticket->load(['ticketType.event.banner', 'orderItem.order.user']);

        $qrCode = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(200)
            ->generate($ticket->qr_code_content));

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdfs.ticket', [
            'ticket' => $ticket,
            'qrCode' => $qrCode,
        ]);

        $path = "orders/{$ticket->orderItem->order->uuid}/tickets/{$ticket->id}.pdf";
        \Illuminate\Support\Facades\Storage::disk('public')->put($path, $pdf->output());

        return $path;
    }

    /**
     * Generate a PDF file for the ticket using Browsershot.
     * 
     * @param Ticket $ticket
     * @return string
     */
    public function generateTicketPdfBrowsershot(Ticket $ticket): string
    {
        $ticket->load(['ticketType.event.banner', 'orderItem.order.user']);

        $qrCode = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(200)
            ->generate($ticket->qr_code_content));

        $html = view('pdfs.ticket', [
            'ticket' => $ticket,
            'qrCode' => $qrCode,
            'bannerBase64' => $this->getBase64Banner($ticket->ticketType->event),
        ])->render();

        $path = "orders/{$ticket->orderItem->order->uuid}/tickets/{$ticket->id}-bs.pdf";
        $fullPath = storage_path("app/public/{$path}");

        // Ensure directory exists
        \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory(dirname($path));

        Browsershot::html($html)
            ->setNodeBinary('/opt/homebrew/bin/node')
            ->setNpmBinary('/opt/homebrew/bin/npm')
            ->showBackground()
            ->margins(0, 0, 0, 0)
            ->format('A4')
            ->save($fullPath);

        return $path;
    }

    /**
     * Generate a PNG image for a ticket (Full Ticket Design).
     * Returns storage path (public disk).
     *
     * @param Ticket $ticket
     * @return string
     */
    public function generateTicketPng(Ticket $ticket): string
    {
        $ticket->load(['ticketType.event.banner', 'orderItem.order.user']);

        $order = $ticket->orderItem->order;
        $uuid = $order->uuid;
        $dir = "orders/{$uuid}/tickets";
        \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory($dir);

        $pngRelative = "{$dir}/{$ticket->id}.png";

        // Dimensions for high quality
        $width = 640;
        $height = 1200;

        $manager = ImageManager::gd();
        $canvas = $manager->create($width, $height);
        
        // Background - Page color
        $canvas->fill('#f4f5f7');

        // Draw Ticket Card (Simulated Rounded Rect by layering)
        $cardX = 40;
        $cardY = 40;
        $cardWidth = 560;
        $cardHeight = 1120;
        $radius = 32;

        // Draw white background for card (Box)
        $canvas->drawRectangle($cardX, $cardY, function ($draw) use ($cardWidth, $cardHeight) {
            $draw->size($cardWidth, $cardHeight);
            $draw->background('#ffffff');
        });

        // 1. BANNER SECTION (approx 280px height)
        $bannerHeight = 280;
        $banner = null;

        if ($ticket->ticketType->event->banner && $ticket->ticketType->event->banner->url) {
            try {
                $banner = $manager->read(file_get_contents($ticket->ticketType->event->banner->url));
            } catch (\Exception $e) {}
        }

        // Random image fallback
        if (!$banner) {
            try {
                $seed = $ticket->ticketType->event_id ?? 'default';
                $randomUrl = "https://picsum.photos/seed/{$seed}/800/400";
                $banner = $manager->read(file_get_contents($randomUrl));
            } catch (\Exception $e) {}
        }

        if ($banner) {
            $banner->cover($cardWidth, $bannerHeight);
            $canvas->place($banner, 'top-left', $cardX, $cardY);
        } else {
            $canvas->drawRectangle($cardX, $cardY, function ($draw) use ($cardWidth, $bannerHeight) {
                $draw->size($cardWidth, $bannerHeight);
                $draw->background('#fdf6e9');
            });
        }

        // 2. TICKET TYPE BADGE (Top Right of Card)
        $badgeX = $cardX + $cardWidth - 110;
        $badgeY = $cardY + 30;
        $badgeText = strtoupper($ticket->ticketType->name);
        
        // Badge background (Pill)
        $canvas->drawRectangle($badgeX, $badgeY, function($draw) {
            $draw->size(80, 40);
            $draw->background('#ffffff');
            // Simplified: rectangular badge for certainty in v3 draw
        });

        $canvas->text('★ ' . $badgeText, $badgeX + 40, $badgeY + 20, function ($font) {
            $font->size(12);
            $font->color('#e67e22');
            $font->align('center');
            $font->valign('middle');
        });

        // 3. MAIN CONTENT
        $y = $cardY + $bannerHeight + 40;
        $canvas->text('LIVE EVENT', $cardX + 40, $y, function ($font) {
            $font->size(14);
            $font->color('#e67e22');
            $font->align('left');
            $font->valign('top');
        });

        $y += 30;
        $eventTitle = $ticket->ticketType->event->title;
        $canvas->text($eventTitle, $cardX + 40, $y, function ($font) {
            $font->size(42);
            $font->color('#0d1b2a');
            $font->align('left');
            $font->valign('top');
            $font->wrap(460);
        });

        $y += 110;
        
        // GRID DATA
        // Labels
        $canvas->text('DATE', $cardX + 40, $y, function ($font) {
            $font->size(12);
            $font->color('#94a3b8');
        });
        $canvas->text('TIME', $cardX + 300, $y, function ($font) {
            $font->size(12);
            $font->color('#94a3b8');
        });

        $y += 25;
        $canvas->text($ticket->ticketType->event->event_date->format('M d, Y'), $cardX + 40, $y, function ($font) {
            $font->size(20);
            $font->color('#0d1b2a');
        });
        $canvas->text($ticket->ticketType->event->event_date->format('h:i A'), $cardX + 300, $y, function ($font) {
            $font->size(20);
            $font->color('#0d1b2a');
        });

        $y += 60;
        $canvas->text('VENUE', $cardX + 40, $y, function ($font) {
            $font->size(12);
            $font->color('#94a3b8');
        });
        $y += 25;
        $canvas->text($ticket->ticketType->event->venue_name, $cardX + 40, $y, function ($font) {
            $font->size(20);
            $font->color('#0d1b2a');
            $font->wrap(460);
        });
        $y += 40;
        $canvas->text($ticket->ticketType->event->location, $cardX + 40, $y, function ($font) {
            $font->size(14);
            $font->color('#64748b');
        });

        $y += 80;
        $canvas->text('SECTION', $cardX + 40, $y, function ($font) {
            $font->size(12);
            $font->color('#94a3b8');
        });
        $canvas->text('SEAT', $cardX + 300, $y, function ($font) {
            $font->size(12);
            $font->color('#94a3b8');
        });

        $y += 25;
        $canvas->text($ticket->section_name ?? 'Floor A', $cardX + 40, $y, function ($font) {
            $font->size(20);
            $font->color('#0d1b2a');
        });
        $canvas->text($ticket->seat_number ?? 'VIP-24', $cardX + 300, $y, function ($font) {
            $font->size(20);
            $font->color('#0d1b2a');
        });

        $y += 80;
        // 4. CUTOUT SEPARATOR
        // Draw dotted line
        for ($i = $cardX; $i < $cardX + $cardWidth; $i += 15) {
            $canvas->drawRectangle($i, $y, function($draw) {
                $draw->size(8, 2);
                $draw->background('#f1f5f9');
            });
        }
        // Cutout circles (match page background)
        $canvas->drawCircle($cardX, $y, function ($draw) { 
            $draw->radius(20);
            $draw->background('#f4f5f7');
        });
        $canvas->drawCircle($cardX + $cardWidth, $y, function ($draw) { 
            $draw->radius(20);
            $draw->background('#f4f5f7');
        });

        $y += 60;
        // 5. FOOTER DETAILS
        $canvas->text('TICKET HOLDER', $cardX + 40, $y, function ($font) {
            $font->size(12);
            $font->color('#94a3b8');
        });
        $canvas->text('ORDER ID', $cardX + $cardWidth - 40, $y, function ($font) {
            $font->size(12);
            $font->color('#94a3b8');
            $font->align('right');
        });

        $y += 25;
        $canvas->text($ticket->orderItem->order->user->name ?? 'John Visitor', $cardX + 40, $y, function ($font) {
            $font->size(18);
            $font->color('#0d1b2a');
        });
        $canvas->text('#' . ($ticket->orderItem->order->order_number ?? '8901'), $cardX + $cardWidth - 40, $y, function ($font) {
            $font->size(18);
            $font->color('#94a3b8');
            $font->align('right');
        });

        $y += 100;
        // 6. QR CODE
        $qrBinary = (string) \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
            ->size(220)
            ->margin(1)
            ->generate($ticket->qr_code_content);
        
        $qrImg = $manager->read($qrBinary);
        $canvas->place($qrImg, 'top-center', 0, $y);

        $y += 240;
        $securityCode = 'SEC:' . implode('-', str_split($ticket->ticket_number, 4));
        $canvas->text($securityCode, $width / 2, $y, function ($font) {
            $font->size(12);
            $font->color('#cbd5e1');
            $font->align('center');
        });

        // Save to public disk
        \Illuminate\Support\Facades\Storage::disk('public')->put($pngRelative, (string) $canvas->toPng());

        return $pngRelative;
    }

    /**
     * Generate a single PNG image containing all tickets for an order.
     * This composes individual ticket tiles into one long PNG and returns the storage path.
     *
     * @param \App\Models\Order $order
     * @return string
     */
    public function generateOrderTicketsImage(\App\Models\Order $order): string
    {
        $order->load(['orderItems.tickets.ticketType.event']);

        $tickets = $order->orderItems->flatMap->tickets;
        $count = $tickets->count() ?: 1;

        $tileWidth = 640;
        $tileHeight = 1200;
        $gap = 40;

        $totalHeight = ($tileHeight + $gap) * $count + $gap;

        $manager = ImageManager::gd();
        $canvas = $manager->create($tileWidth, $totalHeight);
        $canvas->fill('#f4f5f7'); // Background color matching the PDF body bg

        $yOffset = $gap;

        foreach ($tickets as $ticket) {
            // Generate the tile for this ticket
            $ticketPath = $this->generateTicketPng($ticket);
            $ticketTile = $manager->read(\Illuminate\Support\Facades\Storage::disk('public')->get($ticketPath));

            // Insert tile into master canvas
            $canvas->place($ticketTile, 'top-left', 0, $yOffset);

            $yOffset += $tileHeight + $gap;
        }

        $relative = "orders/{$order->uuid}/tickets/all.png";
        \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory("orders/{$order->uuid}/tickets");
        \Illuminate\Support\Facades\Storage::disk('public')->put($relative, (string) $canvas->toPng());

        return $relative;
    }


    /**
     * Generate a PDF file for all tickets in an order.
     * 
     * @param \App\Models\Order $order
     * @param string $driver
     * @return string
     */
    public function generateOrderTicketsPdf(\App\Models\Order $order, string $driver = 'dompdf'): string
    {
        if ($driver === 'browsershot') {
            return $this->generateOrderTicketsPdfBrowsershot($order);
        }

        $order->load(['orderItems.tickets.ticketType.event.banner', 'user']);

        $tickets = $order->orderItems->flatMap->tickets;

        $ticketsData = $tickets->map(function ($ticket) {
            return [
                'ticket' => $ticket,
                'qrCode' => base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                    ->size(200)
                    ->generate($ticket->qr_code_content)),
            ];
        });

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdfs.tickets', [
            'ticketsData' => $ticketsData,
        ]);

        $path = "orders/{$order->uuid}/tickets/all.pdf";
        \Illuminate\Support\Facades\Storage::disk('public')->put($path, $pdf->output());

        return $path;
    }

    /**
     * Generate a PDF file for all tickets in an order using Browsershot.
     * 
     * @param \App\Models\Order $order
     * @return string
     */
    public function generateOrderTicketsPdfBrowsershot(\App\Models\Order $order): string
    {
        $order->load(['orderItems.tickets.ticketType.event.banner', 'user']);

        $tickets = $order->orderItems->flatMap->tickets;

        $ticketsData = $tickets->map(function ($ticket) {
            return [
                'ticket' => $ticket,
                'qrCode' => base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                    ->size(200)
                    ->generate($ticket->qr_code_content)),
            ];
        });

        $html = view('pdfs.tickets', [
            'ticketsData' => $ticketsData,
            'bannerBase64' => $this->getBase64Banner($order->orderItems->first()?->ticketType->event), // Assuming all tickets in order belong to same event or use first as proxy
        ])->render();

        $path = "orders/{$order->uuid}/tickets/all-bs.pdf";
        $fullPath = storage_path("app/public/{$path}");

        // Ensure directory exists
        \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory(dirname($path));

        Browsershot::html($html)
            ->setNodeBinary('/opt/homebrew/bin/node')
            ->setNpmBinary('/opt/homebrew/bin/npm')
            ->showBackground()
            ->margins(0, 0, 0, 0)
            ->format('A4')
            ->save($fullPath);

        return $path;
    }

    /**
     * Generate PNG files for all tickets in an order and package them into a ZIP.
     * Returns storage path to the ZIP (public disk).
     *
     * @param \App\Models\Order $order
     * @return string
     */
    public function generateOrderTicketsZip(\App\Models\Order $order): string
    {
        $order->load(['orderItems.tickets']);

        $ticketFiles = [];

        foreach ($order->orderItems->flatMap->tickets as $ticket) {
            $ticketPath = $this->generateTicketPng($ticket);
            $ticketFiles[] = storage_path('app/public/' . $ticketPath);
        }

        $zipRelative = "orders/{$order->uuid}/tickets/all.zip";
        $zipPath = storage_path('app/public/' . $zipRelative);

        // ensure directory exists
        \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory("orders/{$order->uuid}/tickets");

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            throw new \Exception('Could not create ZIP file for tickets');
        }

        foreach ($ticketFiles as $file) {
            if (file_exists($file)) {
                $zip->addFile($file, basename($file));
            }
        }

        $zip->close();

        return $zipRelative;
    }

    /**
     * Get base64 encoded banner for an event.
     * 
     * @param \App\Models\Event|null $event
     * @return string|null
     */
    private function getBase64Banner($event): ?string
    {
        $content = null;
        $mimeType = 'image/jpeg';

        if ($event && $event->banner) {
            $banner = $event->banner;
            $mimeType = $banner->mime_type ?? 'image/jpeg';

            // Prefer local storage for stability
            if ($banner->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($banner->file_path)) {
                $content = \Illuminate\Support\Facades\Storage::disk('public')->get($banner->file_path);
            } 
            // Fallback to URL if it's external or disk fetch fails
            elseif ($banner->url) {
                try {
                    $content = @file_get_contents($banner->url);
                } catch (\Exception $e) {
                }
            }
        }

        // If no content found, use a random image fallback from Picsum
        if (!$content) {
            try {
                $seed = $event ? $event->id : rand(1, 1000);
                $randomUrl = "https://picsum.photos/seed/{$seed}/800/400";
                $content = @file_get_contents($randomUrl);
                $mimeType = 'image/jpeg';
            } catch (\Exception $e) {
            }
        }

        if ($content) {
            return 'data:' . $mimeType . ';base64,' . base64_encode($content);
        }

        return null;
    }
}
