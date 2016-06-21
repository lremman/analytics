<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Cache;
use Illuminate\Http\Request;
use Vk;

class FriendsController extends Controller
{

    private $friends;

    private $map;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mutual = $this->getMutual();

        $mutual = $this->mapMutualFriends($mutual);

    }

    /**
     *
     */
    public function getMutual()
    {
        if (!$mutual = Cache::get('mutual11451')) {
            $mutual = $this->getMutualRaw();
            Cache::put('mutual11451', $mutual, 100);
        }

        return $mutual;
    }

    /**
     *
     */
    public function getMutualRaw()
    {
        $friends = Vk::get('friends.get', ['user_id' => '59512573']);

        $mutual = Vk::get('friends.getMutual', [
            'target_uids' => $friends,
        ]);

        $this->friends = array_flip($friends);

        return $mutual;

    }

    /**
     *
     */
    public function getUserFriends()
    {
        if (!$friends = Cache::get('friends11451')) {
            $friends = Vk::get('friends.get', ['user_id' => '59512573']);
            $friends = array_flip($friends);
            Cache::put('friends11451', $friends, 100);
        }

        return $friends;
    }

    /**
     *
     */
    public function mapMutualFriends($mutual)
    {
        $map = [];

        $friends = $this->getUserFriends();

        foreach ($mutual as $mutual_item) {
            foreach ($mutual_item['common_friends'] as $item) {
                $map[$friends[$mutual_item['id']]][$friends[$item]] = [];
            }
        }

        $this->map = $map;
        $this->arrayRun();

    }

    /**
     *
     */
    public function arrayRun()
    {
        print 3;

        foreach ($this->map as $key => $values) {
            $this->runRow([$key], $values);
        }

        print 4;

        foreach ($this->map as $key => $values) {
            foreach ($values as $key1 => $values1) {
                $this->runRow([$key, $key1], $values1);
            }
        }

        dd($this->map);

        // print 5;

        // foreach ($this->map as $key => $values) {
        //     foreach ($values as $key1 => $values1) {
        //         foreach ($values as $key2 => $values2) {
        //             $this->runRow([$key, $key2], $values2);
        //         }
        //     }
        // }

    }

    public function runRow($keys, $row)
    {
        $keys_text = $keys;

        array_shift($keys_text);

        foreach ($row as $v1 => $k1) {
            foreach ($row as $v2 => $k2) {
                $imploded = $keys_text ? implode($keys_text, '][') : [];
                $keys_str = $imploded ? '[' . $imploded . ']' : '';
                $code = '$isset = isset($this->map' . $keys_str . '[' . $v1 . '][' . $v2 . ']);';
                eval($code);
                if ($isset) {
                    $this->writeMerge(array_merge($keys, [$v1, $v2]));
                } else {
                    $imploded = $keys_text ? implode($keys_text, '][') : [];
                    $keys_str = $imploded ? '[' . $imploded . ']' : '';
                    $code = 'unset($this->map' . $keys_str . ');';
                    eval($code);
                }
            }
        }

    }

    public function writeMerge($array)
    {
        $rows = pc_permute($array);

        foreach ($rows as $row) {
            eval('$this->map[' . implode($row, '][') . '] = [];');
        }

    }

    /**
     *
     */
    public function itemRun($row)
    {

    }

    /**
     *
     */
    public function finalItem($array)
    {
        pd($array);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
