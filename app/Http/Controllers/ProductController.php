<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\PointsService;
use App\Services\NotificationService;
use App\Http\Requests\ProductRedemptionRequest;

class ProductController extends Controller
{
    public function __construct(
        private PointsService $pointsService,
        private NotificationService $notificationService
    ) {}

    public function index(Request $request)
    {
        return view('products.index');
    }

    public function show(Request $request, Product $product)
    {
        $employee = $request->get('employee');
        $canAfford = $employee ? $this->pointsService->canAffordProduct($employee, $product) : false;
        
        $isFavorite = false;
        
        return view('products.show', compact('product', 'employee', 'canAfford', 'isFavorite'));
    }

    public function redeem(ProductRedemptionRequest $request, Product $product)
    {
        $employee = $request->get('employee');
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        try {
            $result = $this->pointsService->redeemProduct($employee, $product, [
                'observaciones' => $request->validated('observaciones')
            ]);

            if ($result['success']) {
                try {
                    $this->notificationService->sendOrderNotifications($result['order']);
                } catch (\Exception $e) {
                    \Log::warning('Failed to send notifications for order', [
                        'order_id' => $result['order']->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            if ($request->expectsJson()) {
                return response()->json($result);
            }

            return back()->with('success', $result['message']);

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }

            return back()->with('error', $e->getMessage());
        }
    }
}