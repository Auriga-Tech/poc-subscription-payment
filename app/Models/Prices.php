<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prices extends Model
{
    use HasFactory;

    protected $table = 'prices';

    protected $fillable = [
        'product_id',
        'razorpay_price_id',
        'stripe_price_id',
        'type',
        'amount',
        'country_code',
    ];
    
    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
