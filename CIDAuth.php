<?php
/**
 * Created by PhpStorm.
 * User: ikab
 * Date: 15/10/2015
 * Time: 1:15 PM
 */

namespace App;

use Illuminate\Contracts\Auth\Guard;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Log;
use App\CIDAuthListener;

class CIDAuth
{

    private $socialite;
    private $auth;
    private $users;

    public function __construct(Socialite $socialite, Guard $auth, CIDUsers $users) {
        $this->socialite = $socialite;
        $this->auth = $auth;
        $this->users = $users;
    }

    public function execute ($hasCode, CIDAuthListener $listener) {

        if (! $hasCode) return $this->getAuthorizationFirst();

        $user = $this->users->findByUserNameOrCreate($this->getSocialUser());

        $this->auth->login($user, true);

        return $listener->userHasLoggedIn($user);
    }

    private function getSocialUser() {
        $user = $this->socialite->driver('coinidtest')->user();

        Log::debug('CIDAuth: execute - socialuser ' . print_r($user,true));
        return $user;
    }

    private function getAuthorizationFirst() {
        return $this->socialite->driver('coinidtest')->redirect();
    }

    public function logout(CIDAuthListener $listener) {
//        $this->socialite->driver('coinidtest')->deauthorize(\Auth::user()->access_token);
        $this->auth->logout();
        return $listener->userHasLoggedOut();
    }

}