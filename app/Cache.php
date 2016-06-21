<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cache extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    protected $table = 'cache_data';

    /**
     *
     */
    public function scopeCurrent($query)
    {
        return $query; //->whereSessionId(\Session::getId());
    }

}
