<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\Prices;
use Illuminate\Support\Facades\Session;

class Create extends Component
{
    use WithFileUploads;
    public $product_name = '', $product_image = '', $product_description = '', $product_type = 1, $prices = [], $basic_amount = 0, $expiry_date = '';

    protected $rules = [], $messages = [];

    public function mount()
    {
        foreach (config('payments.countries') as $key => $item) {
            $this->prices['basic'][$key] = [
                'code' => $item['code'],
                'price' => ''
            ];
            $this->prices['pro'][$key] = [
                'code' => $item['code'],
                'price' => ''
            ];
        }
    }

    public function render()
    {
        return view('livewire.product.create');
    }

    public function save()
    {
        $this->changeValidation();
        $this->validate($this->rules, $this->messages);

        $product_data = [
            'name' => $this->product_name,
            'image' => $this->product_image->store('Products'),
            'description' => $this->product_description,
            'type' => $this->product_type,
            'expiry_date' => $this->expiry_date,
            'basic_amount' => $this->prices['basic'][$this->basic_amount]['price'],
            'country_code' => $this->prices['basic'][$this->basic_amount]['code'],
        ];
        $product = Product::create($product_data);

        foreach ($this->prices['basic'] as $key => $item) {
            $price_data = [
                'product_id' => $product->id,
                'type' => 1,
                'amount' => $item['price'],
                'country_code' => $item['code'],
            ];
            Prices::create($price_data);
        }

        if($this->product_type == 2) {
            foreach ($this->prices['pro'] as $key => $item) {
                $price_data = [
                    'product_id' => $product->id,
                    'type' => 1,
                    'amount' => $item['price'],
                    'country_code' => $item['code'],
                ];
                Prices::create($price_data);
            }
        }

        Session::flash('message', 'Product Created Successfully!'); 
        Session::flash('alert-class', 'blue'); 
        return redirect()->route('home');
    }

    public function changeValidation()
    {
        $this->rules = [
            'product_name' => 'required|string|min:6',
            'product_image' => 'required|mimes:jpeg,jpg,png',
            'product_description' => 'required|string|min:10',
            'prices.basic.*.price' => 'required|numeric|gt:0',
            'expiry_date' => 'required|numeric|gt:0'
        ];
        if($this->product_type == 2) {
            $this->rules['prices.pro.*.price'] = 'required|numeric|gt:0';
        }
        $this->messages = [
            'prices.*.*.price.required' => 'Please add price for this country.',
            'prices.*.*.price.numeric' => 'Please add a numeric value for price.',
            'prices.*.*.price.gt' => 'Please add a price greater than 0.',
        ];
    }
}
