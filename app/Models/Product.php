<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'product_id';
    protected $fillable = [
        'product_title',
        'category_id',
        'category_title',
        'author',
        'author_id',
        'list_price',
        'stock_quantity',
    ];
}