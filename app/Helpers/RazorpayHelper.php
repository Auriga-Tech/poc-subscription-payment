<?php

namespace App\Helpers;

use Razorpay\Api\Api;
use Carbon\Carbon;


class RazorpayHelper {
    private $key = '', $secret = '';

    public function __construct()
    {
        $this->key = config('services.razorpay.key');
        $this->secret = config('services.razorpay.secret');
    }

    public function createPlan($name, $amount, $currency) 
    {
        $api = new Api($this->key, $this->secret);
        $response = $api->plan->create(
            array(
                'period' => 'monthly', 
                'interval' => 1, 
                'item' => [
                    'name' => $name, 
                    'amount' => ($amount*100), 
                    'currency' => $currency
                ],
                'notes'=> []
            )
        );
        return $response;
    }

    public function createSubscription($plan_id)
    {
        $api = new Api($this->key, $this->secret);

        $response = $api->subscription->create(
            [
                'plan_id' => $plan_id, 
                'customer_notify' => 1,
                'quantity' => 1, 
                'total_count' => 12, 
                'start_at' => Carbon::now()->addMinutes(5)->timestamp, 
                'expire_by' => Carbon::now()->addYears(1)->timestamp,
                'addons' => [],
                'notes'=> []
            ]
        );
        return $response;
    }

    public function fetchSubscription($subscription_id)
    {
        $api = new Api($this->key, $this->secret);

        $response = $api->subscription->fetch($subscription_id);

        return $response;
    }

    public function upgradeSubscription($subscription_id, $plan_id)
    {
        $api = new Api($this->key, $this->secret);

        $response = $api->subscription->fetch($subscription_id)->update([
            'plan_id' => $plan_id,
            'customer_notify' => 1
        ]);
        return $response;
    }

    public function cancelSubscription($subscription_id)
    {
        $api = new Api($this->key, $this->secret);

        $response = $api->subscription->fetch($subscription_id)->cancel([
            'cancel_at_cycle_end' => 1
        ]);
        return $response;
    }

    public function createInvoice($user_name, $user_email, $product_name, $amount, $code)
    {
        $api = new Api($this->key, $this->secret);
        
        $response = $api->invoice->create([
            'type' => 'invoice',
            'date' => Carbon::now()->addMinute()->timestamp, 
            'customer' => [
                'name' => $user_name,
                'email' => $user_email,
            ],
            'line_items' => [
                [
                    'name' => $product_name,
                    'amount' => $amount*100,
                    'currency' => $code,
                    'quantity' => 1
                ]
            ],
            'email_notify' => 1,
            'currency' => $code,
        ]);
        return $response;
    }

    public function issueInvoice($invoice_id)
    {
        $api = new Api($this->key, $this->secret);
        
        $response = $api->invoice->fetch($invoice_id)->issue();
        return $response;
    }

    public function fetchInvoice($invoice_id)
    {
        $api = new Api($this->key, $this->secret);
        
        $response = $api->invoice->fetch($invoice_id);
        return $response;
    }
}