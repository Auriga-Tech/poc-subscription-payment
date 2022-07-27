<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use App\Models\Product;

class Index extends Component
{
    protected $products;
    public function render()
    {
        $this->products = Product::all();
        return view('livewire.product.index', [
            'products' => $this->products
        ]);
    }
}
