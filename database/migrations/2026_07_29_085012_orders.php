<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->decimal('sub_total', 8, 2);
            $table->decimal('discount_amount',8,2)->default(0);
            $table->decimal('shipping_cost', 8, 2)->default(0);
            $table->string('campaign_name')->nullable();
            $table->json('applied_campaigns')->nullable();
            $table->decimal('total_amount', 8, 2);
            $table->string('order_status', 50)->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
