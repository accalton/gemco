<?php

namespace App\Http\Controllers;

use App\Http\Requests\MembershipFormRequest;
use App\Models\Membership;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index()
    {
        $memberships = Membership::all();

        return view('memberships.index', compact('memberships'));
    }

    public function store(MembershipFormRequest $request)
    {
        $validated = $request->validated();

        var_dump($validated);

        var_dump($_POST);

        exit;
        return view('memberships.index');
    }
}
