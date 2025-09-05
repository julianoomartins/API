<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuOverride extends Model
{
    // app/Models/MenuOverride.php
    protected $fillable = [
    'key','label','icon','route_name','custom_url','order','parent_key','new_tab','hidden',
    ];


}
