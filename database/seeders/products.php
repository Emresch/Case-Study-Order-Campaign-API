<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class products extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jasonPath = database_path('data/products.json');
        $jasonContent = File::get($jasonPath);
        $products = json_decode($jasonContent, true);
        foreach ($products as $product) {
            DB::table('products')->insert([
                'product_id' => $product['product_id'],
                'title' => $product['title'],
                'category_id' => $product['category_id'],
                'category_title' => $product['category_title'],
                'author' => $product['author'],
                'author_id' => $product['author_id'],
                'list_price' => $product['list_price'],
                'stock_quantity' => $product['stock_quantity'],
            ]);
        }
    }
}
