<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageSeenStatus extends Model
{

    protected $fillable = ['user_id', 'thread_id', 'message_id', 'status'];

    public function user()
    {
      return $this->belongsTo('App\User');
    }

    public function thread()
    {
      return $this->belongsTo('App\Thread');
    }

    public function message()
    {
      return $this->belongsTo('App\Message');
    }

}
