<?php

use App\Models\Membershipable;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    $foo = Membershipable::all();

    foreach ($foo as $bar) {
        var_dump($bar->order);
    }
});
