<div>
    <div class="bg-white">
        <div class="max-w-2xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:max-w-7xl lg:px-8">
            <p class="w-75 text-md font-medium text-gray-900 m-3">
                Subscription Amount: {{ $symbol.''.$subscription->amount }}<br>
                @if ($subscription->status < 3)
                    End Date: {{ \Carbon\Carbon::parse($subscription->created_at)->addYear()->toDateString() }}<br>
                @endif
                Status: {!! $subscription->status_class !!}<br>
                Plan Type: {!! $subscription->plan_type_class !!}<br>
            </p>
            @if ($subscription->status < 3)
                @if ($subscription->plan_type == 1)
                    <button wire:click="upgradeSubscription" class="w-25 float-right inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Upgrade Subscription
                    </button>
                @endif
                <button wire:click="cancelSubscription" class="w-25 float-right inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel Subscription
                </button><br>
            @endif

            @if ($is_stripe)
                @if ($pay_invoices_alert)
                    <br>
                    <div class="text-white px-6 py-4 border-0 rounded relative mb-4 bg-red-500">
                        <span class="text-xl inline-block mr-5 align-middle">
                            <i class="fas fa-bell"></i>
                        </span>
                        <span class="inline-block align-middle mr-8">
                            Please pay all invoices before upgrading / canceling the subscription.
                        </span>
                        <button class="absolute bg-transparent text-2xl font-semibold leading-none right-0 top-0 mt-4 mr-6 outline-none focus:outline-none" onclick="closeAlert(event)">
                            <span>×</span>          
                        </button>
                    </div>
                @endif
                
                <div class="mt-6 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-3 xl:gap-x-8">
                    @foreach ($subscription->invoices as $item)
                        <div class="p-6 max-w-sm bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Invoice No. : {{ $item->invoice_number }}</h5>
                            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                                @if ($item->paid_at != NULL)
                                    Paid At: {{ $item->paid_at }} <br>
                                @endif
                                Status: {!! $item->status_class !!}
                            </p>
                            <a href="{{ $item->invoice_url }}" class="inline-flex items-center py-2 px-3 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" target="_blank">
                                @if ($item->status == 1)
                                    Pay Now
                                @else
                                    See More
                                @endif
                            </a>
                        </div>
                    @endforeach
                </div>                
            @else
                @if ($pay_invoices_alert)
                    <br>
                    <div class="text-white px-6 py-4 border-0 rounded relative mb-4 bg-red-500">
                        <span class="text-xl inline-block mr-5 align-middle">
                            <i class="fas fa-bell"></i>
                        </span>
                        <span class="inline-block align-middle mr-8">
                            Your Subscription is not active yet. Please activate it.
                        </span>
                        <button class="absolute bg-transparent text-2xl font-semibold leading-none right-0 top-0 mt-4 mr-6 outline-none focus:outline-none" onclick="closeAlert(event)">
                            <span>×</span>          
                        </button>
                    </div>
                @endif
                <a href="{{ $subscription->subscription_url }}" class="inline-flex items-center py-2 px-3 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" target="_blank">
                    @if ($subscription->status == 1)
                        Pay Charge
                    @elseif ($subscription->status == 5)
                        Update Payment Options
                    @else
                        See More
                    @endif
                </a>
            @endif
        </div>
    </div>  
</div>
