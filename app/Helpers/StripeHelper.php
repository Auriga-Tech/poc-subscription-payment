<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class StripeHelper {
    private $key = '', $secret = '';

    public function __construct()
    {
        $this->key = config('services.stripe.key');
        $this->secret = config('services.stripe.secret');
    }

    public function registerCustomer()
    {
        if(Auth::User()->stripe_customer_id == NULL) {
            $user = Auth::User();
            $stripe = new \Stripe\StripeClient($this->secret);
            $response = $stripe->customers->create([
                'name' => $user->name,
                'email' => $user->email,
            ]);
            $user->update([
                'stripe_customer_id' => $response->id
            ]);
        }
    }

    public function updateBillingDetails($customer_id, $address_line, $postal_code, $city, $state, $country)
    {
        $stripe = new \Stripe\StripeClient($this->secret);
        $response = $stripe->customers->update(
            $customer_id,
            [ 
                'address' => [
                    'line1' => $address_line,
                    'postal_code' => $postal_code,
                    'city' => $city,
                    'state' => $state,
                    'country' => $country,
                ]
            ]
        );
        return $response;
    }

    public function createPaymentMethod($number, $exp_month, $exp_year, $cvc)
    {
        $stripe = new \Stripe\StripeClient($this->secret);
        $response = $stripe->paymentMethods->create([
            'type' => 'card',
            'card' => [
                'number' => $number,
                'exp_month' => $exp_month,
                'exp_year' => $exp_year,
                'cvc' => $cvc,
            ],
        ]);
        return $response;
    }

    public function attachPaymentMethod($payment_method, $customer_id)
    {
        $stripe = new \Stripe\StripeClient($this->secret);
        $response = $stripe->paymentMethods->attach(
            $payment_method,
            ['customer' => $customer_id]
        );
        return $response;
    }

    public function createProduct($name)
    {
        $stripe = new \Stripe\StripeClient($this->secret);
        $response = $stripe->products->create([
            'name' => $name,
        ]);
        return $response;
    }

    public function createPrice($amount, $currency, $product_id, $nickname, $recurring = false)
    {
        $stripe = new \Stripe\StripeClient($this->secret);
        $price_data = [
            'unit_amount' => $amount*100,
            'currency' => strtolower($currency),
            'product' => $product_id,
            'nickname' => $nickname
        ];
        if($recurring) {
            $price_data['recurring'] = ['interval' => 'month'];
            $price_data['billing_scheme'] = 'per_unit';
        }
        $response = $stripe->prices->create($price_data);
        return $response;
    }

    public function createSubscription($customer_id, $price_id, $payment_method, $code)
    {
        $stripe = new \Stripe\StripeClient($this->secret);
        $response = $stripe->subscriptions->create([
            'customer' => $customer_id,
            'items' => [
                ['price' => $price_id],
            ],
            'currency' => $code,
            'default_payment_method' => $payment_method,
            'payment_behavior' => 'allow_incomplete',
            'cancel_at' => Carbon::now()->addYears(1)->timestamp
        ]);
        return $response;
    }

    public function fetchSubscription($subscription_id)
    {
        $stripe = new \Stripe\StripeClient($this->secret);
        $response = $stripe->subscriptions->retrieve(
            $subscription_id,
            []
        );
        return $response;
    }

    public function upgradeSubscription($subscription_id, $old_price_id,  $price_id)
    {
        $stripe = new \Stripe\StripeClient($this->secret);

        $old_item_id = $this->fetchSubscriptionItem($subscription_id, $old_price_id);
        $response = $stripe->subscriptions->update(
            $subscription_id,
            [
                'items' => [
                    [
                        'id' => $old_item_id,
                        'deleted' => true
                    ],
                    [
                        'price' => $price_id
                    ],
                ]
            ]
        );
        return $response;
    }

    public function cancelSubscription($subscription_id)
    {
        $stripe = new \Stripe\StripeClient($this->secret);
        $response = $stripe->subscriptions->cancel(
            $subscription_id,
            []
        );
        return $response;
    }

    public function fetchSubscriptionItem($subscription_id, $old_price_id)
    {
        $subscription = $this->fetchSubscription($subscription_id);
        $item_id = '';

        foreach($subscription->items->data as $item) {
            if($item->plan->id == $old_price_id) {
                $item_id = $item->id;
            }
        }
        return $item_id;
    }

    public function createInvoice($customer_id, $price_id, $payment_method, $code)
    {
        $this->createInvoiceItem($customer_id, $price_id, $code);

        $stripe = new \Stripe\StripeClient($this->secret);
        $response = $stripe->invoices->create([
            'customer' => $customer_id,
            'auto_advance' => false,
            'collection_method' => 'charge_automatically',
            'default_payment_method' => $payment_method,
        ]);

        return $response;
    }

    public function createInvoiceItem($customer_id, $price_id, $code)
    {
        $stripe = new \Stripe\StripeClient($this->secret);
        $response = $stripe->invoiceItems->create([
            'customer' => $customer_id,
            'price' => $price_id,
            'currency' => strtolower($code),
        ]);
        return $response;
    }

    public function finalizeInvoice($invoice_id)
    {
        $stripe = new \Stripe\StripeClient($this->secret);
        $response = $stripe->invoices->finalizeInvoice(
            $invoice_id,
            []
        );
        return $response;
    }

    public function fetchInvoice($invoice_id)
    {
        $stripe = new \Stripe\StripeClient($this->secret);
        $response = $stripe->invoices->retrieve(
            $invoice_id,
            []
        );
        return $response;
    }
}