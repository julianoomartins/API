<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuOverride extends Model
{
    protected $fillable = [
    'key','label','icon','order','hidden',
    'route_name','custom_url','new_tab',
    'parent_key',
    ];

}
