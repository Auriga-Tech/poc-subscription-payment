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
        'expiry_date'
    ];

    protected $appends = [
        'image_url'
    ];
    
    public function prices() {
        return $this->hasMany(Prices::class);
    }

    public function getImageUrlAttribute() {
        return config('app.url').'/storage/'.$this->image;
    }
}
