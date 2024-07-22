<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;
    protected $table = 'product_categories';

    protected $fillable = [
        'product_item_id',
        'operation_id',
        'name',
        'price',
    ];

    public function productItem()
    {
        return $this->belongsTo(ProductItem::class, 'product_item_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'operation_id');
    }

}
