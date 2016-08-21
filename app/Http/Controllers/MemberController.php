<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class MemberController extends Controller
{
    public function membershipIncome(User $user)
    {
        return view('memberships.income', compact('user'));
    }
}
