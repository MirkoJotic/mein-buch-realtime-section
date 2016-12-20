<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Cartalyst\Sentinel\Users\EloquentUser as SentinelUser;

class User extends SentinelUser 
{


    public function threadsWithMessagesAndTask() {
        return $this->belongsToMany('App\Thread')->with('messages')->with('task')->with('participants');
    }
}
