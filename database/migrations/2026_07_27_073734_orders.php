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
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('campaign_id')->nullable();
            $table->decimal('sub_total', 10, 2);
            $table->decimal('discount_amount',10,2);
            $table->decimal('shipping_fee',10,2);
            $table->decimal('final_total', 10, 2);
            $table->string('order_status', 50);
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users');
            $table->foreign('campaign_id')->references('campaign_id')->on('campaigns');
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
