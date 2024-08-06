<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    use HasFactory;
    protected $table = 'payment_details';

    protected $fillable = [
        'order_id',
        'total_quantity',
        'total_amount',
        'discount_amount',
        'service_charge',
        'paid_amount',
        'status',
        'payment_type',
    ];
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
