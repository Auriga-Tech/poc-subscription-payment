<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';

    protected $fillable = [
        'user_id',
        'product_id',
        'razorpay_subscription_id',
        'stripe_subscription_id',
        'plan_type',
        'amount',
        'country_code',
        'subscription_url',
        'status',
        'expired_at'
    ];

    protected $appends = [
        'status_class', 'plan_type_class'
    ];

    public function getStatusClassAttribute()
    {
        if($this->status == 1) {
            return '<span class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">Created</span>';
        } else if($this->status == 2) {
            return '<span class="bg-green-100 text-green-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-green-200 dark:text-green-900">Active</span>';
        } else if($this->status == 3) {
            return '<span class="bg-green-100 text-green-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-green-200 dark:text-green-900">Completed</span>';
        } else if($this->status == 4) {
            return '<span class="bg-red-100 text-red-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-red-200 dark:text-red-900">Cancelled</span>';
        } else {
            return '<span class="bg-red-100 text-red-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-red-200 dark:text-red-900">Hold</span>';
        }
    }

    public function getPlanTypeClassAttribute()
    {
        if($this->plan_type == 1) {
            return '<span class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">BASIC</span>';
        }else {
            return '<span class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">PRO</span>';
        }
    }
    
    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function invoices() {
        return $this->hasMany(Invoice::class);
    }
}
