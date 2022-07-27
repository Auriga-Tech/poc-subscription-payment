<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Livewire\Product\Index as ProductIndex;
use App\Http\Livewire\Product\Create as ProductCreate;
use App\Http\Livewire\Product\Details as ProductDetails;

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

});
