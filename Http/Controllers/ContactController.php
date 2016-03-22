<?php

namespace App\Http\Controllers;

use Log;
use Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\CaptchaSecurityImages;
use App\Services\RhinoCommon;
use Session;

class ContactController extends Controller
{

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $c = new CaptchaSecurityImages(240,40,6,session_id(),null,app_path());
        $captchaStringData = $c->getBase64Img();
        return view('contact',compact('captchaStringData'));
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function submit()
    {
        $rhinoCommon = new RhinoCommon();

        $input = Request::all();
        Log::debug('ContactController: request - '. print_r($input, true));
        Log::debug('ContactController: captcha in session - '. Session::get('security_code'));

        // Assign the input values to variables for easy reference
        $name      = Request::get('name');
        $email     = Request::get('email');
        $phone     = Request::get('phone');
        $comment   = Request::get('comment');
        $captcha    = Request::get('captcha');

        // Test input values for errors
        $errors = array();

        if (!$name) {
            $errors[] = "You must enter a name.";
        } elseif(strlen($name) < 2)  {
            $errors[] = "Name must be at least 2 characters.";
        }

        if (!$email) {
            $errors[] = "You must enter an email.";
        } else if (! $rhinoCommon->validEmail($email)) {
            $errors[] = "You must enter a valid email.";
        }

        if ( !is_numeric( $phone ) ) {
            $errors[]= 'Your phone number can only contain digits.';
        }

        if (strlen($comment) < 10) {
            if (!$comment) {
                $errors[] = "You must enter a message.";
            } else {
                $errors[] = "Message must be at least 10 characters.";
            }
        }

        if (!$captcha) {
            $errors[] = "You must enter the security code";
        } else if((Session::get('security_code') == $captcha) && (!empty(Session::get('security_code'))) ) {
            // unset($_SESSION['security_code']);
        } else {
            $errors[] = "The security code you entered is incorrect ";
        }

        if ($errors) {
            $errortext = "";
            foreach ($errors as $error) {
                $errortext .= '<li>'. $error . "</li>";
            }
            Log::debug('ContactController: error - '. print_r($errors,true));
            return '<div class="alert alert-danger">The following errors occured:<br><ul>'. $errortext .'</ul></div>';
        }else {
            Log::debug('ContactController: success');
            return '<div class="alert alert-success">Thanks! Your message has been submitted.</div>';
        }
    }

}
