<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sales = Sale::orderBy('id', 'desc')->take(5)->get();
        return view('home', compact('sales'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function userDisable()
    {
        $user = Auth::user();
        if(! $user){
            return redirect()->to('/login');
        }
        Auth::logout();
        return view('user.user_disable', compact('user'));
    }
}
