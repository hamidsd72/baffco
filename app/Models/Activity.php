<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    
	public function user()
    {
        return $this->belongsTo('App\User');
    }

//    public function activities()
//    {
//        return $this->morphTo();
//    }
}
