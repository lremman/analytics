<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Vk;
use Response;

class LikesController extends Controller
{

    public $postsMaxCount = 100;

    public $postsOwnerId = 236452970;

    public $postsRealCount = 0;

    public $items_per_page = 8;

    public $rating = 1;


    public $filter_map = [
        'sex' => ['sex', '==', '[value]'],
        'status' => ['online', '==', '[value]'],
    ];

    public function show()
    {
        $friends = Vk::get('friends.get', ['fields' => 'first_name,last_name']);

        return view('likes.show', [
            'friends' => $friends,
        ]);
    }

    /**
     *
     */
    public function getAjaxLikes(Request $request)
    {
        $parameters = [
            'posts' => $request->get('posts', 0),
            'wall_photos' => $request->get('wall_photos', 0),
            'profile_photos' => $request->get('profile_photos', 1),
        ];

        $this->postsOwnerId = $request->get('owner', \Session::get('vk_user_id'));

        $this->rating = $request->get('rating', 1);

        $likes = vk_cache(function () use ($parameters, $request) {
        
        $likes = $this->getLikesResult($parameters);

        $filter_options = $this->getFilterOptions($request->get('filters'));


        return $likes = $this->getFilterVk($likes, $filter_options);

        });
        
        $users_raw = vk_cache(function () use ($likes, $request){
            return Vk::get('users.get', [
                'user_ids' => array_chunk(array_keys($likes), $this->items_per_page)[$request->get('page', 1) - 1],
                'fields' => ['photo_50', 'sex'],
            ]);
        });

        $users = collect($users_raw)->keyBy('uid');

        $html = view('likes.ajax', [
            'likes' => $likes, 
            'users' => $users, 
            'all_posts' => $this->postsRealCount,
            'pages_all' => ceil(count($likes)/$this->items_per_page),
        ])->render();

        //dd($html);

        return Response::json([
            'html' => $html,
            'graph' => $this->getGraphJson($users, $likes),
        ]);
    }

    /**
     * 
     */
    public function getGraphJson($users, $likes)
    {
        $coords = [];

        foreach($users as $user)
        {
            $coords[] = [
                array_get($user, 'first_name') . ' ' . array_get($user, 'last_name') => 
                array_get(array_get($likes, array_get($user, 'uid')), 'count') 
            ];
        }


        return $coords;
    }

    /**
     * 
     */
    public function getFilterOptions($request)
    {
        $options = [];

        foreach(array_only($this->filter_map, array_keys($request)) as $key => $value) {
            $value[2] = $request[$key];

            $options[] = $value;
        }

        return $options;
    }

    /**
     * 
     */
    public function getFilterVk($likes, array $options)
    {
        if (empty($options)) {
            return $likes;
        }

        $user_ids_parts = array_chunk(array_keys($likes), 100);

        $fields = [];

        foreach($options as $option)
        {
            $fields[] = $option[0];
        }

        $allowed_ids = [];

        foreach($user_ids_parts as $user_ids)
        {

            $users_ids = Vk::get('users.get', [
                'user_ids' => $user_ids,
                'fields' => $fields,
            ]);

            $users_ids = collect($users_ids)->filter(function( $item ) use ($options) {
                
                $allow = true;

                foreach($options as $option) {
                    if($option[2] == '*') {
                        $allow = $allow && true;
                    } else {
                        eval('$allow = $allow && ($item[$option[0]] ' . $option[1] . ' $option[2]);');
                    }
                }

                return $allow;
            });

            $allowed_ids = array_merge($allowed_ids, $users_ids->lists('uid')->toArray());

        }

        return array_only($likes, $allowed_ids);

    }

    /**
     *
     */
    public function getLikesResult($parameters)
    {

        $likes = collect();

        if ($parameters['posts']) {

            $likes = $likes->merge($this->getWallPostsLikes());

        }

        if ($parameters['wall_photos']) {

            $likes = $likes->merge($this->getWallPhotosLikes('wall'));
        }

        if ($parameters['profile_photos']) {

            $likes = $likes->merge($this->getWallPhotosLikes('profile'));
        }

        return $this->buildLikesRating($likes);
    }

    /**
     *
     */
    public function getWallPhotosLikes($album = 'wall')
    {
        $wall_photos = $this->getPhotosIds($album);

        $this->postsRealCount = $this->postsRealCount + count($wall_photos);

        return collect($this->getLikes($wall_photos, 'photo'));
    }

    /**
     *
     */
    public function getWallPostsLikes()
    {
        $wall_posts_ids = $this->getWallPostsIds();
        $this->postsRealCount = $this->postsRealCount + count($wall_posts_ids);

        return collect($this->getLikes($wall_posts_ids, 'post'));
    }

    /**
     *
     */
    public function getWallPostsIds()
    {

        $ids = Vk::getWithMap('wall.get', [
            'owner_id' => $this->postsOwnerId,
            'count' => $this->postsMaxCount,
            'extended' => 0,
            'filter' => 'owner',
        ], 'response.id');

        array_shift($ids);

        return $ids;
    }

    /**
     *
     */
    public function getPhotosIds($album)
    {

        $ids = Vk::getWithMap('photos.get', [
            'owner_id' => $this->postsOwnerId,
            'count' => $this->postsMaxCount,
            'album_id' => $album,
            'extended' => 0,
        ], 'response.pid');

        array_shift($ids);

        return $ids;
    }

    /**
     *
     */
    public function getLikes($posts_ids, $type = 'post')
    {
        return collect(Vk::getMany('likes.getList', [
            'type' => $type,
            'owner_id' => $this->postsOwnerId,
            'item_id' => '{items}',
        ], $posts_ids))->lists('users');

    }

    /**
     *
     */
    public function buildLikesRating($likes)
    {
        $users = [];

        $step = floor($this->postsRealCount / $this->rating);

        $rating = $this->rating;
        $step_count = 1;

        foreach ($likes as $likes_post) {

            foreach ($likes_post as $likes_user) {

                if (empty($users[$likes_user])) {
                    $users[$likes_user]['rating'] = $rating;
                    $users[$likes_user]['count'] = 1;
                } else {
                    $users[$likes_user]['count'] += 1;
                    $users[$likes_user]['rating'] += $rating;
                }
            }

            if ($step_count >= $step) {

                $step_count = 1;
                $rating = ($rating > 1) ? $rating - 1 : 1;

            } else {
                $step_count++;
            }
        }

        uasort($users, function ($a1, $a2) {
            return $a1['rating'] < $a2['rating'];
        });

        return $users;
    }

}
