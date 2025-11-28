<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryContoller;
use App\Http\Controllers\Api\UserProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function () {
    Route::post("login", [AuthController::class, "login"]);
    Route::post("register", [AuthController::class, "register"]);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::apiResource("user-profiles", UserProfileController::class);
        Route::apiResource("categories", CategoryContoller::class);
    });
});
