<?php

use Illuminate\Support\Facades\Route;
use Modules\Orders\Controllers\OrderApiController;

Route::prefix('order')->group(function () {
  Route::post('index',[OrderApiController::class,'index'])->name('index');
  
});

Route::prefix('cart')->group(function (){
  Route::post('addToCart',[OrderApiController::class,'addToCart'])->name('addToCart');
  Route::post('removeCart',[OrderApiController::class,'removeCart'])->name('removeCart');
  Route::post('showCart',[OrderApiController::class,'showCart'])->name('showCart');
  Route::Delete('removeFromCart/{product}',[OrderApiController::class,'removeFromCart'])->name('removeFromCart');
  Route::patch('edit/{product}',[OrderApiController::class,'editCart'])->name('editCart');

});