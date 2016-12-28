<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    protected $fillable = [ 'title', 'details' ];

    public function creator() {
        return $this->belongsTo('App\User', 'created_by');
    }

}
