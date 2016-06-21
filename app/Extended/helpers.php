<?php

use App\Extended\Vk;

/**
 *
 */
if (!function_exists('pd')) {

    function pd($array)
    {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }

}

/**
 *
 */
if (!function_exists('public_asset')) {

    function public_asset($url)
    {
        return StaticStorage::get($url);
    }

}

/**
 *
 */
if (!function_exists('vk_cache')) {

    function vk_cache($callable)
    {
        Vk::$cache = true;

        $funcc = call_user_func($callable);

        Vk::$cache = false;

        return $funcc;

    }

}

function pc_permute($items, $perms = array())
{
    if (empty($items)) {
        $return = array($perms);
    } else {
        $return = array();
        for ($i = count($items) - 1; $i >= 0; --$i) {
            $newitems = $items;
            $newperms = $perms;
            list($foo) = array_splice($newitems, $i, 1);
            array_unshift($newperms, $foo);
            $return = array_merge($return, pc_permute($newitems, $newperms));
        }
    }
    return $return;
}
