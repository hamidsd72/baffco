<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuInformation extends Model
{
    protected $table = 'menu_informations';

    protected $fillable = [
        'id',
        'text',
        'menu_id',
        'section_number',
        'position',
        'sort',
        'status',
        'created_at',
        'updated_at'
    ];

    public function menu()
    {
        return $this->belongsTo('App\Menu', 'menu_id');
    }

    public function picture()
    {
        return $this->hasMany('App\Models\MenuInformationPicture', 'menu_information_id')->first('pic');
    }

    public function activity()
    {
        return $this->morphOne('App\Activity', 'activities');
    }
}
