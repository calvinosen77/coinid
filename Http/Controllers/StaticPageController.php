<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class StaticPageController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function faq()
    {
        return view('faq');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function how()
    {
        return view('howitworks');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function terms()
    {
        return view('terms');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function privacy()
    {
        return view('privacy');
    }

}
