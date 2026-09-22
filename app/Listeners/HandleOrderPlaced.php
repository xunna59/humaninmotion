<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Mail\OrderConfirmation;
use App\Models\AuditLog;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class HandleOrderPlaced implements ShouldQueue
{
    public function handle(OrderPlaced $event): void
    {
        if ($event->order->customer_email) {
            Mail::to($event->order->customer_email)->send(new OrderConfirmation($event->order));
        }

        AuditLog::record('order.placed', $event->order, [
            'order_number' => $event->order->order_number,
            'total' => $event->order->total,
        ]);
    }
}