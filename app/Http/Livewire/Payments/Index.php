<?php

namespace App\Http\Livewire\Payments;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $orders;

    public function mount()
    {
        $this->orders = Order::where(function($q) {
            if(!Auth::User()->is_admin) {
                $q->where('user_id', Auth::User()->id);
            }
        })
        ->with('product', 'user', 'invoice')
        ->get();
    }

    public function render()
    {
        return view('livewire.payments.index');
    }
}
