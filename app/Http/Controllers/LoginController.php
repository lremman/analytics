<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Guzzle\Http\Client;
use Guzzle\Http\StaticClient;
use App\Extended\Vk;

use App\Extended\Redirect;

class LoginController extends Controller
{
    public $redirect = '';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        $this->beforeAuth();

        if($this->redirect)
            return Redirect::to($this->redirect);

        return $this->goToUrl('https://oauth.vk.com/authorize', [
            'client_id' => env('VK_APP_ID'),
            'scope' => env('VK_PERMISSIONS_SCOPE'),
            'redirect_uri'  => route('authentication'),
            'response_type' => 'code',
            'v=API_VERSION' => env('VK_API_VERSION'),
            'info'  => 'info',
        ]);
    }

    public function authentication(){

        if(!$code = \Input::get('code', false))
            return Redirect::goKey('login-callback');

        $url = $this->goToUrl('https://oauth.vk.com/access_token', [
            'client_id' => env('VK_APP_ID'),
            'client_secret' =>  env('VK_APP_SHARED_SECRET'),
            'redirect_uri'  => route('authentication'),
            'code' => $code,
        ], 1);

        $client = new Client();
        $request = $client->get($url);
        $response = $request->send();
        $response_data = $response->json();

        \Session::put('vk_access_token', $response_data['access_token']);
        \Session::put('vk_user_id', $response_data['user_id']);
        \Session::put('vk_expires_in', $response_data['expires_in']);

        $data = Vk::get('users.get', [
            'fields'    => [
                'photo_100',
                'sex',
                'bdate',
            ],
        ]);

        $data = array_first($data, function ($key, $value) {
            return $value;
        });

        $user_data = [
            'first_name'   => array_get($data, 'first_name'),
            'last_name'    => array_get($data, 'last_name'),
            'sex'          => array_get($data, 'sex'),
            'birthday'     => array_get($data, 'bdate', ''),
            'photo_url'    => array_get($data, 'photo_100'),
            'vk_id'        => array_get($data, 'uid'),
            'access_token' => array_get($response_data, 'access_token'),
        ];

        // if($user = User::where('vk_id', '=',  $response_data['user_id'])->update($user_data))
        // {
        //     return $this->afterAuth($user);
        // }
        // else if($user = User::create($user_data))
        // {
            return $this->afterAuth('$user');
        // }

        // return 1;

    }


    public function goToUrl($base, $parameters, $no_redirect = false){

        $url = $base . '?';

        foreach($parameters as $key => $value)
            $url.= $key . '=' . $value . '&';

        $url = substr($url, 0, -1);

        if($no_redirect)
            return $url;

        return \Redirect::to($url);
    }

    public function logout()
    {
        \Session::put('vk_access_token', NULL);
        \Session::put('vk_user_id', NULL);
        \Session::put('vk_expires_in', NULL);

        Vk::get('auth.logout');
            return \Redirect::route('guest');
    }

    public function beforeAuth()
    {
        $this->redirect = \URL::previous();

        if(!Vk::auth()){
            Redirect::setKey('login-callback', \URL::previous(), route('dashboard'));
            $this->redirect = '';
        }        
    }

    public function afterAuth($user)
    {
        if(Redirect::checkKey('login-callback'))
        {
            return Redirect::goKey('login-callback');
        }
    }

}
