<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class SampleController extends Controller
{
    public function index()
    {

        $firstname = "Kim";
        $friends = ['Dave', 'Lachlan'];
        return view('sample',compact('firstname','friends'));

    }
}
