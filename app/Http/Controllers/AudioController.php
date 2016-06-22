<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Vk;
use Response;

class AudioController extends Controller
{
    /**
     *
     */
    public function __construct() 
    {
        if(!Vk::auth()) {
            return \Redirect::to(route('guest'))->send();
        }
    }

    public $postsMaxCount = 500;

    public $audiosMaxCount = 500;

    public $audioRealCount = 0;

    public $all_tracks = 0;

    public $track_weight = 0;

    public $noAudio;

    public $rating = 1;

    public $legend = [
        'coord' => 'Оцінка жанра, %',
        'title' => 'Оцінка жанрів аудіозаписів профіля',
        'label' => 'Рейтинг по жанрах аудіозаписів профіля',
    ];

    public $audio_genres_list = [
        1 => 'Rock',
        2 => 'Pop',
        3 => 'Rap & Hip-Hop',
        4 => 'Easy Listening',
        5 => 'Dance & House',
        6 => 'Instrumental',
        7 => 'Metal',
        21 => 'Alternative',
        8 => 'Dubstep',
        1001 => 'Jazz & Blues',
        10 => 'Drum & Bass',
        11 => 'Trance',
        12 => 'Chanson',
        13 => 'Ethnic',
        14 => 'Acoustic & Vocal',
        15 => 'Reggae',
        16 => 'Classical',
        17 => 'Indie Pop',
        19 => 'Speech',
        22 => 'Electropop & Disco',
        18 => 'Other',
    ];

