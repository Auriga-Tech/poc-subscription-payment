<?php

namespace App\Console\Commands\Razorpay;

use Illuminate\Console\Command;
use App\Models\Invoice;
use App\Helpers\RazorpayHelper;
use App\Models\Subscription;
use App\Models\Order;

class UpdateInvoiceStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'razorpay:update-invoice-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will update the status of all pending invoices from razorpay.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $razorpay = new RazorpayHelper();
        
        $subscriptions = Subscription::where('status', '!=', 3)
            ->where('status', '!=', 4)
            ->where('razorpay_subscription_id', '!=', NULL)
            ->get();

        foreach ($subscriptions as $item) {
            $subscription = $razorpay->fetchSubscription($item->razorpay_subscription_id);

            if($subscription->status == 'expired' || $subscription->status == 'cancelled') {
                //cancelled
                $item->update([
                    'status' => 4,
                    'subscription_url' => $subscription->short_url
                ]);
            } else if($subscription->status == 'active' || $subscription->status == 'authenticated') {
                //active
                $item->update([
                    'status' => 2,
                    'subscription_url' => $subscription->short_url
                ]);
            } else if($subscription->status == 'halted') {
                //hold
                $item->update([
                    'status' => 5,
                    'subscription_url' => $subscription->short_url
                ]);
            } else if($subscription->status == 'completed') {
                //completed
                $item->update([
                    'status' => 3,
                    'subscription_url' => $subscription->short_url
                ]);
            }
        }

        $orders = Order::where('status', 1)
            ->whereHas('invoice', function ($q) {
                $q->where('status', 1)
                ->where('razorpay_invoice_id', '!=', NULL);
            })
            ->with('invoice')
            ->get();
            
        foreach ($orders as $item) {
            $invoice = $razorpay->fetchInvoice($item->invoice->razorpay_invoice_id);
            if($invoice->status == 'paid' || $invoice->status == 'partially_paid') {
                $item->update([
                    'status' => 2
                ]);
                $item->invoice->update([
                    'status' => 2
                ]);
            } else if($invoice->status == 'cancelled' || $invoice->status == 'expired' || $invoice->status == 'deleted') {
                $item->update([
                    'status' => 3
                ]);
                $item->invoice->update([
                    'status' => 4
                ]);
            }
        }
    }
}
