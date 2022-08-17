<?php

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

use Chriscreates\Social\Http\Controllers\AuthCallbackController;
use Chriscreates\Social\Http\Controllers\AuthRedirectController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => config('social.middleware', ['web'])], function () {
    Route::get('auth/{provider}', AuthRedirectController::class)->name('auth.provider.redirect');
    Route::get('auth/{provider}/callback', AuthCallbackController::class)->name('auth.provider.callback');
});
