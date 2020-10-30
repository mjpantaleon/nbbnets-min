<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BauBloodRequest extends Model
{
    protected $table = 'bau_blood_request';
    protected $primaryKey = 'request_id';
    

    protected $guarded = [];
    public $timestamps = false;

    function patient_min(){
        return $this->belongsTo('App\BauPatient','patient_id','patient_id')->select('patient_id', 'firstname', 'middlename', 'lastname', 'name_suffix', 'blood_type');
    }

    function details(){
        return $this->hasMany('App\BauBloodRequestDetail','request_id', 'request_id');
    }

    
}
