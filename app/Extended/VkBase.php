<?php

namespace App\Extended;

use Cache;
use Guzzle\Http\Client;
use Session;

class VkBase
{
    public static $cache = false;

    /**
     *
     */
    public static function get($method, $data = [], $without_access = false)
    {
        if (self::$cache) {

            $cached_method = [$method, $data, $without_access];

            if ($result = self::getCached($cached_method)) {
                return $result;
            }
        }

        //dd($cached_method);

        $access_token = $without_access ? null : '&access_token=' . Session::get('vk_access_token', false);

        $data = self::buildRequest($data);

        $url = 'http://api.vk.com';

        $method = '/method/' . $method;

        if ($access_token) {
            $data .= $access_token;
        }

        if ($sig = Session::get('vk_secret')) {
            $sig = md5($method . '?' . $data . $sig);
            $data .= '&sig=' . $sig;
        }

        //$method_data = json_decode(file_get_contents($url), true);
                    /* cUrl magic */
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url . $method,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $data,

            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_USERAGENT      => "spider", // who am i
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
            CURLOPT_SSL_VERIFYPEER => false,     // Disabled SSL Cert checks
            CURLOPT_SSL_VERIFYHOST => false
            
        );
        curl_setopt_array($ch, $options);

        $result = curl_exec($ch);

        //dd(curl_error($ch));

        $method_data = json_decode($result, true);
        curl_close($ch);


        // $client = new Client();
        // $request = $client->get($url);
        // $response = $request->send();
        // $method_data = $response->json();

        if (array_key_exists('response', $method_data)) {

            if (self::$cache) {
                self::setCache($cached_method, $method_data['response']);
            }

            return $method_data['response'];
        }

        return $method_data;
    }

    /**
     *
     */
    public static function execute($code)
    {
        return self::get('execute', ['code' => $code]);
    }

    /**
     *
     */
    private static function buildRequest($data)
    {
        $request = '';

        foreach ($data as $key => $value) {
            if (is_array($value)) {

                $values = '';

                foreach ($value as $item) {
                    $values .= $item . ',';
                }

                $values = substr($values, 0, -1);

                $request .= $key . '=' . $values . '&';
            } else {
                $request .= $key . '=' . $value . '&';
            }
        }

        return substr($request, 0, -1);
    }

    /**
     *
     */
    public static function auth()
    {
        return Session::get('vk_user_id', false);
    }

    /**
     *
     */
    public static function getCached($method)
    {
        $method = md5(json_encode($method));

        $data = Cache::get($method);

        if (!empty($data)) {
            return $data;
        }

        //Cache::where('method', 'like', $method)->first();

        // if (!empty($data)) {
        //     return json_decode($data->value, true);
        // }
        // 
        // 
        // 

        return false;
    }

    /**
     *
     */
    public static function setCache($method, $data)
    {

        $method = md5(json_encode($method));

        Cache::put($method, $data, 30);


        // return Cache::firstOrCreate([
        //     'session_id' => \Session::getId(),
        //     'type' => 'vk',
        //     'method' => $method,
        //     'vk_id' => self::auth(),
        //     'value' => json_encode($data),
        // ]);

    }

    // /**
    //  *
    //  */
    // public static function cleanCache($method = false)
    // {
    //     if ($method) {
    //         return Cache::current()->whereMethod($method)->delete();
    //     }

    //     return Cache::current()->delete();
    // }

}
