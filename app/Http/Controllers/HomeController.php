<?php

namespace App\Http\Controllers;

use App\Accredit;
use App\Contract\Channels;
use App\Due;
use App\Incomes;
use App\Migrate;
use App\Periods;
use App\Sale;
use App\Services\BusinessCore;
use App\User;
use Illuminate\Support\Facades\Auth;
use DB;

class HomeController extends Controller
{

    public function init()
    {
        $user = Auth::user();
        switch ($user->role) {
            case BusinessCore::MEMBER_ROLE:
                return $this->welcomeMember();
                break;
            case BusinessCore::VENDOR_ROLE:
                return $this->welcomeVendor();
                break;
        }
        return view('welcome');
    }

    public function welcomeMember()
    {
        $user = Auth::user();
        $period = Periods::getCurrentPeriod();
        $dues = Due::where('payer_id', '=', $user->id)
            ->where('period', '=', $period->uid)
            ->where('state', '!=', Sale::ANNULLED)
            ->get();
        $dueImport = 0;
        foreach ($dues as $due) {
            $dueImport += $due->amount_of_quota;
        }
        return view('welcome_member', compact('user', 'dueImport'));
    }

    public function welcomeVendor()
    {
        $user = Auth::user();
        $period = Periods::getCurrentPeriod();
        $totalSales = Sale::whereDate('created_at', '>=', $period->created_at)
            ->where('collector_id', '=', $user->id)
            ->where('state', '!=', Sale::ANNULLED)
            ->where('state', '!=', Sale::PENDING)
            ->count();
        $accredits = Accredit::where('collector_id', '=', $user->id)
            ->where('period', '=', $period->uid)
            ->where('state', '!=', Sale::ANNULLED)
            ->where('state', '!=', Sale::PENDING)
            ->get();
        $accreditImport = 0;
        foreach ($accredits as $accredit) {
            $accreditImport += $accredit->amount_of_quota;
        }
        $dues = Due::where('payer_id', '=', $user->id)
            ->where('period', '=', $period->uid)
            ->where('state', '!=', Sale::ANNULLED)
            ->where('state', '!=', Sale::PENDING)
            ->get();
        $dueImport = 0;
        foreach ($dues as $due) {
            $dueImport += $due->amount_of_quota;
        }
        return view('welcome_vendor', compact('user', 'accreditImport', 'dueImport', 'totalSales'));
    }

    public function details()
    {
        $data = request()->all();
        $userId = !empty($data['member_id']) ? $data['member_id'] : Auth::user()->id;
        if (!empty($data['init']) && !empty($data['done'])) {
            return redirect()->to('details/' . $userId . '/' . $data['init'] . '/' . $data['done']);
        }
        return view('account_details');
    }


    public function budget()
    {
        $period = request()->get('period', Periods::getCurrentPeriod()->uid);
        $providers = User::where('role', '=', BusinessCore::VENDOR_ROLE)->orderBy('fantasy_name', 'ASC')->get();


        $rows = [];
        $rows [0] = [
            'name' => 'Mutual 7 de Marzo',
            'due' => 0,
            'accredit' => 0,
            'income' => 0,
        ];

        foreach ($providers as $provider) {
            $rows [$provider->id] = [
                'name' => 'Farmacia',
                'due' => 0,
                'accredit' => 0,
                'income' => 0,
            ];
            $rows [$provider->id]['name'] = $provider->fantasy_name;
        }

        $dues = Due::where('period', '=', $period)
            ->where('state', '!=', Sale::ANNULLED)
            ->where('state', '!=', Sale::PENDING)
            ->get();
        $accredits = Accredit::where('period', '=', $period)
            ->where('state', '!=', Sale::ANNULLED)
            ->where('state', '!=', Sale::PENDING)
            ->get();
        $incomes = Incomes::where('period', '=', $period)
            ->where('state', '!=', Sale::ANNULLED)
            ->where('state', '!=', Sale::PENDING)
            ->get();


        foreach ($dues as $due) {
            if ($due->sale->sale_mode != Sale::SUBSIDY && isset($rows[$due->sale->collector_id])) {
                $rows[$due->sale->collector_id]['due'] += $due->amount_of_quota;
            }
        }

        foreach ($accredits as $accredit) {
            if (isset($rows[$accredit->collector_id])) {
                $rows[$accredit->collector_id]['accredit'] += $accredit->amount_of_quota;
            }
        }

        foreach ($incomes as $income) {
            if (isset($rows[$income->collector_id])) {
                $rows[$income->collector_id]['income'] += $income->amount_of_quota;
            }
        }
        return view('budget', compact('rows', 'incomes', 'accredit', 'dues', 'period'));

    }


