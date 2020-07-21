<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    protected $table = 'component';
    protected $primaryKey = 'donation_id';
    // protected $keyType = 'string';

    public $incrementing = false;
    public $timestamps = false;

    protected $guarded = [];

    function test(){
        return $this->belongsTo('App\Testing','donation_id','donation_id');
    }
    
    function discard(){
        return $this->belongsTo('App\Discard','donation_id','donation_id')->whereComponentCd($this->component_cd);
    }
    
    function donation(){
        return $this->belongsTo('App\Donation','donation_id','donation_id');
    }

    function aliqoute_donation(){
        return $this->belongsTo('App\Donation','source_donation_id','donation_id');
    }
    
    function donation_min(){
        return $this->belongsTo('App\Donation','donation_id','donation_id')->select('donation_id','sched_id','created_dt');
    }

    function component_code(){
        return $this->belongsTo('App\ComponentCode','component_cd','component_cd');
    }

    function component_code_min(){
        return $this->belongsTo('App\ComponentCode','component_cd','component_cd')->select('component_cd','comp_name');
    }
    
    function additionaltest(){
        return $this->belongsTo('App\AdditionalTest','donation_id','donation_id');
    }


}
