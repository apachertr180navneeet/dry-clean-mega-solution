<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductItem extends Model
{
    use HasFactory;
    protected $table = 'product_items';

    protected $fillable = [
        'product_id',
        'name',
        'image',
    ];

    public function categories()
    {
        return $this->hasMany(ProductCategory::class);
    }
    public function service()
    {
        return $this->hasMany(Service::class);
    }
}
