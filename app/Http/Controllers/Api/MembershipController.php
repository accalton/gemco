<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function get(Membership $membership)
    {
        return response()->json($membership->load(['address', 'contacts', 'member', 'members']));
    }

    public function post(Membership $membership)
    {
        return response()->json([
            'message' => 'Posted!',
            'data' => request()->post()
        ]);
    }
}
