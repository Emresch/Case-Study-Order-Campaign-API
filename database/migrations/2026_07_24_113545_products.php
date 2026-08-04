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
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->string('product_title', 255);
            $table->unsignedBigInteger('category_id');
            $table->string('category_title', 100)->nullable();
            $table->string('author', 100)->nullable();
            $table->unsignedBigInteger('author_id');
            $table->decimal('list_price', 10, 2);
            $table->integer('stock_quantity')->default(0);
            $table->timestamps();

            $table->foreign('category_id')->references('category_id')->on('categories')->onDelete('cascade');
            $table->foreign('author_id')->references('author_id')->on('authors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
