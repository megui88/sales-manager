<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProviderRegisterRequest;
use Illuminate\Http\Request;

use App\User;
use Illuminate\Support\Facades\Auth;

class ProvidersController extends Controller
{
    public function membershipIncome(User $user)
    {
        return view('providers.income', compact('user'));
    }

    public function register()
    {
        return view('providers.register', []);
    }

    public function createRegister(ProviderRegisterRequest $request)
    {
        $data = $request->all();
        unset($data['_token']);
        unset($data['code_confirmation']);
        unset($data['email_confirmation']);
        unset($data['password_confirmation']);
        $data['password'] = bcrypt($data['password']);
        $user = User::buildVendor($data);
        Auth::login($user);
        return redirect('/');
    }
}
