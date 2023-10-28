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


Route::controller(\App\Http\Controllers\AuthController::class)->prefix("auth")->group(function () {
    Route::post("register", "register");
    Route::post("login", "login");
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post("logout", "logout");
        Route::put("update", "update");
        Route::get('profile', 'profile');
        Route::get('users', 'index');
        Route::get('following/tweets', 'followingTweet');
        Route::get('followings', 'followingByAuth');
        Route::get('followers', 'followersByAuth');
        Route::get('user/{id}', 'UserById');
    });
});
Route::middleware("auth:sanctum")->group(function () {
    Route::apiResource("tweets", \App\Http\Controllers\TweetController::class);
    Route::get("tweets/user/{id}",[\App\Http\Controllers\TweetController::class,"TweetById"]);
    Route::controller(\App\Http\Controllers\FollowingController::class)->group(function () {
        Route::post("follow", "follow");
        Route::post("unfollow","unfollow");
    });
});

