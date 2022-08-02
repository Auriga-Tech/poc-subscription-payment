<div>    
    <section class="text-gray-700 body-font overflow-hidden bg-white">
        <div class="container px-5 py-24 mx-auto">
            <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                @if (count($subscriptions) > 0)
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="py-3 px-6">
                                    Subscription ID
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    Product name
                                </th>
                                @if (Auth::User()->is_admin)
                                    <th scope="col" class="py-3 px-6">
                                        User
                                    </th>
                                @endif
                                <th scope="col" class="py-3 px-6">
                                    Plan Type
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    Price
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    Status
                                </th>
                                <th scope="col" class="py-3 px-6"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subscriptions as $item)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="py-4 px-6">
                                        {{ '#'.$item->id }}
                                    </td>
                                    <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <a href="{{ route('product.details', ['id' => $item->product->id]) }}">
                                            {{ mb_strimwidth($item->product->name, 0, 30, "...") }}
                                        </a>
                                    </th>
                                    @if (Auth::User()->is_admin)
                                        <td class="py-4 px-6">
                                            {{ $item->user->name }}
                                        </td>
                                    @endif
                                    <td class="py-4 px-6">
                                        {!! $item->plan_type_class !!}
                                    </td>
                                    <td class="py-4 px-6">
                                        {{ $item->country_code.' '.$item->amount }}
                                    </td>
                                    <td class="py-4 px-6">
                                        {!! $item->status_class !!}
                                    </td>
                                    <td class="py-4 px-6">
                                        <a href="{{ route('home.subscriptions.details', ['sid' => $item->id]) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                            Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-4xl dark:text-white p mb-5">No subscriptions found.</p>
                @endif
            </div>
        </div>
    </section>
</div>
