<?php 

namespace App\Extended;

class StaticStorage {

	public static function get($filename = '')
	{
		return asset('public/' . $filename);
	}

}