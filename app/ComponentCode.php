<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComponentCode extends Model
{
    protected $table = 'r_component';
    protected $primaryKey = 'component_cd';
    protected $keyType = 'string';

    public $incrementing = false;
    public $timestamps = false;   
}
