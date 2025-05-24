<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\PostController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register'])->name('auth.register');
Route::post('login', [AuthController::class, 'login'])->name('auth.login');

Route::middleware(['auth:sanctum'])->group(function () {

    //profile managment
    Route::get('profile', [ProfileController::class,'index'])->name('profile.index');
    Route::match(['put', 'patch'], 'profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::match(['put', 'patch'], 'profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::delete('profile', [ProfileController::class,'destroy'])->name('profile.destroy');

    //posts
    Route::apiResource('posts',PostController::class)->names('post')->only(['index','store']);

});
