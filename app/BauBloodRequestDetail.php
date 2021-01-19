<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BauBloodRequestDetail extends Model
{
    protected $table = 'bau_blood_request_dtls';
    protected $primaryKey = 'request_component_id';

    protected $guarded = [];
    public $timestamps = false;


    function component_name(){
        return $this->belongsTo('App\RCpComponentCode','component_cd', 'component_code')->select('component_code','comp_name');
    }

    function blood_units(){
        return $this->hasMany('App\Component','component_cd', 'component_code')
                    ->select('donation_id','component_cd','collection_dt', 'expiration_dt', 'comp_stat');
    }

    function blood_unit_dtls(){
        return $this->hasMany('App\Component','donation_id', 'donation_id')
                    ->select('donation_id','component_cd','collection_dt', 'expiration_dt', 'comp_stat');
    }

    function reserved_units(){
        return $this->hasMany('App\Component', 'donation_id', 'donation_id')
                    ->select('donation_id', 'collection_dt', 'expiration_dt', 'comp_stat');
    }
}
