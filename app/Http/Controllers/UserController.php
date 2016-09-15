<?php

namespace App\Http\Controllers;

use App\Http\Requests\CbuRequest;
use App\Http\Requests\CodeRequest;
use App\Http\Requests\EmailRequest;
use App\Http\Requests\UserProfileRequest;
use App\Http\Requests\UserRequest;
use App\Services\BusinessCore;
use App\User;
use Illuminate\Http\JsonResponse;

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

        if(request()->isXmlHttpRequest()) {
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
        $user = User::where('id','=',$user)
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
        $path = $user->role === BusinessCore::MEMBER_ROLE ?  '/members/incoming/' : '/providers/incoming/' ;
        return redirect()->to($path . $user->id);
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function changeCbu(User $user)
    {
        return view('user.change_cbu', compact('user'));
    }


    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function changeEmail(User $user)
    {
        return view('user.change_email', compact('user'));
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
    public function changeCode(User $user)
    {
        return view('user.change_code', compact('user'));
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
        if(request()->method() == request()::METHOD_POST)
        {
            $this->validate(request(), [
                'password' => 'required',
            ]);

            if(BusinessCore::AuthorizationPassword(request()->get('password'))) {
                $user->disEnrolled();
                return view('user.disenrolled_confirm', compact('user'));
            }
            $this->exceptionNotAurhoze();
        }
        $template = $user->status == BusinessCore::MEMBER_DISENROLLED ? 'user.disenrolled_confirm' : 'user.disenrolled';
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
}
