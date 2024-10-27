<?php

use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\AuthorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::name('auth.')->prefix('auth')->controller(AuthController::class)->group(function ($route) {
    Route::post('login', 'login')->name('login');
    Route::post('register', 'register')->name('register');
    Route::post('logout', 'logout')->name('logout')->middleware('auth:sanctum');;
    Route::get('me', 'me')->name('me')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function ($route) {
    Route::apiResource('authors', AuthorController::class);
});
