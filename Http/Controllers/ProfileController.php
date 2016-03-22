<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\CIDAuthListener;
use App\CIDAuth;
use App\User;
use Log;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function overview()
    {
        return view('profile.overview');
    }

    public function profile()
    {
        return view('profile.profile');
    }

    public function security()
    {
        return view('profile.security');
    }

    public function verify()
    {
        return view('profile.verify');
    }

    public function auths()
    {
        return view('profile.auths');
    }

}
