<?php

namespace App\Console\Commands\Stripe;

use Illuminate\Console\Command;
use App\Models\Invoice;
use App\Helpers\StripeHelper;
use App\Models\Subscription;
use App\Models\Order;

class UpdateInvoiceStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:update-invoice-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will update the status of all pending invoices from stripe.';

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
        $stripe = new StripeHelper();

        $subscriptions = Subscription::where('status', '<', 3)
            ->where('stripe_subscription_id', '!=', NULL)
            ->with(['invoices' => function ($q) {
                $q->where('status', 1);
            }])
            ->get();

        foreach ($subscriptions as $item) {
            $subscription = $stripe->fetchSubscription($item->stripe_subscription_id);

            if($subscription->status == 'incomplete_expired' || $subscription->status == 'canceled') {
                //cancelled
                $item->update([
                    'status' => 4,
                ]);
            } else if($subscription->status == 'active') {
                //active
                $item->update([
                    'status' => 2,
                ]);
            }
            
            foreach ($item->invoices as $item2) {
                $invoice = $stripe->fetchInvoice($item2->stripe_invoice_id);
                if($invoice->status == 'paid') {
                    $item2->update([
                        'status' => 2
                    ]);
                } else if($invoice->status == 'uncollectible') {
                    $item2->update([
                        'status' => 4
                    ]);
                }
            }
        }

        $orders = Order::where('status', 1)
            ->whereHas('invoice', function ($q) {
                $q->where('status', 1)
                ->where('stripe_invoice_id', '!=', NULL);
            })
            ->with('invoice')
            ->get();
        foreach ($orders as $item) {
            $invoice = $stripe->fetchInvoice($item->invoice->stripe_invoice_id);
            if($invoice->status == 'paid') {
                $item->update([
                    'status' => 2
                ]);
                $item->invoice->update([
                    'status' => 2
                ]);
            } else if($invoice->status == 'uncollectible') {
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
