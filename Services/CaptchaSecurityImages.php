<?php

namespace App\Services;

use Session;

/*
* File: CaptchaSecurityImages.php
* Author: Simon Jarvis
* Copyright: 2006 Simon Jarvis
* Date: 03/08/06
* Updated: 07/02/07
* Requirements: PHP 4/5 with GD and FreeType libraries
* Link: http://www.white-hat-web-design.co.uk/articles/php-captcha.php
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details:
* http://www.gnu.org/licenses/gpl.html
*
*/

class CaptchaSecurityImages {

    var $font = '/Users/ikab/Documents/CoinID/dev/coinid_web/app/Services/monofont.ttf';
    private $stringdata;
    private $code;

    public function __construct($width='120',$height='40',$characters='6',$sess=null,$code=null, $appPath) {
        $this->font = $appPath . '/Services/monofont.ttf';
        $this->CaptchaSecurityImages($width, $height, $characters, $sess, $code);
    }

    public function getBase64Img() {
        return "data:image/png;base64," . base64_encode($this->stringdata);
    }

    public function getCode() {
        return $this->code;
    }

    private function generateCode($characters) {
        /* list all possible characters, similar looking characters and vowels have been removed */
        $possible = '23456789bcdfghjkmnpqrstvwxyz';
        $this->code = '';
        $i = 0;
        while ($i < $characters) {
            $this->code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
            $i++;
        }
    }

    public function CaptchaSecurityImages($width='120',$height='40',$characters='6',$sess=null,$code=null) {
        //If the passed in sessionId matches this sessionId then all is good, otherwise use the code passed in
        if (!isset($sess) || session_id() == $sess) {
            $this->generateCode($characters);
//            logCaptcha("captcha session_id=" . session_id() . " generating new code " . $code);
        } else {
            $this->code = $code;
//            logCaptcha("captcha session_id=" . session_id() . " using code passed in " . $code);
        }
        /* font size will be 75% of the image height */
        $font_size = $height * 0.75;
        $image = @imagecreate($width, $height) or die('Cannot initialize new GD image stream');
        /* set the colours */
        $background_color = imagecolorallocate($image, 255, 255, 255);
        $text_color = imagecolorallocate($image, 20, 40, 100);
        $noise_color = imagecolorallocate($image, 100, 120, 180);
        /* generate random dots in background */
        for( $i=0; $i<($width*$height)/3; $i++ ) {
            imagefilledellipse($image, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $noise_color);
        }
        /* generate random lines in background */
        for( $i=0; $i<($width*$height)/150; $i++ ) {
            imageline($image, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), $noise_color);
        }
        /* create textbox and add text */
        $textbox = imagettfbbox($font_size, 0, $this->font, $this->code) or die('Error in imagettfbbox function');
        $x = ($width - $textbox[4])/2;
        $y = ($height - $textbox[5])/2;
        imagettftext($image, $font_size, 0, $x, $y, $text_color, $this->font , $this->code) or die('Error in imagettftext function');
        $this->image = $image;

        //Save to file
        //$this->filename = $this->code . ".png";
        //imagepng($image, $this->filename);

        //Save StringData
        ob_start();
        imagepng($image);
        $this->stringdata = ob_get_contents();
        ob_end_clean();

        /* output captcha image to browser */
        //header('Content-Type: image/jpeg');
        //imagejpeg($image);
        //imagedestroy($image);

//        $_SESSION['security_code'] = $this->code;
        Session::put('security_code', $this->code);

        //logCaptcha("captcha session_id=" . session_id() . " captcha=" . $this->code . " security_code=" . $_SESSION['security_code']);
    }

}