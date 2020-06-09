<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLevel extends Model
{
    protected $table = 'userlevels';
    public $timestamps = false;
    protected $primaryKey = 'userlevelid';
}
