<?php

namespace App\Extended;

class Vk extends VkBase
{

    public static function getMany($method, array $data, array $entities, $options = null)
    {
        $entities = array_chunk($entities, 24);

        $response = [];

        foreach ($entities as $entities_allowed) {
            $response = array_merge($response, self::getManyRaw($method, $data, $entities_allowed, $options) ?: []);
        }

        return $response;

    }

    public static function getManyRaw($method, array $data, array $entities, $options = null)
    {
        $options = empty($options) ? ['', ''] : $options;

        $options = is_string($options) ? ['', $options] : $options;

        $data = str_replace('"{items}"', 'entities[i]', json_encode($data));

        $entities = self::printArrayInJs($entities);

        $options = [
            str_replace('response', '', str_replace('.', '@.', $options[0])),
            str_replace('response', '', str_replace('.', '@.', $options[1])),
        ];

        $code = 'var entities = ' . $entities . '; var i=0; var results=[]; while (i < entities.length)' .
            '{ results.push(API.' . $method . '(' . $data . ')' . $options[0] . '); i = i+1; } return results' . $options[1] . ';';

        $code = str_replace("\n", '', $code);

        $result = self::execute(urlencode($code));

        return $result;
    }

    /**
     *
     */
    public static function getWithMap($method, array $data, $options = '')
    {
        $data = json_encode($data);

        $options = str_replace('response', '', str_replace('.', '@.', $options));

        $code = 'var results = API.' . $method . '(' . $data . ');' .
            'return results' . $options . ';';

        $result = self::execute(urlencode($code));

        return $result;
    }

    /**
     *
     */
    public static function printArrayInJs($array)
    {
        $str = '[';

        foreach ($array as $value) {
            $str .= $value . ',';
        }

        $str = substr($str, 0, -1);

        $str .= ']';

        return $str;
    }

    public static function getCurrentFriends($cache = true)
    {
        if (!$cache) {
            self::cleanCache('friends.get');
        }

        $friends = self::getCached('friends.get');

        if (!$friends = self::getCached('friends.get')) {

            $friends = self::get('friends.get', [
                'fields' => 'photo_50,first_name,lsat_name',
                'order' => 'hints',
            ]);

            self::setCache('friends.get', $friends);
        }

        return $friends;
    }

    public static function getUserFriends($user_id, $cache = true)
    {
        if (!$cache) {
            self::cleanCache('friends.get');
        }

        $friends = self::getCached('friends.get');

        if (!$friends = self::getCached('friends.get')) {

            $friends = self::get('friends.get', [
                'user_id' => $user_id,
                'fields' => 'photo_50,first_name,lsat_name',
                'order' => 'hints',
            ]);

            self::setCache('friends.get', $friends);
        }

        return $friends;
    }

    public static function currentId()
    {
        return self::auth();
    }

}
