<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class ProvidersController extends Controller
{
    public function membershipIncome(User $user)
    {
        return view('providers.income', compact('user'));
    }
}
