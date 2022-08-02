<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'image',
        'description',
        'type',
        'basic_amount',
        'country_code',
        'stripe_product_id'
    ];

    protected $appends = [
        'image_url'
    ];

    public function getImageUrlAttribute() {
        return config('app.url').'/storage/'.$this->image;
    }
    
    public function prices() {
        return $this->hasMany(Prices::class);
    }
    
    public function subscriptions() {
        return $this->hasMany(Subscription::class);
    }
    
    public function orders() {
        return $this->hasMany(Order::class);
    }
}
