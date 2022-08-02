<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Livewire\Product\Index as ProductIndex;
use App\Http\Livewire\Product\Create as ProductCreate;
use App\Http\Livewire\Product\Details as ProductDetails;
use App\Http\Livewire\Subscriptions\Stripe as StripeSubscription;
use App\Http\Livewire\Subscriptions\Razorpay as RazorpaySubscription;
use App\Http\Livewire\Subscriptions\Index as SubscriptionIndex;
use App\Http\Livewire\Subscriptions\Details as SubscriptionDetails;
use App\Http\Livewire\Payments\Stripe as StripePayment;
use App\Http\Livewire\Payments\Razorpay as RazorpayPayment;
use App\Http\Livewire\Payments\Index as PaymentIndex;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'welcome'])->name('welcome');

Auth::routes();
Route::middleware(['auth'])->group(function () {
    Route::get('/home', ProductIndex::class)->name('home');

    Route::middleware(['isAdmin'])->group(function () {
        Route::get('/product/create', ProductCreate::class)->name('product.create');

    });

    Route::get('/product/details/{id}', ProductDetails::class)->name('product.details');

    //Subscription Routes...
    Route::get('/home/subscriptions', SubscriptionIndex::class)->name('home.subscriptions');
    Route::get('/home/subscriptions/{sid}/details', SubscriptionDetails::class)->name('home.subscriptions.details');

    Route::get('/buy-product/stripe-subscription/{id}/{code}', StripeSubscription::class)->name('buy-product.stripe-subscription');

    Route::get('/buy-product/razorpay-subscription/{id}/{code}', RazorpaySubscription::class)->name('buy-product.razorpay-subscription');
    Route::post('/buy-product/razorpay-subscription/payment', [RazorpaySubscription::class, 'save'])->name('buy-product.razorpay-subscription.payment');

    //Single Payment Routes...
    Route::get('/home/orders', PaymentIndex::class)->name('home.orders');

    Route::get('/buy-product/stripe-payment/{id}/{code}', StripePayment::class)->name('buy-product.stripe-payment');
    
    Route::get('/buy-product/razorpay-payment/{id}/{code}', RazorpayPayment::class)->name('buy-product.razorpay-payment');
});
