<?php

use App\Http\Controllers\Api\Auth\FacebookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->group( function () {
    Route::resource('products', ProductController::class);
});

Route::get('login/facebook/url', [FacebookController::class, 'loginUrl']);
Route::get('login/facebook/callback', [FacebookController::class, 'loginCallback']);