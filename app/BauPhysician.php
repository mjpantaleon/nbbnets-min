<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BauPhysician extends Model
{
    protected $table = 'bau_physicians';
    
    public $timestamps = false;

    protected $guarded = [];
}
