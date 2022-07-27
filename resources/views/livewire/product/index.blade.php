<div>
    <div class="bg-white">
        <div class="max-w-2xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:max-w-7xl lg:px-8">
            @if (Auth::User()->is_admin)
                <a href="{{ route('product.create') }}" class="w-25 float-right inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Add Product
                </a>
            @endif
            
            <div class="mt-6 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                @foreach ($products as $item)
                    @livewire('product.card', ['product' => $item], key($item->id))
                @endforeach
        
                <!-- More products... -->
            </div>
        </div>
    </div>  
</div>
