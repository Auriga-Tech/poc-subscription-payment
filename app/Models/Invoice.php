<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'subscription_id',
        'order_id',
        'razorpay_invoice_id',
        'stripe_invoice_id',
        'invoice_number',
        'invoice_url',
        'paid_at',
        'status',
    ];

    protected $appends = [
        'status_class'
    ];

    public function getStatusClassAttribute()
    {
        if($this->status == 1) {
            return '<span class="bg-red-100 text-red-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-red-200 dark:text-red-900">Pending</span>';
        } else if($this->status == 2) {
            return '<span class="bg-green-100 text-green-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-green-200 dark:text-green-900">Paid</span>';
        }else if($this->status == 3) {
            return '<span class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">Void</span>';
        }else {
            return '<span class="bg-red-100 text-red-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-red-200 dark:text-red-900">Cancelled</span>';
        }
    }
    
    public function subscription() {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }
    
    public function order() {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
