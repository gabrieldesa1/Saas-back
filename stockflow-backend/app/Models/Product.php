<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['sku', 'name', 'category_id', 'quantity', 'min_stock', 'price', 'cost'];
    public function category() { return $this->belongsTo(Category::class); }
    public function movements() { return $this->belongsTo(StockMovement::class); }
}
