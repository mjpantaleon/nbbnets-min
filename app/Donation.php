<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Donation extends Model
{
    protected $table = 'donation';
    
    protected $guarded = [];
    public $timestamps = false;

    function donor(){
        return $this->belongsTo('App\Donor','donor_sn','seqno')->exclude('donor_photo');
    }

    function bloodtyping(){
        return $this->hasOne('App\BloodTyping','donation_id','donation_id');
    }

    function donor_min(){
        return $this->belongsTo('App\Donor','donor_sn','seqno')->select('seqno','fname','lname');
    }

    function processing(){
        return $this->belongsTo('App\Processing','donation_id','donation_id');
    }

    function facility(){
        return $this->belongsTo('App\Facility','facility_cd','facility_cd');
    }

    function appfacility(){
        return $this->belongsTo('App\Facility','facility_cd','facility_cd')->select('facility_cd','facility_name');
    }

    function test(){
        return $this->belongsTo('App\Testing','donation_id','donation_id');
    }

    function additionaltest(){
        return $this->belongsTo('App\AdditionalTest','donation_id','donation_id');
    }

    function type(){
        return $this->belongsTo('App\BloodTyping','donation_id','donation_id');
    }

    // function mbd(){
    //     return $this->belongsTo('App\MBD','sched_id','sched_id');
    // }

    // function mbd_min(){
    //     return $this->belongsTo('App\MBD','sched_id','sched_id')->select('sched_id','agency_name','donation_dt');
    // }

    function units(){
        return $this->hasMany('App\Component','donation_id','donation_id');
    }

    function units_min(){
        return $this->hasMany('App\Component','donation_id','donation_id')->select('donation_id');
    }

    // function discards(){
    //     return $this->hasMany('App\Discard','donation_id','donation_id');
    // }

    function labels(){
        return $this->hasMany('App\Label','donation_id','donation_id');
    }

}
