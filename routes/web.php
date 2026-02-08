<?php

use App\Http\Controllers\MembershipController;
use App\Models\Member;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('memberships')->name('memberships.')->controller(MembershipController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('create', 'create')->name('create');
    Route::get('edit/{membership}', 'edit')->name('edit');
});
