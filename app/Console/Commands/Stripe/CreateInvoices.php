<?php

namespace App\Console\Commands\Stripe;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Helpers\StripeHelper;
use App\Models\Invoice;

class CreateInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:create-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will create all the latest invoices from stripe for a subscription.';

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
        $subscripions = Subscription::where('status', '<', 3)
            ->where('stripe_subscription_id', '!=', NULL)
            ->get();
        $stripe = new StripeHelper();
        foreach ($subscripions as $item) {
            $latest_invoice = $stripe->fetchSubscription($item->stripe_subscription_id)->latest_invoice;
            if (Invoice::where('subscription_id', $item->id)->where('stripe_invoice_id', $latest_invoice)->count() == 0) {
                $invoice = $stripe->fetchInvoice($latest_invoice);
    
                $invoice_data = [
                    'subscription_id' => $item->id,
                    'stripe_invoice_id' => $invoice->id,
                    'invoice_number' => $invoice->number,
                    'invoice_url' => $invoice->hosted_invoice_url,
                    'status' => 1
                ];
                Invoice::create($invoice_data);
            }
        }
    }
}
