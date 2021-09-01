<?php

use Illuminate\Support\Facades\Route;
use Modules\Products\Controllers\ProductApiController;

Route::prefix('product')->group(function () {
  Route::post('create', [ProductApiController::class,'create'])->name('create');
  Route::patch('edit/{product}', [ProductApiController::class,'edit'])->name('edit');
  Route::delete('delete/{product}', [ProductApiController::class,'delete'])->name('delete');
  Route::patch('restore/{id}',[ProductApiController::class,'restore'])->name('restore');
  Route::patch('show/{product}',[ProductApiController::class,'show'])->name('show');
  Route::post('index',[ProductApiController::class,'index'])->name('index');

});