<?php
/**
 * Created by PhpStorm.
 * User: ikab
 * Date: 15/10/2015
 * Time: 3:33 PM
 */

namespace App;

use Log;
use App\User;

class CIDUsers {

    public function __construct() {
    }

    public function findByUserNameOrCreate($userData) {
        $user = User::where('email', '=', $userData->email)->first();
        if(!$user) {
            $givenName = null;
            if (isset($userData->user['name']['givenName'])) {
                $givenName = $userData->user['name']['givenName'];
            }
            $familyName = null;
            if (isset($userData->user['name']['familyName'])) {
                $familyName = $userData->user['name']['familyName'];
            }

            $user = User::create([
                'oidc_id' => $userData->id,
                'username' => $userData->email,
                'email' => $userData->email,
                'avatar' => $userData->avatar,
                'given_name' => $givenName,
                'family_name' => $familyName,
            ]);
        }

        $this->checkIfUserNeedsUpdating($userData, $user);
        Log::debug('CIDUsers: findByUserNameOrCreate ' . print_r($user,true));
        return $user;
    }

    public function checkIfUserNeedsUpdating($userData, $user) {
        $givenName = null;
        if (isset($userData->user['name']['givenName'])) {
            $givenName = $userData->user['name']['givenName'];
        }
        $familyName = null;
        if (isset($userData->user['name']['familyName'])) {
            $familyName = $userData->user['name']['familyName'];
        }

        $socialData = [
            'avatar' => $userData->avatar,
            'email' => $userData->email,
            'username' => $userData->email,
            'given_name' => $givenName,
            'family_name' => $familyName,
        ];
        $dbData = [
            'avatar' => $user->avatar,
            'email' => $user->email,
            'username' => $user->username,
            'given_name' => $user->given_name,
            'family_name' => $user->family_name,
        ];

        if (!empty(array_diff($socialData, $dbData))) {
            Log::debug('CIDUsers: checkIfUserNeedsUpdating - updating user');
            $user->avatar = $userData->avatar;
            $user->email = $userData->email;
            $user->username = $userData->email;
            $user->given_name = $givenName;
            $user->family_name = $familyName;
            $user->save();
        }
    }

}