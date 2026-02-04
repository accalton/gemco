<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function create()
    {
        return view('memberships.form');
    }

    public function edit(Membership $membership)
    {
        return view('memberships.form');
    }
}
