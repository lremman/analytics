<?php 

namespace App\Extended;

class Redirect extends \Illuminate\Support\Facades\Redirect {

	public static function setKey($key, $options, $default = false)
	{
		if(is_array($options)){
			$options = json_encode(array('options' => $options, 'default' => $default));
		}
		
		if(\Session::put('redirkey:' . $key, $options));
			return true;

		return false;
	}

	public static function withRule($rules, $default = false)
	{
		$previous = rtrim(strtok(\URL::previous(), '?'), "/"); 

		if(array_key_exists($previous, $rules)){
			return self::to($rules[$previous]);
		}

		return self::to($default);
	}

	public static function goKey($key)
	{
		$redirect_data = \Session::get('redirkey:' . $key);
		\Session::put('redirkey:' . $key, NULL);

		if(!empty($json_data = json_decode($redirect_data, true))){
			return self::withRule($json_data['options'], $json_data['default']);
		}

		return self::to($redirect_data);
	}

	public static function checkKey($key)
	{
		return \Session::has('redirkey:' . $key);
	}

}