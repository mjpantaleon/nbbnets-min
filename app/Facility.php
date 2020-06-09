<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $table = 'r_facility';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'facility_cd';
}
