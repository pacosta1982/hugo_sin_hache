<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\Notification;
use App\Services\EmailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CheckLowStockProducts implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {

    }

    public function handle(EmailService $emailService): void
    {
        $lowStockProducts = Product::lowStock()
            ->active()
            ->get();

        $outOfStockProducts = Product::outOfStock()
            ->active()
            ->whereColumn('ultima_alerta_stock', '<', \DB::raw('DATE_SUB(NOW(), INTERVAL 24 HOUR)'))
            ->orWhereNull('ultima_alerta_stock')
            ->get();


        foreach ($lowStockProducts as $product) {
            if ($product->shouldNotifyLowStock()) {
                Notification::notifyLowStock($product);
            }
        }

        foreach ($outOfStockProducts as $product) {
            if ($product->stock === 0) {
                $message = "El producto '{$product->nombre}' está agotado (0 unidades). Requiere restock inmediato.";
                
                Notification::createNotification(
                    null,
                    'error',
                    'Producto Agotado',
                    $message,
                    [
                        'product_id' => $product->id,
                        'current_stock' => 0,
                    ]
                );

                $product->markLowStockNotified();
            }
        }


        if ($lowStockProducts->count() > 0 || $outOfStockProducts->count() > 0) {
            $emailService->sendLowStockAlert($lowStockProducts, $outOfStockProducts);
        }
    }
}
