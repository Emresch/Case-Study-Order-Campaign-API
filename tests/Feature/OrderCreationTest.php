<?php

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed essential tables
    DB::table('authors')->insert([
        'author_id' => 1,
        'author_name' => 'Test Author'
    ]);

    DB::table('categories')->insert([
        'category_id' => 1,
        'category_title' => 'Test Category'
    ]);

    DB::table('products')->insert([
        'product_id' => 1,
        'product_title' => 'Test Product',
        'category_id' => 1,
        'category_title' => 'Test Category',
        'author' => 'Test Author',
        'author_id' => 1,
        'list_price' => 48.75,
        'stock_quantity' => 10,
    ]);

    User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);
});

test('it can create an order successfully via API', function () {
    $response = $this->withHeader('X-API-KEY', 'SiparisCampaignApi2026!')
        ->postJson('/api/orders', [
            'items' => [
                [
                    'product_id' => 1,
                    'quantity' => 1,
                ]
            ]
        ]);

    $response->assertStatus(201);
    $response->assertJsonPath('message', 'Sipariş başarıyla oluşturuldu');

    // Verify order was created in DB
    $this->assertDatabaseCount('orders', 1);
    $this->assertDatabaseCount('order_items', 1);

    // Get order ID from the response or database
    $order = \App\Models\Order::first();

    // Verify retrieving the order works (show endpoint)
    $showResponse = $this->withHeader('X-API-KEY', 'SiparisCampaignApi2026!')
        ->getJson("/api/orders/{$order->order_id}");

    $showResponse->assertStatus(200);
    $showResponse->assertJsonPath('order_id', $order->order_id);
    $showResponse->assertJsonPath('order_items.0.product_name', 'Test Product');
});
