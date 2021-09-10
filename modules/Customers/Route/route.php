<?php

use Illuminate\Support\Facades\Route;
use Modules\Customers\Controllers\CustomerApiController;

Route::post('/customer/mailLogin', [CustomerApiController::class, 'loginByMail'])->name('mailLogin');
Route::post('/customer/mailRegister', [CustomerApiController::class, 'registerByMail'])->name('mailRegister');

Route::prefix('customer')->group(function () {
    Route::post('create', [CustomerApiController::class, 'create'])->name('create');
    Route::post('index', [CustomerApiController::class, 'index'])->name('index');
    Route::patch('show/{customer}', [CustomerApiController::class, 'show'])->name('show');
    Route::post('profile', [CustomerApiController::class, 'profile'])->name('profile');
    Route::post('updateProfile', [CustomerApiController::class, 'updateProfile'])->name('updateProfile');
    Route::post('logout', [CustomerApiController::class, 'logout'])->name('logout');
    Route::delete('delete/{customer}', [CustomerApiController::class, 'delete'])->name('delete');
    Route::patch('restore/{id}', [CustomerApiController::class, 'restore'])->name('restore');
    Route::get('show/{customer}', [CustomerApiController::class, 'show'])->name('show');
    Route::patch('edit/{customer}', [CustomerApiController::class, 'edit'])->name('edit');
});
