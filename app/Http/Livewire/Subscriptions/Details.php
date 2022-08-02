<?php

namespace App\Http\Livewire\Subscriptions;

use Livewire\Component;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use App\Helpers\StripeHelper;
use App\Models\Prices;
use App\Helpers\RazorpayHelper;

class Details extends Component
{
    public $subscription, $symbol = '', $pay_invoices_alert = false, $is_stripe = false, $is_razorpay = false;

    public function mount($sid)
    {
        $this->subscription = Subscription::where(function($q) {
                if(!Auth::User()->is_admin) {
                    $q->where('user_id', Auth::User()->id);
                }
            })
            ->where('id', $sid)
            ->with('product', 'user')
            ->with(['invoices' => function($q) {
                $q->orderBy('id', 'DESC');
            }])
            ->first();

        foreach (config('payments.countries') as $item) {
            if($item['code'] == $this->subscription->country_code) {
                $this->symbol = $item['symbol'];
            }
        }

        if ($this->subscription->stripe_subscription_id != NULL) {
            $this->is_stripe = true;
        } else {
            $this->is_razorpay = true;
        }
        if($this->subscription == NULL) {
            return abort(404);
        }
    }
    
    public function render()
    {
        return view('livewire.subscriptions.details');
    }

    public function upgradeSubscription()
    {
        $this->checkForUnpaidInvoices();
        if(!$this->pay_invoices_alert) {
            $price = Prices::where('product_id', $this->subscription->product_id)
                ->where('type', 2)
                ->where('country_code', $this->subscription->country_code)
                ->first();

            if ($this->is_stripe) {
                $old_price = Prices::where('product_id', $this->subscription->product_id)
                    ->where('type', 1)
                    ->where('country_code', $this->subscription->country_code)
                    ->first();

                $stripe = new StripeHelper();
                
                $stripe->upgradeSubscription($this->subscription->stripe_subscription_id, $old_price->stripe_price_id, $price->stripe_price_id);
            } else {
                $razorpay = new RazorpayHelper();
                
                $razorpay->upgradeSubscription($this->subscription->razorpay_subscription_id, $price->razorpay_price_id);
            }      

            $this->subscription->update([
                'plan_type' => 2,
                'amount' => $price->amount
            ]);
            $this->mount($this->subscription->id);
        }
    }

    public function cancelSubscription()
    {
        $this->checkForUnpaidInvoices();
        if(!$this->pay_invoices_alert) {

            if ($this->subscription->stripe_subscription_id != NULL) {
                $stripe = new StripeHelper();
                
                $stripe->cancelSubscription($this->subscription->stripe_subscription_id);

                $this->subscription->update([
                    'status' => 4,
                ]);                
            } else {
                $razorpay = new RazorpayHelper();
                
                $razorpay->cancelSubscription($this->subscription->razorpay_subscription_id);

                $this->subscription->update([
                    'status' => 4,
                ]);  
            }
            $this->mount($this->subscription->id);
        }
    }

    public function checkForUnpaidInvoices()
    {
        $this->pay_invoices_alert = false;
        if($this->is_stripe) {
            foreach ($this->subscription->invoices as $item) {
                if($item->status != 2) {
                    $this->pay_invoices_alert = true;
                    break;
                } 
            }
        } else {
            if($this->subscription->status != 2) {
                $this->pay_invoices_alert = true;
            }
        }
    }
}
