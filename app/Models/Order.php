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
        'product_id',
        'amount',
        'country_code',
        'status',
    ];
    
    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function invoice() {
        return $this->hasOne(Invoice::class);
    }
}
