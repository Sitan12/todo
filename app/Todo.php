<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable =[
        'name', 'description', 'done'
    ];

    public function user(){
        return $this->belongsTo('App\User', 'creator_id');
    }
//todo affecter a cet user
    public function TodoAffectedTo(){
        return $this->belongsTo('App\User', 'affectedto_id');
    }

    //todo affecter par cet user
    public function TodoAffectedBy(){
        return $this->belongsTo('App\User', 'affectedbo_id');
    }
}
