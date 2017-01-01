<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
use App\Periods;
use App\Services\BusinessCore;
use App\Sale;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function annulled(Sale $sale)
    {
        if(request()->method() == request()::METHOD_POST)
        {
            $this->validate(request(), [
                'password' => 'required',
            ]);

            if(BusinessCore::AuthorizationPassword(request()->get('password'))) {

                $period = Periods::where('uid','=', $sale->period)->first();

                if($period && !is_null($period->closed_at) ){
                    request()->session()->flash('alert-danger', 'No se puede anular, el periodo ya fue cerrado, genere una NOTA DE CREDITO');
                    return redirect()->to(request()->server('REQUEST_URI'));
                }
                $sale->state = Sale::ANNULLED;
                foreach ($sale->dues as $due){
                    $due->state = Sale::ANNULLED;
                    $due->save();
                }
                foreach ($sale->incomes as $income){
                    $income->state = Sale::ANNULLED;
                    $income->save();
                }
                foreach ($sale->accredits as $accredit){
                    $accredit->state = Sale::ANNULLED;
                    $accredit->save();
                }
                $sale->save();
                request()->session()->flash('alert-success', 'Anulada correctamente');
                return redirect()->to('/sales/' . $sale->id);

            }
            $this->exceptionNotAurhoze();
        }
        if($sale->state == Sale::ANNULLED){
            redirect()->to(request()->server('REQUEST_URI'));
        }
        return view('sales.change_annul', compact('sale'));
    }

    public function create(SaleRequest $request)
    {
        $data = $request->all();

        $period = Periods::where('uid','=', $data['period'])->first();

        if($period && !is_null($period->closed_at) ){
            $request->session()->flash('alert-danger', 'El periodo indicado ya cerro.');
            return redirect()->to('/home');
        }

        $collector = User::firstOrFail()->where('id','=',$data['collector_id'])->first();
        $sale = Sale::create([
            'sale_mode' => $data['sale_mode'],
            'payer_id' => $data['payer_id'],
            'collector_id' => $data['collector_id'],
            'period' => $data['period'],
            'concept_id' => $data['concept_id'],
            'description' => $data['description'],
            'installments' => $data['installments'],
            'charge' => $collector->administrative_expenses,
            'state' => Sale::INITIATED,
            'amount' => $data['amount'],
        ]);
/*        if($request->server('REQUEST_URI') == '/home'){
            $request->session()->flash('alert-success', 'Venta cargada <a href="/sales/'. $sale->id.'" class="link">Ver</a>');
            return redirect()->to('/home');
        };*/
        return redirect()->to('/sales/' . $sale->id);
    }

    public function createPurchaseOrder(SaleRequest $request)
    {
        $data = $request->all();

        $period = Periods::where('uid','=', $data['period'])->first();

        if($period && !is_null($period->closed_at) ){
            $request->session()->flash('alert-danger', 'El periodo indicado ya cerro.');
            return redirect()->to('/purchase_orders');
        }

        $collector = User::firstOrFail()->where('id','=',$data['collector_id'])->first();
        $sale = Sale::create([
            'sale_mode' => $data['sale_mode'],
            'payer_id' => $data['payer_id'],
            'collector_id' => $data['collector_id'],
            'period' => $data['period'],
            'concept_id' => $data['concept_id'],
            'description' => $data['description'],
            'installments' => $data['installments'],
            'charge' => $collector->administrative_expenses,
            'state' => Sale::PENDING,
            'amount' => $data['amount'],
        ]);
/*        if($request->server('REQUEST_URI') == '/purchase_orders'){
            $request->session()->flash('alert-success', 'Orden de compra cargada <a href="/purchase_orders/'. $sale->id.'" class="link">Ver</a>');
            return redirect()->to('/purchase_orders');
        };*/
        return redirect()->to('/purchase_orders/' . $sale->id);
    }

    public function createCreditNote(SaleRequest $request)
    {
        $data = $request->all();

        $period = Periods::where('uid','=', $data['period'])->first();

        if($period && !is_null($period->closed_at) ){
            $request->session()->flash('alert-danger', 'El periodo indicado ya cerro.');
            return redirect()->to('/credit_notes');
        }

        $collector = User::firstOrFail()->where('id','=',$data['collector_id'])->first();
        $sale = Sale::create([
            'sale_mode' => $data['sale_mode'],
            'payer_id' => $data['payer_id'],
            'collector_id' => $data['collector_id'],
            'period' => $data['period'],
            'concept_id' => $data['concept_id'],
            'description' => $data['description'],
            'installments' => $data['installments'],
            'charge' => $collector->administrative_expenses,
            'state' => Sale::INITIATED,
            'amount' => -1 * $data['amount'],
        ]);
/*        if($request->server('REQUEST_URI') == '/credit_notes'){
            $request->session()->flash('alert-success', 'Nota de credito cargada <a href="/sales/'. $sale->id.'" class="link">Ver</a>');
            return redirect()->to('/credit_notes');
        };*/
        return redirect()->to('/sales/' . $sale->id);
    }

    public function details(Sale $sale)
    {
        if( in_array(Auth::user()->role,[BusinessCore::MEMBER_ROLE, BusinessCore::VENDOR_ROLE])
            && !in_array(Auth::user()->id,[$sale->collector_id, $sale->payer_id])){
            throw new AuthorizationException();
        }
        $view = 'sales.details';
        $view = ($sale->payer_id === '0') ? 'sales.payer0_details' : $view;
        $view = ($sale->collector_id === '0') ? 'sales.collector0_details' : $view;
        return view($view, compact('sale'));
    }

    public function detailsPurchaseOrder(Sale $sale)
    {
        return view('sales.details_purchase_order', compact('sale'));
    }
}
