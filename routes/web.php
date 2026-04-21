<?php

use App\Livewire\CreateMembership;
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

Route::prefix('memberships')->middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('memberships.index');
    });

    Route::get('/create', CreateMembership::class);
});
