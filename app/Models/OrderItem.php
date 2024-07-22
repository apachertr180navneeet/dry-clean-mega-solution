<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $table = 'order_items';
    protected $fillable = [
        'order_id',
        'product_item_id',
        'product_category_id',
        'operation_id',
        'operation_price',
        'price',
        'quantity',
        'status',
    ];
    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function productItem()
    {
        return $this->belongsTo(ProductItem::class, 'product_item_id');
    }

    public function opertions()
    {
        return $this->belongsTo(Service::class, 'operation_id');
    }
    public function Discounts()
    {
        return $this->belongsTo(Discount::class,'discount_id');
    }
}
