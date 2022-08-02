<?php

namespace App\Http\Livewire\Payments;

use Livewire\Component;
use App\Models\Product;
use App\Models\Prices;
use App\Helpers\RazorpayHelper;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Order;
use App\Models\Invoice;

class Razorpay extends Component
{
    public $product, $price;

    public function mount($id, $code) 
    {
        $this->product = Product::where('id', $id)
            ->first();
        $this->price = Prices::where('product_id', $id)
            ->where('country_code', $code)
            ->first();
        $razorpay = new RazorpayHelper();
        $invoice = $razorpay->createInvoice(Auth::User()->name, Auth::User()->email, $this->product->name, $this->price->amount, $code);

        // $invoice = $razorpay->issueInvoice($invoice->id);

        $order_data = [
            'user_id' => Auth::User()->id,
            'product_id' => $this->product->id,
            'amount' => $this->price->amount,
            'country_code' => $this->price->country_code,
            'status' => 1
        ];
        $order = Order::create($order_data);

        $invoice_data = [
            'order_id' => $order->id,
            'razorpay_invoice_id' => $invoice->id,
            'invoice_number' => Carbon::now()->timestamp,
            'invoice_url' => $invoice->short_url,
            'status' => 1
        ];
        Invoice::create($invoice_data);

        Session::flash('message', 'Order Created Successfully!'); 
        Session::flash('alert-class', 'blue'); 
        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.payments.razorpay');
    }
}
