<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\User;
use App\Services\OrderService;
use Exception;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request, OrderService $orderService)
    {
        try {
            $user = User::first();
            if (! $user) {
                return response()->json(['message' => 'Sistemde kayıtlı kullanıcı bulunamadı'], 404);
            }
            $order = $orderService->createOrder($request->validated()['items'], $user);

            return response()->json(['message' => 'Sipariş başarıyla oluşturuldu', 'order' => $order], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Doğrulama başarısız', 'errors' => $e->getMessage()], 422);
        } catch (Exception $e) {
            return response()->json(['message' => 'Bir hata oluştu'], 500);
        }
    }

    public function show(Order $order)
    {
        return response()->json([
            'order_id' => $order->order_id,
            'user_id' => $order->user_id,
            'order_status' => $order->order_status,
            'created_at' => $order->created_at,
            'sub_total' => $order->sub_total,
            'campaign_name' => $order->campaign_name,
            'discount_amount' => $order->discount_amount,
            'shipping_cost' => $order->shipping_cost,
            'total_amount' => $order->total_amount,
            'order_items' => $order->order_items->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->product_title,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total_price' => (float) ($item->price * $item->quantity),
                ];
            }),
        ]);
    }
}
