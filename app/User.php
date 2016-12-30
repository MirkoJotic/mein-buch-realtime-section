<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Cartalyst\Sentinel\Users\EloquentUser as SentinelUser;

class User extends SentinelUser
{


    public static function populateUserList($search, $usersInConversation)
    {
        return self::whereNotIn('id', $usersInConversation)
                    ->where('email', 'LIKE', "%$search%")
                    ->orWhere('first_name', 'LIKE', "%$search%")
                    ->orWhere('last_name', 'LIKE', "%$search%")
                    ->limit(15)
                    ->get();
    }

    public static function threadsTaskUserMessagesUserParticipantsUser($uid)
    {
        return self::where('id', $uid)
                    ->with('threads')
                    ->with('threads.participants')
                    ->with('threads.messages')
                    ->with('threads.messages.user')
                    ->with('threads.task')
                    ->with('threads.task.creator')
                    ->with('threads.unseenMessages')
                    ->with('threads.unseenMessages.user')
                    ->first();
    }

    public function threads() {
        return $this->belongsToMany('App\Thread');
    }

    public function tasks() {
      return $this->hasMany('App\Task');
    }


}
