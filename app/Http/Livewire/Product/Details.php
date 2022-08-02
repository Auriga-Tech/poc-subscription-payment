<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use App\Models\Product;
use App\Models\Prices;
use Illuminate\Support\Facades\Auth;

class Details extends Component
{
    public $product, $symbol = '', $price = '', $countries = [], $code = '', $show_razorpay = false, $show_stripe = false;

    public function mount($id)
    {
        $this->product = Product::where('id', $id)
            ->first();
        $this->code = $this->product->country_code;
        $this->setAmounts();
    }

    public function render()
    {
        return view('livewire.product.details');
    }

    public function setAmounts()
    {
        $this->countries = [];
        foreach (config('payments.countries') as $item) {
            $this->countries[] = [
                'name' => $item['name'],
                'code' => $item['code']
            ];
            if($item['code'] == $this->code) {
                $this->symbol = $item['symbol'];
                $this->price = Prices::where('country_code', $this->code)
                    ->where('product_id', $this->product->id)
                    ->where('type', 1)
                    ->first()
                    ->amount;
            }
        }

        $this->show_razorpay = false;
        $this->show_stripe = false;
    }

    public function showPaymentOptions()
    {
        if ($this->product->type == 1) {
            if(!Auth::User()->has_subscriptions) {
                $this->show_stripe = true;
            }
            if($this->code == 'INR') {
                $this->show_razorpay = true;
            }
        } else {
            if($this->code == 'INR') {
                $this->show_razorpay = true;
            }
            $this->show_stripe = true;
        }        
    }
}
