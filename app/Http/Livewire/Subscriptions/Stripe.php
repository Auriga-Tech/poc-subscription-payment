<?php

namespace App\Http\Livewire\Subscriptions;

use Livewire\Component;
use App\Models\Product;
use App\Models\Prices;
use App\Helpers\StripeHelper;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class Stripe extends Component
{
    protected $rules = [], $messages = [];

    public $product, $price, $card = [], $address_line = '', $postal_code = '', $city = '', $state = '', $country = '';

    public function mount($id, $code) 
    {
        $this->product = Product::where('id', $id)
            ->first();
        $this->price = Prices::where('product_id', $id)
            ->where('country_code', $code)
            ->first();
        $stripe = new StripeHelper();
        $stripe->registerCustomer();
        $this->card = [
            'number' => '',
            'exp_month' => '',
            'exp_year' => '',
            'cvc' => '',
        ];
    }

    public function render()
    {
        return view('livewire.subscriptions.stripe');
    }

    public function save()
    {
        $this->changeValidation();
        $this->validate($this->rules);

        $this->updateBillingDetails();

        $stripe = new StripeHelper();
        $paymentMethod = $stripe->createPaymentMethod($this->card['number'], $this->card['exp_month'], $this->card['exp_year'], $this->card['cvc']);
        $stripe->attachPaymentMethod($paymentMethod->id, Auth::User()->stripe_customer_id);
        
        $subscription = $stripe->createSubscription(Auth::User()->stripe_customer_id, $this->price->stripe_price_id, $paymentMethod->id, $this->price->country_code);

        $invoice = $stripe->fetchInvoice($subscription->latest_invoice);

        $subscription_data = [
            'user_id' => Auth::User()->id,
            'product_id' => $this->product->id,
            'stripe_subscription_id' => $subscription->id,
            'amount' => $this->price->amount,
            'country_code' => $this->price->country_code,
            'status' => 1,
            'expired_at' => Carbon::now()->addYears(1)->toDateString()
        ];

        $subscription = Subscription::create($subscription_data);

        $invoice_data = [
            'subscription_id' => $subscription->id,
            'stripe_invoice_id' => $invoice->id,
            'invoice_number' => $invoice->number,
            'invoice_url' => $invoice->hosted_invoice_url,
            'status' => 1
        ];
        Invoice::create($invoice_data);

        Session::flash('message', 'Subscription Created Successfully. Please pay your invoice.'); 
        Session::flash('alert-class', 'blue'); 

        return redirect()->route(['home.subscriptions.details', ['sid' => $subscription->id]]);
    }

    public function changeValidation()
    {
        $this->rules = [
            'card.number' => 'required|digits:16',
            'card.cvc' => 'required|string|min:3',
            'card.exp_month' => 'required|numeric|gt:0|lt:13',
            'card.exp_year' => 'required|string|min:4|max:4',
        ];
        if(Auth::User()->address_line == NULL) {
            $this->rules['address_line'] = 'required|string|min:5';
            $this->rules['postal_code'] = 'required|string|min:5';
            $this->rules['city'] = 'required|string';
            $this->rules['state'] = 'required|string';
            $this->rules['country'] = 'required|string|min:2|max:2';
            $this->messages['country.min'] = 'Please enter your 2 letter country code.';
            $this->messages['country.max'] = 'Please enter your 2 letter country code.';
        }
    }

    public function updateBillingDetails()
    {
        if(Auth::User()->address_line == NULL) {
            $stripe = new StripeHelper();
            $stripe->updateBillingDetails(Auth::User()->stripe_customer_id, $this->address_line, $this->postal_code, $this->city, $this->state, $this->country);

            User::where('id', Auth::User()->id)
                ->update([
                    'address_line' => $this->address_line,
                    'postal_code' => $this->postal_code,
                    'city' => $this->city,
                    'state' => $this->state,
                    'country' => $this->country,
                ]);
        }
    }
}