    public function satellite()
    {
        $period = request()->get('period', Periods::getCurrentPeriod()->uid);
        return view('satellite', compact('period'));

    }

    public function futureDebs()
    {
        $period = request()->get('period', Periods::getCurrentPeriod()->uid);
        return view('future_debs', compact('period'));
    }

    public function others()
    {
        $period = request()->get('period', Periods::getCurrentPeriod()->uid);
        return view('others', compact('period'));

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sales = Sale::where('amount', '>', 0)->where('sale_mode', '=', Sale::CURRENT_ACCOUNT)->orderBy('id',
            'desc')->paginate(100);
        return view('sales', compact('sales'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function creditNotes()
    {
        $credit_notes = Sale::where('amount', '<', 0)->orderBy('id', 'desc')->paginate(100);
        return view('credit_notes', compact('credit_notes'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkBook()
    {
        $check_books = Sale::where('sale_mode', '=', Sale::DISCOUNT_SUPPLIER)->orderBy('id', 'desc')->paginate(100);
        return view('check_book', compact('check_books'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function purchaseOrder()
    {
        $purchase_orders = Sale::where('sale_mode', '=', Sale::PURCHASE_ORDER)->orderBy('id', 'desc')->paginate(100);
        return view('purchase_orders', compact('purchase_orders'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function bulkImport()
    {
        $migrations = Migrate::where('type', '=', Migrate::BULK_TYPE)
            ->whereNotIn('status', [Migrate::ANNUL, Migrate::DELETE])
            ->get();
        return view('bulk_import', compact('migrations'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function AxoftImport()
    {
        $migrations = Migrate::where('type', '=', Migrate::AXOFT_TYPE)->get();
        return view('axoft_import', compact('migrations'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function pharmacy()
    {
        $migrations = Migrate::where('type', '=', Migrate::PHARMACY_TYPE)->get();
        return view('pharmacy', compact('migrations'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function unAuthorization()
    {
        return view('un_authorization');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function userDisable()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->to('/login');
        }
        Auth::logout();
        return view('user.user_disable', compact('user'));
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function close()
    {
        $current = Periods::getCurrentPeriod();

        return view('close', [
            'current' => $current,
            'nextPeriod' => BusinessCore::nextPeriod($current),
        ]);
    }

    public function onLimit()
    {
        $data = [];
        $currents = DB::table('dues')
            ->select(DB::raw('payer_id'), DB::raw('sum(amount_of_quota) as amount'))
            ->where('payer_id', '!=', '0')
            ->where('period', '>=', Periods::getCurrentPeriod()->uid)
            ->whereNotIn('state', [Sale::PENDING, Sale::ANNULLED])
            ->groupBy('payer_id')
            ->get();
        foreach ($currents as $current){
            if($current->amount <= (BusinessCore::CURRENT_MAX - 500)){
                continue;
            }

            $data[] = [
                'payer' => User::find($current->payer_id),
                'amount' => $current->amount,
                'exceeded' => ($current->amount >= BusinessCore::CURRENT_MAX),
            ];
        }

        return view('exceeded', [
            'data' => $data,
            'period' => Periods::getCurrentPeriod(),
        ]);
    }
}
