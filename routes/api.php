<?php

use App\Http\Controllers\Api\MembershipController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('memberships')
    ->middleware('auth:sanctum')
    ->name('memberships.')
    ->controller(MembershipController::class)
    ->group(function () {
        Route::get('{membership}', 'get');
        Route::post('{membership}', 'post');
    }
);
