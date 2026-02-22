<?php

namespace App\Services;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public function generateInvoicePdf(Order $order): string
    {
        $order->load(['user', 'event', 'orderItems.ticketType']);

        $pdf = Pdf::loadView('pdfs.invoice', ['order' => $order]);
        
        $path = "orders/{$order->uuid}/invoice.pdf";
        
        Storage::disk('public')->put($path, $pdf->output());

        return $path;
    }
}
