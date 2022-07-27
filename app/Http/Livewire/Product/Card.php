<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;

class Card extends Component
{
    public $product;
    public function render()
    {
        return view('livewire.product.card');
    }
}
