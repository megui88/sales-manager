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
        $sales = Sale::where('amount', '>', 0)->where('sale_mode', '=', Sale::CURRENT_ACCOUNT)->orderBy('id', 'desc')->get();
        return view('sales', compact('sales'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function creditNotes()
    {
        $credit_notes = Sale::where('amount', '<', 0)->orderBy('id', 'desc')->get();
        return view('credit_notes', compact('credit_notes'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function purchaseOrder()
    {
        $purchase_orders = Sale::where('sale_mode', '=', Sale::PURCHASE_ORDER)->orderBy('id', 'desc')->get();
        return view('purchase_orders', compact('purchase_orders'));
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
