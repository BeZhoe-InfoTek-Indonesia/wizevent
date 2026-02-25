<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class OrderFileDownloadController extends Controller
{
    /**
     * Download all payment proof files for an order as a ZIP.
     */
    public function downloadPaymentProofs(Order $order): BinaryFileResponse
    {
        $files = $order->files()->get();

        if ($files->isEmpty()) {
            abort(404, 'No files found');
        }

        $tempPath = sys_get_temp_dir().'/order_'.$order->id.'_payment_proofs_'.time().'.zip';
        $zip = new ZipArchive;

        if ($zip->open($tempPath, ZipArchive::CREATE) !== true) {
            abort(500, 'Could not create zip archive');
        }

        foreach ($files as $file) {
            // Prefer local storage path when available
            if ($file->file_path && ! Str::startsWith($file->file_path, ['http://', 'https://'])) {
                $localPath = Storage::disk('public')->path($file->file_path);
                if (file_exists($localPath)) {
                    $zip->addFile($localPath, $file->original_filename ?? basename($localPath));

                    continue;
                }
            }

            // Fallback: try to fetch remote URL and add content
            $url = $file->url;
            try {
                $contents = @file_get_contents($url);
            } catch (\Throwable $e) {
                $contents = false;
            }

            if ($contents !== false) {
                $zip->addFromString($file->original_filename ?? basename($url), $contents);
            }
        }

        $zip->close();

        if (! file_exists($tempPath)) {
            abort(500, 'Zip file not found');
        }

        return response()->download($tempPath, 'order-'.$order->order_number.'-payment-proofs.zip')->deleteFileAfterSend(true);
    }
}
