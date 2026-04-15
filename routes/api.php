<?php

use App\Http\Controllers\Api\MembershipController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::name('api.')->group(function () {
    Route::prefix('memberships')
        ->middleware('auth:sanctum')
        ->name('memberships.')
        ->controller(MembershipController::class)
        ->group(function () {
            Route::get('{membership}', 'get')->name('get');
            Route::post('/', 'store')->name('store');
            Route::post('{membership}', 'save')->name('save');
        }
    );
});