    /**
     * 
     */
    public function show ()
    {
        $friends =  vk_cache(function () {
            return Vk::get('friends.get', ['fields' => 'first_name,last_name']);
        });

        return view('audio.show', [
            'friends' => $friends,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAjaxGenres(Request $request)
    {
        $genres = vk_cache(function () use ($request){

            $friends = $request->get('owner');

            //$this->getProfileAudioIntelect($friends[0]);

            $friends = array_chunk($friends, 16)[0];

            $genres = $this->getProfileAudio($friends);

            $genres = $this->filterWallAudioGenres($genres);

            $genres = $this->getGenresByIds($genres);

            if($request->get('is_wall')) {
                $wall_genres = $this->getWallAudio('genre', $request->get('owner'));

                $wall_genres = $this->filterWallAudioGenres($wall_genres);

                $wall_genres = $this->getGenresByIds($wall_genres);

                $genres = $this->mergeWallAndProfile($wall_genres, $genres);
            }

            return $genres;

        });

        $genres_json = $this->getGraphJson($genres);

        $users = vk_cache(function () use ($genres) {

            $users_raw = Vk::get('users.get', [
                'user_ids' => collect($genres)->lists(0)->toArray(),
                'fields' => ['photo_50'],
            ]);

            return collect($users_raw)->keyBy('uid');
        });


        $html = view('audio.ajax', [
            'all_tracks' => $this->all_tracks,
            'track_weight' => $this->track_weight,
            'real_count' => $this->audioRealCount,
            'genres' => $genres, 
            'legend' => json_encode($this->legend),
            'users' => $users]
        )->render();

        return Response::json([
            'html' => $html,
            'graph' => $genres_json,
        ]);

    }

    public function mergeWallAndProfile($wall, $profile)
    {
        $user  = array_get($profile, '0.0', array_get($wall, '0.0'));
        $wall = array_get($wall, '0.1', []);
        $profile = array_get($profile, '0.1', []);

        foreach($wall as $item => $v)
        {
            if(array_get($profile, $item)) {
                $wall[$item] += array_get($profile, $item);
                unset($profile[$item]);
            }
        }

        foreach($profile as $item)
        {
            if(array_get($wall, $item)) {
                $profile[$item] += array_get($wall, $item);
                unset($wall[$item]);
            }
        }

        $wall_profile = array_merge($wall, $profile);

        arsort($wall_profile);

        return [[$user, $wall_profile]];
    }

    /**
     * 
     */
    public function getGraphJson($genres)
    {
        $genres_all = [];

        foreach($genres as $user_genres) {

            foreach($user_genres[1] as $genre => $count) {
                $genres_all[$genre] = array_get($genres_all, $genre, 0) + $count;
            }
        }

        $this->all_tracks = array_sum($genres_all);

        $this->track_weight = 100/$this->all_tracks;

        arsort($genres_all);

        $genres = [];

        foreach($genres_all as $genre => $count) {
            $genres[] = [$genre => $count * $this->track_weight];
        }

        return $genres;

    }

    public function getGenresByIds($genres)
    {
        $genres = array_map(function ($user_genres) {

            $rewrited_genres = [];

            foreach ($user_genres[1] as $key => $value) {
                if ($genre = array_get($this->audio_genres_list, $key)) {
                    $rewrited_genres[$genre] = $value;
                }
            }

            return [
                $user_genres[0],
                $rewrited_genres,
            ];
        }, $genres);

        $genres = array_filter($genres, function ($genre) {
            if (empty($genre[1])) {
                $this->noAudio[] = $genre[0];
                return false;
            }

            return true;

        });

        return $genres;
    }

    /**
     *
     */
    public function getProfileAudioIntelect($friend)
    {
        $data = [
            'owner_id' => $friend,
            'need_user' => 0,
            'count' => $this->audiosMaxCount,
        ];

        $data = json_encode($data);

        $code = 'var results = API.audio.get(' . $data . ');' .
            'return results;';

        $code = str_replace("\n", '', $code);

        dd($code);
        $result = Vk::execute(urlencode($code));

        $audios = [];

        foreach ($audios_raw as $key => $user_audio) {
            array_shift($user_audio);
            $audios[] = [
                $friends[$key],
                $user_audio = $user_audio ?: [],
            ];
        }
        return $audios;
    }

    /**
     *
     */
    public function getProfileAudio($friends)
    {
        $audios_raw = Vk::getMany('audio.get', [
            'owner_id' => '{items}',
            'need_user' => 0,
            'count' => $this->audiosMaxCount,
        ], $friends, ['response.genre', '']);

        $audios = [];

        foreach ($audios_raw as $key => $user_audio) {
            array_shift($user_audio);
            $audios[] = [
                $friends[$key],
                $user_audio = $user_audio ?: [],
            ];
        }

        $this->audioRealCount += count($audios[0][1]);

        return $audios;
    }

    /**
     *
     */
    public function getWallAudio($field, array $friends)
    {

        $audios = [];

        foreach (array_chunk($friends, 24) as $allowed_friends) {
            $wall_audio = $this->getWallAudioRaw($field, $allowed_friends);
            if ($wall_audio) {
                $audios = array_merge($audios, $wall_audio);
            }
        }

        $this->audioRealCount += count($audios[0][1]);

        return $audios;
    }

    /**
     *
     */
    public function getWallAudioRaw($field, array $friends)
    {
        if ($field) {
            $field = '@.' . $field;
        }

        $data = json_encode([
            'owner_id' => '{friends}',
            'count' => $this->postsMaxCount,
            'extended' => 0,
            'filter' => 'owner',
        ]);

        $data = str_replace('"{friends}"', 'friends[i]', $data);

        $method = 'wall.get';

        $code =
        'var audio = [];' .
        'var friends = ' . Vk::printArrayInJs($friends) . ';' .
            'var i = 0; while(i < friends.length) {' .
            'var audio_friend = [];' .
            'var results = API.' . $method . '(' . $data . ');' .
            'results = results@.attachments;' .
            'var j = 1; while(j < results.length) {' .
            'audio_friend = audio_friend + (results[j]@.audio)' . $field . ';' .
            'j = j + 1; };' .
            'audio = audio + [[friends[i], audio_friend]];' .
            'i = i + 1; };' .

            'return audio;'
        ;

        $result = Vk::execute(urlencode($code));

        return $result;
    }

    public function filterWallAudioGenres($result)
    {
        $result = array_map(function ($item) {
            $genres = array_filter($item[1], function ($item_value) {
                return !(!$item_value || ($item_value == 18));
            });

            $genres = array_count_values($genres);

            arsort($genres);

            return [
                $item[0],
                $genres,
            ];
        }, $result);

        return $result;
    }

}
