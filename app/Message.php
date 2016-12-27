<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['thread_id', 'user_id', 'content'];

    public static messageWithUser($id)
    {
      return self::where('id', $id)
                  ->with('user')
                  ->first();
    }

    public function thread()
    {
        return $this->belongsTo('App\Thread');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
