<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
use App\Sale;

class SaleController extends Controller
{
    public function crate(SaleRequest $request)
    {
        $sale = Sale::create($request->all());
        return redirect()->to('/sales/' . $sale->id);
    }

    public function details(Sale $sale)
    {
        return view('sales.details', compact('sale'));
    }
}
