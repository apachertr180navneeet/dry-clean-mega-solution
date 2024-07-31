<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $fillable = [
        'user_id',
        'invoice_number',
        'order_date',
        'order_time',
        'delivery_date',
        'delivery_time',
        'discount_id',
        'service_id',
        'status',
        'total_qty',
        'total_price',
        'is_deleted',
        'express_charge'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function Discounts()
    {
        return $this->belongsTo(Discount::class,'discount_id');
    }

    public function paymentDetail()
    {
        return $this->hasOne(PaymentDetail::class, 'order_id');
    }


}
