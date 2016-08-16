<?php

namespace App\Http\Controllers;

use App\Services\BusinessCore;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;

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
        $roles = [BusinessCore::VENDOR_ROLE => 'proveedor', BusinessCore::MEMBER_ROLE => 'socio'];
        return view('users', compact('users', 'filters', 'roles'));
    }
}
