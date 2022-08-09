<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'stripe_customer_id',
        'address_line',
        'postal_code',
        'city',
        'state',
        'country'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'is_admin', 'is_user', 'has_subscriptions', 'global_country'
    ];

    public function getIsAdminAttribute() {
        if($this->role == 'Admin') {
            return true;
        }
        return false;
    }

    public function getIsUserAttribute() {
        if($this->role == 'User') {
            return true;
        }
        return false;
    }

    public function getHasSubscriptionsAttribute() {
        if($this->subscriptions->count() > 0) {
            return true;
        }
        return false;
    }

    public function getGlobalCountryAttribute() {
        if($this->subscriptions->count() > 0) {
            return $this->subscriptions[0]->country_code;
        } else if($this->orders->count() > 0) {
            return $this->orders[0]->country_code;
        }
        return NULL;
    }
    
    public function subscriptions() {
        return $this->hasMany(Subscription::class);
    }
    
    public function orders() {
        return $this->hasMany(Order::class);
    }
}
