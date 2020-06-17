<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    protected $table = 'component';
    protected $primaryKey = 'component_cd';
    protected $keyType = 'string';

    public $incrementing = false;
    public $timestamps = false;   
}
