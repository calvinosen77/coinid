<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use GuzzleHttp\Client;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/','HomeController@index');
Route::get('/login','AuthController@index');
Route::get('/logout','AuthController@logout');
Route::get('/contact','ContactController@index');
Route::post('/contact', ['before' => 'csrf', 'uses' => 'ContactController@submit']);

Route::get('/how','StaticPageController@how');
Route::get('/faq','StaticPageController@faq');
Route::get('/terms','StaticPageController@terms');
Route::get('/privacy','StaticPageController@privacy');

Route::get('/profile','ProfileController@overview');
Route::get('/profile/profile','ProfileController@profile');
Route::get('/profile/security','ProfileController@security');
Route::get('/profile/verify','ProfileController@verify');
Route::get('/profile/auths','ProfileController@auths');

Route::get('/profiletest', function () {
    return view('profile');
});

Route::get('/test', function () {


//    $client = new Client([
//        // Base URI is used with relative requests
//        'base_uri' => 'https://testanvil.coinid.com',
//        // You can set any number of default request options.
//        'timeout'  => 2.0,
//    ]);
//    $response = $client->get('/');
//    dd($response);


    $client = new \GuzzleHttp\Client();
    $response = $client->post('https://testanvil.coinid.com.au/oauth/access_token',  array('headers' => array('Accept' => 'application/json'), 'form_params' => array('client_id' => '5e0656af-8b7e-4cf1-9a1a-e4a68cd05a50', 'client_secret' => 'b613368381a785b11c74', 'code' => '93c19561af1fdb11d779', 'redirect_uri' => 'http://test.coinid.com/login', 'grant_type' => 'authorization_code')));
    $xml = $response;
    dd($response);
});