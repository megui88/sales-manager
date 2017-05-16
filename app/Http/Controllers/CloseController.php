<?php

namespace App\Http\Controllers;

use App\Periods;
use App\Sale;
use App\Services\BusinessCore;
use Illuminate\Http\Request;

use App\Http\Requests;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CloseController extends Controller
{
    public function step($step)
    {
        if (method_exists($this, $step)) {
            return $this->$step();
        }
        throw new NotFoundHttpException();
    }

    protected function understand()
    {
        return view('close.understand');
    }

    protected function check_steps()
    {
        $period = Periods::getCurrentPeriod()->uid;
        $oldPeriod = BusinessCore::previousPeriod($period);
        $purchase_orders = Sale::where('sale_mode', '=', Sale::PURCHASE_ORDER)
            ->where('period', '<', $oldPeriod)
            ->orderBy('id', 'desc')->get();
        return view('close.check_steps', compact('purchase_orders'));
    }

    protected function check_purchase_orders()
    {
        return $this->close();
        $period = Periods::getCurrentPeriod()->uid;
        $sales = Sale::where('sale_mode', '=', Sale::PHARMACY_SELLING)
            ->where('period', '=', $period)
            ->orderBy('id', 'desc')->get();
        return view('close.check_purchase_orders', compact('sales'));
    }

    protected function close()
    {
        $period = Periods::getCurrentPeriod();
        $period->close();
        return redirect()->to('/');
    }
}
