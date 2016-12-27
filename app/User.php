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

    public static function threadsTaskUserMessagesUserParticipantsUser($uid) {
        return self::where('id', $uid)
                    ->with('threads')
                    ->with('threads')
                    ->with('threads.participants')
                    ->with('threads.messages')
                    ->with('threads.messages.user')
                    ->with('threads.task')
                    ->first();
    }

    public function threads() {
        return $this->belongsToMany('App\Thread');
    }

    public function tasks() {
      return $this->hasMany('App\Task');
    }
}
