<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class CancelExpiredOrders extends Command
{
    protected $signature = 'orders:cancel-expired';

    protected $description = 'Cancel pending orders older than the 30-minute reservation window and refund the diamonds';

    public function handle(): int
    {
        $canceled = 0;

        Order::query()->expired()->get()->each(function (Order $order) use (&$canceled): void {
            if ($order->cancelAndRefund()) {
                $canceled++;
                $this->info("Canceled {$order->order_code}, refunded {$order->total_diamond} diamonds.");
            }
        });

        $this->info("Done. {$canceled} order(s) canceled.");

        return self::SUCCESS;
    }
}
