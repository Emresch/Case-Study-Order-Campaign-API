<?php

namespace App\Http\Controllers;

use app\Models\User;
use app\Requests\StoreOrderRequest;
use app\Services\OrderService;
use Illuminate\Validation\ValidationException;

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
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Doğrulama başarısız', 'errors' => $e->getMessage()], 422);
        }
    }
    public function show(Order $order)
    {
        
    }
}
