<?php

use App\Http\Controllers\MembershipController;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/create-token', function (Request $request) {
    $expiry = (new DateTime())->modify('+1 hour');
    $token = $request->user()->tokens()->where('name', $request->token_name)->first();

    if (!$token?->plainTextToken) {
        $request->user()->tokens()->delete();

        $token = $request->user()->createToken(
            $request->token_name, ['*'], $expiry
        );
    }

    return response()->json([
        'token' => $token->plainTextToken
    ]);
});

Route::prefix('memberships')
    ->name('memberships.')
    ->controller(MembershipController::class)
    ->middleware('auth')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('edit/{membership}', 'edit')->name('edit');
    }
);
