<?php
/**
 * Created by PhpStorm.
 * User: ikab
 * Date: 15/10/2015
 * Time: 5:40 PM
 */
namespace App;

use App\User;

interface CIDAuthListener
{
    public function userHasLoggedIn(User $user);
    public function userHasLoggedOut();
}