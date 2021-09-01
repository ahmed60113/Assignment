<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Controllers\AdminApiController;
use App\Http\Middleware\AuthJWT;

Route::post('/admin/login',[AdminApiController::class,'login'])->name('login');

Route::prefix('admin')->group(function () {
   Route::post('create',[AdminApiController::class, 'create'])->name('create');
   Route::post('index',[AdminApiController::class, 'index'])->name('index');
   Route::patch('show/{admin}',[AdminApiController::class, 'show'])->name('show');
   Route::post('profile',[AdminApiController::class, 'profile'])->name('profile');
   Route::post('updateProfile',[AdminApiController::class, 'updateProfile'])->name('updateProfile');
   Route::post('logout',[AdminApiController::class, 'logout'])->name('logout');
   Route::delete('delete/{admin}',[AdminApiController::class, 'delete'])->name('delete');
   Route::patch('restore/{id}',[AdminApiController::class, 'restore'])->name('restore');
   Route::get('show/{admin}',[AdminApiController::class, 'show'])->name('show');
   Route::patch('edit/{admin}',[AdminApiController::class, 'edit'])->name('edit');
});