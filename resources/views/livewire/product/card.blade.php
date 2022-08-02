<div>
    <div class="group relative">
        <div class="w-full min-h-80 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75 lg:h-80 lg:aspect-none">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-center object-cover lg:w-full lg:h-full">
        </div>
        <div class="mt-4 flex justify-between">
            <div>
                <h3 class="text-sm text-gray-700">
                    <a href="{{ route('product.details', ['id' => $product->id]) }}">
                        <span aria-hidden="true" class="absolute inset-0"></span>
                        {{ mb_strimwidth($product->name, 0, 30, "...") }}
                    </a>
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    {{ mb_strimwidth($product->description, 0, 30, "...") }}
                </p>
            </div>
            <p class="text-sm font-medium text-gray-900">
                {{ $product->country_code.' '.$product->basic_amount }}
            </p>
        </div>
    </div>
</div>
