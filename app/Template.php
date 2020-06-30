<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $table = 'label_templates';

    protected $fillable = ['facility_cd'];

    function facility(){
        return $this->belongsTo('App\Facility','facility_cd','facility_cd')->select('facility_cd','facility_name');
    }
}
