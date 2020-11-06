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
        return $this->belongsTo('App\Donation','donation_id','donation_id')->select('donation_id','sched_id','blood_bag','created_dt', 'collection_method', 'collection_type');
    }

    function component_code(){
        return $this->belongsTo('App\ComponentCode','component_cd','component_cd');
    }

    function cp_component_code(){
        return $this->belongsTo('App\RCpComponentCode','component_cd','component_code');
    }

    function component_code_min(){
        return $this->belongsTo('App\ComponentCode','component_cd','component_cd')->select('component_cd','comp_name');
    }

    function cp_component_code_min(){
        return $this->belongsTo('App\RCpComponentCode','component_code','component_cd')->select('component_code','comp_name');
    }

    function get_cp_details_min(){
        return $this->belongsTo('App\RCpComponentCode','component_code','component_cd')
                    ->select('component_code','comp_name', 'component_abbr');
    }
    
    function additionaltest(){
        return $this->belongsTo('App\AdditionalTest','donation_id','donation_id');
    }

    function hla_hna_details(){
        return $this->belongsTo('App\AdditionalHlaHnaTest', 'donation_id', 'donation_id')->select('donation_id')
                ->with('pre_screened_donor');
    }


}
