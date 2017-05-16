<?php

namespace App\Http\Controllers;

use App\Accredit;
use App\Due;
use App\Http\Requests\AdministrativeExpensesRequest;
use App\Http\Requests\CbuRequest;
use App\Http\Requests\CodeRequest;
use App\Http\Requests\EmailRequest;
use App\Http\Requests\Request;
use App\Http\Requests\UserProfileRequest;
use App\Http\Requests\UserRequest;
use App\Incomes;
use App\Sale;
use App\Services\BusinessCore;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use DB;

class UserController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->pagination(User::class);
        $filters = $this->getFilters();

        if (request()->isXmlHttpRequest()) {
            return $users;
        }

        return view('user.users', compact('users', 'filters'));
    }

    /**
     * @param string $user
     * @return User
     */
    public function details($user)
    {
        $user = User::where('id', '=', $user)
            ->orWhere('code', '=', $user)
            ->firstOrFail();
        return $user;
    }

    public function newUser()
    {
        return view('user.new_user');
    }

    public function create(UserRequest $request)
    {
        $user = User::create($request->all());
        $path = $user->role === BusinessCore::MEMBER_ROLE ? '/members/income/' : '/providers/income/';
        return redirect()->to($path . $user->id);
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateEmail(User $user, EmailRequest $request)
    {
        $user->update($request->all());
        $request->session()->flash('alert-success', 'Los cambios fueron guardados correctamente!');
        return redirect()->to('/profile/' . $user->id);
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateCode(User $user, CodeRequest $request)
    {
        $user->update($request->all());
        $request->session()->flash('alert-success', 'Los cambios fueron guardados correctamente!');
        return redirect()->to('/profile/' . $user->id);
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function confirmCbu(User $user)
    {
        return view('user.confirm_cbu', compact('user'));
    }

    /**
     * @param $property
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function changeProperty($property, User $user)
    {
        return view('user.change_' . $property, compact('user'));
    }

    /**
     * @param $property
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function confirmProperty($property, User $user)
    {
        return view('user.confirm_' . $property, compact('user'));
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateAdministrativeExpenses(User $user, AdministrativeExpensesRequest $request)
    {
        $user->update($request->all());
        $request->session()->flash('alert-success', 'Los cambios fueron guardados correctamente!');
        return redirect()->to('/users/administrative_expenses/confirm/' . $user->id);
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateCbu(User $user, CbuRequest $request)
    {
        $user->update($request->all());
        $request->session()->flash('alert-success', 'Los cambios fueron guardados correctamente!');
        return redirect()->to('/users/cbu/confirm/' . $user->id);
    }

    public function disEnrolled(User $user)
    {
        if (request()->method() == Request::METHOD_POST) {
            $this->validate(request(), [
                'password' => 'required',
            ]);

            if (BusinessCore::AuthorizationPassword(request()->get('password'))) {
                $user->disEnrolled();
                return view('user.confirm_disenrolled', compact('user'));
            }
            $this->exceptionNotAurhoze();
        }
        $template = $user->state == BusinessCore::MEMBER_DISENROLLED ? 'user.confirm_disenrolled' : 'user.change_disenrolled';
        return view($template, compact('user'));
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile(User $user)
    {
        return view('user.profile', compact('user'));
    }


    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editProfile(User $user)
    {
        return view('user.edit', compact('user'));
    }

    /**
     * @param User $user
     * @param UserProfileRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateProfile(User $user, UserProfileRequest $request)
    {
        $user->update($request->all());
        $request->session()->flash('alert-success', 'Los cambios fueron guardados correctamente!');
        return redirect()->to('/profile/' . $user->id);
    }

    /**
     * @param $periodInit
     * @param $periodDone
     * @return \Illuminate\Http\RedirectResponse
     */
    public function accountDetails(User $user, $periodInit, $periodDone)
    {
        if (!BusinessCore::isValidPeriodFormat($periodInit) || !BusinessCore::isValidPeriodFormat($periodDone)) {
            request()->session()->flash('alert-warning', 'El periodo no es valido');
            return redirect()->to('/details');
        }
        if ($periodInit > $periodDone) {
            request()->session()->flash('alert-warning', 'El periodo inicial debe ser menor al periodo de fin!');
            return redirect()->to('/details');
        }

        $dues = Due::where('period', '>=', $periodInit)
            ->where('period', '<=', $periodDone)
            ->where('payer_id', '=', $user->id)
            ->where('state', '!=', Sale::ANNULLED)
            ->orderBy('created_at', 'ASC')
            ->get();
        $accredits = Accredit::where('period', '>=', $periodInit)
            ->where('period', '<=', $periodDone)
            ->where('collector_id', '=', $user->id)
            ->where('state', '!=', Sale::ANNULLED)
            ->orderBy('created_at', 'ASC')
            ->get();

        $periods = [];

        //structure
        for ($period = $periodInit; $period <= $periodDone; $period = BusinessCore::nextPeriod($period)) {
            $periods[$period] = [
                'dues' => [],
                'accredits' => [],
                'dues_pending' => [],
                'accredits_pending' => [],
            ];
        }

        //populate
        foreach ($dues as $due) {
            if ($due->state == Sale::PENDING) {
                $periods[$due->period]['dues_pending'] [] = $due;
                continue;
            }
            $periods[$due->period]['dues'] [] = $due;
        }


        foreach ($accredits as $accredit) {
            if ($accredits->state == Sale::PENDING) {
                $periods[$accredits->period]['accredits_pending'] [] = $accredits;
                continue;
            }
            $periods[$accredit->period]['accredits'] [] = $accredit;
        }

        $template = 'user.account_detail';

        switch ($user->role) {
            case BusinessCore::VENDOR_ROLE:
                $template = 'providers.account_detail';
                break;
        }

        return view($template, compact('periods', 'periodInit', 'periodDone', 'user'));
    }

    /**
     * @param $periodInit
     * @param $periodDone
     * @return \Illuminate\Http\RedirectResponse
     */
    public function account0Details($periodInit, $periodDone)
    {
        if (!BusinessCore::isValidPeriodFormat($periodInit) || !BusinessCore::isValidPeriodFormat($periodDone)) {
            request()->session()->flash('alert-warning', 'El periodo no es valido');
            return redirect()->to('/details');
        }
        if ($periodInit > $periodDone) {
            request()->session()->flash('alert-warning', 'El periodo inicial debe ser menor al periodo de fin!');
            return redirect()->to('/details');
        }

        $dues = Due::where('period', '>=', $periodInit)
            ->where('period', '<=', $periodDone)
            ->where('payer_id', '=', '0')
            ->where('state', '!=', Sale::ANNULLED)
            ->where('state', '!=', Sale::PENDING)
            ->orderBy('created_at', 'ASC')
            ->get();
        $accredits = Accredit::where('period', '>=', $periodInit)
            ->where('period', '<=', $periodDone)
            ->where('collector_id', '=', '0')
            ->where('state', '!=', Sale::ANNULLED)
            ->where('state', '!=', Sale::PENDING)
            ->orderBy('created_at', 'ASC')
            ->get();
        $incomes = Incomes::where('period', '>=', $periodInit)
            ->where('period', '<=', $periodDone)
            ->where('state', '!=', Sale::ANNULLED)
            ->where('state', '!=', Sale::PENDING)
            ->orderBy('created_at', 'ASC')
            ->get();

        $periods = [];

        //structure
        for ($period = $periodInit; $period <= $periodDone; $period = BusinessCore::nextPeriod($period)) {
            $periods[$period] = [
                'dues' => [],
                'accredits' => [],
            ];
        }

        //populate
        foreach ($dues as $due) {
            $periods[$due->period]['dues'] [] = $due;
        }

        foreach ($accredits as $accredit) {
            $periods[$accredit->period]['accredits'] [] = $accredit;
        }

        foreach ($incomes as $income) {
            $periods[$income->period]['incomes'] [] = $income;
        }

        $sale = new Sale();
        $user = $sale->getMutualUser();

        return view('providers.account0_detail', compact('periods', 'periodInit', 'periodDone', 'user'));
    }
}
