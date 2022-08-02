<?php

namespace App\Http\Livewire\Subscriptions;

use Livewire\Component;
use App\Models\Product;
use App\Models\Prices;
use Illuminate\Http\Request;
use App\Helpers\RazorpayHelper;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class Razorpay extends Component
{
    public $product, $price, $subscription_id;

    public function mount($id, $code) 
    {
        $this->product = Product::where('id', $id)
            ->first();
        $this->price = Prices::where('product_id', $id)
            ->where('country_code', $code)
            ->first();
        $razorpay = new RazorpayHelper();

        $subscription = $razorpay->createSubscription($this->price->razorpay_price_id);

        $this->subscription_id = $subscription->id;

        $subscription_data = [
            'user_id' => Auth::User()->id,
            'product_id' => $this->product->id,
            'razorpay_subscription_id' => $this->subscription_id,
            'amount' => $this->price->amount,
            'country_code' => $this->price->country_code,
            'subscription_url' => $subscription->short_url,    
            'status' => 1,
            'expired_at' => Carbon::now()->addYears(1)->toDateString()
        ];

        Subscription::create($subscription_data);
    }

    public function render()
    {
        return view('livewire.subscriptions.razorpay');
    }

    public function save(Request $request)
    {
        $subscription = Subscription::where('razorpay_subscription_id', $request->razorpay_subscription_id)
            ->first();
            
        $razorpay = new RazorpayHelper();

        $response = $razorpay->fetchSubscription($request->razorpay_subscription_id);

        if($response->status == 'active' || $response->status == 'authenticated') {
            $subscription->update([
                'status' => 2
            ]);
            Session::flash('message', 'Subscription Activated Successfully!'); 
            Session::flash('alert-class', 'blue'); 
        } else {
            Session::flash('message', 'Subscription failed to activate'); 
            Session::flash('alert-class', 'red'); 
        }

        return redirect()->route(['home.subscriptions.details', ['sid' => $subscription->id]]);

    }
}
