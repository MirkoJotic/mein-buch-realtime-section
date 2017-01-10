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

    protected $fillable = ['task_id', 'type'];

    public static function getTaskThreads($currentUser, $task) {
        return self::whereHas('participants', function($q) use ($currentUser) {
                                $q->where('user_id', '=', $currentUser->id);
                            })->whereHas('participants', function($q) use ($task) {
                                $q->where('user_id', '=', $task->creator->id);
                            })->where('type', 'task')
                              ->where('task_id', $task->id)
                              ->get();
    }

    public static function getPrivateThreads($currentUser, $otherUser) {
        return self::whereHas('participants', function($q) use ($currentUser) {
                                $q->where('user_id', '=', $currentUser->id);
                            })->whereHas('participants', function($q) use ($otherUser) {
                                $q->where('user_id', '=', $otherUser->id);
                            })->where('type', 'private')->get();
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
