<?php

use App\Models\Member;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    $member = Member::find(1);

    var_dump($member->address->count());
});
