<div>
    <div class="bg-white">
        <div class="max-w-2xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:max-w-7xl lg:px-8">
            <p class="text-4xl dark:text-white mb-5">Add Product</p>
            <form wire:submit.prevent="save">
                <div class="relative z-0 mb-6 w-full group">
                    <input type="text" wire:model="product_name" id="product_name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" "/>
                    <label for="product_name" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Product Name</label>
                    @error('product_name') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="relative z-0 mb-6 w-full group">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300" for="product_image">Product Image</label>
                    <input class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="product_image_help" id="product_image" wire:model="product_image" type="file">
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="product_image_help">Product image can be a jpg, jpeg or png</div>
                    @error('product_image') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="relative z-0 mb-6 w-full group">
                    <textarea wire:model="product_description" id="product_description" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" "></textarea>
                    <label for="product_description" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-10 scale-75 top-8 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-10">Product Description</label>
                    @error('product_description') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="relative z-0 mb-6 w-full group">
                    <label class="mb-5 text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0]">Product Type</label>
                    <div class="flex">
                        <div class="flex items-center mr-4">
                            <input id="inline-radio" type="radio" value="1" checked wire:model="product_type" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="inline-radio" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Single Item</label>
                        </div>
                        <div class="flex items-center mr-4">
                            <input id="inline-2-radio" type="radio" value="2" wire:model="product_type" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="inline-2-radio" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Subscription Item</label>
                        </div>
                    </div>
                </div>
                <p class="text-4xl dark:text-white mb-5">Basic Plan</p>
                <div class="grid gap-4 mb-6 md:grid-cols-3">
                    @foreach ($prices['basic'] as $key => $item)
                        <div class="relative z-0 mb-6 w-full group">
                            <input type="text" name="item['code']" value="{{ $item['code'] }}" id="country_code-{{ $key }}" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required disabled/>
                            <label for="country_code-{{ $key }}" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Country Code</label>
                        </div>
                        <div class="relative z-0 mb-6 w-full group">
                            <input type="text" wire:model="prices.basic.{{ $key }}.price" id="product_price-{{ $key }}" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" "/>
                            <label for="product_price-{{ $key }}" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Product Price</label>
                            @error('prices.basic.'.$key.'.price') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="relative z-0 mb-6 w-full group">
                            <input id="inline-radio-{{ $key }}" type="radio" value="{{ $key }}" wire:model="basic_amount" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="inline-radio-{{ $key }}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Set as Basic</label>
                        </div>
                    @endforeach
                </div>
                @if ($product_type == 2)
                    <p class="text-4xl dark:text-white mb-5">Pro Plan</p>
                    <div class="grid gap-6 mb-6 md:grid-cols-2">
                        @foreach ($prices['pro'] as $key => $item)
                            <div class="relative z-0 mb-6 w-full group">
                                <input type="text" name="item['code']" value="{{ $item['code'] }}" id="country_code-pro-{{ $key }}" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required disabled/>
                                <label for="country_code-{{ $key }}" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Country Code</label>
                            </div>
                            <div class="relative z-0 mb-6 w-full group">
                                <input type="text" wire:model="prices.pro.{{ $key }}.price" id="product_price-pro-{{ $key }}" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" "/>
                                <label for="product_price-pro-{{ $key }}" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Product Price</label>
                                @error('prices.pro.'.$key.'.price') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="relative z-0 mb-6 w-full group">
                    <input type="text" wire:model="expiry_date" id="expiry_date" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" "/>
                    <label for="expiry_date" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Product Expiry Duration</label>
                    @error('expiry_date') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="w-25 float-right inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Submit
                </button>
            </form>
        </div>
    </div>
</div>
