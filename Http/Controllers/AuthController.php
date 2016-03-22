<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\CIDAuthListener;
use App\CIDAuth;
use App\User;
use Log;

class AuthController extends Controller implements CIDAuthListener
{

    public function index(CIDAuth $cidAuth, Request $request)
    {
        return $cidAuth->execute($request->has('code'), $this);
    }

    public function logout(CIDAuth $cidAuth, Request $request)
    {
        return $cidAuth->logout($this);
    }

    public function userHasLoggedIn(User $user)
    {
        Log::debug('LogInController: userHasLoggedIn');
        return redirect('/profile');
    }

    public function userHasLoggedOut()
    {
        Log::debug('LogInController: userHasLoggedOut');
        return redirect('/');
    }

}
