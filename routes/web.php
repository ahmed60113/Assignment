<?php

use Illuminate\Support\Facades\Route;
use Modules\Customers\Controllers\FbController;
use Modules\Customers\Controllers\GoogleController;
use Modules\Customers\Controllers\GithubController;
use Illuminate\Support\Facades\Auth;
use Modules\Customers\Controllers\CustomerApiController;

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

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('auth/mail',[CustomerApiController::class,'loginByMail']);

//facebook login 
Route::get('auth/facebook', [FbController::class, 'redirectToFacebook']);

Route::get('auth/facebook/callback', [FbController::class, 'facebookSignin']);

//google login
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle']);

Route::get('auth/google/callback', [GoogleController::class, 'GoogleSignin']);

//github login
Route::get('auth/github', [GithubController::class, 'redirectToGithub']);

Route::get('auth/github/callback', [GithubController::class, 'gitHubSignin']);
