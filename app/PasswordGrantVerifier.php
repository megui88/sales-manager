<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PasswordGrantVerifier
{
    public function verify($username, $password)
    {
        $credentials = [
            'email'    => $username,
            'password' => $password,
        ];
        if (Auth::once($credentials)) {
            $user = Auth::user();
            $this->sessionsGarbageCollector($user);

            return $user->id;
        }

        return false;
    }

    private function sessionsGarbageCollector($user)
    {
        $client_id = (int) request()->get('client_id');
        DB::table('oauth_sessions')
            ->where('client_id', '=', $client_id)
            ->where('owner_type', '=', 'user')
            ->where('owner_id', '=', $user->id)
            ->delete();
    }
}
