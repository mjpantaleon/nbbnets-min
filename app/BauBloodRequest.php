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
        return $this->belongsTo('App\BauPatient','patient_id','patient_id')
                    ->select('patient_id', 'firstname', 'middlename', 'lastname', 'name_suffix', 'blood_type');
    }

    function patient_details(){
        return $this->belongsTo('App\BauPatient', 'patient_id', 'patient_id')
                    ->select('patient_id', 'firstname', 'middlename', 'lastname', 'name_suffix', 'blood_type', 'gender', 'civil_status', 'birthday', 'age', 'diagnosis');
    }

    function physician_details(){
        return $this->belongsTo('App\BauPhysician', 'request_id','request_id')
                    ->select('id','request_id','fname', 'mname', 'lname', 'name_suffix', 'mobile_num', 'email');
    }

    function details(){
        return $this->hasMany('App\BauBloodRequestDetail','request_id', 'request_id')->with('component_name')
                    ->select('request_component_id', 'request_id', 'blood_type', 'donation_id','component_cd');
    }

    function blood_type(){
        return $this->hasMany('App\BauBloodRequestDetail','request_id', 'request_id')
                    ->select('request_component_id', 'request_id', 'blood_type', 'donation_id');
    }



    
}
