<div>
    <section class="text-gray-700 body-font overflow-hidden bg-white">
        <div class="container px-5 py-24 mx-auto">
            <div class="lg:w-4/5 mx-auto flex flex-wrap">
                <img alt="ecommerce" class="lg:w-1/2 w-full object-cover object-center rounded border border-gray-200" src="{{ $product->image_url }}">
                <div class="lg:w-1/2 w-full lg:pl-10 lg:py-6 mt-6 lg:mt-0">
                    <h1 class="text-gray-900 text-3xl title-font font-medium mb-1">{{ $product->name }}</h1>
                    <p class="leading-relaxed">{{ $product->description }}</p>
                    <div class="flex mt-6 items-center pb-5 border-b-2 border-gray-200 mb-5">
                        @if ($product->type == 2)
                            <div class="flex">
                                <span class="mr-3">Subscription ends after 1 year.</span>
                            </div>
                        @endif
                        <div class="flex ml-6 items-center">
                            <span class="mr-3">Select Your Country</span>
                            <div class="relative">
                                <select wire:model="code" wire:change="setAmounts" class="rounded border appearance-none border-gray-400 py-2 focus:outline-none focus:border-red-500 text-base pl-3 pr-10">
                                    @foreach ($countries as $item)
                                        <option value="{{ $item['code'] }}">{{ $item['name'] }}</option> 
                                    @endforeach
                                </select>
                                <span class="absolute right-0 top-0 h-full w-10 text-center text-gray-600 pointer-events-none flex items-center justify-center">
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4" viewBox="0 0 24 24">
                                        <path d="M6 9l6 6 6-6"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex">
                        <span class="title-font font-medium text-2xl text-gray-900">
                            {{ $symbol.''.$price }}
                            @if ($product->type == 2)
                                per month
                            @endif
                        </span>
                        <button class="flex ml-auto text-white bg-red-500 border-0 py-2 px-6 focus:outline-none hover:bg-red-600 rounded" wire:click="showPaymentOptions">
                            @if ($product->type == 1)
                                Buy Now
                            @else
                                Buy Subscription                                
                            @endif
                        </button>
                    </div>
                    <div class="flex mt-5">
                        @if ($product->type == 1)
                            @if ($show_razorpay)
                                <a href="{{ route('buy-product.razorpay-payment', ['id' => $product->id, 'code' => $code]) }}" class="flex text-white bg-blue-500 border-0 py-2 px-6 focus:outline-none hover:bg-blue-600 rounded">
                                    Razorpay
                                </a>
                            @endif
                            @if ($show_stripe)
                                <a href="{{ route('buy-product.stripe-payment', ['id' => $product->id, 'code' => $code]) }}" class="flex ml-5 text-white bg-blue-500 border-0 py-2 px-6 focus:outline-none hover:bg-blue-600 rounded">
                                    Stripe
                                </a>
                            @endif
                        @else
                            @if ($show_razorpay)
                                <a href="{{ route('buy-product.razorpay-subscription', ['id' => $product->id, 'code' => $code]) }}" class="flex text-white bg-blue-500 border-0 py-2 px-6 focus:outline-none hover:bg-blue-600 rounded">
                                    Razorpay
                                </a>
                            @endif
                            @if ($show_stripe)
                                <a href="{{ route('buy-product.stripe-subscription', ['id' => $product->id, 'code' => $code]) }}" class="flex ml-5 text-white bg-blue-500 border-0 py-2 px-6 focus:outline-none hover:bg-blue-600 rounded">
                                    Stripe
                                </a>
                            @endif                           
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>