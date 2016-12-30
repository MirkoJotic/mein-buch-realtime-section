<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $with = [
        'participants',
        'messages',
        'messages.user',
        'task',
        'task.creator',
        'unseenMessages',
    ];

    protected $fillable = ['task_id'];

    public static function taskMessagesParticipants($thread_id)
    {
        return self::where('id', $thread_id)
                    ->with('task')
                    ->with('task.creator')
                    ->with('participants')
                    ->with('messages')
                    ->with('messages.user')
                    ->first();
    }

    public function unseenMessages()
    {
        return $this->hasMany('App\MessageSeenStatus');
    }

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
