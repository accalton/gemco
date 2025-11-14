<?php

use App\Models\MembershipUser;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/foobar', function () {
    $membershipUser = MembershipUser::first();

    $users = User::query()->whereRelation('membershipUsers', 'id', $membershipUser->id)->orWhereDoesntHave('membershipUsers')->get();

    foreach ($users as $user) {
        var_dump($user->name);
    }

    exit;
});
