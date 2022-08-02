<?php

namespace App\Http\Livewire\Subscriptions;

use Livewire\Component;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $subscriptions;

    public function mount()
    {
        $this->subscriptions = Subscription::where(function($q) {
            if(!Auth::User()->is_admin) {
                $q->where('user_id', Auth::User()->id);
            }
        })
        ->with('product', 'user')
        ->get();
    }
    
    public function render()
    {
        return view('livewire.subscriptions.index');
    }
}
