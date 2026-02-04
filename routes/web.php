<?php

use App\Http\Controllers\MembershipController;
use App\Models\Member;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('memberships')->name('memberships.')->controller(MembershipController::class)->group(function () {
    Route::get('create', 'create');
    Route::get('edit/{membership}', 'edit');
});
