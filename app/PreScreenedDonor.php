<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreScreenedDonor extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    // public function donor(){
    //     return $this->belongsTo('App\Donor', 'seqno');
    // }

    function donor(){
        return $this->belongsTo('App\Donor','donor_sn','seqno')->exclude('donor_photo');
    }

    function facility(){
        return $this->belongsTo('App\Facility','facility_cd','facility_cd');
    }

    // get donation_id at igg table
    function igg_detail(){
        return $this->hasMany('App\IggResult', 'donor_sn', 'donor_sn')
                    ->select('donor_sn','donation_id');
    }
}
