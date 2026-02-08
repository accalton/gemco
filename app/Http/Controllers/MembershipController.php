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

    public function edit()
    {
        return view('memberships.form');
    }

    public function index()
    {
        $memberships = Membership::all();

        return view('memberships.index', compact('memberships'));
    }
}
