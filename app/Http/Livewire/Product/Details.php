<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use App\Models\Product;
use App\Models\Prices;

class Details extends Component
{
    public $product, $symbol = '', $price = '', $countries = [], $code = '';

    public function mount($id)
    {
        $this->product = Product::where('id', $id)
            ->with('prices')
            ->first();
        $this->code = $this->product->country_code;
        $this->setAmounts();
    }

    public function render()
    {
        return view('livewire.product.details');
    }

    public function setAmounts()
    {
        $this->countries = [];
        foreach (config('payments.countries') as $item) {
            $this->countries[] = [
                'name' => $item['name'],
                'code' => $item['code']
            ];
            if($item['code'] == $this->code) {
                $this->symbol = $item['symbol'];
                $this->price = Prices::where('country_code', $this->code)
                    ->where('product_id', $this->product->id)
                    ->first()
                    ->amount;
            }
        }

    }
}
