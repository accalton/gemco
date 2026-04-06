<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Membership;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function create()
    {
        return view('memberships.form', [
            'postApi' => route('api.memberships.store')
        ]);
    }

    public function edit(Membership $membership)
    {
        return view('memberships.form', [
            'fetchApi' => route('api.memberships.get', ['membership' => $membership]),
            'postApi'  => route('api.memberships.save', ['membership' => $membership])
        ]);
    }

    public function index()
    {
        $memberships = Membership::all();

        return view('memberships.index', compact('memberships'));
    }
}
