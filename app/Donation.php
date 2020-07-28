<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Donation extends Model
{
    protected $table = 'donation';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'seqno';

    function donor(){
        return $this->belongsTo('App\Donor','donor_sn','seqno')->exclude('donor_photo');
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

    function pheresis_label(){
        return $this->hasMany('App\PheresisBloodLabel','source_donation_id','donation_id');
    }

    function aliquote_component(){
        return $this->hasMany('App\Component','source_donation_id','donation_id');
    }

    // function discards(){
    //     return $this->hasMany('App\Discard','donation_id','donation_id');
    // }

    function labels(){
        return $this->hasMany('App\Label','donation_id','donation_id');
    }

    static function generateSeqno($facility_cd,$i = 1,$max = null){
        if(!$max){
            $max = Donation::selectRaw('max(seqno) as seqno')
                    ->whereFacilityCd($facility_cd)
                    ->whereRaw('CHAR_LENGTH(seqno) = 16')
                    ->first();
            if($max){
                $max = $max->seqno;
            }else if(!$max){
                $max = $facility_cd.date('Y').str_pad('1',5,'0',STR_PAD_LEFT);
            }
        }

        
        $num = substr($max,9,strlen($max));
        $num = abs($num);
        $num = $num+$i;
        $new = $facility_cd.date('Y').str_pad($num,7,'0',STR_PAD_LEFT);
        $isExists = Donation::whereSeqno($new)->first();
        if($isExists){
            $i++;
            return self::generateSeqno($facility_cd,$i,$max);
        }

        return $new;
    }

}
