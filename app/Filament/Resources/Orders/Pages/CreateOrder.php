<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Services\OrderService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $order = app(OrderService::class)->createOrder($data);
        
        if (isset($data['status']) && $data['status'] === 'completed') {
             app(OrderService::class)->approveManualOrder($order);
        } elseif (isset($data['status']) && $data['status'] !== 'pending_payment') {
             if ($data['status'] === 'cancelled') {
                 app(OrderService::class)->cancelOrder($order, 'Created as cancelled');
             } elseif ($data['status'] === 'expired') {
                 app(OrderService::class)->markOrderAsExpired($order);
             } else {
                 $order->update(['status' => $data['status']]);
             }
        }
        
        return $order;
    }
}
