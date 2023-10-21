<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::controller(\App\Http\Controllers\AuthController::class)->prefix("auth")->group(function (){
    Route::post("register","register");
    Route::post("login","login");
    Route::middleware(['auth:sanctum'])->group(function (){
        Route::post("logout","logout");
        Route::get('profile','profile');
    });
});
Route::middleware("auth:sanctum")->group(function (){
   Route::apiResource("tweets",\App\Http\Controllers\TweetController::class);
});
