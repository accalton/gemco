<?php

use App\Models\Identification;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/foobar', function () {
    for ($i = 0; $i < 10; $i++) {
        $val = array_rand(Identification::TYPES);
        var_dump($val);
    }
});
