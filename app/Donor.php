<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    protected $table = 'donor';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'seqno';

    protected $columns = [
        'donor_photo','seqno','donor_id','name_suffix','lname','mname','fname','bdate','gender',
        'civil_stat','tel_no','mobile_no','email','nationality','occupation',
        'home_no_st_blk','home_brgy','home_citymun','home_prov','home_region','home_zip',
        'office_no_st_blk','office_brgy','office_citymun','office_prov','office_region','office_zip',
        'donation_stat','donor_stat','deferral_basis','facility_cd',
        'created_by','created_dt','updated_by','updated_dt'
    ];

    function scopeExclude($query,$value = array()){
        return $query->select( array_diff( $this->columns,(array) $value) );
    }

    static function generateSeqno($facility_cd,$i = 1,$max = null){
        if(!$max){
            $max = Donor::selectRaw('seqno')->whereFacilityCd($facility_cd)->orderBy('seqno','desc')->first();
            if($max){
                $max = $max->seqno;
            }else if(!$max){
                return $facility_cd.date('Y').str_pad('1',5,'0',STR_PAD_LEFT);
            }
        }

        $num = substr($max,9,strlen($max));
        $num = abs($num);
        $num = $num+$i;
        $new = $facility_cd.date('Y').str_pad($num,7,'0',STR_PAD_LEFT);
        $isExists = Donor::whereSeqno($new)->first();
        if($isExists){
            $i++;
            return self::generateSeqno($facility_cd,$i,$max);
        }

        return $new;
    }

    static function generateNo($facility_cd,$i = 1,$max = null){
        if(!$max){
            $max = Donor::select('donor_id')
                    ->whereFacilityCd($facility_cd)
                    ->where('donor_id','like',$facility_cd.'%')
                    ->orderBy('donor_id','desc')->first();
            if($max){
                $max = $max->donor_id;
            }else if(!$max){
                return $facility_cd.date('Y').str_pad('1',7,'0',STR_PAD_LEFT);
            }
        }

        $num = substr($max,9,strlen($max));
        $num = abs($num);
        $num = $num+$i;
        $new = $facility_cd.date('Y').str_pad($num,7,'0',STR_PAD_LEFT);
        $isExists = Donor::whereDonorId($new)->first();
        if($isExists){
            $i++;
            return self::generateNo($facility_cd,$i,$max);
        }

        return $new;
    }

    static function replaceNye($val){
        $val = str_replace('??','',$val);
        $val = str_replace('Â','',$val);
        return $val;
    }
    
    function region(){
        return $this->belongsTo('App\Region','home_region','regcode')->select('regcode','regname');
    }

    function province(){
        return $this->belongsTo('App\Province','home_prov','provcode')->select('provcode','provname');
    }

    function city(){
        return $this->belongsTo('App\City','home_citymun','citycode')->select('citycode','cityname');
    }

    function barangay(){
        return $this->belongsTo('App\Barangay','home_brgy','bgycode')->select('bgycode','bgyname');
    }

    function officeregion(){
        return $this->belongsTo('App\Region','office_region','regcode')->select('regcode','regname');
    }

    function officeprovince(){
        return $this->belongsTo('App\Province','office_prov','provcode')->select('provcode','provname');
    }

    function officecity(){
        return $this->belongsTo('App\City','office_citymun','citycode')->select('citycode','cityname');
    }

    function officebarangay(){
        return $this->belongsTo('App\Barangay','office_brgy','bgycode')->select('bgycode','bgyname');
    }

    function donations(){
        return $this->hasMany('App\Donation','donor_sn','seqno');
    }

    function appdonations(){
        return $this->hasMany('App\Donation','donor_sn','seqno')->select('donor_sn','donation_id','facility_cd','created_dt');
    }

    function donations2(){
        return $this->hasMany('App\Donation','donor_sn','seqno');
    }

    function nation(){
        return $this->belongsTo('App\Nation','nationality','countrycode');
    }
}
