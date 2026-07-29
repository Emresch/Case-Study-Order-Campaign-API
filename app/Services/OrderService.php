<?php

namespace App\Services;

use App\Campaigns\CampaignManager;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Exception;

use Illuminate\Support\Facades\DB;

class OrderService
{
    protected CampaignManager $campaignManager;

    public function __construct(CampaignManager $campaignManager)
    {
        $this->campaignManager = $campaignManager;
    }

    public function createOrder(array $cartItems, $user): Order
    {
        return DB::transaction(function () use ($cartItems, $user) {
            $subtotal = 0;
            $enritchedCartItems = [];
            foreach ($cartItems as $item) {
                $product = Product::where('product_id', $item['product_id'])
                    ->lockForUpdate()
                    ->firstorfail();
                if ($product->stock_quantity < $item['quantity']) {
                    throw new Exception("Yetersiz stok, ürün ID: {$item['product_id']}");
                }

                $enritchedCartItems[] = [
                    'product_id' => $product->product_id,
                    'category_id' => $product->category_id,
                    'author_id' => $product->author_id,
                    'price' => (float) $product->list_price,
                    'quantity' => (int) $item['quantity'],
                ];
                $subtotal += $product->list_price * $item['quantity'];
                $product->decrement('stock_quantity', $item['quantity']);

            }

           $campainResult = $this->campaignManager->getBestCampaigns($enritchedCartItems, $subtotal);
           $discountAmount = $campainResult['discount_amount'];
           $campaignName = $campainResult['campaign_name'];

           $netTotal = $subtotal - $discountAmount;
           $shippingCost = $netTotal > 50 ? 0 : 10;
           $finalTotal = $netTotal + $shippingCost;

           $oder = Order::create([
                'user_id' => $user->id,
                'sub_total' => $subtotal,
                'discount_amount' => $discountAmount,
                'shipping_cost' => $shippingCost,
                'campaign_name' => $campaignName,
                'total_amount' => $finalTotal,
                'order_status' => '',    
                'applied_campaigns' => $campaignName ? [$campaignName] : [],
            ]);

            foreach ($enritchedCartItems as $item) {
                OrderItem::create([
                    'order_id' => $oder->id,
                    'product_id' => $item['product_id'],
                    'category_id' => $item['category_id'],
                    'author_id' => $item['author_id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);
                
            }
            return $oder;
       });
    }
}