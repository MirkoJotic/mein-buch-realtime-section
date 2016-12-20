<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    public function participants()
    {
        return $this->belongsToMany('App\User', 'thread_user', 'thread_id', 'user_id');
    }

    public function messages()
    {
        return $this->hasMany('App\Message');
    }

    public function task()
    {
        return $this->belongsTo('App\Task');
    }
}
